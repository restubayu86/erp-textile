<?php

namespace App\Controllers\Access;

use App\Controllers\BaseController;
use App\Models\Access\UserModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\HTTP\RedirectResponse;
use Hermawan\DataTables\DataTable;

class UserController extends BaseController
{
    protected UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    // ============================================================
    // PAGES
    // ============================================================

    public function index(): string|RedirectResponse
    {
        if (!canDo('access.users.view')) {
            return $this->forbidden();
        }

        return view('access/users/index', [
            'title'            => 'Manajemen User',
            'page_title'       => 'Manajemen User',
            'page_description' => 'Kelola akun pengguna & assign role/group',
            'groups'           => $this->getAvailableGroups(),
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    public function trash(): string|RedirectResponse
    {
        if (!canDo('access.users.delete')) {
            return $this->forbidden();
        }

        return view('access/users/trash', [
            'title'            => 'Sampah — User',
            'page_title'       => 'Sampah User',
            'page_description' => 'User yang telah dihapus',
            'breadcrumbs'      => $this->breadcrumbs([
                ['name' => 'Sampah', 'active' => true],
            ]),
        ]);
    }

    // ============================================================
    // DATATABLE
    // ============================================================

    public function datatables()
    {
        if (!canDo('access.users.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db = \Config\Database::connect();

        $builder = $db->table('users u')
            ->select([
                'u.id',
                'u.username',
                'u.employee_id',
                'u.active',
                'u.last_active',
                'u.created_at',
                'ai.secret as email',
                'e.fullname as employee_name',
                'e.nik as employee_nik',
                '(SELECT GROUP_CONCAT(`group`) FROM auth_groups_users WHERE user_id = u.id) as groups_csv',
            ])
            ->join('auth_identities ai', "ai.user_id = u.id AND ai.type = 'email_password'", 'left')
            ->join('employees e', 'e.id = u.employee_id', 'left')
            ->where('u.deleted_at', null);

        if ($search = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('u.username', $search)
                ->orLike('ai.secret', $search)
                ->orLike('e.fullname', $search)
                ->groupEnd();
        }

        if ($status = $this->request->getGet('filter_status')) {
            $builder->where('u.active', $status === 'active' ? 1 : 0);
        }

        // Filter by group - menggunakan closure di whereIn
        if ($group = trim($this->request->getGet('filter_group') ?? '')) {
            $builder->whereIn('u.id', function ($sub) use ($group) {
                $sub->select('user_id')
                    ->from('auth_groups_users')
                    ->where('group', $group);
            });
        }

        $datatable = DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['u.username', 'ai.secret', 'e.fullname']);

        return $datatable->add('groups', function ($row) {
            if (empty($row->groups_csv)) {
                return [];
            }
            return explode(',', $row->groups_csv);
        })->toJson(true);
    }
    public function trashDatatables()
    {
        if (!canDo('access.users.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('users u')
            ->select([
                'u.id',
                'u.username',
                'u.deleted_at',
                'ai.secret as email',
                'e.fullname as employee_name',
            ])
            ->join('auth_identities ai', "ai.user_id = u.id AND ai.type = 'email_password'", 'left')
            ->join('employees e', 'e.id = u.employee_id', 'left')
            ->where('u.deleted_at IS NOT NULL');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['u.username', 'ai.secret'])
            ->toJson(true);
    }

    // ============================================================
    // AJAX — GET DETAIL
    // ============================================================

    public function show(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('access.users.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $user = $this->model->find($id);

        if (!$user) {
            return $this->jsonError('User tidak ditemukan', 404);
        }

        $employee = null;
        if ($user->employee_id) {
            $db       = \Config\Database::connect();
            $employee = $db->table('employees')
                ->select('id, fullname, nik')
                ->where('id', $user->employee_id)
                ->get()->getRowArray();
        }

        return $this->jsonResponse([
            'status' => 'success',
            'data'   => [
                'id'            => $user->id,
                'username'      => $user->username,
                'email'         => $this->model->getEmailForUser($id),
                'active'        => (bool) $user->active,
                'groups'        => $this->model->getGroupsForUser($id),
                'employee_id'   => $user->employee_id,
                'employee_name' => $employee['fullname'] ?? null,
                'employee_nik'  => $employee['nik'] ?? null,
                'last_active'   => $user->last_active?->toDateTimeString(),
                'created_at'    => $user->created_at?->toDateTimeString(),
            ],
        ]);
    }

    // ============================================================
    // AJAX — STORE (create / update)
    // ============================================================

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        $id       = (int) $this->request->getPost('id');
        $isUpdate = $id > 0;

        if ($isUpdate && !canDo('access.users.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }
        if (!$isUpdate && !canDo('access.users.create')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[30]|alpha_numeric_punct',
            'email'    => 'required|valid_email',
        ];

        $password        = $this->request->getPost('password');
        $passwordConfirm = $this->request->getPost('password_confirm');

        if (!$isUpdate) {
            $rules['password']         = 'required|min_length[8]';
            $rules['password_confirm'] = 'required|matches[password]';
        } elseif (!empty($password)) {
            $rules['password']         = 'min_length[8]';
            $rules['password_confirm'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ], 422);
        }

        $username   = trim($this->request->getPost('username'));
        $email      = trim($this->request->getPost('email'));
        $employeeId = $this->request->getPost('employee_id') ?: null;

        // Validasi employee belum dipakai user lain
        if ($employeeId) {
            $existingEmp = $this->model->where('employee_id', $employeeId);
            if ($isUpdate) {
                $existingEmp->where('id !=', $id);
            }
            if ($existingEmp->first()) {
                return $this->jsonResponse(['status' => 'error', 'errors' => ['employee_id' => 'Karyawan ini sudah terhubung ke user lain']], 422);
            }
        }

        try {
            if ($isUpdate) {
                $user = $this->model->find($id);
                if (!$user) {
                    return $this->jsonError('User tidak ditemukan', 404);
                }

                $existing = $this->model->where('username', $username)->where('id !=', $id)->first();
                if ($existing) {
                    return $this->jsonResponse(['status' => 'error', 'errors' => ['username' => 'Username sudah digunakan']], 422);
                }

                $user->username    = $username;
                $user->employee_id = $employeeId;
                $this->model->save($user);

                $db = \Config\Database::connect();
                $db->table('auth_identities')
                    ->where('user_id', $id)
                    ->where('type', 'email_password')
                    ->update(['secret' => $email]);

                if (!empty($password)) {
                    $user->setPassword($password);
                    $this->model->save($user);
                }

                $message = 'User berhasil diperbarui';
            } else {
                if ($this->model->where('username', $username)->first()) {
                    return $this->jsonResponse(['status' => 'error', 'errors' => ['username' => 'Username sudah digunakan']], 422);
                }

                $db = \Config\Database::connect();
                if ($db->table('auth_identities')->where('type', 'email_password')->where('secret', $email)->countAllResults() > 0) {
                    return $this->jsonResponse(['status' => 'error', 'errors' => ['email' => 'Email sudah digunakan']], 422);
                }

                $user = new User([
                    'username'    => $username,
                    'employee_id' => $employeeId,
                    'active'      => 1,
                ]);
                $user->setEmail($email);
                $user->setPassword($password);

                $this->model->save($user);
                $id = $this->model->getInsertID();

                $newUser = $this->model->findById($id);
                $newUser->addGroup('viewer');

                $message = 'User berhasil ditambahkan';
            }

            return $this->jsonResponse([
                'status'  => 'success',
                'message' => $message,
                'id'      => $id,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'UserController::store - ' . $e->getMessage());
            return $this->jsonError('Terjadi kesalahan sistem', 500);
        }
    }

    // ============================================================
    // AJAX — TOGGLE ACTIVE / INACTIVE
    // ============================================================

    public function toggle(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('access.users.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $user = $this->model->find($id);
        if (!$user) {
            return $this->jsonError('User tidak ditemukan', 404);
        }

        if ($id === auth()->id() && $user->active) {
            return $this->jsonError('Anda tidak dapat menonaktifkan akun sendiri', 422);
        }

        $user->active = $user->active ? 0 : 1;
        $this->model->save($user);

        return $this->jsonResponse([
            'status'  => 'success',
            'message' => $user->active ? 'User diaktifkan' : 'User dinonaktifkan',
            'active'  => (bool) $user->active,
        ]);
    }

    // ============================================================
    // AJAX — ASSIGN GROUPS
    // ============================================================

    public function assignGroups(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('access.users.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $user = $this->model->find($id);
        if (!$user) {
            return $this->jsonError('User tidak ditemukan', 404);
        }

        $groups = $this->request->getPost('groups') ?? [];
        if (!is_array($groups)) {
            $groups = [$groups];
        }

        $validGroups = array_keys(config('AuthGroups')->groups);
        $groups      = array_values(array_intersect($groups, $validGroups));

        if (empty($groups)) {
            return $this->jsonError('Minimal satu group harus dipilih', 422);
        }

        $currentGroups = $this->model->getGroupsForUser($id);
        if (in_array('superadmin', $currentGroups) && !in_array('superadmin', $groups)) {
            if ($this->model->countUsersInGroup('superadmin') <= 1) {
                return $this->jsonError('Tidak dapat menghapus role superadmin terakhir', 422);
            }
        }

        $this->model->syncGroups($id, $groups);

        return $this->jsonResponse([
            'status'  => 'success',
            'message' => 'Group user berhasil diperbarui',
            'groups'  => $groups,
        ]);
    }

    // ============================================================
    // AJAX — DELETE / RESTORE / FORCE DELETE / EMPTY TRASH
    // ============================================================

    public function delete(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('access.users.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        if ($id === auth()->id()) {
            return $this->jsonError('Anda tidak dapat menghapus akun sendiri', 422);
        }

        $user = $this->model->find($id);
        if (!$user) {
            return $this->jsonError('User tidak ditemukan', 404);
        }

        $currentGroups = $this->model->getGroupsForUser($id);
        if (in_array('superadmin', $currentGroups) && $this->model->countUsersInGroup('superadmin') <= 1) {
            return $this->jsonError('Tidak dapat menghapus superadmin terakhir', 422);
        }

        $this->model->delete($id);

        return $this->jsonResponse(['status' => 'success', 'message' => 'User dipindahkan ke sampah']);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('access.users.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        if (!$this->model->onlyDeleted()->find($id)) {
            return $this->jsonError('User tidak ditemukan di sampah', 404);
        }

        $this->model->db->table('users')->where('id', $id)->update(['deleted_at' => null]);

        return $this->jsonResponse(['status' => 'success', 'message' => 'User berhasil dipulihkan']);
    }

    public function forceDelete(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('access.users.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        if (!$this->model->onlyDeleted()->find($id)) {
            return $this->jsonError('User tidak ditemukan di sampah', 404);
        }

        $this->model->delete($id, true);

        return $this->jsonResponse(['status' => 'success', 'message' => 'User berhasil dihapus permanen']);
    }

    public function emptyTrash()
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('access.users.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $trashed = $this->model->onlyDeleted()->findAll();

        if (empty($trashed)) {
            return $this->jsonResponse(['status' => 'success', 'message' => 'Sampah sudah kosong']);
        }

        foreach ($trashed as $row) {
            $this->model->delete($row->id, true);
        }

        return $this->jsonResponse(['status' => 'success', 'message' => count($trashed) . ' user dihapus permanen']);
    }

    // ============================================================
    // AJAX — STATS, SELECT2, ACTIVITY
    // ============================================================

    public function stats()
    {
        if (!canDo('access.users.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $this->model->getStats()]);
    }

    public function select2Employees()
    {
        $excludeUserId = (int) ($this->request->getGet('exclude_user_id') ?? 0);
        $search        = trim($this->request->getGet('search') ?? '');

        $options = $this->model->getEmployeeOptionsAvailable($excludeUserId ?: null);

        if ($search !== '') {
            $options = array_values(array_filter(
                $options,
                fn($e) =>
                stripos($e['fullname'], $search) !== false || stripos($e['nik'], $search) !== false
            ));
        }

        $results = array_map(fn($e) => [
            'id'   => $e['id'],
            'name' => $e['fullname'],
            'nik'  => $e['nik'],
        ], $options);

        return $this->response->setJSON(['status' => 'success', 'data' => $results]);
    }

    public function activity(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('access.users.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $user = $this->model->find($id);
        if (!$user) {
            return $this->jsonError('User tidak ditemukan', 404);
        }

        $logs = $this->model->getActivityLogs($id);

        return $this->jsonResponse([
            'status'   => 'success',
            'username' => $user->username,
            'data'     => $logs,
        ]);
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function getAvailableGroups(): array
    {
        $config = config('AuthGroups');
        $groups = [];

        foreach ($config->groups as $key => $meta) {
            $groups[] = [
                'key'         => $key,
                'title'       => $meta['title'] ?? $key,
                'description' => $meta['description'] ?? '',
            ];
        }

        return $groups;
    }

    private function breadcrumbs(array $extra = []): array
    {
        $base = [
            ['name' => 'Dashboard', 'url' => site_url('dashboard')],
            ['name' => 'Hak Akses', 'url' => site_url('access/users')],
            ['name' => 'User',      'url' => site_url('access/users')],
        ];

        if (empty($extra)) {
            $base[2]['active'] = true;
            unset($base[2]['url']);
            return $base;
        }

        return array_merge($base, $extra);
    }

    private function forbidden(): RedirectResponse
    {
        return redirect()->to(site_url('errors/403'));
    }

    private function jsonResponse(array $result, int $code = 200)
    {
        return $this->response->setStatusCode($code)->setJSON(array_merge($result, ['csrfHash' => csrf_hash()]));
    }

    private function jsonError(string $message, int $code = 500)
    {
        return $this->response->setStatusCode($code)->setJSON(['status' => 'error', 'message' => $message, 'csrfHash' => csrf_hash()]);
    }
}
