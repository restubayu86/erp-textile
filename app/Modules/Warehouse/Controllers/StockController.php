<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\ChemicalStockModel;
use App\Modules\Warehouse\Models\ChemicalStockMovementModel;
use CodeIgniter\HTTP\RedirectResponse;

class StockController extends BaseController
{
    protected ChemicalStockModel $model;
    protected ChemicalStockMovementModel $movementModel;

    public function __construct()
    {
        $this->model         = new ChemicalStockModel();
        $this->movementModel = new ChemicalStockMovementModel();
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

    // ============================================================
    // PENERIMAAN (RECEIPT)
    // ============================================================

    public function receipt(): string|RedirectResponse
    {
        if (!canDo('warehouse.stocks.receive')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\stocks\receipt', [
            'title'            => 'Penerimaan Stok',
            'page_title'       => 'Penerimaan Stok Bahan Kimia',
            'page_description' => 'Catat penerimaan bahan kimia masuk ke gudang untuk periode berjalan',
            'breadcrumbs'      => $this->breadcrumbs('Penerimaan'),
        ]);
    }

    /**
     * Simpan bulk baris Penerimaan — POST period_id, warehouse_id, rows[] (JSON string).
     * Setiap baris di rows[] membawa movement_date sendiri (diisi per item lewat modal),
     * jadi satu kali "Simpan Penerimaan" boleh berisi tanggal yang berbeda-beda.
     */
    public function storeReceipt()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.stocks.receive')) return $this->jsonError('Akses ditolak', 403);

        $periodId    = (int) $this->request->getPost('period_id');
        $warehouseId = (int) $this->request->getPost('warehouse_id');
        $rowsRaw     = $this->request->getPost('rows');

        if (!$periodId || !$warehouseId) {
            return $this->jsonError('Periode dan gudang wajib dipilih', 422);
        }

        $rows = is_string($rowsRaw) ? json_decode($rowsRaw, true) : $rowsRaw;
        if (!is_array($rows) || empty($rows)) {
            return $this->jsonError('Belum ada item penerimaan yang ditambahkan', 422);
        }

        $result = $this->movementModel->saveReceiptBulk($periodId, $warehouseId, $rows, (int) auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    /**
     * Riwayat penerimaan — GET ?period_id=&warehouse_id=&from_date=&to_date=
     * from_date/to_date opsional: kosong semua = N terbaru, isi keduanya sama = per tanggal,
     * isi beda = rentang tanggal.
     */
    public function receiptRecent()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.stocks.receive')) return $this->jsonError('Akses ditolak', 403);

        $periodId    = (int) $this->request->getGet('period_id');
        $warehouseId = (int) $this->request->getGet('warehouse_id');
        if (!$periodId || !$warehouseId) {
            return $this->jsonError('Periode dan gudang wajib dipilih', 422);
        }

        $fromDate = trim((string) $this->request->getGet('from_date')) ?: null;
        $toDate   = trim((string) $this->request->getGet('to_date')) ?: null;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->movementModel->getRecent($periodId, $warehouseId, 'Receipt', $fromDate, $toDate, 30),
        ]);
    }

    /**
     * Batalkan 1 baris Penerimaan yang salah input — POST id=
     */
    public function receiptDelete()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.stocks.receive')) return $this->jsonError('Akses ditolak', 403);

        $id = (int) $this->request->getPost('id');
        if (!$id) return $this->jsonError('ID transaksi tidak valid', 422);

        $result = $this->movementModel->deleteOne($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
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
    private function jsonResponse(array $result, int $code = 200)
    {
        return $this->response->setStatusCode($code)->setJSON(array_merge($result, ['csrfHash' => csrf_hash()]));
    }
    private function jsonError(string $message, int $code = 500)
    {
        return $this->response->setStatusCode($code)->setJSON(['status' => 'error', 'message' => $message, 'csrfHash' => csrf_hash()]);
    }
}