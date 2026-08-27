<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

/**
 * Model transaksi pergerakan stok kimia (chemical_stock_movements).
 * Dipakai oleh modul Penerimaan (Receipt), dan nantinya Pemakaian (Issue) &
 * Penyesuaian (Adjustment) — satu tabel ledger untuk semua jenis pergerakan.
 *
 * Aturan wajib sebelum insert (ditegakkan di saveReceiptBulk()):
 *  1. Periode harus ada & belum Closed.
 *  2. Gudang harus ada.
 *  3. Setiap bahan kimia WAJIB sudah punya baris Stok Awal (boleh 0) di
 *     kombinasi periode+gudang tsb — lihat catatan di ChemicalStockOpeningModel::hasOpeningStock().
 *  4. Tanggal transaksi harus berada dalam rentang periode.
 */
class ChemicalStockMovementModel extends Model
{
    protected $table            = 'chemical_stock_movements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'period_id',
        'warehouse_id',
        'chemical_id',
        'variant_id',
        'movement_type',
        'usage_purpose',
        'quantity_in',
        'qty_unit',
        'qty_berat',
        'quantity_out',
        'unit',
        'reference_type',
        'reference_id',
        'movement_date',
        'notes',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ============================================================
    // PENERIMAAN (RECEIPT)
    // ============================================================

    /**
     * Simpan bulk baris Penerimaan untuk 1 periode + 1 gudang. Setiap baris membawa
     * movement_date sendiri (diisi per item lewat modal "Tambah Item"), jadi satu batch
     * simpan boleh berisi tanggal yang berbeda-beda, selama masih dalam rentang periode.
     * $rows: array of ['chemical_id' => int, 'variant_id' => ?int, 'qty_unit' => ?float,
     *                   'quantity' => float, 'unit' => string, 'movement_date' => 'Y-m-d', 'notes' => string]
     *
     * Return: ['status' => 'success'|'error', 'message' => string, 'saved' => int, 'skipped' => array]
     */
    public function saveReceiptBulk(int $periodId, int $warehouseId, array $rows, int $userId): array
    {
        $period = $this->db->table('periods')->where('id', $periodId)->where('deleted_at', null)->get()->getRowArray();
        if (!$period) {
            return ['status' => 'error', 'message' => 'Periode tidak ditemukan'];
        }
        if ($period['status'] === 'Closed') {
            return ['status' => 'error', 'message' => 'Periode sudah ditutup, transaksi tidak bisa dicatat'];
        }

        $warehouse = $this->db->table('warehouses')->where('id', $warehouseId)->where('deleted_at', null)->get()->getRowArray();
        if (!$warehouse) {
            return ['status' => 'error', 'message' => 'Gudang tidak ditemukan'];
        }

        $saved   = 0;
        $skipped = [];

        foreach ($rows as $row) {
            $chemicalId = (int) ($row['chemical_id'] ?? 0);
            if (!$chemicalId) continue;

            $movementDate = trim((string) ($row['movement_date'] ?? ''));
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $movementDate)) {
                $skipped[] = "Chemical #{$chemicalId}: tanggal penerimaan tidak valid";
                continue;
            }
            if ($movementDate < $period['start_date'] || $movementDate > $period['end_date']) {
                $skipped[] = "Chemical #{$chemicalId}: tanggal {$movementDate} di luar rentang periode ({$period['start_date']} s/d {$period['end_date']})";
                continue;
            }

            $quantity = is_numeric($row['quantity'] ?? null) ? (float) $row['quantity'] : 0;
            if ($quantity <= 0) {
                $skipped[] = "Chemical #{$chemicalId}: qty harus lebih dari 0";
                continue;
            }

            $chemical = $this->db->table('chemicals')
                ->where('id', $chemicalId)->where('status', 'Active')->where('deleted_at', null)
                ->get()->getRowArray();
            if (!$chemical) {
                $skipped[] = "Chemical #{$chemicalId}: tidak ditemukan / non-aktif";
                continue;
            }

            // Wajib: bahan kimia ini sudah punya baris Stok Awal (boleh 0) di periode+gudang ini.
            $hasOpening = $this->db->table('chemical_stock_openings')
                ->where('period_id', $periodId)
                ->where('warehouse_id', $warehouseId)
                ->where('chemical_id', $chemicalId)
                ->countAllResults() > 0;
            if (!$hasOpening) {
                $skipped[] = "{$chemical['chemical_name']}: belum ada Stok Awal di gudang & periode ini";
                continue;
            }

            $variantId = !empty($row['variant_id']) ? (int) $row['variant_id'] : null;
            if ($variantId) {
                $variantOk = $this->db->table('chemical_variants')
                    ->where('id', $variantId)->where('chemical_id', $chemicalId)
                    ->countAllResults() > 0;
                if (!$variantOk) $variantId = null; // varian tidak cocok dgn chemical — abaikan saja, bukan alasan gagal simpan
            }

            $unit  = trim($row['unit'] ?? '') ?: 'kg';
            $notes = trim($row['notes'] ?? '') ?: null;

            // Qty Unit (jumlah kemasan, mis. 5 drum) — opsional, hanya relevan kalau varian
            // dipilih. Qty Berat (qty_berat) selalu diisi sama dengan quantity_in supaya kedua
            // kolom konsisten: quantity_in tetap jadi acuan tunggal untuk saldo stok
            // (Posisi Stok / Kartu Stok), sedangkan qty_unit & qty_berat menyimpan rincian
            // varian secara terstruktur untuk kebutuhan laporan/cetak.
            $qtyUnit = is_numeric($row['qty_unit'] ?? null) ? (float) $row['qty_unit'] : 0;
            if ($qtyUnit < 0) $qtyUnit = 0;

            $this->insert([
                'period_id'      => $periodId,
                'warehouse_id'   => $warehouseId,
                'chemical_id'    => $chemicalId,
                'variant_id'     => $variantId,
                'movement_type'  => 'Receipt',
                'quantity_in'    => $quantity,
                'qty_unit'       => $qtyUnit,
                'qty_berat'      => $quantity,
                'quantity_out'   => 0,
                'unit'           => $unit,
                'reference_type' => 'manual',
                'reference_id'   => null,
                'movement_date'  => $movementDate,
                'notes'          => $notes,
                'created_by'     => $userId,
            ]);
            $saved++;
        }

        if ($saved === 0) {
            return [
                'status'  => 'error',
                'message' => 'Tidak ada item yang berhasil disimpan' . (!empty($skipped) ? ': ' . implode('; ', $skipped) : ''),
                'saved'   => 0,
                'skipped' => $skipped,
            ];
        }

        $message = "Penerimaan berhasil disimpan ({$saved} item)";
        if (!empty($skipped)) {
            $message .= '. ' . count($skipped) . ' item dilewati: ' . implode('; ', $skipped);
        }

        return ['status' => 'success', 'message' => $message, 'saved' => $saved, 'skipped' => $skipped];
    }

    // ============================================================
    // PEMAKAIAN LANGSUNG (ISSUE) — Sample, Litbang, Sampling Produksi, Perbaikan
    // ============================================================

    /**
     * Simpan bulk baris Pemakaian Langsung untuk 1 periode + 1 gudang. Setiap baris
     * membawa movement_date & usage_purpose sendiri. Beda dengan Penerimaan: di sini
     * WAJIB divalidasi saldo "Available" cukup (tidak boleh minus) — pakai
     * ChemicalStockModel::getAvailableBalance() sebagai satu-satunya sumber kebenaran
     * saldo (sama persis dgn yang tampil di Posisi Stok).
     * $rows: array of ['chemical_id' => int, 'variant_id' => ?int, 'qty_unit' => ?float,
     *                   'quantity' => float, 'unit' => string, 'movement_date' => 'Y-m-d',
     *                   'usage_purpose' => 'Sample'|'Litbang'|'SamplingProduksi'|'Perbaikan', 'notes' => string]
     */
    public function saveIssueBulk(int $periodId, int $warehouseId, array $rows, int $userId): array
    {
        $period = $this->db->table('periods')->where('id', $periodId)->where('deleted_at', null)->get()->getRowArray();
        if (!$period) {
            return ['status' => 'error', 'message' => 'Periode tidak ditemukan'];
        }
        if ($period['status'] === 'Closed') {
            return ['status' => 'error', 'message' => 'Periode sudah ditutup, transaksi tidak bisa dicatat'];
        }

        $warehouse = $this->db->table('warehouses')->where('id', $warehouseId)->where('deleted_at', null)->get()->getRowArray();
        if (!$warehouse) {
            return ['status' => 'error', 'message' => 'Gudang tidak ditemukan'];
        }

        $validPurposes = ['Sample', 'Litbang', 'SamplingProduksi', 'Perbaikan'];
        $stockModel    = new \App\Modules\Warehouse\Models\ChemicalStockModel();

        $saved   = 0;
        $skipped = [];

        // Saldo "berjalan" per chemical selama loop ini — supaya beberapa baris pemakaian
        // untuk chemical yang SAMA dalam satu batch simpan tetap saling mengurangi
        // (tidak masing-masing divalidasi terhadap saldo awal yang sama / stale).
        $runningAvailable = [];

        foreach ($rows as $row) {
            $chemicalId = (int) ($row['chemical_id'] ?? 0);
            if (!$chemicalId) continue;

            $movementDate = trim((string) ($row['movement_date'] ?? ''));
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $movementDate)) {
                $skipped[] = "Chemical #{$chemicalId}: tanggal pemakaian tidak valid";
                continue;
            }
            if ($movementDate < $period['start_date'] || $movementDate > $period['end_date']) {
                $skipped[] = "Chemical #{$chemicalId}: tanggal {$movementDate} di luar rentang periode ({$period['start_date']} s/d {$period['end_date']})";
                continue;
            }

            $usagePurpose = trim((string) ($row['usage_purpose'] ?? ''));
            if (!in_array($usagePurpose, $validPurposes, true)) {
                $skipped[] = "Chemical #{$chemicalId}: tujuan pemakaian tidak valid";
                continue;
            }

            $quantity = is_numeric($row['quantity'] ?? null) ? (float) $row['quantity'] : 0;
            if ($quantity <= 0) {
                $skipped[] = "Chemical #{$chemicalId}: qty harus lebih dari 0";
                continue;
            }

            $chemical = $this->db->table('chemicals')
                ->where('id', $chemicalId)->where('status', 'Active')->where('deleted_at', null)
                ->get()->getRowArray();
            if (!$chemical) {
                $skipped[] = "Chemical #{$chemicalId}: tidak ditemukan / non-aktif";
                continue;
            }

            $hasOpening = $this->db->table('chemical_stock_openings')
                ->where('period_id', $periodId)
                ->where('warehouse_id', $warehouseId)
                ->where('chemical_id', $chemicalId)
                ->countAllResults() > 0;
            if (!$hasOpening) {
                $skipped[] = "{$chemical['chemical_name']}: belum ada Stok Awal di gudang & periode ini";
                continue;
            }

            // Validasi saldo cukup — pakai saldo berjalan dalam batch ini kalau sudah pernah dihitung.
            if (!array_key_exists($chemicalId, $runningAvailable)) {
                $runningAvailable[$chemicalId] = $stockModel->getAvailableBalance($periodId, $warehouseId, $chemicalId) ?? 0;
            }
            if ($quantity > $runningAvailable[$chemicalId] + 0.0005) {
                $skipped[] = "{$chemical['chemical_name']}: qty ({$quantity}) melebihi saldo tersedia ({$runningAvailable[$chemicalId]})";
                continue;
            }

            $variantId = !empty($row['variant_id']) ? (int) $row['variant_id'] : null;
            if ($variantId) {
                $variantOk = $this->db->table('chemical_variants')
                    ->where('id', $variantId)->where('chemical_id', $chemicalId)
                    ->countAllResults() > 0;
                if (!$variantOk) $variantId = null;
            }

            $unit  = trim($row['unit'] ?? '') ?: 'kg';
            $notes = trim($row['notes'] ?? '') ?: null;
            $qtyUnit = is_numeric($row['qty_unit'] ?? null) ? (float) $row['qty_unit'] : 0;
            if ($qtyUnit < 0) $qtyUnit = 0;

            $this->insert([
                'period_id'      => $periodId,
                'warehouse_id'   => $warehouseId,
                'chemical_id'    => $chemicalId,
                'variant_id'     => $variantId,
                'movement_type'  => 'Issue',
                'usage_purpose'  => $usagePurpose,
                'quantity_in'    => 0,
                'qty_unit'       => $qtyUnit,
                'qty_berat'      => $quantity,
                'quantity_out'   => $quantity,
                'unit'           => $unit,
                'reference_type' => 'manual',
                'reference_id'   => null,
                'movement_date'  => $movementDate,
                'notes'          => $notes,
                'created_by'     => $userId,
            ]);

            $runningAvailable[$chemicalId] -= $quantity;
            $saved++;
        }

        if ($saved === 0) {
            return [
                'status'  => 'error',
                'message' => 'Tidak ada item yang berhasil disimpan' . (!empty($skipped) ? ': ' . implode('; ', $skipped) : ''),
                'saved'   => 0,
                'skipped' => $skipped,
            ];
        }

        $message = "Pemakaian berhasil disimpan ({$saved} item)";
        if (!empty($skipped)) {
            $message .= '. ' . count($skipped) . ' item dilewati: ' . implode('; ', $skipped);
        }

        return ['status' => 'success', 'message' => $message, 'saved' => $saved, 'skipped' => $skipped];
    }

    /**
     * Riwayat transaksi untuk 1 jenis pergerakan di 1 periode+gudang — dipakai tabel
     * "Riwayat Penerimaan". Bisa difilter: seluruh periode (default, dibatasi $limit
     * terbaru), rentang tanggal ($fromDate & $toDate), atau satu tanggal saja
     * ($fromDate == $toDate).
     */
    public function getRecent(int $periodId, int $warehouseId, string $movementType, ?string $fromDate = null, ?string $toDate = null, int $limit = 30): array
    {
        $hasDateFilter = $fromDate !== null || $toDate !== null;

        $q = $this->db->table('chemical_stock_movements m')
            ->select([
                'm.id',
                'm.movement_date',
                'm.quantity_in',
                'm.qty_unit',
                'm.qty_berat',
                'm.quantity_out',
                'm.usage_purpose',
                'm.unit',
                'm.notes',
                'm.created_at',
                'c.chemical_code',
                'c.chemical_name',
                'v.variant_name',
                'v.packaging',
                'v.packaging_size',
                'u.username',
                'e.fullname as employee_fullname',
            ])
            ->join('chemicals c', 'c.id = m.chemical_id', 'left')
            ->join('chemical_variants v', 'v.id = m.variant_id', 'left')
            ->join('users u', 'u.id = m.created_by', 'left')
            ->join('employees e', 'e.id = u.employee_id', 'left')
            ->where('m.period_id', $periodId)
            ->where('m.warehouse_id', $warehouseId)
            ->where('m.movement_type', $movementType);

        if ($fromDate !== null) $q->where('m.movement_date >=', $fromDate);
        if ($toDate !== null) $q->where('m.movement_date <=', $toDate);

        $q->orderBy('m.movement_date', 'DESC')->orderBy('m.created_at', 'DESC');

        // Kalau user memang minta rentang/tanggal tertentu, tampilkan semua (dibatasi wajar 1000)
        // supaya tidak terpotong; kalau tanpa filter tanggal, cukup N terbaru saja.
        $q->limit($hasDateFilter ? 1000 : $limit);

        return $q->get()->getResultArray();
    }

    /**
     * Hapus 1 baris transaksi (dipakai untuk membatalkan input yang salah).
     * Hanya boleh dihapus kalau periode masih Open.
     */
    public function deleteOne(int $id): array
    {
        $row = $this->find($id);
        if (!$row) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        $period = $this->db->table('periods')->where('id', $row['period_id'])->get()->getRowArray();
        if ($period && $period['status'] === 'Closed') {
            return ['status' => 'error', 'message' => 'Periode sudah ditutup, transaksi tidak bisa dihapus'];
        }

        $this->delete($id);
        return ['status' => 'success', 'message' => 'Transaksi berhasil dihapus'];
    }
}