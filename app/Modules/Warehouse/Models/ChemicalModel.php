<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

class ChemicalModel extends Model
{
    protected $table            = 'chemicals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'chemical_code',
        'chemical_name',
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

    const CODE_PREFIX = 'CH-';
    const CODE_PAD     = 5; // CH-00001

    const STATUS_ACTIVE   = 'Active';
    const STATUS_DRAFT    = 'Draft';
    const STATUS_ARCHIVED = 'Archived';

    // ============================================================
    // VALIDATION RULES
    // ============================================================

    protected $validationRules = [
        'chemical_name' => 'required|max_length[150]',
        'status'        => 'required|in_list[Active,Draft,Archived]',
        'description'   => 'permit_empty|max_length[500]',
    ];

    protected $validationMessages = [
        'chemical_name' => [
            'required' => 'Nama bahan kimia wajib diisi',
            'max_length' => 'Nama bahan kimia maksimal 150 karakter',
        ],
        'status' => [
            'in_list' => 'Status tidak valid',
        ],
    ];

    // ============================================================
    // AUTO CODE GENERATION
    // ============================================================

    public function generateNextCode(): string
    {
        $db = $this->db;

        // Coba ambil dari sequence table
        $db->transStart();

        try {
            // Pastikan sequence table ada
            $this->ensureSequenceTable();

            // Kunci baris sequence untuk update (row lock)
            $db->query('SELECT last_number FROM chemical_code_sequence LIMIT 1 FOR UPDATE');
            $db->table('chemical_code_sequence')->set('last_number', 'last_number + 1', false)->update();
            $row = $db->table('chemical_code_sequence')->select('last_number')->get()->getRowArray();

            $db->transComplete();

            $next = (int) ($row['last_number'] ?? 1);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'generateNextCode sequence error: ' . $e->getMessage());

            // Fallback: hitung dari data existing
            $maxExisting = (int) ($db->table('chemicals')
                ->selectMax('CAST(SUBSTRING(chemical_code, ' . (strlen(self::CODE_PREFIX) + 1) . ') AS UNSIGNED)', 'max_num')
                ->like('chemical_code', self::CODE_PREFIX, 'after')
                ->get()->getRowArray()['max_num'] ?? 0);

            $next = $maxExisting + 1;

            // Update sequence untuk sinkronisasi
            $this->ensureSequenceTable();
            $db->table('chemical_code_sequence')->update(['last_number' => $next]);
        }

        return self::CODE_PREFIX . str_pad((string) $next, self::CODE_PAD, '0', STR_PAD_LEFT);
    }

    private function ensureSequenceTable(): void
    {
        $db = $this->db;

        // Cek apakah table sequence ada
        $tableExists = $db->tableExists('chemical_code_sequence');

        if (!$tableExists) {
            // Buat table sequence
            $db->query("
                CREATE TABLE IF NOT EXISTS chemical_code_sequence (
                    id INT PRIMARY KEY DEFAULT 1,
                    last_number INT NOT NULL DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");

            // Insert default value
            $maxExisting = (int) ($db->table('chemicals')
                ->selectMax('CAST(SUBSTRING(chemical_code, ' . (strlen(self::CODE_PREFIX) + 1) . ') AS UNSIGNED)', 'max_num')
                ->like('chemical_code', self::CODE_PREFIX, 'after')
                ->get()->getRowArray()['max_num'] ?? 0);

            $db->table('chemical_code_sequence')->insert(['last_number' => $maxExisting]);
        }
    }

    // ============================================================
    // DUPLICATE CHECKS
    // ============================================================

    private function isDuplicateCode(string $code, ?int $excludeId = null): bool
    {
        $q = $this->db->table('chemicals')
            ->where('LOWER(chemical_code)', strtolower(trim($code)))
            ->where('deleted_at', null);
        if ($excludeId) {
            $q->where('id !=', $excludeId);
        }
        return $q->countAllResults() > 0;
    }

    private function isDuplicateName(string $name, ?int $excludeId = null): bool
    {
        $q = $this->db->table('chemicals')
            ->where('LOWER(chemical_name)', strtolower(trim($name)))
            ->where('deleted_at', null);
        if ($excludeId) {
            $q->where('id !=', $excludeId);
        }
        return $q->countAllResults() > 0;
    }

    // ============================================================
    // CRUD (main chemical)
    // ============================================================

    public function createData(array $data, array $categoryIds = []): array
    {
        // Validasi
        if (!$this->validate($data)) {
            return ['status' => 'error', 'errors' => $this->validator->getErrors()];
        }

        // Kode dibuat otomatis
        $data['chemical_code'] = $this->generateNextCode();

        // Cek duplikat nama
        if ($this->isDuplicateName($data['chemical_name'] ?? '')) {
            return ['status' => 'error', 'errors' => ['chemical_name' => 'Nama bahan kimia sudah digunakan']];
        }

        // Mulai transaksi
        $this->db->transStart();

        try {
            if (!$this->insert($data)) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
            }

            $id = $this->getInsertID();
            $this->syncCategories($id, $categoryIds);

            $this->db->transComplete();
            return ['status' => 'success', 'message' => 'Bahan kimia berhasil ditambahkan', 'id' => $id];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'createData: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()];
        }
    }

    public function updateData(int $id, array $data, array $categoryIds = []): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        // Validasi
        if (!$this->validate($data)) {
            return ['status' => 'error', 'errors' => $this->validator->getErrors()];
        }

        // Kode bersifat permanen, tidak diubah saat update
        unset($data['chemical_code']);

        // Cek duplikat nama
        if ($this->isDuplicateName($data['chemical_name'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['chemical_name' => 'Nama bahan kimia sudah digunakan']];
        }

        // Mulai transaksi
        $this->db->transStart();

        try {
            if (!$this->update($id, $data)) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
            }

            $this->syncCategories($id, $categoryIds);

            $this->db->transComplete();
            return ['status' => 'success', 'message' => 'Bahan kimia berhasil diperbarui'];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'updateData: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal memperbarui data: ' . $e->getMessage()];
        }
    }

    public function getData(int $id): array
    {
        $data = $this->db->table('chemicals c')
            ->select('c.*')
            ->where('c.id', $id)
            ->where('c.deleted_at', null)
            ->get()->getRowArray();

        if (!$data) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        $data['categories'] = $this->getCategories($id);
        $data['variants']   = $this->getVariants($id);

        return ['status' => 'success', 'data' => $data];
    }

    public function deleteData(int $id, int $userId): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        $this->db->transStart();

        try {
            $this->update($id, ['status' => self::STATUS_ARCHIVED, 'deleted_by' => $userId]);
            $this->delete($id);

            $this->db->transComplete();
            return ['status' => 'success', 'message' => 'Bahan kimia dipindahkan ke sampah'];
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
            return ['status' => 'success', 'message' => 'Bahan kimia berhasil dipulihkan'];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'restoreData: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal memulihkan data: ' . $e->getMessage()];
        }
    }

    public function forceDeleteData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        }

        $this->db->transStart();

        try {
            // Hapus relasi
            $this->db->table('chemical_variants')->where('chemical_id', $id)->delete();
            $this->db->table('chemical_category_map')->where('chemical_id', $id)->delete();

            if (!$this->delete($id, true)) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Gagal menghapus permanen'];
            }

            $this->db->transComplete();
            return ['status' => 'success', 'message' => 'Bahan kimia berhasil dihapus permanen'];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'forceDeleteData: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal menghapus permanen: ' . $e->getMessage()];
        }
    }

    // ============================================================
    // CATEGORIES (many-to-many)
    // ============================================================

    public function getCategories(int $chemicalId): array
    {
        return $this->db->table('chemical_category_map m')
            ->select('cc.id, cc.category_name, cc.category_code')
            ->join('chemical_categories cc', 'cc.id = m.category_id')
            ->where('m.chemical_id', $chemicalId)
            ->orderBy('cc.category_name', 'ASC')
            ->get()->getResultArray();
    }

    public function syncCategories(int $chemicalId, array $categoryIds): void
    {
        $categoryIds = array_values(array_unique(array_filter(array_map('intval', $categoryIds))));

        $this->db->table('chemical_category_map')->where('chemical_id', $chemicalId)->delete();

        if (empty($categoryIds)) {
            return;
        }

        $rows = [];
        foreach ($categoryIds as $catId) {
            $rows[] = [
                'chemical_id' => $chemicalId,
                'category_id' => $catId,
                'created_at'  => date('Y-m-d H:i:s'),
            ];
        }
        $this->db->table('chemical_category_map')->insertBatch($rows);
    }

    // ============================================================
    // VARIANTS (read-only helpers)
    // ============================================================

    public function getVariants(int $chemicalId): array
    {
        return $this->db->table('chemical_variants')
            ->where('chemical_id', $chemicalId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('variant_name', 'ASC')
            ->get()->getResultArray();
    }

    // ============================================================
    // STATS WITH CACHE
    // ============================================================

    public function getStats(): array
    {
        $cacheKey = 'chemical_stats_' . md5(date('Y-m-d H') . '00'); // Cache per jam

        if ($cached = cache($cacheKey)) {
            return $cached;
        }

        $rows = $this->db->table('chemicals')
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
            'variants' => 0,
        ];

        foreach ($rows as $row) {
            $stats['total'] += (int) $row['count'];
            $key = strtolower($row['status']);
            if (isset($stats[$key])) {
                $stats[$key] = (int) $row['count'];
            }
        }

        $stats['trash'] = $this->db->table('chemicals')
            ->where('deleted_at IS NOT NULL')
            ->countAllResults();

        $stats['variants'] = $this->db->table('chemical_variants')
            ->countAllResults();

        // Cache selama 1 jam
        cache()->save($cacheKey, $stats, 3600);

        return $stats;
    }
}
