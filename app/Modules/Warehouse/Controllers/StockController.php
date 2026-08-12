<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\ChemicalStockModel;
use CodeIgniter\HTTP\RedirectResponse;

class StockController extends BaseController
{
    protected ChemicalStockModel $model;

    public function __construct()
    {
        $this->model = new ChemicalStockModel();
    }

    /**
     * Landing "Stok Kimia" — arahkan ke Posisi Stok sebagai default.
     */
    public function index(): RedirectResponse
    {
        return redirect()->to(site_url('warehouse/stocks/position'));
    }

    // ============================================================
    // POSISI STOK
    // ============================================================
    public function position(): string|RedirectResponse
    {
        if (!canDo('warehouse.stocks.view')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\stocks\position', [
            'title'            => 'Posisi Stok',
            'page_title'       => 'Posisi Stok Bahan Kimia',
            'page_description' => 'Saldo stok kimia per gudang atau gabungan seluruh gudang, dihitung dari stok awal + transaksi berjalan',
            'breadcrumbs'      => $this->breadcrumbs('Posisi Stok'),
        ]);
    }

    /**
     * Grid posisi per gudang — GET ?period_id=&warehouse_id=
     */
    public function positionGrid()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.stocks.view')) return $this->jsonError('Akses ditolak', 403);

        $periodId    = (int) $this->request->getGet('period_id');
        $warehouseId = (int) $this->request->getGet('warehouse_id');
        if (!$periodId || !$warehouseId) return $this->jsonError('Periode dan gudang wajib dipilih', 422);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getPositionGrid($periodId, $warehouseId),
        ]);
    }

    /**
     * Grid posisi gabungan seluruh gudang — GET ?period_id=
     */
    public function positionCombinedGrid()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.stocks.view')) return $this->jsonError('Akses ditolak', 403);

        $periodId = (int) $this->request->getGet('period_id');
        if (!$periodId) return $this->jsonError('Periode wajib dipilih', 422);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getPositionCombinedGrid($periodId),
        ]);
    }

    /**
     * Rincian posisi per gudang untuk 1 bahan kimia — GET ?period_id=&chemical_id=
     */
    public function positionBreakdown()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.stocks.view')) return $this->jsonError('Akses ditolak', 403);

        $periodId   = (int) $this->request->getGet('period_id');
        $chemicalId = (int) $this->request->getGet('chemical_id');
        if (!$periodId || !$chemicalId) return $this->jsonError('Parameter tidak lengkap', 422);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getPositionBreakdown($periodId, $chemicalId),
        ]);
    }

    // ============================================================
    // KARTU STOK
    // ============================================================
    public function stockCard(): string|RedirectResponse
    {
        if (!canDo('warehouse.stocks.view')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\stocks\stock_card', [
            'title'            => 'Kartu Stok',
            'page_title'       => 'Kartu Stok Bahan Kimia',
            'page_description' => 'Histori transaksi & saldo berjalan 1 bahan kimia di 1 gudang, untuk periode tertentu',
            'breadcrumbs'      => $this->breadcrumbs('Kartu Stok'),
        ]);
    }

    /**
     * Data kartu stok — GET ?period_id=&warehouse_id=&chemical_id=&from_date=&to_date=
     * from_date/to_date opsional (format Y-m-d) untuk mempersempit tampilan ke rentang tanggal tertentu.
     */
    public function stockCardData()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.stocks.view')) return $this->jsonError('Akses ditolak', 403);

        $periodId    = (int) $this->request->getGet('period_id');
        $warehouseId = (int) $this->request->getGet('warehouse_id');
        $chemicalId  = (int) $this->request->getGet('chemical_id');
        $fromDate    = trim((string) $this->request->getGet('from_date')) ?: null;
        $toDate      = trim((string) $this->request->getGet('to_date')) ?: null;

        if (!$periodId || !$warehouseId || !$chemicalId) {
            return $this->jsonError('Periode, gudang, dan bahan kimia wajib dipilih', 422);
        }

        if ($fromDate && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)) {
            return $this->jsonError('Format tanggal dari tidak valid', 422);
        }
        if ($toDate && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)) {
            return $this->jsonError('Format tanggal sampai tidak valid', 422);
        }
        if ($fromDate && $toDate && $fromDate > $toDate) {
            return $this->jsonError('Tanggal dari tidak boleh lebih besar dari tanggal sampai', 422);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getStockCard($periodId, $warehouseId, $chemicalId, $fromDate, $toDate),
        ]);
    }

    private function breadcrumbs(string $label): array
    {
        return [
            ['name' => 'Dashboard', 'url' => site_url('dashboard')],
            ['name' => 'Warehouse', 'url' => site_url('warehouse')],
            ['name' => $label, 'active' => true],
        ];
    }

    private function forbidden(): RedirectResponse
    {
        return redirect()->to(site_url('errors/403'));
    }
    private function jsonError(string $message, int $code = 500)
    {
        return $this->response->setStatusCode($code)->setJSON(['status' => 'error', 'message' => $message, 'csrfHash' => csrf_hash()]);
    }
}
