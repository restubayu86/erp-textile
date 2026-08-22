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
        'quantity_in',
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
     * Simpan bulk baris Penerimaan untuk 1 periode + 1 gudang + 1 tanggal.
     * $rows: array of ['chemical_id' => int, 'quantity' => float, 'unit' => string, 'notes' => string]
     *
     * Return: ['status' => 'success'|'error', 'message' => string, 'saved' => int, 'skipped' => array]
     */
    public function saveReceiptBulk(int $periodId, int $warehouseId, string $movementDate, array $rows, int $userId): array
    {
        $period = $this->db->table('periods')->where('id', $periodId)->where('deleted_at', null)->get()->getRowArray();
        if (!$period) {
            return ['status' => 'error', 'message' => 'Periode tidak ditemukan'];
        }
        if ($period['status'] === 'Closed') {
            return ['status' => 'error', 'message' => 'Periode sudah ditutup, transaksi tidak bisa dicatat'];
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $movementDate)) {
            return ['status' => 'error', 'message' => 'Format tanggal penerimaan tidak valid'];
        }
        if ($movementDate < $period['start_date'] || $movementDate > $period['end_date']) {
            return ['status' => 'error', 'message' => 'Tanggal penerimaan harus berada dalam rentang periode (' . $period['start_date'] . ' s/d ' . $period['end_date'] . ')'];
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

            $unit  = trim($row['unit'] ?? '') ?: 'kg';
            $notes = trim($row['notes'] ?? '') ?: null;

            $this->insert([
                'period_id'      => $periodId,
                'warehouse_id'   => $warehouseId,
                'chemical_id'    => $chemicalId,
                'variant_id'     => null,
                'movement_type'  => 'Receipt',
                'quantity_in'    => $quantity,
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

    /**
     * Riwayat transaksi terbaru untuk 1 jenis pergerakan di 1 periode+gudang — dipakai
     * untuk tabel "Riwayat Penerimaan Terbaru" setelah user menyimpan.
     */
    public function getRecent(int $periodId, int $warehouseId, string $movementType, int $limit = 20): array
    {
        return $this->db->table('chemical_stock_movements m')
            ->select([
                'm.id',
                'm.movement_date',
                'm.quantity_in',
                'm.quantity_out',
                'm.unit',
                'm.notes',
                'm.created_at',
                'c.chemical_code',
                'c.chemical_name',
                'u.username',
                'e.fullname as employee_fullname',
            ])
            ->join('chemicals c', 'c.id = m.chemical_id', 'left')
            ->join('users u', 'u.id = m.created_by', 'left')
            ->join('employees e', 'e.id = u.employee_id', 'left')
            ->where('m.period_id', $periodId)
            ->where('m.warehouse_id', $warehouseId)
            ->where('m.movement_type', $movementType)
            ->orderBy('m.created_at', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
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
