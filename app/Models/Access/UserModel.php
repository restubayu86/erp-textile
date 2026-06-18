<?php

namespace App\Models\Access;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected $allowedFields = ['username', 'employee_id', 'active', 'last_active'];

    // ============================================================
    // GROUPS (auth_groups_users)
    // ============================================================

    public function getGroupsForUser(int $userId): array
    {
        $rows = $this->db->table('auth_groups_users')
            ->select('group')
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();

        return array_column($rows, 'group');
    }

    public function syncGroups(int $userId, array $groups): void
    {
        $this->db->table('auth_groups_users')->where('user_id', $userId)->delete();

        $rows = [];
        foreach ($groups as $group) {
            $rows[] = [
                'user_id'    => $userId,
                'group'      => $group,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($rows)) {
            $this->db->table('auth_groups_users')->insertBatch($rows);
        }
    }

    public function countUsersInGroup(string $group): int
    {
        return $this->db->table('auth_groups_users')
            ->where('group', $group)
            ->countAllResults();
    }

    // ============================================================
    // IDENTITY (email)
    // ============================================================

    public function getEmailForUser(int $userId): ?string
    {
        $row = $this->db->table('auth_identities')
            ->select('secret')
            ->where('user_id', $userId)
            ->where('type', 'email_password')
            ->get()
            ->getRowArray();

        return $row['secret'] ?? null;
    }

    public function getLastLogin(int $userId): ?string
    {
        $row = $this->db->table('auth_logins')
            ->select('date')
            ->where('user_id', $userId)
            ->where('success', 1)
            ->orderBy('date', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return $row['date'] ?? null;
    }

    // ============================================================
    // EMPLOYEE RELATION
    // ============================================================

    public function getEmployeeOptionsAvailable(?int $excludeUserId = null): array
    {
        // Employee yang belum punya akun user, atau employee milik user yang sedang diedit
        $sub = $this->db->table('users')
            ->select('employee_id')
            ->where('employee_id IS NOT NULL')
            ->where('deleted_at', null);

        if ($excludeUserId) {
            $sub->where('id !=', $excludeUserId);
        }

        $usedIds = array_column($sub->get()->getResultArray(), 'employee_id');

        $builder = $this->db->table('employees')
            ->select('id, fullname, nik')
            ->where('deleted_at', null);

        if (!empty($usedIds)) {
            $builder->whereNotIn('id', $usedIds);
        }

        return $builder->orderBy('fullname', 'ASC')->get()->getResultArray();
    }

    /**
     * Get users with employee relation and groups
     * 
     * @param array $filters [
     *   'search' => string,
     *   'group' => string,
     *   'status' => 'active'|'inactive',
     *   'has_employee' => bool,
     *   'limit' => int,
     *   'offset' => int,
     *   'order_by' => string,
     *   'order_dir' => 'ASC'|'DESC'
     * ]
     * @return array ['users' => array, 'total' => int]
     */
    public function getUsersWithEmployee(array $filters = []): array
    {
        $builder = $this->builder();
        $builder->select('users.*')
            ->where('users.deleted_at', null);

        // Filter by status
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $builder->where('users.active', 1);
            } elseif ($filters['status'] === 'inactive') {
                $builder->where('users.active', 0);
            }
        }

        // Filter by group
        if (!empty($filters['group'])) {
            $builder->join('auth_groups_users agu', 'agu.user_id = users.id', 'inner')
                ->where('agu.group', $filters['group']);
        }

        // Filter by employee relation
        if (isset($filters['has_employee'])) {
            if ($filters['has_employee']) {
                $builder->where('users.employee_id IS NOT NULL');
            } else {
                $builder->where('users.employee_id IS NULL');
            }
        }

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $builder->groupStart()
                ->like('users.username', $search)
                ->orLike('users.id', $search)
                ->orWhereIn('users.employee_id', function ($sub) use ($search) {
                    $sub->select('id')
                        ->from('employees')
                        ->where('deleted_at', null)
                        ->groupStart()
                        ->like('fullname', $search)
                        ->orLike('nik', $search)
                        ->groupEnd();
                })
                ->groupEnd();
        }

        // Order
        $orderBy = $filters['order_by'] ?? 'users.id';
        $orderDir = $filters['order_dir'] ?? 'DESC';
        $builder->orderBy($orderBy, $orderDir);

        // Get total count
        $total = $builder->countAllResults(false);

        // Pagination
        if (isset($filters['limit'])) {
            $offset = $filters['offset'] ?? 0;
            $builder->limit((int) $filters['limit'], (int) $offset);
        }

        $users = $builder->get()->getResultArray();

        // Enrich with employee data and groups
        $results = [];
        foreach ($users as $user) {
            $results[] = $this->enrichUserData($user);
        }

        return [
            'users' => $results,
            'total' => $total
        ];
    }

    /**
     * Get single user with employee relation
     * 
     * @param int $userId
     * @return array|null
     */
    public function getUserWithEmployee(int $userId): ?array
    {
        $user = $this->where('id', $userId)
            ->where('deleted_at', null)
            ->first();

        if (!$user) {
            return null;
        }

        return $this->enrichUserData($user);
    }

    /**
     * Get user by employee ID
     * 
     * @param int $employeeId
     * @return array|null
     */
    public function getUserByEmployee(int $employeeId): ?array
    {
        $user = $this->where('employee_id', $employeeId)
            ->where('deleted_at', null)
            ->first();

        if (!$user) {
            return null;
        }

        return $this->enrichUserData($user);
    }

    /**
     * Get users by group with employee relation
     * 
     * @param string $group
     * @param int $limit
     * @return array
     */
    public function getUsersByGroup(string $group, int $limit = 100): array
    {
        $users = $this->db->table('users u')
            ->select('u.*')
            ->join('auth_groups_users agu', 'agu.user_id = u.id', 'inner')
            ->where('agu.group', $group)
            ->where('u.deleted_at', null)
            ->limit($limit)
            ->get()
            ->getResultArray();

        $results = [];
        foreach ($users as $user) {
            $results[] = $this->enrichUserData($user);
        }

        return $results;
    }

    /**
     * Get users with no employee relation
     * 
     * @return array
     */
    public function getUsersWithoutEmployee(): array
    {
        $users = $this->where('employee_id IS NULL')
            ->where('deleted_at', null)
            ->findAll();

        $results = [];
        foreach ($users as $user) {
            $results[] = $this->enrichUserData($user);
        }

        return $results;
    }

    /**
     * Get statistics for users with employee
     * 
     * @return array
     */
    public function getUsersWithEmployeeStats(): array
    {
        $total = $this->where('deleted_at', null)->countAllResults();
        $withEmployee = $this->where('employee_id IS NOT NULL')
            ->where('deleted_at', null)
            ->countAllResults();
        $withoutEmployee = $total - $withEmployee;

        $activeWithEmployee = $this->where('employee_id IS NOT NULL')
            ->where('active', 1)
            ->where('deleted_at', null)
            ->countAllResults();

        $adminWithEmployee = $this->db->table('users u')
            ->join('auth_groups_users agu', 'agu.user_id = u.id', 'inner')
            ->where('u.employee_id IS NOT NULL')
            ->where('u.deleted_at', null)
            ->whereIn('agu.group', ['superadmin', 'admin'])
            ->countAllResults();

        return [
            'total_users' => $total,
            'with_employee' => $withEmployee,
            'without_employee' => $withoutEmployee,
            'active_with_employee' => $activeWithEmployee,
            'admin_with_employee' => $adminWithEmployee,
        ];
    }

    /**
     * Enrich user data with employee, groups, email, last login
     * 
     * @param array $user
     * @return array
     */
    private function enrichUserData(array $user): array
    {
        $userId = (int) $user['id'];

        // Get employee data
        $employee = null;
        if (!empty($user['employee_id'])) {
            $employee = $this->db->table('employees')
                ->select('id, nik, fullname, nickname, gender, phone, position_id, department_id, work_area, shift, join_date, employment_status, status as employee_status, photo')
                ->where('id', $user['employee_id'])
                ->where('deleted_at', null)
                ->get()
                ->getRowArray();
        }

        return [
            'id' => $userId,
            'username' => $user['username'],
            'email' => $this->getEmailForUser($userId),
            'employee_id' => $user['employee_id'],
            'employee' => $employee,
            'groups' => $this->getGroupsForUser($userId),
            'active' => (bool) $user['active'],
            'last_active' => $user['last_active'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at'],
            'last_login' => $this->getLastLogin($userId),
        ];
    }

    // ============================================================
    // ACTIVITY LOG (auth_logins)
    // ============================================================

    public function getActivityLogs(int $userId, int $limit = 50): array
    {
        return $this->db->table('auth_logins')
            ->select('id_type, identifier, ip_address, user_agent, success, date')
            ->where('user_id', $userId)
            ->orderBy('date', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    // ============================================================
    // STATS
    // ============================================================

    public function getStats(): array
    {
        $total    = $this->where('deleted_at', null)->countAllResults();
        $active   = $this->where('deleted_at', null)->where('active', 1)->countAllResults();
        $inactive = $total - $active;

        $admins = $this->db->table('auth_groups_users gu')
            ->select('COUNT(DISTINCT gu.user_id) as cnt')
            ->whereIn('gu.group', ['superadmin', 'admin'])
            ->get()
            ->getRowArray();

        $trash = $this->onlyDeleted()->countAllResults();

        return [
            'total'    => $total,
            'active'   => $active,
            'inactive' => $inactive,
            'admin'    => (int) ($admins['cnt'] ?? 0),
            'trash'    => $trash,
        ];
    }
}
