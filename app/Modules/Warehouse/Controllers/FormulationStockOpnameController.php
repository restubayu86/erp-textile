<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\FormulationStockOpnameModel;
use CodeIgniter\HTTP\RedirectResponse;

class FormulationStockOpnameController extends BaseController
{
    protected FormulationStockOpnameModel $model;

    public function __construct()
    {
        $this->model = new FormulationStockOpnameModel();
    }

    public function index(): string|RedirectResponse
    {
        if (!canDo('warehouse.formulation_stock_opname.view')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\formulation_stock_opname\index', [
            'title'            => 'Stock Opname Formulasi',
            'page_title'       => 'Stock Opname Formulasi',
            'page_description' => 'Catat hasil hitung fisik stok formulasi (premix) di akhir periode, untuk dibandingkan dengan catatan sistem',
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    public function grid()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulation_stock_opname.view')) return $this->jsonError('Akses ditolak', 403);

        $periodId    = (int) $this->request->getGet('period_id');
        $warehouseId = (int) $this->request->getGet('warehouse_id');
        if (!$periodId || !$warehouseId) return $this->jsonError('Periode dan gudang wajib dipilih', 422);
        if (!warehouseAccessAllowed($this->getWarehouseScope(), $warehouseId)) {
            return $this->jsonError('Anda tidak memiliki akses ke gudang ini', 403);
        }

        $period = \Config\Database::connect()->table('periods')->where('id', $periodId)->where('deleted_at', null)->get()->getRowArray();
        if (!$period) return $this->jsonError('Periode tidak ditemukan', 404);

        return $this->response->setJSON([
            'status'         => 'success',
            'data'           => $this->model->getGrid($periodId, $warehouseId),
            'is_initialized' => $this->model->isInitialized($periodId, $warehouseId),
            'period_status'  => $period['status'],
        ]);
    }

    public function combinedGrid()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulation_stock_opname.view')) return $this->jsonError('Akses ditolak', 403);

        $periodId = (int) $this->request->getGet('period_id');
        if (!$periodId) return $this->jsonError('Periode wajib dipilih', 422);

        if ($this->getWarehouseScope() !== null) {
            return $this->jsonError('Mode "Gabungan Semua Gudang" tidak tersedia untuk akun Anda', 403);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getCombinedGrid($periodId),
        ]);
    }

    public function breakdown()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulation_stock_opname.view')) return $this->jsonError('Akses ditolak', 403);

        $periodId      = (int) $this->request->getGet('period_id');
        $formulationId = (int) $this->request->getGet('formulation_id');
        if (!$periodId || !$formulationId) return $this->jsonError('Parameter tidak lengkap', 422);

        if ($this->getWarehouseScope() !== null) {
            return $this->jsonError('Anda tidak memiliki akses ke rincian gabungan seluruh gudang', 403);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getBreakdown($periodId, $formulationId),
        ]);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulation_stock_opname.create')) return $this->jsonError('Akses ditolak', 403);

        $periodId    = (int) $this->request->getPost('period_id');
        $warehouseId = (int) $this->request->getPost('warehouse_id');
        $rowsRaw     = $this->request->getPost('rows');

        if (!$periodId || !$warehouseId) return $this->jsonError('Periode dan gudang wajib dipilih', 422);
        if (!warehouseAccessAllowed($this->getWarehouseScope(), $warehouseId)) {
            return $this->jsonError('Anda tidak memiliki akses ke gudang ini', 403);
        }

        $rows = is_string($rowsRaw) ? json_decode($rowsRaw, true) : $rowsRaw;
        if (!is_array($rows) || empty($rows)) return $this->jsonError('Data opname kosong', 422);

        $result = $this->model->saveBulk($periodId, $warehouseId, $rows, auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    private function breadcrumbs(): array
    {
        return [
            ['name' => 'Dashboard', 'url' => site_url('dashboard')],
            ['name' => 'Warehouse', 'url' => site_url('warehouse')],
            ['name' => 'Stock Opname Formulasi', 'active' => true],
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
