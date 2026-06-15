<?php

namespace App\Models\Access;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    /**
     * Ambil semua group milik sebuah user (array of group names)
     */
    public function getGroupsForUser(int $userId): array
    {
        return $this->db->table('auth_groups_users')
            ->select('group')
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil email dari auth_identities (type=email_password)
     */
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

    /**
     * Ambil last login untuk user
     */
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
}
