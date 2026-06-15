<?php

namespace App\Controllers\Access;

use App\Controllers\BaseController;
use App\Models\Access\UserModel;
use CodeIgniter\Shield\Entities\User;
use Hermawan\DataTables\DataTable;
use CodeIgniter\HTTP\RedirectResponse;

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

    // ============================================================
    // DATATABLE
    // ============================================================

    public function datatables()
    {
        if (!canDo('access.users.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db = \Config\Database::connect();

        $groupsSubquery = $db->table('auth_groups_users')
            ->select('user_id, GROUP_CONCAT(`group`) as groups_csv')
            ->groupBy('user_id')
            ->getCompiledSelect();

        $builder = $db->table('users u')
            ->select([
                'u.id',
                'u.username',
                'u.active',
                'u.last_active',
                'u.created_at',
                'ai.secret as email',
                'COALESCE(g.groups_csv, "") as groups_csv',
            ])
            ->join('auth_identities ai', "ai.user_id = u.id AND ai.type = 'email_password'", 'left')
            ->join("({$groupsSubquery}) g", 'g.user_id = u.id', 'left')
            ->where('u.deleted_at', null);

        // Filter
        if ($search = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('u.username', $search)
                ->orLike('ai.secret', $search)
                ->groupEnd();
        }

        if ($status = $this->request->getGet('filter_status')) {
            $builder->where('u.active', $status === 'active' ? 1 : 0);
        }

        if ($group = trim($this->request->getGet('filter_group') ?? '')) {
            $builder->having("FIND_IN_SET('{$group}', groups_csv)");
        }

        $datatable = DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['u.username', 'ai.secret']);

        // Tambahkan kolom groups (custom, per-row)
        return $datatable->add('groups', function ($row) {
            if (empty($row->groups_csv)) {
                return [];
            }
            return explode(',', $row->groups_csv);
        })->toJson(true);
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

        $groups = array_column($this->model->getGroupsForUser($id), 'group');

        return $this->jsonResponse([
            'status' => 'success',
            'data'   => [
                'id'          => $user->id,
                'username'    => $user->username,
                'email'       => $this->model->getEmailForUser($id),
                'active'      => (bool) $user->active,
                'groups'      => $groups,
                'last_active' => $user->last_active?->toDateTimeString(),
                'created_at'  => $user->created_at?->toDateTimeString(),
            ],
        ]);
    }

    // ============================================================
    // AJAX — STORE (create / update basic info)
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

        if (!$isUpdate) {
            $rules['password'] = 'required|min_length[8]';
        }

        if (!$this->validate($rules)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ], 422);
        }

        $username = trim($this->request->getPost('username'));
        $email    = trim($this->request->getPost('email'));

        try {
            if ($isUpdate) {
                $user = $this->model->find($id);
                if (!$user) {
                    return $this->jsonError('User tidak ditemukan', 404);
                }

                // Cek username unik (kecuali milik sendiri)
                $existing = $this->model->where('username', $username)->where('id !=', $id)->first();
                if ($existing) {
                    return $this->jsonResponse(['status' => 'error', 'errors' => ['username' => 'Username sudah digunakan']], 422);
                }

                $user->username = $username;
                $this->model->save($user);

                // Update email identity
                $db = \Config\Database::connect();
                $db->table('auth_identities')
                    ->where('user_id', $id)
                    ->where('type', 'email_password')
                    ->update(['secret' => $email]);

                // Update password jika diisi
                $password = $this->request->getPost('password');
                if (!empty($password)) {
                    if (strlen($password) < 8) {
                        return $this->jsonResponse(['status' => 'error', 'errors' => ['password' => 'Password minimal 8 karakter']], 422);
                    }
                    $user->setPassword($password);
                    $this->model->save($user);
                }

                $message = 'User berhasil diperbarui';
            } else {
                // Cek username & email unik
                if ($this->model->where('username', $username)->first()) {
                    return $this->jsonResponse(['status' => 'error', 'errors' => ['username' => 'Username sudah digunakan']], 422);
                }

                $db = \Config\Database::connect();
                if ($db->table('auth_identities')->where('type', 'email_password')->where('secret', $email)->countAllResults() > 0) {
                    return $this->jsonResponse(['status' => 'error', 'errors' => ['email' => 'Email sudah digunakan']], 422);
                }

                $user = new User([
                    'username' => $username,
                    'active'   => 1,
                ]);
                $user->setEmail($email);
                $user->setPassword($this->request->getPost('password'));

                $this->model->save($user);
                $id = $this->model->getInsertID();

                // Assign group default (viewer) jika tidak ada
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

        // Cegah deactivate diri sendiri
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

        // Validasi semua group yang dikirim memang terdaftar di AuthGroups
        $validGroups = array_keys(config('AuthGroups')->groups);
        $groups      = array_values(array_intersect($groups, $validGroups));

        if (empty($groups)) {
            return $this->jsonError('Minimal satu group harus dipilih', 422);
        }

        // Cegah user menghapus role superadmin dari dirinya sendiri jika dia satu-satunya superadmin
        $currentGroups = array_column($this->model->getGroupsForUser($id), 'group');
        if (in_array('superadmin', $currentGroups) && !in_array('superadmin', $groups)) {
            $superadminCount = $this->db_countSuperadmins();
            if ($superadminCount <= 1) {
                return $this->jsonError('Tidak dapat menghapus role superadmin terakhir', 422);
            }
        }

        // Sync groups: hapus semua, lalu assign ulang
        $db = \Config\Database::connect();
        $db->table('auth_groups_users')->where('user_id', $id)->delete();

        foreach ($groups as $group) {
            $user->addGroup($group);
        }

        return $this->jsonResponse([
            'status'  => 'success',
            'message' => 'Group user berhasil diperbarui',
            'groups'  => $groups,
        ]);
    }

    // ============================================================
    // AJAX — DELETE (soft delete)
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

        // Cegah hapus superadmin terakhir
        $currentGroups = array_column($this->model->getGroupsForUser($id), 'group');
        if (in_array('superadmin', $currentGroups) && $this->db_countSuperadmins() <= 1) {
            return $this->jsonError('Tidak dapat menghapus superadmin terakhir', 422);
        }

        $this->model->delete($id);

        return $this->jsonResponse([
            'status'  => 'success',
            'message' => 'User berhasil dihapus',
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

    private function db_countSuperadmins(): int
    {
        $db = \Config\Database::connect();
        return $db->table('auth_groups_users')
            ->where('group', 'superadmin')
            ->countAllResults();
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

    private function forbidden()
    {
        return redirect()->to(site_url('errors/403'));
    }

    private function jsonResponse(array $result, int $code = 200)
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON(array_merge($result, ['csrfHash' => csrf_hash()]));
    }

    private function jsonError(string $message, int $code = 500)
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON(['status' => 'error', 'message' => $message, 'csrfHash' => csrf_hash()]);
    }
}
