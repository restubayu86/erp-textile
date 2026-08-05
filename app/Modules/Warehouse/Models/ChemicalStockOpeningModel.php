<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

class ChemicalStockOpeningModel extends Model
{
    protected $table            = 'chemical_stock_openings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'period_id',
        'warehouse_id',
        'chemical_id',
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
     * Daftar semua bahan kimia aktif + nilai stok awal (kalau sudah pernah diinput)
     * untuk kombinasi periode + gudang tertentu.
     */
    public function getGrid(int $periodId, int $warehouseId): array
    {
        $chemicals = $this->db->table('chemicals c')
            ->select([
                'c.id as chemical_id',
                'c.chemical_code',
                'c.chemical_name',
                'GROUP_CONCAT(DISTINCT cc.category_name ORDER BY cc.category_name SEPARATOR ", ") as category_name',
                'dv.unit as default_unit',
            ])
            ->join('chemical_category_map ccm', 'ccm.chemical_id = c.id', 'left')
            ->join('chemical_categories cc', 'cc.id = ccm.category_id', 'left')
            ->join('chemical_variants dv', 'dv.chemical_id = c.id AND dv.is_default = 1', 'left')
            ->where('c.status', 'Active')
            ->where('c.deleted_at', null)
            ->groupBy('c.id, c.chemical_code, c.chemical_name, dv.unit')
            ->orderBy('c.chemical_name', 'ASC')
            ->get()->getResultArray();

        $existing = $this->where('period_id', $periodId)->where('warehouse_id', $warehouseId)->findAll();
        $existingMap = [];
        foreach ($existing as $row) {
            $existingMap[$row['chemical_id']] = $row;
        }

        foreach ($chemicals as &$chem) {
            $ex = $existingMap[$chem['chemical_id']] ?? null;
            $chem['opening_id'] = $ex['id'] ?? null;
            $chem['quantity']   = $ex ? (float) $ex['quantity'] : null;
            $chem['unit']       = $ex['unit'] ?? $chem['default_unit'];
            $chem['notes']      = $ex['notes'] ?? null;
        }

        return $chemicals;
    }

    /**
     * Simpan bulk stok awal untuk 1 periode + 1 gudang.
     * $rows: array of ['chemical_id' => int, 'quantity' => float, 'unit' => string, 'notes' => string]
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
            $chemicalId = (int) ($row['chemical_id'] ?? 0);
            if (!$chemicalId) continue;

            $quantity = is_numeric($row['quantity'] ?? null) ? (float) $row['quantity'] : 0;
            $unit     = 'kg';
            $notes    = trim($row['notes'] ?? '') ?: null;

            $existing = $this->where('period_id', $periodId)
                ->where('warehouse_id', $warehouseId)
                ->where('chemical_id', $chemicalId)
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
                    'period_id'    => $periodId,
                    'warehouse_id' => $warehouseId,
                    'chemical_id'  => $chemicalId,
                    'quantity'     => $quantity,
                    'unit'         => $unit,
                    'notes'        => $notes,
                    'created_by'   => $userId,
                    'updated_by'   => $userId,
                ]);
            }
            $saved++;
        }

        return ['status' => 'success', 'message' => "Stok awal berhasil disimpan ({$saved} item)"];
    }

    // ============================================================
    // GABUNGAN — semua gudang (read-only)
    // ============================================================

    /**
     * Total stok awal per bahan kimia, digabung dari seluruh gudang, untuk 1 periode.
     */
    public function getCombinedGrid(int $periodId): array
    {
        $chemicals = $this->db->table('chemicals c')
            ->select([
                'c.id as chemical_id',
                'c.chemical_code',
                'c.chemical_name',
                'GROUP_CONCAT(DISTINCT cc.category_name ORDER BY cc.category_name SEPARATOR ", ") as category_name',
                'dv.unit as default_unit',
            ])
            ->join('chemical_category_map ccm', 'ccm.chemical_id = c.id', 'left')
            ->join('chemical_categories cc', 'cc.id = ccm.category_id', 'left')
            ->join('chemical_variants dv', 'dv.chemical_id = c.id AND dv.is_default = 1', 'left')
            ->where('c.status', 'Active')
            ->where('c.deleted_at', null)
            ->groupBy('c.id, c.chemical_code, c.chemical_name, dv.unit')
            ->orderBy('c.chemical_name', 'ASC')
            ->get()->getResultArray();

        $sums = $this->db->table('chemical_stock_openings')
            ->select('chemical_id, SUM(quantity) as total_quantity, COUNT(DISTINCT warehouse_id) as warehouse_count')
            ->where('period_id', $periodId)
            ->groupBy('chemical_id')
            ->get()->getResultArray();

        $sumMap = [];
        foreach ($sums as $s) {
            $sumMap[$s['chemical_id']] = $s;
        }

        foreach ($chemicals as &$chem) {
            $s = $sumMap[$chem['chemical_id']] ?? null;
            $chem['quantity']        = $s ? (float) $s['total_quantity'] : 0;
            $chem['warehouse_count'] = $s ? (int) $s['warehouse_count'] : 0;
            $chem['unit']            = $chem['default_unit'];
        }

        return $chemicals;
    }

    /**
     * Rincian per gudang untuk 1 bahan kimia dalam 1 periode (dipakai saat user klik baris di mode Gabungan).
     */
    public function getBreakdown(int $periodId, int $chemicalId): array
    {
        return $this->db->table('chemical_stock_openings cso')
            ->select(['cso.warehouse_id', 'w.warehouse_name', 'w.warehouse_code', 'cso.quantity', 'cso.unit', 'cso.notes'])
            ->join('warehouses w', 'w.id = cso.warehouse_id', 'left')
            ->where('cso.period_id', $periodId)
            ->where('cso.chemical_id', $chemicalId)
            ->orderBy('w.warehouse_name', 'ASC')
            ->get()->getResultArray();
    }

    // ============================================================
    // HELPERS — dipakai modul lain (penerimaan/pengeluaran/alokasi)
    // ============================================================

    /**
     * Apakah kombinasi periode+gudang+bahan kimia ini SUDAH punya stok awal?
     * WAJIB dipanggil oleh modul Penerimaan/Pengeluaran/Alokasi Stok sebelum
     * mengizinkan transaksi baru dibuat.
     */
    public function hasOpeningStock(int $periodId, int $warehouseId, int $chemicalId): bool
    {
        return $this->where('period_id', $periodId)
            ->where('warehouse_id', $warehouseId)
            ->where('chemical_id', $chemicalId)
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
