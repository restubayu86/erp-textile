<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\WarehouseModel;
use CodeIgniter\HTTP\RedirectResponse;
use Hermawan\DataTables\DataTable;

class WarehouseController extends BaseController
{
    protected WarehouseModel $model;

    public function __construct()
    {
        $this->model = new WarehouseModel();
    }

    public function index(): string|RedirectResponse
    {
        if (!canDo('warehouse.warehouses.view')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\warehouses\index', [
            'title'            => 'Gudang',
            'page_title'       => 'Daftar Gudang',
            'page_description' => 'Kelola data master gudang',
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    public function trash(): string|RedirectResponse
    {
        if (!canDo('warehouse.warehouses.delete')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\warehouses\trash', [
            'title'            => 'Sampah — Gudang',
            'page_title'       => 'Sampah Gudang',
            'page_description' => 'Gudang yang telah dihapus',
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Sampah', 'active' => true]]),
        ]);
    }

    public function datatables()
    {
        if (!canDo('warehouse.warehouses.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('warehouses')
            ->select([
                'warehouses.id',
                'warehouses.warehouse_code',
                'warehouses.warehouse_name',
                'warehouses.location',
                'warehouses.description',
                'warehouses.status',
                'warehouses.created_at',
                'warehouses.updated_at',
                'd.id as department_id',
                'd.department as department_name',
                'cu.username as created_by_name',
                'cu_emp.nickname as created_by_employee',
                'uu.username as updated_by_name',
                'uu_emp.nickname as updated_by_employee',
            ])
            ->join('departments d', 'd.id = warehouses.department_id', 'left')
            ->join('users cu', 'cu.id = warehouses.created_by', 'left')
            ->join('employees cu_emp', 'cu_emp.id = cu.employee_id', 'left')
            ->join('users uu', 'uu.id = warehouses.updated_by', 'left')
            ->join('employees uu_emp', 'uu_emp.id = uu.employee_id', 'left')
            ->where('warehouses.deleted_at', null);

        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('warehouses.warehouse_name', $name)
                ->orLike('warehouses.warehouse_code', $name)
                ->groupEnd();
        }

        if ($department = trim($this->request->getGet('filter_department') ?? '')) {
            $builder->where('warehouses.department_id', $department);
        }

        if ($status = trim($this->request->getGet('filter_status') ?? '')) {
            $builder->where('warehouses.status', $status);
        }

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'warehouses.location',
            ])
            ->toJson(true);
    }

    public function trashDatatables()
    {
        if (!canDo('warehouse.warehouses.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('warehouses')
            ->select([
                'warehouses.id',
                'warehouses.warehouse_code',
                'warehouses.warehouse_name',
                'warehouses.status',
                'warehouses.deleted_at',
                'd.department as department_name',
                'cu.username as created_by_name',
                'cu_emp.nickname as created_by_employee',
                'du.username as deleted_by_name',
                'du_emp.nickname as deleted_by_employee',
            ])
            ->join('departments d', 'd.id = warehouses.department_id', 'left')
            ->join('users cu', 'cu.id = warehouses.created_by', 'left')
            ->join('employees cu_emp', 'cu_emp.id = cu.employee_id', 'left')
            ->join('users du', 'du.id = warehouses.deleted_by', 'left')
            ->join('employees du_emp', 'du_emp.id = du.employee_id', 'left')
            ->where('warehouses.deleted_at IS NOT NULL');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            ])
            ->toJson(true);
    }

    public function get(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        $result = $this->model->getData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);

        $id = (int) $this->request->getPost('id');
        $isUpdate = $id > 0;

        if ($isUpdate && !canDo('warehouse.warehouses.edit'))   return $this->jsonError('Akses ditolak', 403);
        if (!$isUpdate && !canDo('warehouse.warehouses.create')) return $this->jsonError('Akses ditolak', 403);

        $rules = [
            'warehouse_code' => 'required|max_length[30]|alpha_numeric_punct',
            'warehouse_name' => 'required|max_length[100]',
            'department_id'  => 'permit_empty|is_natural_no_zero',
            'status'         => 'required|in_list[Active,Draft,Archived]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse(['status' => 'error', 'errors' => $this->validator->getErrors()], 422);
        }

        $userId = auth()->id();
        $data = [
            'warehouse_code' => strtoupper(trim($this->request->getPost('warehouse_code'))),
            'warehouse_name' => trim($this->request->getPost('warehouse_name')),
            'department_id'  => $this->request->getPost('department_id') ?: null,
            'location'       => trim($this->request->getPost('location') ?? '') ?: null,
            'description'    => trim($this->request->getPost('description') ?? '') ?: null,
            'status'         => $this->request->getPost('status'),
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
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.warehouses.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->deleteData($id, auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.warehouses.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->restoreData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function forceDelete(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.warehouses.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->forceDeleteData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function emptyTrash()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.warehouses.delete')) return $this->jsonError('Akses ditolak', 403);

        $trashed = $this->model->onlyDeleted()->findAll();
        if (empty($trashed)) return $this->jsonResponse(['status' => 'success', 'message' => 'Sampah sudah kosong']);

        $deleted = 0;
        foreach ($trashed as $row) {
            if ($this->model->forceDeleteData($row['id'])['status'] === 'success') $deleted++;
        }
        return $this->jsonResponse(['status' => 'success', 'message' => "{$deleted} gudang berhasil dihapus permanen"]);
    }

    public function stats()
    {
        if (!canDo('warehouse.warehouses.view')) return $this->jsonError('Akses ditolak', 403);
        return $this->response->setJSON(['status' => 'success', 'data' => $this->model->getStats()]);
    }

    public function select2()
    {
        $search  = trim($this->request->getGet('search') ?? '');
        $builder = $this->model->db->table('warehouses')
            ->select('id, warehouse_code AS code, warehouse_name AS name')
            ->where('status', 'Active')->where('deleted_at', null)->orderBy('warehouse_name', 'ASC');

        if ($department = $this->request->getGet('department_id')) {
            $builder->where('department_id', $department);
        }
        if ($search !== '') $builder->groupStart()->like('warehouse_name', $search)->orLike('warehouse_code', $search)->groupEnd();

        return $this->response->setJSON(['status' => 'success', 'data' => $builder->limit(50)->get()->getResultArray()]);
    }

    private function breadcrumbs(array $extra = []): array
    {
        $base = [
            ['name' => 'Dashboard', 'url' => site_url('dashboard')],
            ['name' => 'Warehouse',  'url' => site_url('warehouse')],
            ['name' => 'Gudang',     'url' => site_url('warehouse/warehouses')],
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
