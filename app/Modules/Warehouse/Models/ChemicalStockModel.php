<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

/**
 * Model pelaporan stok kimia: Posisi Stok & Kartu Stok.
 * Sumber data: chemical_stock_openings (stok awal) + chemical_stock_movements (ledger transaksi).
 * Perhitungan saldo dilakukan sum-on-the-fly per periode (bukan running balance tersimpan).
 */
class ChemicalStockModel extends Model
{
    protected $table      = 'chemical_stock_movements';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $movementTypeLabels = [
        'Receipt'       => 'Penerimaan',
        'Issue'         => 'Pemakaian',
        'TransferIn'    => 'Transfer Masuk',
        'TransferOut'   => 'Transfer Keluar',
        'AdjustmentIn'  => 'Penyesuaian (+)',
        'AdjustmentOut' => 'Penyesuaian (-)',
    ];

    // ============================================================
    // DAFTAR BAHAN KIMIA AKTIF (dasar untuk grid posisi)
    // ============================================================
    private function activeChemicalsBase()
    {
        return $this->db->table('chemicals c')
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
            ->orderBy('c.chemical_name', 'ASC');
    }

    // ============================================================
    // POSISI STOK — per gudang
    // ============================================================
    public function getPositionGrid(int $periodId, int $warehouseId): array
    {
        $chemicals = $this->activeChemicalsBase()->get()->getResultArray();

        $openings = $this->db->table('chemical_stock_openings')
            ->select('chemical_id, quantity')
            ->where('period_id', $periodId)
            ->where('warehouse_id', $warehouseId)
            ->get()->getResultArray();
        $openingMap = array_column($openings, 'quantity', 'chemical_id');

        $movements = $this->db->table('chemical_stock_movements')
            ->select('chemical_id, SUM(quantity_in) as total_in, SUM(quantity_out) as total_out')
            ->where('period_id', $periodId)
            ->where('warehouse_id', $warehouseId)
            ->groupBy('chemical_id')
            ->get()->getResultArray();
        $movementMap = [];
        foreach ($movements as $m) {
            $movementMap[$m['chemical_id']] = $m;
        }

        foreach ($chemicals as &$c) {
            $opening  = (float) ($openingMap[$c['chemical_id']] ?? 0);
            $totalIn  = (float) ($movementMap[$c['chemical_id']]['total_in'] ?? 0);
            $totalOut = (float) ($movementMap[$c['chemical_id']]['total_out'] ?? 0);

            $c['unit']        = $c['default_unit'] ?? 'kg';
            $c['opening_qty'] = $opening;
            $c['total_in']    = $totalIn;
            $c['total_out']   = $totalOut;
            $c['balance']     = $opening + $totalIn - $totalOut;
        }

        return $chemicals;
    }

    // ============================================================
    // POSISI STOK — gabungan semua gudang
    // ============================================================
    public function getPositionCombinedGrid(int $periodId): array
    {
        $chemicals = $this->activeChemicalsBase()->get()->getResultArray();

        $openings = $this->db->table('chemical_stock_openings')
            ->select('chemical_id, SUM(quantity) as total_opening, COUNT(DISTINCT warehouse_id) as warehouse_count')
            ->where('period_id', $periodId)
            ->groupBy('chemical_id')
            ->get()->getResultArray();
        $openingMap = [];
        foreach ($openings as $o) {
            $openingMap[$o['chemical_id']] = $o;
        }

        $movements = $this->db->table('chemical_stock_movements')
            ->select('chemical_id, SUM(quantity_in) as total_in, SUM(quantity_out) as total_out')
            ->where('period_id', $periodId)
            ->groupBy('chemical_id')
            ->get()->getResultArray();
        $movementMap = [];
        foreach ($movements as $m) {
            $movementMap[$m['chemical_id']] = $m;
        }

        foreach ($chemicals as &$c) {
            $opening  = (float) ($openingMap[$c['chemical_id']]['total_opening'] ?? 0);
            $totalIn  = (float) ($movementMap[$c['chemical_id']]['total_in'] ?? 0);
            $totalOut = (float) ($movementMap[$c['chemical_id']]['total_out'] ?? 0);

            $c['unit']            = $c['default_unit'] ?? 'kg';
            $c['opening_qty']     = $opening;
            $c['total_in']        = $totalIn;
            $c['total_out']       = $totalOut;
            $c['balance']         = $opening + $totalIn - $totalOut;
            $c['warehouse_count'] = (int) ($openingMap[$c['chemical_id']]['warehouse_count'] ?? 0);
        }

        return $chemicals;
    }

    /**
     * Rincian posisi per gudang untuk 1 bahan kimia (dipakai saat klik baris di mode Gabungan).
     */
    public function getPositionBreakdown(int $periodId, int $chemicalId): array
    {
        $openings = $this->db->table('chemical_stock_openings o')
            ->select(['o.warehouse_id', 'w.warehouse_name', 'w.warehouse_code', 'o.quantity as opening_qty'])
            ->join('warehouses w', 'w.id = o.warehouse_id', 'left')
            ->where('o.period_id', $periodId)
            ->where('o.chemical_id', $chemicalId)
            ->get()->getResultArray();

        $movements = $this->db->table('chemical_stock_movements')
            ->select('warehouse_id, SUM(quantity_in) as total_in, SUM(quantity_out) as total_out')
            ->where('period_id', $periodId)
            ->where('chemical_id', $chemicalId)
            ->groupBy('warehouse_id')
            ->get()->getResultArray();
        $movementMap = [];
        foreach ($movements as $m) {
            $movementMap[$m['warehouse_id']] = $m;
        }

        // Gabungkan gudang yang punya opening DAN gudang yang cuma punya movement tanpa opening
        $warehouseIds = array_unique(array_merge(
            array_column($openings, 'warehouse_id'),
            array_keys($movementMap)
        ));

        $openingMap = [];
        foreach ($openings as $o) {
            $openingMap[$o['warehouse_id']] = $o;
        }

        if (empty($warehouseIds)) return [];

        $warehouses = $this->db->table('warehouses')
            ->select('id, warehouse_name, warehouse_code')
            ->whereIn('id', $warehouseIds)
            ->get()->getResultArray();

        $result = [];
        foreach ($warehouses as $w) {
            $opening  = (float) ($openingMap[$w['id']]['opening_qty'] ?? 0);
            $totalIn  = (float) ($movementMap[$w['id']]['total_in'] ?? 0);
            $totalOut = (float) ($movementMap[$w['id']]['total_out'] ?? 0);

            $result[] = [
                'warehouse_id'   => $w['id'],
                'warehouse_name' => $w['warehouse_name'],
                'warehouse_code' => $w['warehouse_code'],
                'opening_qty'    => $opening,
                'total_in'       => $totalIn,
                'total_out'      => $totalOut,
                'balance'        => $opening + $totalIn - $totalOut,
            ];
        }

        usort($result, fn($a, $b) => strcmp($a['warehouse_name'], $b['warehouse_name']));
        return $result;
    }

    // ============================================================
    // KARTU STOK — histori transaksi 1 bahan kimia di 1 gudang, 1 periode
    // Opsional: batasi tampilan ke rentang tanggal tertentu (fromDate/toDate),
    // tapi saldo tetap dihitung akurat berjalan dari stok awal periode.
    // ============================================================
    public function getStockCard(int $periodId, int $warehouseId, int $chemicalId, ?string $fromDate = null, ?string $toDate = null): array
    {
        $opening = $this->db->table('chemical_stock_openings')
            ->select('quantity')
            ->where('period_id', $periodId)
            ->where('warehouse_id', $warehouseId)
            ->where('chemical_id', $chemicalId)
            ->get()->getRowArray();
        $openingQty = (float) ($opening['quantity'] ?? 0);

        $rows = $this->db->table('chemical_stock_movements')
            ->select(['id', 'movement_type', 'quantity_in', 'quantity_out', 'unit', 'reference_type', 'reference_id', 'movement_date', 'notes'])
            ->where('period_id', $periodId)
            ->where('warehouse_id', $warehouseId)
            ->where('chemical_id', $chemicalId)
            ->orderBy('movement_date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()->getResultArray();

        // Hitung saldo berjalan atas SEMUA transaksi periode dulu (akurat),
        // baru difilter tampilannya sesuai rentang tanggal kalau diminta.
        $running    = $openingQty;
        $fullLedger = [];
        foreach ($rows as $r) {
            $in  = (float) $r['quantity_in'];
            $out = (float) $r['quantity_out'];
            $running += $in - $out;

            $fullLedger[] = [
                'id'              => $r['id'],
                'movement_date'   => $r['movement_date'],
                'movement_type'   => $r['movement_type'],
                'movement_label'  => $this->movementTypeLabels[$r['movement_type']] ?? $r['movement_type'],
                'quantity_in'     => $in,
                'quantity_out'    => $out,
                'unit'            => $r['unit'],
                'reference_type'  => $r['reference_type'],
                'reference_id'    => $r['reference_id'],
                'notes'           => $r['notes'],
                'running_balance' => $running,
            ];
        }

        // Saldo sebelum baris pertama yang tampil (= saldo awal utk tampilan yang difilter)
        $displayOpeningQty = $openingQty;
        $ledger = $fullLedger;

        if ($fromDate !== null || $toDate !== null) {
            $displayOpeningQty = $openingQty;
            foreach ($fullLedger as $r) {
                if ($fromDate !== null && $r['movement_date'] < $fromDate) {
                    $displayOpeningQty = $r['running_balance']; // saldo terakhir sebelum rentang tampil
                    continue;
                }
                break;
            }

            $ledger = array_values(array_filter($fullLedger, function ($r) use ($fromDate, $toDate) {
                if ($fromDate !== null && $r['movement_date'] < $fromDate) return false;
                if ($toDate !== null && $r['movement_date'] > $toDate) return false;
                return true;
            }));
        }

        $totalIn  = array_sum(array_column($ledger, 'quantity_in'));
        $totalOut = array_sum(array_column($ledger, 'quantity_out'));

        return [
            'opening_qty'    => $displayOpeningQty,
            'total_in'       => $totalIn,
            'total_out'      => $totalOut,
            'closing_qty'    => $displayOpeningQty + $totalIn - $totalOut,
            'ledger'         => $ledger,
            'is_filtered'    => ($fromDate !== null || $toDate !== null),
        ];
    }
}
