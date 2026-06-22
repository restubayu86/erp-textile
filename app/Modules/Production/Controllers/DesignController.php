<?php

namespace App\Modules\Production\Controllers;

use App\Controllers\BaseController;
use App\Modules\Production\Models\DesignModel;
use CodeIgniter\HTTP\RedirectResponse;
use Hermawan\DataTables\DataTable;

class DesignController extends BaseController
{
    protected DesignModel $model;

    public function __construct()
    {
        $this->model = new DesignModel();
    }

    // ============================================================
    // PAGES
    // ============================================================

    public function index(): string|RedirectResponse
    {
        if (!canDo('production.designs.view')) {
            return $this->forbidden();
        }

        return view('App\Modules\Production\Views\designs\index', [
            'title'            => 'Design',
            'page_title'       => 'Daftar Design',
            'page_description' => 'Kelola data jenis desain',
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    public function show(int $id): string|RedirectResponse
    {
        if (!canDo('production.designs.view')) {
            return $this->forbidden();
        }

        $result = $this->model->getData($id);

        if ($result['status'] !== 'success') {
            return redirect()->to(site_url('production/master/designs'))
                ->with('error', 'Design tidak ditemukan');
        }

        return view('App\Modules\Production\Views\designs\show', [
            'title'            => 'Detail Design',
            'page_title'       => $result['data']['design_name'],
            'page_description' => 'Detail design & flow process terkait',
            'design'           => $result['data'],
            'breadcrumbs'      => $this->breadcrumbs([
                ['name' => $result['data']['design_name'], 'active' => true],
            ]),
        ]);
    }

    public function trash(): string|RedirectResponse
    {
        if (!canDo('production.designs.delete')) {
            return $this->forbidden();
        }

        return view('App\Modules\Production\Views\designs\trash', [
            'title'            => 'Sampah — Design',
            'page_title'       => 'Sampah Design',
            'page_description' => 'Design yang telah dihapus',
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
        if (!canDo('production.designs.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('design_master')
            ->select([
                'design_master.id',
                'design_master.design_code',
                'design_master.design_name',
                'design_master.description',
                'design_master.status',
                'design_master.created_at',
                'design_master.updated_at',
                'cu.username as created_by_name',
                'cu_emp.nickname as created_by_employee',
                'uu.username as updated_by_name',
                'uu_emp.nickname as updated_by_employee',
            ])
            ->join('users cu', 'cu.id = design_master.created_by', 'left')
            ->join('employees cu_emp', 'cu_emp.id = cu.employee_id', 'left')
            ->join('users uu', 'uu.id = design_master.updated_by', 'left')
            ->join('employees uu_emp', 'uu_emp.id = uu.employee_id', 'left')
            ->where('design_master.deleted_at', null);

        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('design_master.design_name', $name)
                ->orLike('design_master.design_code', $name)
                ->groupEnd();
        }

        if ($status = trim($this->request->getGet('filter_status') ?? '')) {
            $builder->where('design_master.status', $status);
        }

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'design_master.design_name',
                'design_master.design_code',
                'design_master.description',
            ])
            ->toJson(true);
    }

    public function trashDatatables()
    {
        if (!canDo('production.designs.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('design_master')
            ->select([
                'design_master.id',
                'design_master.design_code',
                'design_master.design_name',
                'design_master.status',
                'design_master.deleted_at',
                'cu.username as created_by_name',
                'cu_emp.nickname as created_by_employee',
                'du.username as deleted_by_name',
                'du_emp.nickname as deleted_by_employee',
            ])
            ->join('users cu', 'cu.id = design_master.created_by', 'left')
            ->join('employees cu_emp', 'cu_emp.id = cu.employee_id', 'left')
            ->join('users du', 'du.id = design_master.deleted_by', 'left')
            ->join('employees du_emp', 'du_emp.id = du.employee_id', 'left')
            ->where('design_master.deleted_at IS NOT NULL');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'design_master.design_name',
                'design_master.design_code',
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

        if ($isUpdate && !canDo('production.designs.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        if (!$isUpdate && !canDo('production.designs.create')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $rules = [
            'design_code' => 'required|max_length[30]|alpha_numeric_punct',
            'design_name' => 'required|max_length[150]',
            'status'      => 'required|in_list[Active,Draft,Archived]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ], 422);
        }

        $userId = auth()->id();

        $data = [
            'design_code' => strtoupper(trim($this->request->getPost('design_code'))),
            'design_name' => trim($this->request->getPost('design_name')),
            'description' => trim($this->request->getPost('description') ?? '') ?: null,
            'status'      => $this->request->getPost('status'),
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

        if (!canDo('production.designs.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $flowCount = $this->model->isUsedByFlowProcesses($id);
        if ($flowCount > 0) {
            return $this->jsonError("Design tidak dapat dihapus karena digunakan oleh {$flowCount} flow process.", 422);
        }

        $result = $this->model->deleteData($id, auth()->id());

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('production.designs.delete')) {
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

        if (!canDo('production.designs.delete')) {
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

        if (!canDo('production.designs.delete')) {
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

        $msg = "{$deleted} design berhasil dihapus permanen";
        if ($skipped) $msg .= ", {$skipped} gagal dihapus";

        return $this->jsonResponse(['status' => 'success', 'message' => $msg]);
    }

    // ============================================================
    // AJAX — STATS & SELECT2
    // ============================================================

    public function stats()
    {
        if (!canDo('production.designs.view')) {
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
        $builder = $this->model->db->table('design_master')
            ->select(['id', 'design_code AS code', 'design_name AS name'])
            ->where('status', 'Active')
            ->where('deleted_at', null)
            ->orderBy('design_name', 'ASC');

        if ($search !== '') {
            $builder->groupStart()
                ->like('design_name', $search)
                ->orLike('design_code', $search)
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

        if (!in_array($field, ['design_code', 'design_name'])) {
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
            ['name' => 'Dashboard',  'url' => site_url('dashboard')],
            ['name' => 'Produksi',   'url' => site_url('production')],
            ['name' => 'Design',     'url' => site_url('production/master/designs')],
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
