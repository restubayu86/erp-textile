<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

class ChemicalStockIfsModel extends Model
{
    protected $table            = 'chemical_stock_ifs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'period_id',
        'warehouse_id',
        'chemical_id',
        'ifs_qty',
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
            $chem['ifs_id']  = $ex['id'] ?? null;
            $chem['ifs_qty'] = $ex ? (float) $ex['ifs_qty'] : null;
            $chem['unit']    = $ex['unit'] ?? $chem['default_unit'];
            $chem['notes']   = $ex['notes'] ?? null;
        }

        return $chemicals;
    }

    /**
     * Simpan bulk stok akhir IFS untuk 1 periode + 1 gudang.
     */
    public function saveBulk(int $periodId, int $warehouseId, array $rows, int $userId): array
    {
        $period = $this->db->table('periods')->where('id', $periodId)->where('deleted_at', null)->get()->getRowArray();
        if (!$period) {
            return ['status' => 'error', 'message' => 'Periode tidak ditemukan'];
        }

        $warehouse = $this->db->table('warehouses')->where('id', $warehouseId)->where('deleted_at', null)->get()->getRowArray();
        if (!$warehouse) {
            return ['status' => 'error', 'message' => 'Gudang tidak ditemukan'];
        }

        $saved = 0;
        foreach ($rows as $row) {
            $chemicalId = (int) ($row['chemical_id'] ?? 0);
            if (!$chemicalId) continue;

            $qty = is_numeric($row['ifs_qty'] ?? null) ? (float) $row['ifs_qty'] : null;
            if ($qty === null) continue;

            $unit  = 'kg';
            $notes = trim($row['notes'] ?? '') ?: null;

            $existing = $this->where('period_id', $periodId)
                ->where('warehouse_id', $warehouseId)
                ->where('chemical_id', $chemicalId)
                ->first();

            if ($existing) {
                $this->update($existing['id'], [
                    'ifs_qty'    => $qty,
                    'unit'       => $unit,
                    'notes'      => $notes,
                    'updated_by' => $userId,
                ]);
            } else {
                $this->insert([
                    'period_id'    => $periodId,
                    'warehouse_id' => $warehouseId,
                    'chemical_id'  => $chemicalId,
                    'ifs_qty'      => $qty,
                    'unit'         => $unit,
                    'notes'        => $notes,
                    'created_by'   => $userId,
                    'updated_by'   => $userId,
                ]);
            }
            $saved++;
        }

        return ['status' => 'success', 'message' => "Stok akhir IFS berhasil disimpan ({$saved} item)"];
    }

    // ============================================================
    // GABUNGAN — semua gudang (read-only)
    // ============================================================
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

        $sums = $this->db->table('chemical_stock_ifs')
            ->select('chemical_id, SUM(ifs_qty) as total_qty, COUNT(DISTINCT warehouse_id) as warehouse_count')
            ->where('period_id', $periodId)
            ->groupBy('chemical_id')
            ->get()->getResultArray();

        $sumMap = [];
        foreach ($sums as $s) {
            $sumMap[$s['chemical_id']] = $s;
        }

        foreach ($chemicals as &$chem) {
            $s = $sumMap[$chem['chemical_id']] ?? null;
            $chem['ifs_qty']         = $s ? (float) $s['total_qty'] : null;
            $chem['warehouse_count'] = $s ? (int) $s['warehouse_count'] : 0;
            $chem['unit']            = $chem['default_unit'];
        }

        return $chemicals;
    }

    public function getBreakdown(int $periodId, int $chemicalId): array
    {
        return $this->db->table('chemical_stock_ifs csi')
            ->select(['csi.warehouse_id', 'w.warehouse_name', 'w.warehouse_code', 'csi.ifs_qty', 'csi.unit', 'csi.notes'])
            ->join('warehouses w', 'w.id = csi.warehouse_id', 'left')
            ->where('csi.period_id', $periodId)
            ->where('csi.chemical_id', $chemicalId)
            ->orderBy('w.warehouse_name', 'ASC')
            ->get()->getResultArray();
    }

    public function isInitialized(int $periodId, int $warehouseId): bool
    {
        return $this->where('period_id', $periodId)
            ->where('warehouse_id', $warehouseId)
            ->countAllResults() > 0;
    }
}
