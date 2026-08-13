<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

class FormulationStockOpeningModel extends Model
{
    protected $table            = 'formulation_stock_openings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'period_id',
        'warehouse_id',
        'formulation_id',
        'formulation_version_id',
        'quantity',
        'unit',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ============================================================
    // GRID — per gudang (editable)
    // ============================================================

    /**
     * Daftar semua formulasi aktif (versi Active) + nilai stok awal (kalau sudah pernah diinput)
     * untuk kombinasi periode + gudang tertentu.
     */
    public function getGrid(int $periodId, int $warehouseId): array
    {
        $formulations = $this->db->table('formulations f')
            ->select([
                'f.id as formulation_id',
                'f.formulation_code',
                'f.formulation_name',
                'f.process_type',
                'f.process_sub_type',
                'fg.group_name',
                'v.id as active_version_id',
                'v.version_no as active_version_no',
            ])
            ->join('formulation_versions v', 'v.id = f.current_version_id', 'left')
            ->join('formulation_groups fg', 'fg.id = f.group_id', 'left')
            ->where('v.status', 'Active')
            ->where('f.deleted_at', null)
            ->orderBy('f.formulation_name', 'ASC')
            ->get()->getResultArray();

        $existing = $this->where('period_id', $periodId)->where('warehouse_id', $warehouseId)->findAll();
        $existingMap = [];
        foreach ($existing as $row) {
            $existingMap[$row['formulation_id']] = $row;
        }

        foreach ($formulations as &$f) {
            $ex = $existingMap[$f['formulation_id']] ?? null;
            $f['opening_id'] = $ex['id'] ?? null;
            $f['quantity']   = $ex ? (float) $ex['quantity'] : null;
            $f['unit']       = $ex['unit'] ?? 'kg';
            $f['notes']      = $ex['notes'] ?? null;
            // Versi resep yang tersimpan di data (kalau sudah ada) vs versi aktif saat ini
            $f['saved_version_id'] = $ex['formulation_version_id'] ?? null;
        }

        return $formulations;
    }

    /**
     * Simpan bulk stok awal untuk 1 periode + 1 gudang.
     * $rows: array of ['formulation_id' => int, 'quantity' => float, 'notes' => string]
     */
    public function saveBulk(int $periodId, int $warehouseId, array $rows, int $userId): array
    {
        $period = $this->db->table('periods')->where('id', $periodId)->where('deleted_at', null)->get()->getRowArray();
        if (!$period) {
            return ['status' => 'error', 'message' => 'Periode tidak ditemukan'];
        }
        if ($period['status'] === 'Closed') {
            return ['status' => 'error', 'message' => 'Periode sudah ditutup, stok awal tidak bisa diubah'];
        }

        $warehouse = $this->db->table('warehouses')->where('id', $warehouseId)->where('deleted_at', null)->get()->getRowArray();
        if (!$warehouse) {
            return ['status' => 'error', 'message' => 'Gudang tidak ditemukan'];
        }

        // Peta formulation_id => versi resep AKTIF saat ini — dipakai untuk menandai
        // "resep versi yang memiliki stok" pada setiap baris yang disimpan.
        $activeVersions = $this->db->table('formulations')
            ->select('id as formulation_id, current_version_id')
            ->where('deleted_at', null)
            ->get()->getResultArray();
        $activeVersionMap = array_column($activeVersions, 'current_version_id', 'formulation_id');

        $saved = 0;
        foreach ($rows as $row) {
            $formulationId = (int) ($row['formulation_id'] ?? 0);
            if (!$formulationId) continue;

            $quantity  = is_numeric($row['quantity'] ?? null) ? (float) $row['quantity'] : 0;
            $unit      = 'kg';
            $notes     = trim($row['notes'] ?? '') ?: null;
            $versionId = $activeVersionMap[$formulationId] ?? null;

            $existing = $this->where('period_id', $periodId)
                ->where('warehouse_id', $warehouseId)
                ->where('formulation_id', $formulationId)
                ->first();

            if ($existing) {
                $this->update($existing['id'], [
                    'quantity'                => $quantity,
                    'unit'                     => $unit,
                    'notes'                    => $notes,
                    'formulation_version_id'   => $versionId,
                    'updated_by'               => $userId,
                ]);
            } else {
                $this->insert([
                    'period_id'               => $periodId,
                    'warehouse_id'            => $warehouseId,
                    'formulation_id'          => $formulationId,
                    'formulation_version_id'  => $versionId,
                    'quantity'                => $quantity,
                    'unit'                    => $unit,
                    'notes'                   => $notes,
                    'created_by'              => $userId,
                    'updated_by'              => $userId,
                ]);
            }
            $saved++;
        }

        return ['status' => 'success', 'message' => "Stok awal formulasi berhasil disimpan ({$saved} item)"];
    }

    // ============================================================
    // GABUNGAN — semua gudang (read-only)
    // ============================================================

    /**
     * Total stok awal per formulasi, digabung dari seluruh gudang, untuk 1 periode.
     */
    public function getCombinedGrid(int $periodId): array
    {
        $formulations = $this->db->table('formulations f')
            ->select([
                'f.id as formulation_id',
                'f.formulation_code',
                'f.formulation_name',
                'f.process_type',
                'f.process_sub_type',
                'fg.group_name',
            ])
            ->join('formulation_versions v', 'v.id = f.current_version_id', 'left')
            ->join('formulation_groups fg', 'fg.id = f.group_id', 'left')
            ->where('v.status', 'Active')
            ->where('f.deleted_at', null)
            ->orderBy('f.formulation_name', 'ASC')
            ->get()->getResultArray();

        $sums = $this->db->table('formulation_stock_openings')
            ->select('formulation_id, SUM(quantity) as total_quantity, COUNT(DISTINCT warehouse_id) as warehouse_count')
            ->where('period_id', $periodId)
            ->groupBy('formulation_id')
            ->get()->getResultArray();

        $sumMap = [];
        foreach ($sums as $s) {
            $sumMap[$s['formulation_id']] = $s;
        }

        foreach ($formulations as &$f) {
            $s = $sumMap[$f['formulation_id']] ?? null;
            $f['quantity']        = $s ? (float) $s['total_quantity'] : 0;
            $f['warehouse_count'] = $s ? (int) $s['warehouse_count'] : 0;
            $f['unit']            = 'kg';
        }

        return $formulations;
    }

    /**
     * Rincian per gudang untuk 1 formulasi dalam 1 periode (dipakai saat user klik baris di mode Gabungan).
     */
    public function getBreakdown(int $periodId, int $formulationId): array
    {
        return $this->db->table('formulation_stock_openings fso')
            ->select(['fso.warehouse_id', 'w.warehouse_name', 'w.warehouse_code', 'fso.quantity', 'fso.unit', 'fso.notes', 'v.version_no'])
            ->join('warehouses w', 'w.id = fso.warehouse_id', 'left')
            ->join('formulation_versions v', 'v.id = fso.formulation_version_id', 'left')
            ->where('fso.period_id', $periodId)
            ->where('fso.formulation_id', $formulationId)
            ->orderBy('w.warehouse_name', 'ASC')
            ->get()->getResultArray();
    }

    // ============================================================
    // HELPERS — dipakai modul lain (transfer/adjustment/aplikasi produksi)
    // ============================================================

    /**
     * Apakah kombinasi periode+gudang+formulasi ini SUDAH punya stok awal?
     * WAJIB dipanggil oleh modul Transfer/Adjustment sebelum mengizinkan transaksi baru dibuat.
     */
    public function hasOpeningStock(int $periodId, int $warehouseId, int $formulationId): bool
    {
        return $this->where('period_id', $periodId)
            ->where('warehouse_id', $warehouseId)
            ->where('formulation_id', $formulationId)
            ->countAllResults() > 0;
    }

    /**
     * Apakah proses input stok awal sudah pernah dilakukan sama sekali
     * untuk kombinasi periode+gudang ini (dipakai untuk banner peringatan di UI).
     */
    public function isInitialized(int $periodId, int $warehouseId): bool
    {
        return $this->where('period_id', $periodId)
            ->where('warehouse_id', $warehouseId)
            ->countAllResults() > 0;
    }

    // ============================================================
    // TARIK DARI PERIODE SEBELUMNYA
    // ============================================================

    /**
     * Cari periode tepat sebelum periode tertentu (berdasarkan urutan start_date).
     */
    private function getPreviousPeriod(int $periodId): ?array
    {
        $current = $this->db->table('periods')->where('id', $periodId)->get()->getRowArray();
        if (!$current) return null;

        return $this->db->table('periods')
            ->where('start_date <', $current['start_date'])
            ->where('deleted_at', null)
            ->orderBy('start_date', 'DESC')
            ->limit(1)
            ->get()->getRowArray();
    }

    /**
     * Hitung saldo akhir formulasi (stok awal + pergerakan) untuk 1 periode + 1 gudang.
     * Dipakai sebagai sumber "Tarik dari Periode Sebelumnya".
     */
    private function getClosingBalances(int $periodId, int $warehouseId): array
    {
        // Ambil baris opening (bisa lebih dari 1 versi per formulasi dalam kasus edge)
        $openings = $this->where('period_id', $periodId)->where('warehouse_id', $warehouseId)
            ->orderBy('id', 'DESC')->findAll();

        $closing = []; // formulation_id => ['quantity' => float, 'version_id' => int|null]
        foreach ($openings as $o) {
            $fid = $o['formulation_id'];
            if (!isset($closing[$fid])) {
                $closing[$fid] = ['quantity' => (float) $o['quantity'], 'version_id' => $o['formulation_version_id']];
            } else {
                $closing[$fid]['quantity'] += (float) $o['quantity'];
            }
        }

        $movements = $this->db->table('formulation_stock_movements')
            ->select('formulation_id, SUM(quantity_in) as total_in, SUM(quantity_out) as total_out')
            ->where('period_id', $periodId)
            ->where('warehouse_id', $warehouseId)
            ->groupBy('formulation_id')
            ->get()->getResultArray();

        foreach ($movements as $m) {
            $fid = $m['formulation_id'];
            $net = (float) $m['total_in'] - (float) $m['total_out'];
            if (!isset($closing[$fid])) {
                $closing[$fid] = ['quantity' => $net, 'version_id' => null];
            } else {
                $closing[$fid]['quantity'] += $net;
            }
        }

        return $closing;
    }

    /**
     * Ambil saldo akhir periode sebelumnya untuk 1 gudang — dipakai untuk
     * mengisi otomatis form Stok Awal (user tetap harus klik Simpan).
     */
    public function pullFromPreviousPeriod(int $currentPeriodId, int $warehouseId): array
    {
        $prevPeriod = $this->getPreviousPeriod($currentPeriodId);
        if (!$prevPeriod) {
            return ['status' => 'error', 'message' => 'Tidak ada periode sebelumnya untuk ditarik'];
        }

        $closing = $this->getClosingBalances((int) $prevPeriod['id'], $warehouseId);

        // Lengkapi version_no untuk ditampilkan di UI
        $versionIds = array_filter(array_column($closing, 'version_id'));
        $versionMap = [];
        if (!empty($versionIds)) {
            $versions = $this->db->table('formulation_versions')
                ->select('id, version_no')
                ->whereIn('id', $versionIds)
                ->get()->getResultArray();
            $versionMap = array_column($versions, 'version_no', 'id');
        }

        $data = [];
        foreach ($closing as $formulationId => $c) {
            $data[$formulationId] = [
                'quantity'   => $c['quantity'],
                'version_id' => $c['version_id'],
                'version_no' => $c['version_id'] ? ($versionMap[$c['version_id']] ?? null) : null,
            ];
        }

        return [
            'status' => 'success',
            'period' => [
                'id'   => $prevPeriod['id'],
                'code' => $prevPeriod['period_code'],
                'name' => $prevPeriod['period_name'],
            ],
            'data' => $data,
        ];
    }
}
