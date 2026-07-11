<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\ChemicalStockOpeningModel;
use CodeIgniter\HTTP\RedirectResponse;

class ChemicalStockOpeningController extends BaseController
{
    protected ChemicalStockOpeningModel $model;

    public function __construct()
    {
        $this->model = new ChemicalStockOpeningModel();
    }

    public function index(): string|RedirectResponse
    {
        if (!canDo('warehouse.stock_opening.view')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\stock_opening\index', [
            'title'            => 'Stok Awal',
            'page_title'       => 'Stok Awal Bahan Kimia',
            'page_description' => 'Input stok awal per periode & gudang, atau lihat total gabungan seluruh gudang',
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    /**
     * Grid per gudang (editable) — GET ?period_id=&warehouse_id=
     */
    public function grid()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.stock_opening.view')) return $this->jsonError('Akses ditolak', 403);

        $periodId    = (int) $this->request->getGet('period_id');
        $warehouseId = (int) $this->request->getGet('warehouse_id');

        if (!$periodId || !$warehouseId) {
            return $this->jsonError('Periode dan gudang wajib dipilih', 422);
        }

        $period = \Config\Database::connect()->table('periods')->where('id', $periodId)->where('deleted_at', null)->get()->getRowArray();
        if (!$period) return $this->jsonError('Periode tidak ditemukan', 404);

        return $this->response->setJSON([
            'status'        => 'success',
            'data'          => $this->model->getGrid($periodId, $warehouseId),
            'is_initialized' => $this->model->isInitialized($periodId, $warehouseId),
            'period_status' => $period['status'],
        ]);
    }

    /**
     * Grid gabungan seluruh gudang (read-only) — GET ?period_id=
     */
    public function combinedGrid()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.stock_opening.view')) return $this->jsonError('Akses ditolak', 403);

        $periodId = (int) $this->request->getGet('period_id');
        if (!$periodId) return $this->jsonError('Periode wajib dipilih', 422);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getCombinedGrid($periodId),
        ]);
    }

    /**
     * Rincian per gudang untuk 1 bahan kimia — GET ?period_id=&chemical_id=
     */
    public function breakdown()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.stock_opening.view')) return $this->jsonError('Akses ditolak', 403);

        $periodId   = (int) $this->request->getGet('period_id');
        $chemicalId = (int) $this->request->getGet('chemical_id');
        if (!$periodId || !$chemicalId) return $this->jsonError('Parameter tidak lengkap', 422);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getBreakdown($periodId, $chemicalId),
        ]);
    }

    /**
     * Simpan bulk stok awal — POST period_id, warehouse_id, rows[] (JSON string)
     */
    public function store()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.stock_opening.create')) return $this->jsonError('Akses ditolak', 403);

        $periodId    = (int) $this->request->getPost('period_id');
        $warehouseId = (int) $this->request->getPost('warehouse_id');
        $rowsRaw     = $this->request->getPost('rows');

        if (!$periodId || !$warehouseId) {
            return $this->jsonError('Periode dan gudang wajib dipilih', 422);
        }

        $rows = is_string($rowsRaw) ? json_decode($rowsRaw, true) : $rowsRaw;
        if (!is_array($rows) || empty($rows)) {
            return $this->jsonError('Data stok awal kosong', 422);
        }

        $result = $this->model->saveBulk($periodId, $warehouseId, $rows, auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    /**
     * Cek apakah stok awal sudah diinput untuk kombinasi tertentu.
     * Dipakai modul Penerimaan/Pengeluaran/Alokasi sebelum mengizinkan transaksi.
     * GET ?period_id=&warehouse_id=&chemical_id=
     */
    public function status()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);

        $periodId    = (int) $this->request->getGet('period_id');
        $warehouseId = (int) $this->request->getGet('warehouse_id');
        $chemicalId  = (int) $this->request->getGet('chemical_id');

        if (!$periodId || !$warehouseId) {
            return $this->jsonError('Periode dan gudang wajib dipilih', 422);
        }

        $data = ['is_initialized' => $this->model->isInitialized($periodId, $warehouseId)];

        if ($chemicalId) {
            $data['has_opening_stock'] = $this->model->hasOpeningStock($periodId, $warehouseId, $chemicalId);
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }

    private function breadcrumbs(array $extra = []): array
    {
        $base = [
            ['name' => 'Dashboard', 'url' => site_url('dashboard')],
            ['name' => 'Warehouse',  'url' => site_url('warehouse')],
            ['name' => 'Stok Awal',  'url' => site_url('warehouse/stocks/opening')],
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
