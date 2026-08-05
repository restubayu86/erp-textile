<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

class PeriodModel extends Model
{
    protected $table            = 'periods';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'period_code',
        'period_name',
        'start_date',
        'end_date',
        'status',
        'is_current',
        'notes',
        'closed_at',
        'closed_by',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // ============================================================
    // VALIDATION HELPERS
    // ============================================================

    private function isDuplicateCode(string $code, ?int $excludeId = null): bool
    {
        $q = $this->db->table('periods')
            ->where('LOWER(period_code)', strtolower(trim($code)))
            ->where('deleted_at', null);
        if ($excludeId) $q->where('id !=', $excludeId);
        return $q->countAllResults() > 0;
    }

    /**
     * Cek apakah rentang tanggal tumpang tindih dengan periode lain yang masih aktif.
     */
    private function isOverlapping(string $startDate, string $endDate, ?int $excludeId = null): bool
    {
        $q = $this->db->table('periods')
            ->where('deleted_at', null)
            ->where('start_date <=', $endDate)
            ->where('end_date >=', $startDate);
        if ($excludeId) $q->where('id !=', $excludeId);
        return $q->countAllResults() > 0;
    }

    // ============================================================
    // CRUD
    // ============================================================

    public function createData(array $data): array
    {
        if ($this->isDuplicateCode($data['period_code'] ?? '')) {
            return ['status' => 'error', 'errors' => ['period_code' => 'Kode periode sudah digunakan']];
        }
        if (!empty($data['start_date']) && !empty($data['end_date']) && $data['start_date'] > $data['end_date']) {
            return ['status' => 'error', 'errors' => ['end_date' => 'Tanggal akhir tidak boleh sebelum tanggal mulai']];
        }
        if ($this->isOverlapping($data['start_date'] ?? '', $data['end_date'] ?? '')) {
            return ['status' => 'error', 'errors' => ['start_date' => 'Rentang tanggal tumpang tindih dengan periode lain']];
        }

        if (!empty($data['is_current'])) {
            $this->clearCurrentFlag();
        }

        if (!$this->insert($data)) {
            return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
        }
        return ['status' => 'success', 'message' => 'Periode berhasil ditambahkan', 'id' => $this->getInsertID()];
    }

    public function updateData(int $id, array $data): array
    {
        $existing = $this->find($id);
        if (!$existing) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];

        if ($existing['status'] === 'Closed') {
            return ['status' => 'error', 'message' => 'Periode yang sudah ditutup tidak bisa diedit'];
        }
        if ($this->isDuplicateCode($data['period_code'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['period_code' => 'Kode periode sudah digunakan']];
        }
        if (!empty($data['start_date']) && !empty($data['end_date']) && $data['start_date'] > $data['end_date']) {
            return ['status' => 'error', 'errors' => ['end_date' => 'Tanggal akhir tidak boleh sebelum tanggal mulai']];
        }
        if ($this->isOverlapping($data['start_date'] ?? '', $data['end_date'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['start_date' => 'Rentang tanggal tumpang tindih dengan periode lain']];
        }

        if (!empty($data['is_current'])) {
            $this->clearCurrentFlag($id);
        }

        if (!$this->update($id, $data)) {
            return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
        }
        return ['status' => 'success', 'message' => 'Periode berhasil diperbarui'];
    }

    public function getData(int $id): array
    {
        $data = $this->find($id);
        if (!$data) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        return ['status' => 'success', 'data' => $data];
    }

    public function deleteData(int $id, int $userId): array
    {
        $existing = $this->find($id);
        if (!$existing) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];

        if ($existing['status'] === 'Closed') {
            return ['status' => 'error', 'message' => 'Periode yang sudah ditutup tidak bisa dihapus'];
        }
        if (!empty($existing['is_current'])) {
            return ['status' => 'error', 'message' => 'Periode yang sedang aktif tidak bisa dihapus'];
        }

        $this->update($id, ['deleted_by' => $userId]);
        $this->delete($id);
        return ['status' => 'success', 'message' => 'Periode dipindahkan ke sampah'];
    }

    public function restoreData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        $this->db->table($this->table)->where('id', $id)->update(['deleted_at' => null, 'deleted_by' => null]);
        return ['status' => 'success', 'message' => 'Periode berhasil dipulihkan'];
    }

    public function forceDeleteData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];

        $used = $this->db->table('chemical_stock_openings')->where('period_id', $id)->countAllResults();
        if ($used > 0) {
            return ['status' => 'error', 'message' => "Periode tidak dapat dihapus permanen karena sudah memiliki {$used} data stok awal"];
        }

        if (!$this->delete($id, true)) return ['status' => 'error', 'message' => 'Gagal menghapus permanen'];
        return ['status' => 'success', 'message' => 'Periode berhasil dihapus permanen'];
    }

    // ============================================================
    // BUSINESS ACTIONS
    // ============================================================

    /**
     * Set periode ini sebagai periode aktif berjalan (is_current), nonaktifkan yang lain.
     */
    public function setCurrent(int $id): array
    {
        $existing = $this->find($id);
        if (!$existing) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        if ($existing['status'] === 'Closed') {
            return ['status' => 'error', 'message' => 'Periode yang sudah ditutup tidak bisa dijadikan periode aktif'];
        }

        $this->clearCurrentFlag($id);
        $this->update($id, ['is_current' => 1]);
        return ['status' => 'success', 'message' => 'Periode berhasil dijadikan periode aktif'];
    }

    /**
     * Tutup periode (kunci dari transaksi lebih lanjut).
     */
    public function closePeriod(int $id, int $userId): array
    {
        $existing = $this->find($id);
        if (!$existing) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        if ($existing['status'] === 'Closed') {
            return ['status' => 'error', 'message' => 'Periode sudah ditutup sebelumnya'];
        }

        $this->update($id, [
            'status'    => 'Closed',
            'closed_at' => date('Y-m-d H:i:s'),
            'closed_by' => $userId,
        ]);
        return ['status' => 'success', 'message' => 'Periode berhasil ditutup'];
    }

    private function clearCurrentFlag(?int $excludeId = null): void
    {
        $q = $this->db->table('periods')->where('is_current', 1);
        if ($excludeId) $q->where('id !=', $excludeId);
        $q->update(['is_current' => 0]);
    }

    /**
     * Cari periode yang cakupannya meliputi bulan kalender tertentu.
     * $month format: 'YYYY-MM'
     */
    public function findByMonth(string $month): ?array
    {
        $firstDay = $month . '-01';
        $lastDay  = date('Y-m-t', strtotime($firstDay));

        return $this->where('deleted_at', null)
            ->where('start_date <=', $lastDay)
            ->where('end_date >=', $firstDay)
            ->first();
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function getCurrentPeriod(): ?array
    {
        return $this->where('is_current', 1)->where('deleted_at', null)->first();
    }

    public function getStats(): array
    {
        $rows = $this->db->table('periods')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get()->getResultArray();

        $stats = ['total' => 0, 'open' => 0, 'closed' => 0];
        foreach ($rows as $row) {
            $stats['total'] += (int) $row['count'];
            $key = strtolower($row['status']);
            if (isset($stats[$key])) $stats[$key] = (int) $row['count'];
        }
        $stats['trash'] = $this->db->table('periods')->where('deleted_at IS NOT NULL')->countAllResults();
        return $stats;
    }
}
