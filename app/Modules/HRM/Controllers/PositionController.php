<?php

namespace App\Modules\HRM\Controllers;

use App\Controllers\BaseController;
use App\Modules\HRM\Models\PositionModel;
use App\Modules\HRM\Models\DepartmentModel;
use Hermawan\DataTables\DataTable;
use CodeIgniter\HTTP\ResponseInterface;

class PositionController extends BaseController
{
    protected PositionModel $model;
    protected DepartmentModel $departmentModel;

    public function __construct()
    {
        $this->model = new PositionModel();
        $this->departmentModel = new DepartmentModel();
    }

    // ============================================================
    // PAGES
    // ============================================================

    public function index(): ResponseInterface|string
    {
        if (!canDo('hrm.positions.view')) {
            return $this->forbidden();
        }

        return view('App\Modules\HRM\Views\positions\index', [
            'title'            => 'Posisi',
            'page_title'       => 'Daftar Posisi',
            'page_description' => 'Kelola data posisi/jabatan perusahaan',
            'breadcrumbs'      => $this->breadcrumbs(),
            'departments'      => $this->departmentModel->getAllActive(),
        ]);
    }

    public function trash(): ResponseInterface|string
    {
        if (!canDo('hrm.positions.delete')) {
            return $this->forbidden();
        }

        return view('App\Modules\HRM\Views\positions\trash', [
            'title'            => 'Sampah — Posisi',
            'page_title'       => 'Sampah Posisi',
            'page_description' => 'Posisi yang telah dihapus',
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
        if (!canDo('hrm.positions.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('positions')
            ->select([
                'positions.id',
                'positions.position_code',
                'positions.position_name',
                'positions.description',
                'positions.status',
                'positions.created_at',
                'positions.updated_at',
                'positions.department_id',
                'departments.department as department_name',
                'cu.username as created_by_name',
                'uu.username as updated_by_name',
            ])
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->join('users cu', 'cu.id = positions.created_by', 'left')
            ->join('users uu', 'uu.id = positions.updated_by', 'left')
            ->where('positions.deleted_at', null);

        // Filter tambahan
        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('positions.position_name', $name)
                ->orLike('positions.position_code', $name)
                ->groupEnd();
        }

        if ($department = trim($this->request->getGet('filter_department') ?? '')) {
            $builder->where('positions.department_id', $department);
        }

        if ($status = trim($this->request->getGet('filter_status') ?? '')) {
            $builder->where('positions.status', $status);
        }

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'positions.position_name',
                'positions.position_code',
                'positions.description',
            ])
            ->toJson(true);
    }

    public function trashDatatables()
    {
        if (!canDo('hrm.positions.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('positions')
            ->select([
                'positions.id',
                'positions.position_code',
                'positions.position_name',
                'positions.description',
                'positions.status',
                'positions.deleted_at',
                'positions.department_id',
                'departments.department as department_name',
                'cu.username as created_by_name',
                'du.username as deleted_by_name',
            ])
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->join('users cu', 'cu.id = positions.created_by', 'left')
            ->join('users du', 'du.id = positions.deleted_by', 'left')
            ->where('positions.deleted_at IS NOT NULL');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'positions.position_name',
                'positions.position_code',
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

        if ($isUpdate && !canDo('hrm.positions.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        if (!$isUpdate && !canDo('hrm.positions.create')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        // Validasi
        $rules = [
            'position_code' => 'required|max_length[50]|alpha_numeric_punct',
            'position_name' => 'required|max_length[100]',
            'status'        => 'required|in_list[Active,Draft,Archived]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ], 422);
        }

        $userId = auth()->id();

        $data = [
            'position_code' => strtoupper(trim($this->request->getPost('position_code'))),
            'position_name' => trim($this->request->getPost('position_name')),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'description'   => trim($this->request->getPost('description') ?? '') ?: null,
            'status'        => $this->request->getPost('status'),
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

        if (!canDo('hrm.positions.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        // Cek apakah dipakai oleh employees
        $employeeCount = $this->model->db->table('employees')
            ->where('position_id', $id)
            ->where('deleted_at', null)
            ->countAllResults();

        if ($employeeCount > 0) {
            return $this->jsonError("Posisi tidak dapat dihapus karena digunakan oleh {$employeeCount} karyawan", 422);
        }

        $result = $this->model->deleteData($id, auth()->id());

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.positions.delete')) {
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

        if (!canDo('hrm.positions.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $result = $this->model->forceDeleteData($id);

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function emptyTrash()
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.positions.delete')) {
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

        $msg = "{$deleted} posisi berhasil dihapus permanen";
        if ($skipped) $msg .= ", {$skipped} gagal dihapus";

        return $this->jsonResponse(['status' => 'success', 'message' => $msg]);
    }

    // ============================================================
    // AJAX — STATS & SELECT2
    // ============================================================

    public function stats()
    {
        if (!canDo('hrm.positions.view')) {
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
        $departmentId = $this->request->getGet('department_id');

        $builder = $this->model->db->table('positions')
            ->select('id, position_code AS code, position_name AS name, department_id')
            ->where('status', 'Active')
            ->where('deleted_at', null)
            ->orderBy('position_name', 'ASC');

        if ($departmentId) {
            $builder->where('department_id', $departmentId);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('position_name', $search)
                ->orLike('position_code', $search)
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

        if (!in_array($field, ['position_code', 'position_name'])) {
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
            ['name' => 'Posisi',    'url' => site_url('hrm/positions')],
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
