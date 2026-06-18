<?php

namespace App\Modules\Production\Controllers;

use App\Controllers\BaseController;
use App\Modules\Production\Models\MachineModel;
use CodeIgniter\HTTP\RedirectResponse;
use Hermawan\DataTables\DataTable;

class MachineController extends BaseController
{
    protected MachineModel $model;

    public function __construct()
    {
        $this->model = new MachineModel();
    }

    // ============================================================
    // PAGES
    // ============================================================

    public function index(): string|RedirectResponse
    {
        if (!canDo('production.machines.view')) {
            return $this->forbidden();
        }

        return view('App\Modules\Production\Views\machines\index', [
            'title'            => 'Mesin',
            'page_title'       => 'Daftar Mesin',
            'page_description' => 'Kelola data mesin produksi',
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    public function trash(): string|RedirectResponse
    {
        if (!canDo('production.machines.delete')) {
            return $this->forbidden();
        }

        return view('App\Modules\Production\Views\machines\trash', [
            'title'            => 'Sampah — Mesin',
            'page_title'       => 'Sampah Mesin',
            'page_description' => 'Mesin yang telah dihapus',
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
        if (!canDo('production.machines.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('machines')
            ->select([
                'machines.id',
                'machines.machine_code',
                'machines.machine_name',
                'machines.capacity',
                'machines.capacity_unit',
                'machines.description',
                'machines.status',
                'machines.created_at',
                'machines.updated_at',
                'd.department as department_name',
                'cu.username as created_by_name',
                'uu.username as updated_by_name',
            ])
            ->join('departments d', 'd.id = machines.department_id', 'left')
            ->join('users cu', 'cu.id = machines.created_by', 'left')
            ->join('users uu', 'uu.id = machines.updated_by', 'left')
            ->where('machines.deleted_at', null);

        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('machines.machine_name', $name)
                ->orLike('machines.machine_code', $name)
                ->groupEnd();
        }

        if ($department = trim($this->request->getGet('filter_department') ?? '')) {
            $builder->where('machines.department_id', $department);
        }

        if ($status = trim($this->request->getGet('filter_status') ?? '')) {
            $builder->where('machines.status', $status);
        }

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'machines.machine_name',
                'machines.machine_code',
                'machines.description',
            ])
            ->toJson(true);
    }

    public function trashDatatables()
    {
        if (!canDo('production.machines.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('machines')
            ->select([
                'machines.id',
                'machines.machine_code',
                'machines.machine_name',
                'machines.status',
                'machines.deleted_at',
                'd.department as department_name',
                'cu.username as created_by_name',
                'du.username as deleted_by_name',
            ])
            ->join('departments d', 'd.id = machines.department_id', 'left')
            ->join('users cu', 'cu.id = machines.created_by', 'left')
            ->join('users du', 'du.id = machines.deleted_by', 'left')
            ->where('machines.deleted_at IS NOT NULL');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'machines.machine_name',
                'machines.machine_code',
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

        if ($isUpdate && !canDo('production.machines.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        if (!$isUpdate && !canDo('production.machines.create')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $rules = [
            'machine_code' => 'required|max_length[50]|alpha_numeric_punct',
            'machine_name' => 'required|max_length[100]',
            'capacity'     => 'permit_empty|decimal',
            'status'       => 'required|in_list[Active,Draft,Maintenance,Archived]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ], 422);
        }

        $userId = auth()->id();

        $data = [
            'machine_code'  => strtoupper(trim($this->request->getPost('machine_code'))),
            'machine_name'  => trim($this->request->getPost('machine_name')),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'capacity'      => $this->request->getPost('capacity') ?: null,
            'capacity_unit' => trim($this->request->getPost('capacity_unit') ?? '') ?: null,
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

        if (!canDo('production.machines.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $woCount = $this->model->isUsedByWorkOrders($id);
        $csCount = $this->model->isUsedByChecksheets($id);

        if ($woCount > 0 || $csCount > 0) {
            $parts = [];
            if ($woCount) $parts[] = "{$woCount} work order";
            if ($csCount) $parts[] = "{$csCount} checksheet";
            return $this->jsonError('Mesin tidak dapat dihapus karena digunakan oleh ' . implode(' dan ', $parts), 422);
        }

        $result = $this->model->deleteData($id, auth()->id());

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('production.machines.delete')) {
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

        if (!canDo('production.machines.delete')) {
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

        if (!canDo('production.machines.delete')) {
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

        $msg = "{$deleted} mesin berhasil dihapus permanen";
        if ($skipped) $msg .= ", {$skipped} gagal dihapus";

        return $this->jsonResponse(['status' => 'success', 'message' => $msg]);
    }

    // ============================================================
    // AJAX — STATS & SELECT2
    // ============================================================

    public function stats()
    {
        if (!canDo('production.machines.view')) {
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
        $builder = $this->model->db->table('machines m')
            ->select('m.id, m.machine_code AS code, m.machine_name AS name, d.department AS department_name, d.id AS department_id')
            ->join('departments d', 'd.id = m.department_id', 'left')
            ->where('m.status', 'Active')
            ->where('m.deleted_at', null)
            ->orderBy('m.machine_name', 'ASC');

        if ($search !== '') {
            $builder->groupStart()
                ->like('m.machine_name', $search)
                ->orLike('m.machine_code', $search)
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

        if (!in_array($field, ['machine_code', 'machine_name'])) {
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
            ['name' => 'Dashboard',   'url' => site_url('dashboard')],
            ['name' => 'Produksi',    'url' => site_url('production')],
            ['name' => 'Mesin',       'url' => site_url('production/machines')],
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
