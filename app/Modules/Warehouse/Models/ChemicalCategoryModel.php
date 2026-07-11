<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

class ChemicalCategoryModel extends Model
{
    protected $table            = 'chemical_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'category_code',
        'category_name',
        'description',
        'status',
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
    // CONSTANTS
    // ============================================================

    const STATUS_ACTIVE   = 'Active';
    const STATUS_DRAFT    = 'Draft';
    const STATUS_ARCHIVED = 'Archived';

    // FIX: cache key TETAP (bukan berbasis jam berjalan) supaya bisa
    // di-invalidate secara eksplisit lewat clearStatsCache(). Sebelumnya
    // key-nya berubah tiap jam (`date('Y-m-d H')`), sehingga angka stat
    // card selalu menampilkan data lama sampai maksimal 1 jam meskipun
    // kategori baru saja ditambah/diedit/dihapus/dipulihkan — walaupun
    // frontend sudah benar memanggil loadStats() persis setelah aksi itu.
    private const STATS_CACHE_KEY = 'chemical_category_stats';

    // ============================================================
    // VALIDATION RULES
    // ============================================================

    protected $validationRules = [
        'category_code' => 'required|max_length[30]|alpha_numeric_punct',
        'category_name' => 'required|max_length[100]',
        'status'        => 'required|in_list[Active,Draft,Archived]',
        'description'   => 'permit_empty|max_length[500]',
    ];

    protected $validationMessages = [
        'category_code' => [
            'required' => 'Kode kategori wajib diisi',
            'max_length' => 'Kode kategori maksimal 30 karakter',
            'alpha_numeric_punct' => 'Kode kategori hanya boleh huruf, angka, dan tanda baca',
        ],
        'category_name' => [
            'required' => 'Nama kategori wajib diisi',
            'max_length' => 'Nama kategori maksimal 100 karakter',
        ],
        'status' => [
            'in_list' => 'Status tidak valid',
        ],
    ];

    // ============================================================
    // DUPLICATE CHECKS
    // ============================================================

    private function isDuplicateCode(string $code, ?int $excludeId = null): bool
    {
        $q = $this->db->table('chemical_categories')
            ->where('LOWER(category_code)', strtolower(trim($code)))
            ->where('deleted_at', null);
        if ($excludeId) {
            $q->where('id !=', $excludeId);
        }
        return $q->countAllResults() > 0;
    }

    private function isDuplicateName(string $name, ?int $excludeId = null): bool
    {
        $q = $this->db->table('chemical_categories')
            ->where('LOWER(category_name)', strtolower(trim($name)))
            ->where('deleted_at', null);
        if ($excludeId) {
            $q->where('id !=', $excludeId);
        }
        return $q->countAllResults() > 0;
    }

    // ============================================================
    // CHECK IF CATEGORY IS USED
    // ============================================================

    /**
     * Cek apakah kategori digunakan oleh bahan kimia
     * Menggunakan tabel pivot chemical_category_map
     */
    public function isCategoryUsed(int $categoryId): bool
    {
        return $this->db->table('chemical_category_map')
            ->where('category_id', $categoryId)
            ->countAllResults() > 0;
    }

    /**
     * Hitung jumlah bahan kimia yang menggunakan kategori ini
     */
    public function getChemicalCount(int $categoryId): int
    {
        return $this->db->table('chemical_category_map')
            ->where('category_id', $categoryId)
            ->countAllResults();
    }

    // ============================================================
    // CRUD
    // ============================================================

    public function createData(array $data): array
    {
        // Validasi
        if (!$this->validate($data)) {
            return ['status' => 'error', 'errors' => $this->validator->getErrors()];
        }

        // Cek duplikat kode
        if ($this->isDuplicateCode($data['category_code'])) {
            return ['status' => 'error', 'errors' => ['category_code' => 'Kode kategori sudah digunakan']];
        }

        // Cek duplikat nama
        if ($this->isDuplicateName($data['category_name'])) {
            return ['status' => 'error', 'errors' => ['category_name' => 'Nama kategori sudah digunakan']];
        }

        $this->db->transStart();

        try {
            if (!$this->insert($data)) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
            }

            $this->db->transComplete();
            $this->clearStatsCache(); // FIX: total/active/draft/archived berubah
            return ['status' => 'success', 'message' => 'Kategori berhasil ditambahkan', 'id' => $this->getInsertID()];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'createData: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()];
        }
    }

    public function updateData(int $id, array $data): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        // Validasi
        if (!$this->validate($data)) {
            return ['status' => 'error', 'errors' => $this->validator->getErrors()];
        }

        // Cek duplikat kode
        if ($this->isDuplicateCode($data['category_code'], $id)) {
            return ['status' => 'error', 'errors' => ['category_code' => 'Kode kategori sudah digunakan']];
        }

        // Cek duplikat nama
        if ($this->isDuplicateName($data['category_name'], $id)) {
            return ['status' => 'error', 'errors' => ['category_name' => 'Nama kategori sudah digunakan']];
        }

        $this->db->transStart();

        try {
            if (!$this->update($id, $data)) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
            }

            $this->db->transComplete();
            $this->clearStatsCache(); // FIX: status bisa berubah (mis. Draft -> Active)
            return ['status' => 'success', 'message' => 'Kategori berhasil diperbarui'];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'updateData: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal memperbarui data: ' . $e->getMessage()];
        }
    }

    public function getData(int $id): array
    {
        $data = $this->db->table('chemical_categories c')
            ->select('c.*')
            ->where('c.id', $id)
            ->where('c.deleted_at', null)
            ->get()->getRowArray();

        if (!$data) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        // Hitung jumlah bahan kimia yang menggunakan kategori ini (via pivot table)
        $data['chemical_count'] = $this->getChemicalCount($id);

        return ['status' => 'success', 'data' => $data];
    }

    public function deleteData(int $id, int $userId): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        // Cek apakah kategori masih digunakan (via pivot table)
        $usedCount = $this->getChemicalCount($id);

        if ($usedCount > 0) {
            return ['status' => 'error', 'message' => "Kategori masih digunakan oleh {$usedCount} bahan kimia. Tidak bisa dihapus."];
        }

        $this->db->transStart();

        try {
            $this->update($id, ['status' => self::STATUS_ARCHIVED, 'deleted_by' => $userId]);
            $this->delete($id);

            $this->db->transComplete();
            $this->clearStatsCache(); // FIX: total & trash berubah
            return ['status' => 'success', 'message' => 'Kategori dipindahkan ke sampah'];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'deleteData: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()];
        }
    }

    public function restoreData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        }

        $this->db->transStart();

        try {
            $this->db->table($this->table)
                ->where('id', $id)
                ->update([
                    'deleted_at' => null,
                    'deleted_by' => null,
                    'status' => self::STATUS_DRAFT
                ]);

            $this->db->transComplete();
            $this->clearStatsCache(); // FIX: total & trash berubah
            return ['status' => 'success', 'message' => 'Kategori berhasil dipulihkan'];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'restoreData: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal memulihkan data: ' . $e->getMessage()];
        }
    }

    public function forceDeleteData(int $id): array
    {
        $category = $this->onlyDeleted()->find($id);
        if (!$category) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        }

        // Cek apakah kategori masih digunakan (termasuk di trash)
        $usedCount = $this->getChemicalCount($id);

        if ($usedCount > 0) {
            return ['status' => 'error', 'message' => "Kategori masih digunakan oleh {$usedCount} bahan kimia. Tidak bisa dihapus permanen."];
        }

        $this->db->transStart();

        try {
            // Hapus relasi di chemical_category_map
            $this->db->table('chemical_category_map')
                ->where('category_id', $id)
                ->delete();

            if (!$this->delete($id, true)) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Gagal menghapus permanen'];
            }

            $this->db->transComplete();
            $this->clearStatsCache(); // FIX: trash berubah (baris hilang dari sampah)
            return ['status' => 'success', 'message' => 'Kategori berhasil dihapus permanen'];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'forceDeleteData: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal menghapus permanen: ' . $e->getMessage()];
        }
    }

    // ============================================================
    // STATS WITH CACHE
    // ============================================================

    public function getStats(): array
    {
        if ($cached = cache(self::STATS_CACHE_KEY)) {
            return $cached;
        }

        $rows = $this->db->table('chemical_categories')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get()->getResultArray();

        $stats = [
            'total' => 0,
            'active' => 0,
            'draft' => 0,
            'archived' => 0,
            'trash' => 0,
        ];

        foreach ($rows as $row) {
            $stats['total'] += (int) $row['count'];
            $key = strtolower($row['status']);
            if (isset($stats[$key])) {
                $stats[$key] = (int) $row['count'];
            }
        }

        $stats['trash'] = $this->db->table('chemical_categories')
            ->where('deleted_at IS NOT NULL')
            ->countAllResults();

        cache()->save(self::STATS_CACHE_KEY, $stats, 3600);

        return $stats;
    }

    /**
     * Hapus cache stats. Dipanggil setiap kali jumlah/status kategori
     * berubah (create, update, delete, restore, force delete) supaya
     * stat card di halaman selalu menampilkan angka terkini — bukan
     * data basi sampai 1 jam seperti sebelumnya.
     */
    private function clearStatsCache(): void
    {
        cache()->delete(self::STATS_CACHE_KEY);
    }

    // ============================================================
    // SELECT2
    // ============================================================

    public function getSelect2Data(string $search = '', int $limit = 50): array
    {
        $builder = $this->db->table('chemical_categories')
            ->select('id, category_code AS code, category_name AS name')
            ->where('status', 'Active')
            ->where('deleted_at', null)
            ->orderBy('category_name', 'ASC');

        if ($search !== '') {
            $builder->groupStart()
                ->like('category_name', $search)
                ->orLike('category_code', $search)
                ->groupEnd();
        }

        return $builder->limit($limit)->get()->getResultArray();
    }
}
