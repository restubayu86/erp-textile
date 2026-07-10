<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

/**
 * ChemicalModel
 *
 * Namespace : App\Modules\Warehouse\Models
 * Table     : chemicals
 * Relations : chemical_categories (M-M via chemical_category_map)
 *             chemical_variants   (1-M)
 *
 * Konvensi field:
 *   chemical_code → auto-generate, format CH-00001
 *   chemical_name → nama bahan kimia
 *   status        → ENUM string: Active | Draft | Archived
 */
class ChemicalModel extends Model
{
    protected $table            = 'chemicals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

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

    // ── Constants ─────────────────────────────────────────────────

    const CODE_PREFIX     = 'CH-';
    const CODE_PAD        = 5;           // CH-00001
    const STATUS_ACTIVE   = 'Active';
    const STATUS_DRAFT    = 'Draft';
    const STATUS_ARCHIVED = 'Archived';

    // Berapa kali retry insert jika kebentur duplicate chemical_code
    // (hanya bisa terjadi kalau ada dua request nyaris bersamaan).
    private const CODE_GENERATION_MAX_ATTEMPTS = 5;

    // ── Validation ────────────────────────────────────────────────

    protected $validationRules = [
        'chemical_name' => 'required|max_length[150]',
        'status'        => 'required|in_list[Active,Draft,Archived]',
        'description'   => 'permit_empty|max_length[500]',
    ];

    protected $validationMessages = [
        'chemical_name' => [
            'required'   => 'Nama bahan kimia wajib diisi.',
            'max_length' => 'Nama bahan kimia maksimal 150 karakter.',
        ],
        'status' => [
            'required' => 'Status wajib dipilih.',
            'in_list'  => 'Status tidak valid.',
        ],
    ];

    // ════════════════════════════════════════════════════════════
    //  AUTO CODE GENERATION
    //  (langsung dari MAX(chemical_code) di tabel `chemicals` —
    //   TIDAK ada tabel sequence terpisah lagi)
    // ════════════════════════════════════════════════════════════

    /**
     * Generate kode berikutnya berdasarkan angka terbesar yang ada
     * di kolom chemical_code saat ini (termasuk yang soft-deleted,
     * supaya kode yang pernah dipakai tidak diulang).
     *
     * Dipakai untuk PREVIEW (modal create) maupun sebagai titik awal
     * insert — keduanya lewat method yang sama supaya logikanya
     * konsisten di satu tempat.
     */
    public function peekNextCode(): string
    {
        $next = $this->getMaxCodeNumber() + 1;
        return self::CODE_PREFIX . str_pad((string) $next, self::CODE_PAD, '0', STR_PAD_LEFT);
    }

    /**
     * Alias — dipertahankan supaya kode lama yang masih memanggil
     * generateNextCode() tetap jalan tanpa perlu diubah satu-satu.
     */
    public function generateNextCode(): string
    {
        return $this->peekNextCode();
    }

    /**
     * Ambil nomor tertinggi dari kolom chemical_code yang sudah ada,
     * dengan mengekstrak bagian integer di UJUNG kode (mis. "CH-00042" → 42).
     * Query langsung ke tabel (bukan lewat builder Model) supaya ikut
     * menghitung baris yang sudah soft-deleted sekalipun.
     */
    private function getMaxCodeNumber(): int
    {
        $row = $this->db->table('chemicals')
            // Ambil digit di ujung string, berapa pun panjang prefiksnya —
            // lebih tahan banting dibanding SUBSTRING dengan posisi tetap
            // kalau suatu saat ada kode legacy dengan format sedikit beda.
            ->selectMax(
                "CAST(REGEXP_SUBSTR(chemical_code, '[0-9]+$') AS UNSIGNED)",
                'max_num'
            )
            ->like('chemical_code', self::CODE_PREFIX, 'after')
            ->get()->getRowArray();

        return (int) ($row['max_num'] ?? 0);
    }

    // ════════════════════════════════════════════════════════════
    //  DUPLICATE CHECKS
    // ════════════════════════════════════════════════════════════

    private function isDuplicateName(string $name, ?int $excludeId = null): bool
    {
        $b = $this->db->table('chemicals')
            ->where('LOWER(chemical_name)', strtolower(trim($name)))
            ->where('deleted_at', null);

        if ($excludeId) {
            $b->where('id !=', $excludeId);
        }

        return $b->countAllResults() > 0;
    }

    // ════════════════════════════════════════════════════════════
    //  CRUD — CHEMICAL
    // ════════════════════════════════════════════════════════════

    /**
     * Tambah bahan kimia baru.
     * Kode di-generate otomatis di sini, dengan retry kalau kebentur
     * duplicate key (race condition dua request hampir bersamaan) —
     * ini SATU-SATUNYA jaring pengaman sekarang karena tidak ada lagi
     * row lock dari tabel sequence, jadi WAJIB ada UNIQUE KEY di
     * chemicals.chemical_code di level database.
     *
     * Return: ['status', 'message', 'id', 'code']
     */
    public function createData(array $data, array $categoryIds = []): array
    {
        // Cek duplikat nama sebelum proses lebih jauh
        if ($this->isDuplicateName($data['chemical_name'] ?? '')) {
            return [
                'status' => 'error',
                'errors' => ['chemical_name' => 'Nama bahan kimia sudah digunakan.'],
            ];
        }

        // Validasi field
        if (!$this->validate($data)) {
            return ['status' => 'error', 'errors' => $this->validator->getErrors()];
        }

        $attempts = 0;

        while ($attempts < self::CODE_GENERATION_MAX_ATTEMPTS) {
            $attempts++;
            $data['chemical_code'] = $this->peekNextCode();

            $this->db->transStart();

            try {
                if (!$this->insert($data)) {
                    $this->db->transRollback();
                    return [
                        'status'  => 'error',
                        'message' => 'Gagal menyimpan data.',
                        'errors'  => $this->errors(),
                    ];
                }

                $id = $this->getInsertID();
                $this->syncCategories($id, $categoryIds);
                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \RuntimeException('Transaksi gagal saat menyimpan bahan kimia.');
                }

                return [
                    'status'  => 'success',
                    'message' => 'Bahan kimia berhasil ditambahkan.',
                    'id'      => $id,
                    'code'    => $data['chemical_code'],
                ];
            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
                $this->db->transRollback();

                $isDuplicateCode = stripos($e->getMessage(), 'uniq_chemical_code') !== false
                    || stripos($e->getMessage(), 'Duplicate entry') !== false;

                if ($isDuplicateCode && $attempts < self::CODE_GENERATION_MAX_ATTEMPTS) {
                    // Kode kebentur punya request lain — coba lagi dengan angka berikutnya
                    continue;
                }

                log_message('error', 'ChemicalModel::createData — ' . $e->getMessage());
                return ['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()];
            } catch (\Throwable $e) {
                $this->db->transRollback();
                log_message('error', 'ChemicalModel::createData — ' . $e->getMessage());
                return ['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()];
            }
        }

        return ['status' => 'error', 'message' => 'Gagal menghasilkan kode unik, silakan coba lagi.'];
    }

    /**
     * Perbarui bahan kimia.
     * Kode bersifat permanen — tidak bisa diubah setelah create.
     * Return: ['status', 'message']
     */
    public function updateData(int $id, array $data, array $categoryIds = []): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan.'];
        }

        // Cek duplikat nama (exclude diri sendiri)
        if ($this->isDuplicateName($data['chemical_name'] ?? '', $id)) {
            return [
                'status' => 'error',
                'errors' => ['chemical_name' => 'Nama bahan kimia sudah digunakan.'],
            ];
        }

        // Validasi field
        if (!$this->validate($data)) {
            return ['status' => 'error', 'errors' => $this->validator->getErrors()];
        }

        // Kode tidak boleh diubah
        unset($data['chemical_code']);

        $this->db->transStart();

        try {
            if (!$this->update($id, $data)) {
                $this->db->transRollback();
                return [
                    'status'  => 'error',
                    'message' => 'Gagal memperbarui data.',
                    'errors'  => $this->errors(),
                ];
            }

            $this->syncCategories($id, $categoryIds);

            $this->db->transComplete();

            return ['status' => 'success', 'message' => 'Bahan kimia berhasil diperbarui.'];
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'ChemicalModel::updateData — ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal memperbarui data: ' . $e->getMessage()];
        }
    }

    /**
     * Ambil satu record lengkap beserta kategori dan varian.
     */
    public function getData(int $id): array
    {
        $data = $this->db->table('chemicals c')
            ->select('c.*')
            ->where('c.id', $id)
            ->where('c.deleted_at', null)
            ->get()->getRowArray();

        if (!$data) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan.'];
        }

        $data['categories'] = $this->getCategories($id);
        $data['variants']   = $this->getVariants($id);

        return ['status' => 'success', 'data' => $data];
    }

    /**
     * Soft delete — pindahkan ke sampah.
     */
    public function deleteData(int $id, int $userId): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan.'];
        }

        $this->db->transStart();

        try {
            $this->update($id, [
                'status'     => self::STATUS_ARCHIVED,
                'deleted_by' => $userId,
            ]);
            $this->delete($id);

            $this->db->transComplete();

            return ['status' => 'success', 'message' => 'Bahan kimia dipindahkan ke sampah.'];
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'ChemicalModel::deleteData — ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()];
        }
    }

    /**
     * Restore dari sampah.
     */
    public function restoreData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah.'];
        }

        $this->db->transStart();

        try {
            $this->db->table($this->table)
                ->where('id', $id)
                ->update([
                    'deleted_at' => null,
                    'deleted_by' => null,
                    'status'     => self::STATUS_DRAFT,
                ]);

            $this->db->transComplete();

            return ['status' => 'success', 'message' => 'Bahan kimia berhasil dipulihkan.'];
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'ChemicalModel::restoreData — ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal memulihkan data: ' . $e->getMessage()];
        }
    }

    /**
     * Force delete — hapus permanen beserta relasi.
     */
    public function forceDeleteData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah.'];
        }

        $this->db->transStart();

        try {
            // Hapus relasi dulu sebelum record utama
            $this->db->table('chemical_variants')->where('chemical_id', $id)->delete();
            $this->db->table('chemical_category_map')->where('chemical_id', $id)->delete();

            if (!$this->delete($id, true)) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Gagal menghapus permanen.'];
            }

            $this->db->transComplete();

            return ['status' => 'success', 'message' => 'Bahan kimia berhasil dihapus permanen.'];
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'ChemicalModel::forceDeleteData — ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal menghapus permanen: ' . $e->getMessage()];
        }
    }

    // ════════════════════════════════════════════════════════════
    //  CATEGORIES  (many-to-many via chemical_category_map)
    // ════════════════════════════════════════════════════════════

    public function getCategories(int $chemicalId): array
    {
        return $this->db->table('chemical_category_map m')
            ->select('cc.id, cc.category_name, cc.category_code')
            ->join('chemical_categories cc', 'cc.id = m.category_id')
            ->where('m.chemical_id', $chemicalId)
            ->orderBy('cc.category_name', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Sync kategori: hapus semua lalu insert ulang.
     * Aman dipakai di dalam transaksi yang sudah berjalan.
     */
    public function syncCategories(int $chemicalId, array $categoryIds): void
    {
        $categoryIds = array_values(
            array_unique(
                array_filter(array_map('intval', $categoryIds))
            )
        );

        $this->db->table('chemical_category_map')
            ->where('chemical_id', $chemicalId)
            ->delete();

        if (empty($categoryIds)) {
            return;
        }

        $rows = array_map(fn($catId) => [
            'chemical_id' => $chemicalId,
            'category_id' => $catId,
            'created_at'  => date('Y-m-d H:i:s'),
        ], $categoryIds);

        $this->db->table('chemical_category_map')->insertBatch($rows);
    }

    // ════════════════════════════════════════════════════════════
    //  VARIANTS  (read-only helpers — write via ChemicalVariantModel)
    // ════════════════════════════════════════════════════════════

    public function getVariants(int $chemicalId): array
    {
        return $this->db->table('chemical_variants')
            ->where('chemical_id', $chemicalId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('variant_name', 'ASC')
            ->get()->getResultArray();
    }

    // ════════════════════════════════════════════════════════════
    //  STATS
    // ════════════════════════════════════════════════════════════

    public function getStats(): array
    {
        $rows = $this->db->table('chemicals')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get()->getResultArray();

        $stats = [
            'total'    => 0,
            'active'   => 0,
            'draft'    => 0,
            'archived' => 0,
            'trash'    => 0,
            'variants' => 0,
        ];

        foreach ($rows as $row) {
            $stats['total'] += (int) $row['count'];
            $key = strtolower($row['status']);
            if (array_key_exists($key, $stats)) {
                $stats[$key] = (int) $row['count'];
            }
        }

        $stats['trash'] = $this->db->table('chemicals')
            ->where('deleted_at IS NOT NULL')
            ->countAllResults();

        $stats['variants'] = $this->db->table('chemical_variants')
            ->countAllResults();

        return $stats;
    }

    // ════════════════════════════════════════════════════════════
    //  SELECT2 HELPER
    // ════════════════════════════════════════════════════════════

    public function getForSelect2(string $search = '', int $limit = 50): array
    {
        $b = $this->db->table('chemicals c')
            ->select('c.id, c.chemical_code AS code, c.chemical_name AS name')
            ->where('c.status', self::STATUS_ACTIVE)
            ->where('c.deleted_at', null)
            ->orderBy('c.chemical_name', 'ASC');

        if ($search !== '') {
            $b->groupStart()
                ->like('c.chemical_name', $search)
                ->orLike('c.chemical_code', $search)
                ->groupEnd();
        }

        return $b->limit($limit)->get()->getResultArray();
    }
}
