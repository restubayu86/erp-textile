<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

class FormulationStockOpnameModel extends Model
{
    protected $table            = 'formulation_stock_opnames';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'period_id',
        'warehouse_id',
        'formulation_id',
        'formulation_version_id',
        'opname_qty',
        'unit',
        'opname_date',
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
            $f['opname_id']       = $ex['id'] ?? null;
            $f['opname_qty']      = $ex ? (float) $ex['opname_qty'] : null;
            $f['unit']            = $ex['unit'] ?? 'kg';
            $f['opname_date']     = $ex['opname_date'] ?? null;
            $f['notes']           = $ex['notes'] ?? null;
            $f['saved_version_id'] = $ex['formulation_version_id'] ?? null;
        }

        return $formulations;
    }

    /**
     * Simpan bulk hasil stock opname formulasi untuk 1 periode + 1 gudang.
     * Versi resep ditandai dari versi AKTIF formulasi saat opname dicatat.
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

        $activeVersions = $this->db->table('formulations')
            ->select('id as formulation_id, current_version_id')
            ->where('deleted_at', null)
            ->get()->getResultArray();
        $activeVersionMap = array_column($activeVersions, 'current_version_id', 'formulation_id');

        $saved = 0;
        foreach ($rows as $row) {
            $formulationId = (int) ($row['formulation_id'] ?? 0);
            if (!$formulationId) continue;

            $qty = is_numeric($row['opname_qty'] ?? null) ? (float) $row['opname_qty'] : null;
            if ($qty === null) continue;

            $unit       = 'kg';
            $opnameDate = trim($row['opname_date'] ?? '') ?: date('Y-m-d');
            $notes      = trim($row['notes'] ?? '') ?: null;
            $versionId  = $activeVersionMap[$formulationId] ?? null;

            $existing = $this->where('period_id', $periodId)
                ->where('warehouse_id', $warehouseId)
                ->where('formulation_id', $formulationId)
                ->first();

            if ($existing) {
                $this->update($existing['id'], [
                    'opname_qty'              => $qty,
                    'unit'                    => $unit,
                    'opname_date'             => $opnameDate,
                    'notes'                   => $notes,
                    'formulation_version_id'  => $versionId,
                    'updated_by'              => $userId,
                ]);
            } else {
                $this->insert([
                    'period_id'               => $periodId,
                    'warehouse_id'            => $warehouseId,
                    'formulation_id'          => $formulationId,
                    'formulation_version_id'  => $versionId,
                    'opname_qty'              => $qty,
                    'unit'                    => $unit,
                    'opname_date'             => $opnameDate,
                    'notes'                   => $notes,
                    'created_by'              => $userId,
                    'updated_by'              => $userId,
                ]);
            }
            $saved++;
        }

        return ['status' => 'success', 'message' => "Stock opname formulasi berhasil disimpan ({$saved} item)"];
    }

    // ============================================================
    // GABUNGAN — semua gudang (read-only)
    // ============================================================
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

        $sums = $this->db->table('formulation_stock_opnames')
            ->select('formulation_id, SUM(opname_qty) as total_qty, COUNT(DISTINCT warehouse_id) as warehouse_count')
            ->where('period_id', $periodId)
            ->groupBy('formulation_id')
            ->get()->getResultArray();

        $sumMap = [];
        foreach ($sums as $s) {
            $sumMap[$s['formulation_id']] = $s;
        }

        foreach ($formulations as &$f) {
            $s = $sumMap[$f['formulation_id']] ?? null;
            $f['opname_qty']      = $s ? (float) $s['total_qty'] : null;
            $f['warehouse_count'] = $s ? (int) $s['warehouse_count'] : 0;
            $f['unit']            = 'kg';
        }

        return $formulations;
    }

    public function getBreakdown(int $periodId, int $formulationId): array
    {
        return $this->db->table('formulation_stock_opnames fso')
            ->select(['fso.warehouse_id', 'w.warehouse_name', 'w.warehouse_code', 'fso.opname_qty', 'fso.unit', 'fso.opname_date', 'fso.notes', 'v.version_no'])
            ->join('warehouses w', 'w.id = fso.warehouse_id', 'left')
            ->join('formulation_versions v', 'v.id = fso.formulation_version_id', 'left')
            ->where('fso.period_id', $periodId)
            ->where('fso.formulation_id', $formulationId)
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
