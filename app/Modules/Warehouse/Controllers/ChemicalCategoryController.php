<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\ChemicalCategoryModel;
use CodeIgniter\HTTP\RedirectResponse;
use Hermawan\DataTables\DataTable;

class ChemicalCategoryController extends BaseController
{
    protected ChemicalCategoryModel $model;

    public function __construct()
    {
        $this->model = new ChemicalCategoryModel();
    }

    public function index(): string|RedirectResponse
    {
        if (!canDo('warehouse.chemical_categories.view')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\chemical_categories\index', [
            'title'            => 'Kategori Bahan Kimia',
            'page_title'       => 'Kategori Bahan Kimia',
            'page_description' => 'Kelola kategori bahan kimia',
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    public function trash(): string|RedirectResponse
    {
        if (!canDo('warehouse.chemical_categories.delete')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\chemical_categories\trash', [
            'title'            => 'Sampah — Kategori Kimia',
            'page_title'       => 'Sampah Kategori Kimia',
            'page_description' => 'Kategori yang telah dihapus',
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Sampah', 'active' => true]]),
        ]);
    }

    public function datatables()
    {
        if (!canDo('warehouse.chemical_categories.view')) return $this->jsonError('Akses ditolak', 403);

        $db      = \Config\Database::connect();
        $builder = $db->table('chemical_categories cc')
            ->select([
                'cc.id',
                'cc.category_code',
                'cc.category_name',
                'cc.description',
                'cc.status',
                'cc.created_at',
                'cc.updated_at',
                'cu.username as created_by_name',
                'uu.username as updated_by_name',
                'COUNT(c.id) as chemical_count',
            ])
            ->join('users cu', 'cu.id = cc.created_by', 'left')
            ->join('users uu', 'uu.id = cc.updated_by', 'left')
            ->join('chemicals c', 'c.category_id = cc.id AND c.deleted_at IS NULL', 'left')
            ->where('cc.deleted_at', null)
            ->groupBy('cc.id');

        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()->like('cc.category_name', $name)->orLike('cc.category_code', $name)->groupEnd();
        }
        if ($status = $this->request->getGet('filter_status')) {
            $builder->where('cc.status', $status);
        }

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['cc.category_name', 'cc.category_code'])
            ->toJson(true);
    }

    public function trashDatatables()
    {
        if (!canDo('warehouse.chemical_categories.delete')) return $this->jsonError('Akses ditolak', 403);

        $db      = \Config\Database::connect();
        $builder = $db->table('chemical_categories cc')
            ->select(['cc.id', 'cc.category_code', 'cc.category_name', 'cc.status', 'cc.deleted_at', 'du.username as deleted_by_name'])
            ->join('users du', 'du.id = cc.deleted_by', 'left')
            ->where('cc.deleted_at IS NOT NULL');

        return DataTable::of($builder)->addNumbering('no')
            ->setSearchableColumns(['cc.category_name', 'cc.category_code'])->toJson(true);
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

        if ($isUpdate && !canDo('warehouse.chemical_categories.edit'))   return $this->jsonError('Akses ditolak', 403);
        if (!$isUpdate && !canDo('warehouse.chemical_categories.create')) return $this->jsonError('Akses ditolak', 403);

        $rules = [
            'category_code' => 'required|max_length[30]|alpha_numeric_punct',
            'category_name' => 'required|max_length[100]',
            'status'        => 'required|in_list[Active,Draft,Archived]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse(['status' => 'error', 'errors' => $this->validator->getErrors()], 422);
        }

        $userId = auth()->id();
        $data = [
            'category_code' => strtoupper(trim($this->request->getPost('category_code'))),
            'category_name' => trim($this->request->getPost('category_name')),
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
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemical_categories.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->deleteData($id, auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemical_categories.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->restoreData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function forceDelete(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemical_categories.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->forceDeleteData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function emptyTrash()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemical_categories.delete')) return $this->jsonError('Akses ditolak', 403);

        $trashed = $this->model->onlyDeleted()->findAll();
        if (empty($trashed)) return $this->jsonResponse(['status' => 'success', 'message' => 'Sampah sudah kosong']);

        $deleted = 0;
        foreach ($trashed as $row) {
            if ($this->model->forceDeleteData($row['id'])['status'] === 'success') $deleted++;
        }
        return $this->jsonResponse(['status' => 'success', 'message' => "{$deleted} kategori berhasil dihapus permanen"]);
    }

    public function stats()
    {
        if (!canDo('warehouse.chemical_categories.view')) return $this->jsonError('Akses ditolak', 403);
        return $this->response->setJSON(['status' => 'success', 'data' => $this->model->getStats()]);
    }

    public function select2()
    {
        $search  = trim($this->request->getGet('search') ?? '');
        $builder = $this->model->db->table('chemical_categories')
            ->select('id, category_code AS code, category_name AS name')
            ->where('status', 'Active')->where('deleted_at', null)->orderBy('category_name', 'ASC');
        if ($search !== '') $builder->groupStart()->like('category_name', $search)->orLike('category_code', $search)->groupEnd();
        return $this->response->setJSON(['status' => 'success', 'data' => $builder->limit(50)->get()->getResultArray()]);
    }

    private function breadcrumbs(array $extra = []): array
    {
        $base = [
            ['name' => 'Dashboard',        'url' => site_url('dashboard')],
            ['name' => 'Warehouse',         'url' => site_url('warehouse')],
            ['name' => 'Kategori Kimia',    'url' => site_url('warehouse/chemical-categories')],
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
