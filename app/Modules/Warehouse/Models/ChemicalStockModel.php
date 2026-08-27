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
                'c.status',
                'GROUP_CONCAT(DISTINCT cc.category_name ORDER BY cc.category_name SEPARATOR ", ") as category_name',
                'dv.unit as default_unit',
            ])
            ->join('chemical_category_map ccm', 'ccm.chemical_id = c.id', 'left')
            ->join('chemical_categories cc', 'cc.id = ccm.category_id', 'left')
            ->join('chemical_variants dv', 'dv.chemical_id = c.id AND dv.is_default = 1', 'left')
            ->where('c.deleted_at', null)
            ->groupBy('c.id, c.chemical_code, c.chemical_name, c.status, dv.unit')
            ->orderBy('c.chemical_name', 'ASC');
    }

    // ============================================================
    // BREAKDOWN RESEP — urai stok formulasi jadi kimia penyusun
    // Hanya item dengan composition_type='chemical' DAN unit='percent' yang bisa diurai
    // (owf%/gpl adalah dosis-terhadap-produksi, bukan komposisi tetap per-kg formulasi).
    // Resep yang dipakai adalah versi yang TERTERA pada baris stok itu sendiri
    // (formulation_version_id) — bukan asumsi versi aktif sekarang.
    // ============================================================

    /**
     * $balances: array of ['formulation_id'=>int, 'formulation_version_id'=>?int, 'qty'=>float]
     * return: [chemical_id => float qty terurai]
     */
    private function decomposeFormulationBalances(array $balances): array
    {
        $versionIds = array_values(array_unique(array_filter(array_column($balances, 'formulation_version_id'))));
        if (empty($versionIds)) return [];

        $items = $this->db->table('formulation_items')
            ->select('formulation_version_id, chemical_id, percentage')
            ->whereIn('formulation_version_id', $versionIds)
            ->where('composition_type', 'chemical')
            ->where('unit', 'percent')
            ->get()->getResultArray();

        $itemsByVersion = [];
        foreach ($items as $it) {
            $itemsByVersion[$it['formulation_version_id']][] = $it;
        }

        $result = [];
        foreach ($balances as $b) {
            $vid = $b['formulation_version_id'] ?? null;
            if (!$vid || empty($itemsByVersion[$vid]) || (float) $b['qty'] === 0.0) continue;

            foreach ($itemsByVersion[$vid] as $it) {
                $chemQty = (float) $b['qty'] * ((float) $it['percentage'] / 100);
                $result[$it['chemical_id']] = ($result[$it['chemical_id']] ?? 0) + $chemQty;
            }
        }
        return $result;
    }

    private function whWhere($builder, ?int $warehouseId)
    {
        if ($warehouseId !== null) $builder->where('warehouse_id', $warehouseId);
        return $builder;
    }

    /** Saldo stok awal formulasi per (formulation_id, version_id) — untuk 1 gudang atau gabungan (null). */
    private function getFormulationOpeningBalances(int $periodId, ?int $warehouseId): array
    {
        $q = $this->db->table('formulation_stock_openings')
            ->select('formulation_id, formulation_version_id, SUM(quantity) as qty')
            ->where('period_id', $periodId)
            ->groupBy('formulation_id, formulation_version_id');
        return $this->whWhere($q, $warehouseId)->get()->getResultArray();
    }

    /** Saldo formulasi SAAT INI (opening + net movement) per (formulation_id, version_id). */
    private function getFormulationCurrentBalances(int $periodId, ?int $warehouseId): array
    {
        $balances = [];
        foreach ($this->getFormulationOpeningBalances($periodId, $warehouseId) as $o) {
            $key = $o['formulation_id'] . '-' . ($o['formulation_version_id'] ?? '0');
            $balances[$key] = ['formulation_id' => $o['formulation_id'], 'formulation_version_id' => $o['formulation_version_id'], 'qty' => (float) $o['qty']];
        }

        $q = $this->db->table('formulation_stock_movements')
            ->select('formulation_id, formulation_version_id, SUM(quantity_in) as total_in, SUM(quantity_out) as total_out')
            ->where('period_id', $periodId)
            ->groupBy('formulation_id, formulation_version_id');
        foreach ($this->whWhere($q, $warehouseId)->get()->getResultArray() as $m) {
            $key = $m['formulation_id'] . '-' . ($m['formulation_version_id'] ?? '0');
            $net = (float) $m['total_in'] - (float) $m['total_out'];
            if (!isset($balances[$key])) {
                $balances[$key] = ['formulation_id' => $m['formulation_id'], 'formulation_version_id' => $m['formulation_version_id'], 'qty' => $net];
            } else {
                $balances[$key]['qty'] += $net;
            }
        }
        return array_values($balances);
    }

    /** Stok formulasi KELUAR (AppliedOut + TransferOut), diurai jadi kimia. */
    private function getFormulationOutflowDecomposed(int $periodId, ?int $warehouseId): array
    {
        $q = $this->db->table('formulation_stock_movements')
            ->select('formulation_id, formulation_version_id, SUM(quantity_out) as qty')
            ->where('period_id', $periodId)
            ->whereIn('movement_type', ['AppliedOut', 'TransferOut'])
            ->groupBy('formulation_id, formulation_version_id');
        $rows = $this->whWhere($q, $warehouseId)->get()->getResultArray();
        $balances = array_map(fn($r) => ['formulation_id' => $r['formulation_id'], 'formulation_version_id' => $r['formulation_version_id'], 'qty' => (float) $r['qty']], $rows);
        return $this->decomposeFormulationBalances($balances);
    }

    /** Adjustment formulasi (net), diurai jadi kimia. */
    private function getFormulationAdjustmentDecomposed(int $periodId, ?int $warehouseId): array
    {
        $q = $this->db->table('formulation_stock_movements')
            ->select('formulation_id, formulation_version_id, SUM(quantity_in) as total_in, SUM(quantity_out) as total_out')
            ->where('period_id', $periodId)
            ->whereIn('movement_type', ['AdjustmentIn', 'AdjustmentOut'])
            ->groupBy('formulation_id, formulation_version_id');
        $rows = $this->whWhere($q, $warehouseId)->get()->getResultArray();
        $balances = array_map(fn($r) => ['formulation_id' => $r['formulation_id'], 'formulation_version_id' => $r['formulation_version_id'], 'qty' => (float) $r['total_in'] - (float) $r['total_out']], $rows);
        return $this->decomposeFormulationBalances($balances);
    }

    /** Hasil stock opname formulasi, diurai jadi kimia. */
    private function getFormulationOpnameDecomposed(int $periodId, ?int $warehouseId): array
    {
        $q = $this->db->table('formulation_stock_opnames')
            ->select('formulation_id, formulation_version_id, SUM(opname_qty) as qty')
            ->where('period_id', $periodId)
            ->groupBy('formulation_id, formulation_version_id');
        $rows = $this->whWhere($q, $warehouseId)->get()->getResultArray();
        $balances = array_map(fn($r) => ['formulation_id' => $r['formulation_id'], 'formulation_version_id' => $r['formulation_version_id'], 'qty' => (float) $r['qty']], $rows);
        return $this->decomposeFormulationBalances($balances);
    }

    // ============================================================
    // POSISI STOK — mesin hitung utama (dipakai per-gudang & gabungan)
    // Kolom sesuai spesifikasi 13-kolom:
    //  1 available_opening   6 allocated         11 ifs_qty
    //  2 on_hand_opening     7 on_hand           12 variance_on_hand
    //  3 stock_in            8 available         13 variance_ifs
    //  4 stock_out           9 opname_available
    //  5 adjustment         10 opname_on_hand
    // ============================================================
    private function computePositionData(int $periodId, ?int $warehouseId): array
    {
        $chemicals = $this->activeChemicalsBase()->get()->getResultArray();

        // --- 1. Available opening (kimia murni) ---
        $q = $this->db->table('chemical_stock_openings')->select('chemical_id, SUM(quantity) as qty')->where('period_id', $periodId)->groupBy('chemical_id');
        $openingMap = array_column($this->whWhere($q, $warehouseId)->get()->getResultArray(), 'qty', 'chemical_id');

        // --- 2. On Hand opening = Available opening + breakdown stok awal formulasi ---
        $formOpeningDecomposed = $this->decomposeFormulationBalances($this->getFormulationOpeningBalances($periodId, $warehouseId));

        // --- 3. Stok Masuk (Receipt + TransferIn) ---
        $q = $this->db->table('chemical_stock_movements')->select('chemical_id, SUM(quantity_in) as qty')
            ->where('period_id', $periodId)->whereIn('movement_type', ['Receipt', 'TransferIn'])->groupBy('chemical_id');
        $stockInMap = array_column($this->whWhere($q, $warehouseId)->get()->getResultArray(), 'qty', 'chemical_id');

        // --- 4a. Stok Keluar - Issue (pemakaian langsung ke produksi) + Transfer kimia murni ---
        $q = $this->db->table('chemical_stock_movements')->select('chemical_id, SUM(quantity_out) as qty')
            ->where('period_id', $periodId)->whereIn('movement_type', ['Issue', 'TransferOut'])->groupBy('chemical_id');
        $outChemMap = array_column($this->whWhere($q, $warehouseId)->get()->getResultArray(), 'qty', 'chemical_id');

        // --- 4b. Stok Keluar - aplikasi produksi + transfer formulasi (diurai) ---
        $outFormDecomposed = $this->getFormulationOutflowDecomposed($periodId, $warehouseId);

        // --- 5a. Adjustment kimia murni (net) ---
        $q = $this->db->table('chemical_stock_movements')->select('chemical_id, SUM(quantity_in) as total_in, SUM(quantity_out) as total_out')
            ->where('period_id', $periodId)->whereIn('movement_type', ['AdjustmentIn', 'AdjustmentOut'])->groupBy('chemical_id');
        $adjChemMap = [];
        foreach ($this->whWhere($q, $warehouseId)->get()->getResultArray() as $r) {
            $adjChemMap[$r['chemical_id']] = (float) $r['total_in'] - (float) $r['total_out'];
        }

        // --- 5b. Adjustment formulasi (net, diurai) ---
        $adjFormDecomposed = $this->getFormulationAdjustmentDecomposed($periodId, $warehouseId);

        // --- 6. Allocated Stock = saldo formulasi SAAT INI, diurai ---
        $allocatedMap = $this->decomposeFormulationBalances($this->getFormulationCurrentBalances($periodId, $warehouseId));

        // --- 9. Stock Opname (Available) kimia ---
        $q = $this->db->table('chemical_stock_opnames')->select('chemical_id, SUM(opname_qty) as qty')->where('period_id', $periodId)->groupBy('chemical_id');
        $opnameChemMap = array_column($this->whWhere($q, $warehouseId)->get()->getResultArray(), 'qty', 'chemical_id');

        // --- 10b. Stock Opname formulasi (diurai) ---
        $opnameFormDecomposed = $this->getFormulationOpnameDecomposed($periodId, $warehouseId);

        // --- 11. Stok Akhir IFS ---
        $q = $this->db->table('chemical_stock_ifs')->select('chemical_id, SUM(ifs_qty) as qty')->where('period_id', $periodId)->groupBy('chemical_id');
        $ifsMap = array_column($this->whWhere($q, $warehouseId)->get()->getResultArray(), 'qty', 'chemical_id');

        // Ada tidaknya opname/ifs dicatat terpisah (untuk bedakan "0" vs "belum diisi")
        $q = $this->db->table('chemical_stock_opnames')->select('chemical_id')->where('period_id', $periodId)->groupBy('chemical_id');
        $opnameRecorded = array_flip(array_column($this->whWhere($q, $warehouseId)->get()->getResultArray(), 'chemical_id'));
        $q = $this->db->table('chemical_stock_ifs')->select('chemical_id')->where('period_id', $periodId)->groupBy('chemical_id');
        $ifsRecorded = array_flip(array_column($this->whWhere($q, $warehouseId)->get()->getResultArray(), 'chemical_id'));

        foreach ($chemicals as &$c) {
            $cid  = $c['chemical_id'];
            $unit = $c['default_unit'] ?? 'kg';

            $availableOpening = (float) ($openingMap[$cid] ?? 0);
            $onHandOpening    = $availableOpening + (float) ($formOpeningDecomposed[$cid] ?? 0);

            $stockIn  = (float) ($stockInMap[$cid] ?? 0);
            $stockOut = (float) ($outChemMap[$cid] ?? 0) + (float) ($outFormDecomposed[$cid] ?? 0);

            $adjustment = (float) ($adjChemMap[$cid] ?? 0) + (float) ($adjFormDecomposed[$cid] ?? 0);

            $allocated = (float) ($allocatedMap[$cid] ?? 0);

            $onHand    = $onHandOpening + $stockIn - $stockOut + $adjustment;
            $available = $onHand - $allocated;

            $hasOpname       = isset($opnameRecorded[$cid]);
            $opnameAvailable = $hasOpname ? (float) ($opnameChemMap[$cid] ?? 0) : null;
            $opnameOnHand    = $hasOpname ? $opnameAvailable + (float) ($opnameFormDecomposed[$cid] ?? 0) : null;

            $hasIfs = isset($ifsRecorded[$cid]);
            $ifsQty = $hasIfs ? (float) ($ifsMap[$cid] ?? 0) : null;

            $c['unit']              = $unit;
            $c['available_opening'] = $availableOpening;
            $c['on_hand_opening']   = $onHandOpening;
            $c['stock_in']          = $stockIn;
            $c['stock_out']         = $stockOut;
            $c['adjustment']        = $adjustment;
            $c['allocated']         = $allocated;
            $c['on_hand']           = $onHand;
            $c['available']         = $available;
            $c['opname_available']  = $opnameAvailable;
            $c['opname_on_hand']    = $opnameOnHand;
            $c['ifs_qty']           = $ifsQty;
            $c['variance_on_hand']  = $opnameOnHand !== null ? ($onHand - $opnameOnHand) : null;
            $c['variance_ifs']      = ($ifsQty !== null && $opnameOnHand !== null) ? ($ifsQty - $opnameOnHand) : null;
        }

        return $chemicals;
    }

    // ============================================================
    // POSISI STOK — per gudang
    // ============================================================
    public function getPositionGrid(int $periodId, int $warehouseId): array
    {
        return $this->computePositionData($periodId, $warehouseId);
    }

    /**
     * Saldo "Available" (siap dipakai) 1 bahan kimia di periode+gudang tertentu —
     * dipakai untuk validasi Pemakaian Langsung (Issue) supaya tidak minus.
     * Bukan endpoint ringan (tetap hitung posisi semua kimia), tapi memastikan
     * angkanya SELALU konsisten dengan yang ditampilkan di Posisi Stok.
     */
    public function getAvailableBalance(int $periodId, int $warehouseId, int $chemicalId): ?float
    {
        foreach ($this->computePositionData($periodId, $warehouseId) as $row) {
            if ((int) $row['chemical_id'] === $chemicalId) return (float) $row['available'];
        }
        return null; // chemical tidak aktif / tidak ditemukan
    }

    // ============================================================
    // POSISI STOK — gabungan semua gudang
    // ============================================================
    public function getPositionCombinedGrid(int $periodId): array
    {
        $chemicals = $this->computePositionData($periodId, null);

        $q = $this->db->table('chemical_stock_openings')->select('chemical_id, COUNT(DISTINCT warehouse_id) as warehouse_count')
            ->where('period_id', $periodId)->groupBy('chemical_id');
        $whCountMap = array_column($q->get()->getResultArray(), 'warehouse_count', 'chemical_id');

        foreach ($chemicals as &$c) {
            $c['warehouse_count'] = (int) ($whCountMap[$c['chemical_id']] ?? 0);
        }

        return $chemicals;
    }

    /**
     * Rincian posisi per gudang untuk 1 bahan kimia (dipakai saat klik baris di mode Gabungan).
     * Catatan performa: melakukan hitung penuh (computePositionData) per gudang yang relevan —
     * cukup untuk jumlah gudang yang wajar (puluhan), bisa dioptimasi lagi kalau gudang sangat banyak.
     */
    public function getPositionBreakdown(int $periodId, int $chemicalId): array
    {
        $warehouses = $this->db->table('warehouses')
            ->select('id, warehouse_name, warehouse_code')
            ->where('deleted_at', null)
            ->orderBy('warehouse_name', 'ASC')
            ->get()->getResultArray();

        $result = [];
        foreach ($warehouses as $w) {
            $data = $this->computePositionData($periodId, (int) $w['id']);
            foreach ($data as $row) {
                if ($row['chemical_id'] != $chemicalId) continue;

                // Lewati gudang yang benar-benar tidak punya data apa pun untuk chemical ini
                $hasAnyData = $row['available_opening'] != 0 || $row['on_hand_opening'] != 0
                    || $row['stock_in'] != 0 || $row['stock_out'] != 0 || $row['adjustment'] != 0
                    || $row['allocated'] != 0 || $row['opname_available'] !== null || $row['ifs_qty'] !== null;
                if (!$hasAnyData) continue;

                $result[] = array_merge(['warehouse_id' => $w['id'], 'warehouse_name' => $w['warehouse_name'], 'warehouse_code' => $w['warehouse_code']], $row);
                break;
            }
        }

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
            ->select('quantity, created_at')
            ->where('period_id', $periodId)
            ->where('warehouse_id', $warehouseId)
            ->where('chemical_id', $chemicalId)
            ->get()->getRowArray();
        $openingQty  = (float) ($opening['quantity'] ?? 0);
        $openingDate = $opening['created_at'] ?? null; // tanggal input opening stok, dipakai sbg tanggal baris "Stok Awal"

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
            'opening_date'   => $openingDate,
            'total_in'       => $totalIn,
            'total_out'      => $totalOut,
            'closing_qty'    => $displayOpeningQty + $totalIn - $totalOut,
            'ledger'         => $ledger,
            'is_filtered'    => ($fromDate !== null || $toDate !== null),
        ];
    }
}
