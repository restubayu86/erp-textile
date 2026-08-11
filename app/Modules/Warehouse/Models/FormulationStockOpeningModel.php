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

        $saved = 0;
        foreach ($rows as $row) {
            $formulationId = (int) ($row['formulation_id'] ?? 0);
            if (!$formulationId) continue;

            $quantity = is_numeric($row['quantity'] ?? null) ? (float) $row['quantity'] : 0;
            $unit     = 'kg';
            $notes    = trim($row['notes'] ?? '') ?: null;

            $existing = $this->where('period_id', $periodId)
                ->where('warehouse_id', $warehouseId)
                ->where('formulation_id', $formulationId)
                ->first();

            if ($existing) {
                $this->update($existing['id'], [
                    'quantity'   => $quantity,
                    'unit'       => $unit,
                    'notes'      => $notes,
                    'updated_by' => $userId,
                ]);
            } else {
                $this->insert([
                    'period_id'      => $periodId,
                    'warehouse_id'   => $warehouseId,
                    'formulation_id' => $formulationId,
                    'quantity'       => $quantity,
                    'unit'           => $unit,
                    'notes'          => $notes,
                    'created_by'     => $userId,
                    'updated_by'     => $userId,
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
            ->select(['fso.warehouse_id', 'w.warehouse_name', 'w.warehouse_code', 'fso.quantity', 'fso.unit', 'fso.notes'])
            ->join('warehouses w', 'w.id = fso.warehouse_id', 'left')
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
}
