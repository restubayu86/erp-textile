<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\ChemicalModel;
use CodeIgniter\HTTP\RedirectResponse;
use Hermawan\DataTables\DataTable;

class ChemicalController extends BaseController
{
    protected ChemicalModel $model;

    public function __construct()
    {
        $this->model = new ChemicalModel();
    }

    // ============================================================
    // PAGES
    // ============================================================

    public function index(): string|RedirectResponse
    {
        if (!canDo('warehouse.chemicals.view')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\chemicals\index', [
            'title'            => 'Bahan Kimia',
            'page_title'       => 'Daftar Bahan Kimia',
            'page_description' => 'Kelola data bahan kimia beserta variannya',
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    public function trash(): string|RedirectResponse
    {
        if (!canDo('warehouse.chemicals.delete')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\chemicals\trash', [
            'title'            => 'Sampah — Bahan Kimia',
            'page_title'       => 'Sampah Bahan Kimia',
            'page_description' => 'Bahan kimia yang telah dihapus',
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Sampah', 'active' => true]]),
        ]);
    }

    // ============================================================
    // DATATABLE
    // ============================================================

    public function datatables()
    {
        if (!canDo('warehouse.chemicals.view')) return $this->jsonError('Akses ditolak', 403);

        $db      = \Config\Database::connect();
        $builder = $db->table('chemicals c')
            ->select([
                'c.id',
                'c.chemical_code',
                'c.chemical_name',
                'c.description',
                'c.status',
                'c.created_at',
                'c.updated_at',
                'cc.category_name',
                'cc.category_code',
                'cu.username as created_by_name',
                'uu.username as updated_by_name',
                'COUNT(cv.id) as variant_count',
            ])
            ->join('chemical_categories cc', 'cc.id = c.category_id', 'left')
            ->join('users cu', 'cu.id = c.created_by', 'left')
            ->join('users uu', 'uu.id = c.updated_by', 'left')
            ->join('chemical_variants cv', 'cv.chemical_id = c.id AND cv.status = "Active"', 'left')
            ->where('c.deleted_at', null)
            ->groupBy('c.id');

        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()->like('c.chemical_name', $name)->orLike('c.chemical_code', $name)->groupEnd();
        }
        if ($category = trim($this->request->getGet('filter_category') ?? '')) {
            $builder->where('c.category_id', $category);
        }
        if ($status = $this->request->getGet('filter_status')) {
            $builder->where('c.status', $status);
        }

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['c.chemical_name', 'c.chemical_code', 'cc.category_name'])
            ->toJson(true);
    }

    public function trashDatatables()
    {
        if (!canDo('warehouse.chemicals.delete')) return $this->jsonError('Akses ditolak', 403);

        $db      = \Config\Database::connect();
        $builder = $db->table('chemicals c')
            ->select([
                'c.id',
                'c.chemical_code',
                'c.chemical_name',
                'c.status',
                'c.deleted_at',
                'cc.category_name',
                'du.username as deleted_by_name'
            ])
            ->join('chemical_categories cc', 'cc.id = c.category_id', 'left')
            ->join('users du', 'du.id = c.deleted_by', 'left')
            ->where('c.deleted_at IS NOT NULL');

        return DataTable::of($builder)->addNumbering('no')
            ->setSearchableColumns(['c.chemical_name', 'c.chemical_code'])->toJson(true);
    }

    // ============================================================
    // AJAX — CHEMICAL CRUD
    // ============================================================

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

        if ($isUpdate && !canDo('warehouse.chemicals.edit'))   return $this->jsonError('Akses ditolak', 403);
        if (!$isUpdate && !canDo('warehouse.chemicals.create')) return $this->jsonError('Akses ditolak', 403);

        $rules = [
            'chemical_code' => 'required|max_length[50]|alpha_numeric_punct',
            'chemical_name' => 'required|max_length[150]',
            'status'        => 'required|in_list[Active,Draft,Archived]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse(['status' => 'error', 'errors' => $this->validator->getErrors()], 422);
        }

        $userId = auth()->id();
        $data = [
            'chemical_code' => strtoupper(trim($this->request->getPost('chemical_code'))),
            'chemical_name' => trim($this->request->getPost('chemical_name')),
            'category_id'   => $this->request->getPost('category_id') ?: null,
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

        if ($result['status'] !== 'success') {
            return $this->jsonResponse($result, 422);
        }

        $savedId = $isUpdate ? $id : $result['id'];

        // Simpan varian jika dikirim
        $variants = $this->request->getPost('variants');
        if (!empty($variants) && is_array($variants)) {
            $this->model->saveVariants($savedId, $variants, $userId);
        }

        return $this->jsonResponse($result);
    }

    public function delete(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemicals.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->deleteData($id, auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemicals.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->restoreData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function forceDelete(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemicals.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->forceDeleteData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function emptyTrash()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemicals.delete')) return $this->jsonError('Akses ditolak', 403);

        $trashed = $this->model->onlyDeleted()->findAll();
        if (empty($trashed)) return $this->jsonResponse(['status' => 'success', 'message' => 'Sampah sudah kosong']);

        $deleted = 0;
        foreach ($trashed as $row) {
            if ($this->model->forceDeleteData($row['id'])['status'] === 'success') $deleted++;
        }
        return $this->jsonResponse(['status' => 'success', 'message' => "{$deleted} bahan kimia berhasil dihapus permanen"]);
    }

    // ============================================================
    // AJAX — VARIANT CRUD
    // ============================================================

    public function getVariants(int $chemicalId)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemicals.view')) return $this->jsonError('Akses ditolak', 403);

        return $this->jsonResponse([
            'status' => 'success',
            'data'   => $this->model->getVariants($chemicalId),
        ]);
    }

    public function storeVariant(int $chemicalId)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemicals.edit')) return $this->jsonError('Akses ditolak', 403);

        $rules = [
            'variant_name' => 'required|max_length[100]',
            'unit'         => 'permit_empty|max_length[20]',
            'price'        => 'permit_empty|decimal',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse(['status' => 'error', 'errors' => $this->validator->getErrors()], 422);
        }

        $userId = auth()->id();
        $data   = [
            'chemical_id'    => $chemicalId,
            'variant_name'   => trim($this->request->getPost('variant_name')),
            'packaging'      => trim($this->request->getPost('packaging') ?? '') ?: null,
            'packaging_size' => $this->request->getPost('packaging_size') ?: null,
            'unit'           => trim($this->request->getPost('unit') ?? '') ?: null,
            'price'          => $this->request->getPost('price') ?: null,
            'is_default'     => (int) ($this->request->getPost('is_default') ?? 0),
            'status'         => $this->request->getPost('status') ?? 'Active',
            'created_by'     => $userId,
            'updated_by'     => $userId,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        // Jika is_default, reset yang lain
        if ($data['is_default']) {
            \Config\Database::connect()->table('chemical_variants')
                ->where('chemical_id', $chemicalId)
                ->update(['is_default' => 0]);
        }

        \Config\Database::connect()->table('chemical_variants')->insert($data);
        $id = \Config\Database::connect()->insertID();

        return $this->jsonResponse([
            'status'  => 'success',
            'message' => 'Varian berhasil ditambahkan',
            'id'      => $id,
        ]);
    }

    public function deleteVariant(int $variantId)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemicals.edit')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->deleteVariant($variantId);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function setDefaultVariant(int $chemicalId, int $variantId)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.chemicals.edit')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->setDefaultVariant($chemicalId, $variantId);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    // ============================================================
    // STATS & SELECT2
    // ============================================================

    public function stats()
    {
        if (!canDo('warehouse.chemicals.view')) return $this->jsonError('Akses ditolak', 403);
        return $this->response->setJSON(['status' => 'success', 'data' => $this->model->getStats()]);
    }

    public function select2()
    {
        $search  = trim($this->request->getGet('search') ?? '');
        $builder = $this->model->db->table('chemicals c')
            ->select('c.id, c.chemical_code AS code, c.chemical_name AS name, cc.category_name')
            ->join('chemical_categories cc', 'cc.id = c.category_id', 'left')
            ->where('c.status', 'Active')->where('c.deleted_at', null)->orderBy('c.chemical_name', 'ASC');
        if ($search !== '') $builder->groupStart()->like('c.chemical_name', $search)->orLike('c.chemical_code', $search)->groupEnd();
        return $this->response->setJSON(['status' => 'success', 'data' => $builder->limit(50)->get()->getResultArray()]);
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function breadcrumbs(array $extra = []): array
    {
        $base = [
            ['name' => 'Dashboard',    'url' => site_url('dashboard')],
            ['name' => 'Warehouse',     'url' => site_url('warehouse')],
            ['name' => 'Bahan Kimia',   'url' => site_url('warehouse/chemicals')],
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
