<?php

namespace App\Modules\HRM\Controllers;

use App\Controllers\BaseController;
use App\Modules\HRM\Models\DepartmentModel;
use Hermawan\DataTables\DataTable;
use CodeIgniter\HTTP\ResponseInterface;

class DepartmentController extends BaseController
{
    protected DepartmentModel $model;

    public function __construct()
    {
        $this->model = new DepartmentModel();
    }

    // ============================================================
    // PAGES
    // ============================================================

    public function index(): ResponseInterface|string
    {
        if (!canDo('hrm.departments.view')) {
            return $this->forbidden();
        }

        return view('App\Modules\HRM\Views\departments\index', [
            'title'            => 'Departemen',
            'page_title'       => 'Daftar Departemen',
            'page_description' => 'Kelola data departemen perusahaan',
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    public function trash(): ResponseInterface|string
    {
        if (!canDo('hrm.departments.delete')) {
            return $this->forbidden();
        }

        return view('App\Modules\HRM\Views\departments\trash', [
            'title'            => 'Sampah — Departemen',
            'page_title'       => 'Sampah Departemen',
            'page_description' => 'Departemen yang telah dihapus',
            'breadcrumbs'      => $this->breadcrumbs([
                ['name' => 'Sampah', 'active' => true],
            ]),
        ]);
    }

    // ============================================================
    // DATATABLE ENDPOINTS
    // ============================================================

    public function datatables()
    {
        if (!canDo('hrm.departments.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('departments')
            ->select([
                'departments.id',
                'departments.department_code',
                'departments.department',
                'departments.description',
                'departments.status',
                'departments.created_at',
                'departments.updated_at',
                'cu.username as created_by_name',
                'uu.username as updated_by_name',
            ])
            ->join('users cu', 'cu.id = departments.created_by', 'left')
            ->join('users uu', 'uu.id = departments.updated_by', 'left')
            ->where('departments.deleted_at', null);

        // Filter tambahan
        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->like('departments.department', $name);
        }

        if ($status = trim($this->request->getGet('filter_status') ?? '')) {
            $builder->where('departments.status', $status);
        }

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'departments.department',
                'departments.department_code',
                'departments.description',
            ])
            ->toJson(true);
    }

    public function trashDatatables()
    {
        if (!canDo('hrm.departments.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('departments')
            ->select([
                'departments.id',
                'departments.department_code',
                'departments.department',
                'departments.description',
                'departments.status',
                'departments.deleted_at',
                'cu.username as created_by_name',
                'du.username as deleted_by_name',
            ])
            ->join('users cu', 'cu.id = departments.created_by', 'left')
            ->join('users du', 'du.id = departments.deleted_by', 'left')
            ->where('departments.deleted_at IS NOT NULL');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'departments.department',
                'departments.department_code',
            ])
            ->toJson(true);
    }

    // ============================================================
    // AJAX — CRUD
    // ============================================================

    public function get(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        $result = $this->model->getData($id);

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        $id       = (int) $this->request->getPost('id');
        $isUpdate = $id > 0;

        if ($isUpdate && !canDo('hrm.departments.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        if (!$isUpdate && !canDo('hrm.departments.create')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        // Validasi
        $rules = [
            'department_code' => 'required|max_length[20]|alpha_numeric_punct',
            'department'      => 'required|max_length[100]',
            'status'          => 'required|in_list[Active,Draft,Archived]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ], 422);
        }

        $userId = auth()->id();

        $data = [
            'department_code' => strtoupper(trim($this->request->getPost('department_code'))),
            'department'      => trim($this->request->getPost('department')),
            'description'     => trim($this->request->getPost('description') ?? '') ?: null,
            'status'          => $this->request->getPost('status'),
        ];

        if ($isUpdate) {
            $data['updated_by'] = $userId;
            $result = $this->model->updateData($id, $data);
        } else {
            $data['created_by'] = $userId;
            $result = $this->model->createData($data);
        }

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }


    public function delete(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.departments.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $positionCount = $this->model->isUsedByPositions($id);
        if ($positionCount > 0) {
            return $this->jsonError("Departemen tidak dapat dihapus karena digunakan oleh {$positionCount} jabatan.", 422);
        }

        $result = $this->model->deleteData($id, auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.departments.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $result = $this->model->restoreData($id);

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function forceDelete(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.departments.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        // Cek posisi & karyawan di sampah juga
        $result = $this->model->forceDeleteData($id);

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function emptyTrash()
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.departments.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $trashed = $this->model->onlyDeleted()->findAll();

        if (empty($trashed)) {
            return $this->jsonResponse(['status' => 'success', 'message' => 'Sampah sudah kosong']);
        }

        $deleted = 0;
        $skipped = 0;

        foreach ($trashed as $row) {
            $result = $this->model->forceDeleteData($row['id']);
            $result['status'] === 'success' ? $deleted++ : $skipped++;
        }

        $msg = "{$deleted} departemen berhasil dihapus permanen";
        if ($skipped) $msg .= ", {$skipped} gagal dihapus";

        return $this->jsonResponse(['status' => 'success', 'message' => $msg]);
    }

    // ============================================================
    // AJAX — STATS & SELECT2
    // ============================================================

    public function stats()
    {
        if (!canDo('hrm.departments.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getStats(),
        ]);
    }

    public function select2()
    {
        $search  = trim($this->request->getGet('search') ?? '');
        $builder = $this->model->db->table('departments')
            ->select('id, department_code AS code, department AS name')
            ->where('status', 'Active')
            ->where('deleted_at', null)
            ->orderBy('department', 'ASC');

        if ($search !== '') {
            $builder->groupStart()
                ->like('department', $search)
                ->orLike('department_code', $search)
                ->groupEnd();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $builder->limit(50)->get()->getResultArray(),
        ]);
    }

    public function checkUnique()
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        $field = $this->request->getPost('field');
        $value = trim($this->request->getPost('value') ?? '');
        $id    = (int) $this->request->getPost('id');

        if (!in_array($field, ['department_code', 'department'])) {
            return $this->jsonError('Field tidak valid', 422);
        }

        $q = $this->model->where("LOWER({$field})", strtolower($value))->where('deleted_at', null);
        if ($id > 0) $q->where('id !=', $id);

        return $this->jsonResponse([
            'status'    => 'success',
            'available' => !$q->first(),
        ]);
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function breadcrumbs(array $extra = []): array
    {
        $base = [
            ['name' => 'Dashboard', 'url' => site_url('dashboard')],
            ['name' => 'HRM',       'url' => site_url('hrm')],
            ['name' => 'Departemen', 'url' => site_url('hrm/departments')],
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
