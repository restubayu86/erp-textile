<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\ChemicalModel;
use App\Modules\Warehouse\Models\ChemicalVariantModel;
use CodeIgniter\HTTP\RedirectResponse;
use Hermawan\DataTables\DataTable;

class ChemicalController extends BaseController
{
    protected ChemicalModel $model;
    protected ChemicalVariantModel $variantModel;

    public function __construct()
    {
        $this->model        = new ChemicalModel();
        $this->variantModel = new ChemicalVariantModel();
    }

    // ============================================================
    // PAGES
    // ============================================================

    public function index(): string|RedirectResponse
    {
        if (!canDo('warehouse.chemicals.view')) {
            return $this->forbidden();
        }

        return view('App\Modules\Warehouse\Views\chemicals\index', [
            'title'            => 'Bahan Kimia',
            'page_title'       => 'Daftar Bahan Kimia',
            'page_description' => 'Kelola data bahan kimia beserta variannya',
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    public function trash(): string|RedirectResponse
    {
        if (!canDo('warehouse.chemicals.delete')) {
            return $this->forbidden();
        }

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
        if (!canDo('warehouse.chemicals.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

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
                'cu.username as created_by_name',
                'uu.username as updated_by_name',
                // Gunakan subquery untuk menghitung variant
                '(SELECT COUNT(*) FROM chemical_variants cv WHERE cv.chemical_id = c.id AND cv.status = "Active") as variant_count',
                'GROUP_CONCAT(DISTINCT cc.category_name ORDER BY cc.category_name SEPARATOR ", ") as category_names',
            ])
            ->join('users cu', 'cu.id = c.created_by', 'left')
            ->join('users uu', 'uu.id = c.updated_by', 'left')
            ->join('chemical_category_map m', 'm.chemical_id = c.id', 'left')
            ->join('chemical_categories cc', 'cc.id = m.category_id', 'left')
            ->where('c.deleted_at', null)
            ->groupBy('c.id');

        // Filter dengan prepared statement untuk keamanan
        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('c.chemical_name', $name)
                ->orLike('c.chemical_code', $name)
                ->groupEnd();
        }

        // Cast ke integer untuk keamanan
        if ($category = $this->request->getGet('filter_category')) {
            $builder->where('m.category_id', (int) $category);
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
        if (!canDo('warehouse.chemicals.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('chemicals c')
            ->select([
                'c.id',
                'c.chemical_code',
                'c.chemical_name',
                'c.status',
                'c.deleted_at',
                'du.username as deleted_by_name',
                'GROUP_CONCAT(DISTINCT cc.category_name ORDER BY cc.category_name SEPARATOR ", ") as category_names',
            ])
            ->join('users du', 'du.id = c.deleted_by', 'left')
            ->join('chemical_category_map m', 'm.chemical_id = c.id', 'left')
            ->join('chemical_categories cc', 'cc.id = m.category_id', 'left')
            ->where('c.deleted_at IS NOT NULL')
            ->groupBy('c.id');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['c.chemical_name', 'c.chemical_code'])
            ->toJson(true);
    }

    // ============================================================
    // AJAX — CHEMICAL CRUD
    // ============================================================

    public function get(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('warehouse.chemicals.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $result = $this->model->getData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        $id = (int) $this->request->getPost('id');
        $isUpdate = $id > 0;

        if ($isUpdate && !canDo('warehouse.chemicals.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        if (!$isUpdate && !canDo('warehouse.chemicals.create')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        // Validasi
        $rules = [
            'chemical_name' => 'required|max_length[150]',
            'status'        => 'required|in_list[Active,Draft,Archived]',
            'description'   => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ], 422);
        }

        $userId = auth()->id();
        $data = [
            'chemical_name' => trim($this->request->getPost('chemical_name')),
            'description'   => trim($this->request->getPost('description') ?? '') ?: null,
            'status'        => $this->request->getPost('status'),
        ];

        // Category IDs - sanitasi
        $categoryIds = $this->request->getPost('category_ids') ?? [];
        if (!is_array($categoryIds)) {
            $categoryIds = [$categoryIds];
        }
        $categoryIds = array_filter(array_map('intval', $categoryIds));

        if ($isUpdate) {
            $data['updated_by'] = $userId;
            $result = $this->model->updateData($id, $data, $categoryIds);
        } else {
            $data['created_by'] = $userId;
            $result = $this->model->createData($data, $categoryIds);
        }

        if ($result['status'] !== 'success') {
            return $this->jsonResponse($result, 422);
        }

        return $this->jsonResponse($result);
    }

    public function delete(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('warehouse.chemicals.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $result = $this->model->deleteData($id, auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('warehouse.chemicals.delete')) {
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

        if (!canDo('warehouse.chemicals.delete')) {
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

        if (!canDo('warehouse.chemicals.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $trashed = $this->model->onlyDeleted()->findAll();
        if (empty($trashed)) {
            return $this->jsonResponse(['status' => 'success', 'message' => 'Sampah sudah kosong']);
        }

        $deleted = 0;
        foreach ($trashed as $row) {
            if ($this->model->forceDeleteData($row['id'])['status'] === 'success') {
                $deleted++;
            }
        }

        return $this->jsonResponse([
            'status' => 'success',
            'message' => "{$deleted} bahan kimia berhasil dihapus permanen"
        ]);
    }

    // ============================================================
    // AJAX — VARIANT CRUD (modal per baris)
    // ============================================================

    public function getVariants(int $chemicalId)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('warehouse.chemicals.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        try {
            $chemical = $this->model->find($chemicalId);
            if (!$chemical) {
                return $this->jsonError('Bahan kimia tidak ditemukan', 404);
            }

            return $this->jsonResponse([
                'status'   => 'success',
                'chemical' => [
                    'id'            => $chemical['id'],
                    'chemical_code' => $chemical['chemical_code'],
                    'chemical_name' => $chemical['chemical_name'],
                ],
                'data' => $this->variantModel->listByChemical($chemicalId),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'getVariants: ' . $e->getMessage());
            return $this->jsonError('Gagal memuat varian: ' . $e->getMessage(), 500);
        }
    }

    public function storeVariant(int $chemicalId)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('warehouse.chemicals.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        try {
            if (!$this->model->find($chemicalId)) {
                return $this->jsonError('Bahan kimia tidak ditemukan', 404);
            }

            // Validasi
            $rules = [
                'variant_name' => 'required|max_length[100]',
                'packaging'    => 'permit_empty|max_length[50]',
                'packaging_size' => 'permit_empty|decimal',
                'unit'         => 'permit_empty|max_length[20]',
                'price'        => 'permit_empty|decimal|greater_than_equal_to[0]',
                'status'       => 'permit_empty|in_list[Active,Archived]',
            ];

            if (!$this->validate($rules)) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors()
                ], 422);
            }

            $userId = (int) auth()->id();

            // Hanya ambil field yang diizinkan
            $data = $this->request->getPost([
                'variant_name',
                'packaging',
                'packaging_size',
                'unit',
                'price',
                'is_default',
                'status'
            ]);

            // Sanitasi
            $data['variant_name'] = trim($data['variant_name'] ?? '');
            $data['packaging'] = trim($data['packaging'] ?? '');
            $data['unit'] = trim($data['unit'] ?? '');
            $data['is_default'] = (int) ($data['is_default'] ?? 0);
            $data['status'] = $data['status'] ?? 'Active';

            $result = $this->variantModel->createVariant($chemicalId, $data, $userId);
            return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
        } catch (\Throwable $e) {
            log_message('error', 'storeVariant: ' . $e->getMessage());
            return $this->jsonError('Gagal menambahkan varian: ' . $e->getMessage(), 500);
        }
    }

    public function updateVariant(int $chemicalId, int $variantId)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('warehouse.chemicals.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        try {
            // Validasi
            $rules = [
                'variant_name' => 'required|max_length[100]',
                'packaging'    => 'permit_empty|max_length[50]',
                'packaging_size' => 'permit_empty|decimal',
                'unit'         => 'permit_empty|max_length[20]',
                'price'        => 'permit_empty|decimal|greater_than_equal_to[0]',
                'status'       => 'permit_empty|in_list[Active,Archived]',
            ];

            if (!$this->validate($rules)) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors()
                ], 422);
            }

            $userId = (int) auth()->id();

            // Hanya ambil field yang diizinkan
            $data = $this->request->getPost([
                'variant_name',
                'packaging',
                'packaging_size',
                'unit',
                'price',
                'is_default',
                'status'
            ]);

            // Sanitasi
            $data['variant_name'] = trim($data['variant_name'] ?? '');
            $data['packaging'] = trim($data['packaging'] ?? '');
            $data['unit'] = trim($data['unit'] ?? '');
            $data['is_default'] = (int) ($data['is_default'] ?? 0);
            $data['status'] = $data['status'] ?? 'Active';

            $result = $this->variantModel->updateVariant($variantId, $chemicalId, $data, $userId);
            return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
        } catch (\Throwable $e) {
            log_message('error', 'updateVariant: ' . $e->getMessage());
            return $this->jsonError('Gagal memperbarui varian: ' . $e->getMessage(), 500);
        }
    }

    public function deleteVariant(int $variantId)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('warehouse.chemicals.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        try {
            // Gunakan method yang sudah diperbaiki
            $result = $this->variantModel->deleteVariantById($variantId);
            return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
        } catch (\Throwable $e) {
            log_message('error', 'deleteVariant: ' . $e->getMessage());
            return $this->jsonError('Gagal menghapus varian: ' . $e->getMessage(), 500);
        }
    }

    public function setDefaultVariant(int $chemicalId, int $variantId)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('warehouse.chemicals.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        try {
            $result = $this->variantModel->setDefault($variantId, $chemicalId);
            return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
        } catch (\Throwable $e) {
            log_message('error', 'setDefaultVariant: ' . $e->getMessage());
            return $this->jsonError('Gagal mengatur varian default: ' . $e->getMessage(), 500);
        }
    }

    // ============================================================
    // STATS & SELECT2
    // ============================================================

    public function stats()
    {
        if (!canDo('warehouse.chemicals.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $this->model->getStats()
        ]);
    }

    public function select2()
    {
        if (!canDo('warehouse.chemicals.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $search = trim($this->request->getGet('search') ?? '');
        $builder = $this->model->db->table('chemicals c')
            ->select('c.id, c.chemical_code AS code, c.chemical_name AS name')
            ->where('c.status', 'Active')
            ->where('c.deleted_at', null)
            ->orderBy('c.chemical_name', 'ASC');

        if ($search !== '') {
            $builder->groupStart()
                ->like('c.chemical_name', $search)
                ->orLike('c.chemical_code', $search)
                ->groupEnd();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $builder->limit(50)->get()->getResultArray()
        ]);
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function breadcrumbs(array $extra = []): array
    {
        $base = [
            ['name' => 'Dashboard', 'url' => site_url('dashboard')],
            ['name' => 'Warehouse', 'url' => site_url('warehouse')],
            ['name' => 'Bahan Kimia', 'url' => site_url('warehouse/master/chemicals')],
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
            ->setJSON([
                'status' => 'error',
                'message' => $message,
                'csrfHash' => csrf_hash()
            ]);
    }
}
