<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

class ChemicalVariantModel extends Model
{
    protected $table            = 'chemical_variants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'chemical_id',
        'variant_name',
        'packaging',
        'packaging_size',
        'unit',
        'price',
        'is_default',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ============================================================
    // VALIDATION RULES
    // ============================================================

    protected $validationRules = [
        'variant_name'   => 'required|max_length[100]',
        'packaging'      => 'permit_empty|max_length[50]',
        'packaging_size' => 'permit_empty|decimal',
        'unit'           => 'permit_empty|max_length[20]',
        'price'          => 'permit_empty|decimal|greater_than_equal_to[0]',
        'is_default'     => 'permit_empty|in_list[0,1]',
        'status'         => 'required|in_list[Active,Archived]',
    ];

    protected $validationMessages = [
        'variant_name' => [
            'required' => 'Nama varian wajib diisi',
            'max_length' => 'Nama varian maksimal 100 karakter',
        ],
        'price' => [
            'decimal' => 'Harga harus berupa angka',
            'greater_than_equal_to' => 'Harga tidak boleh negatif',
        ],
        'status' => [
            'in_list' => 'Status tidak valid',
        ],
    ];

    // ============================================================
    // LIST VARIAN BY CHEMICAL
    // ============================================================

    public function listByChemical(int $chemicalId): array
    {
        return $this->db->table('chemical_variants cv')
            ->select('cv.*, cu.username as created_by_name, uu.username as updated_by_name')
            ->join('users cu', 'cu.id = cv.created_by', 'left')
            ->join('users uu', 'uu.id = cv.updated_by', 'left')
            ->where('cv.chemical_id', $chemicalId)
            ->orderBy('cv.is_default', 'DESC')
            ->orderBy('cv.variant_name', 'ASC')
            ->get()->getResultArray();
    }

    // ============================================================
    // CREATE VARIANT
    // ============================================================

    public function createVariant(int $chemicalId, array $data, int $userId): array
    {
        // Validasi
        if (empty(trim($data['variant_name'] ?? ''))) {
            return ['status' => 'error', 'errors' => ['variant_name' => 'Nama varian wajib diisi']];
        }

        // Validasi harga
        if (isset($data['price']) && $data['price'] !== '' && $data['price'] < 0) {
            return ['status' => 'error', 'errors' => ['price' => 'Harga tidak boleh negatif']];
        }

        // Cek duplikat nama
        if ($this->isDuplicateVariantName($chemicalId, $data['variant_name'])) {
            return ['status' => 'error', 'errors' => ['variant_name' => 'Nama varian sudah digunakan untuk bahan kimia ini']];
        }

        $isDefault = (int) ($data['is_default'] ?? 0);

        // Validasi: default tidak bisa di-archive
        if ($isDefault && ($data['status'] ?? 'Active') === 'Archived') {
            return ['status' => 'error', 'message' => 'Varian default tidak bisa di-archive'];
        }

        $row = [
            'chemical_id'    => $chemicalId,
            'variant_name'   => trim($data['variant_name']),
            'packaging'      => trim($data['packaging'] ?? '') ?: null,
            'packaging_size' => is_numeric($data['packaging_size'] ?? '') ? (float) $data['packaging_size'] : null,
            'unit'           => trim($data['unit'] ?? '') ?: null,
            'price'          => is_numeric($data['price'] ?? '') ? (float) $data['price'] : null,
            'is_default'     => $isDefault,
            'status'         => $data['status'] ?? 'Active',
            'created_by'     => $userId,
            'updated_by'     => $userId,
        ];

        // Jika hanya ini varian pertama, paksa jadi default
        $existingCount = $this->where('chemical_id', $chemicalId)->countAllResults();
        if ($existingCount === 0) {
            $row['is_default'] = 1;
        }

        // Mulai transaksi
        $this->db->transStart();

        try {
            if ($row['is_default']) {
                $this->where('chemical_id', $chemicalId)->set(['is_default' => 0])->update();
            }

            if (!$this->insert($row)) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Gagal menambahkan varian', 'errors' => $this->errors()];
            }

            $this->db->transComplete();
            return ['status' => 'success', 'message' => 'Varian berhasil ditambahkan', 'id' => $this->getInsertID()];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'createVariant: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal menambahkan varian: ' . $e->getMessage()];
        }
    }

    // ============================================================
    // UPDATE VARIANT
    // ============================================================

    public function updateVariant(int $variantId, int $chemicalId, array $data, int $userId): array
    {
        $existing = $this->where('chemical_id', $chemicalId)->find($variantId);
        if (!$existing) {
            return ['status' => 'error', 'message' => 'Varian tidak ditemukan'];
        }

        // Validasi nama
        if (empty(trim($data['variant_name'] ?? ''))) {
            return ['status' => 'error', 'errors' => ['variant_name' => 'Nama varian wajib diisi']];
        }

        // Validasi harga
        if (isset($data['price']) && $data['price'] !== '' && $data['price'] < 0) {
            return ['status' => 'error', 'errors' => ['price' => 'Harga tidak boleh negatif']];
        }

        // Cek duplikat nama
        if ($this->isDuplicateVariantName($chemicalId, $data['variant_name'], $variantId)) {
            return ['status' => 'error', 'errors' => ['variant_name' => 'Nama varian sudah digunakan untuk bahan kimia ini']];
        }

        $isDefault = (int) ($data['is_default'] ?? 0);
        $newStatus = $data['status'] ?? $existing['status'];

        // Validasi: default tidak bisa di-archive
        if ($isDefault && $newStatus === 'Archived') {
            return ['status' => 'error', 'message' => 'Varian default tidak bisa di-archive'];
        }

        // Jika existing adalah default dan ingin di-archive
        if ((int) $existing['is_default'] === 1 && $newStatus === 'Archived') {
            return ['status' => 'error', 'message' => 'Varian default tidak bisa di-archive. Set varian lain sebagai default terlebih dahulu.'];
        }

        $row = [
            'variant_name'   => trim($data['variant_name']),
            'packaging'      => trim($data['packaging'] ?? '') ?: null,
            'packaging_size' => is_numeric($data['packaging_size'] ?? '') ? (float) $data['packaging_size'] : null,
            'unit'           => trim($data['unit'] ?? '') ?: null,
            'price'          => is_numeric($data['price'] ?? '') ? (float) $data['price'] : null,
            'is_default'     => $isDefault,
            'status'         => $newStatus,
            'updated_by'     => $userId,
        ];

        // Mulai transaksi
        $this->db->transStart();

        try {
            if ($row['is_default']) {
                $this->where('chemical_id', $chemicalId)->set(['is_default' => 0])->update();
            }

            if (!$this->update($variantId, $row)) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui varian', 'errors' => $this->errors()];
            }

            $this->db->transComplete();
            return ['status' => 'success', 'message' => 'Varian berhasil diperbarui'];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'updateVariant: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal memperbarui varian: ' . $e->getMessage()];
        }
    }

    // ============================================================
    // DELETE VARIANT (by ID) - FIXED: Added missing method
    // ============================================================

    public function deleteVariantById(int $variantId): array
    {
        $variant = $this->find($variantId);
        if (!$variant) {
            return ['status' => 'error', 'message' => 'Varian tidak ditemukan'];
        }

        return $this->deleteVariant($variantId, $variant['chemical_id']);
    }

    // ============================================================
    // DELETE VARIANT
    // ============================================================

    public function deleteVariant(int $variantId, int $chemicalId): array
    {
        $existing = $this->where('chemical_id', $chemicalId)->find($variantId);
        if (!$existing) {
            return ['status' => 'error', 'message' => 'Varian tidak ditemukan'];
        }

        $wasDefault = (int) $existing['is_default'] === 1;

        // Mulai transaksi
        $this->db->transStart();

        try {
            $this->delete($variantId);

            // Jika varian default dihapus, jadikan varian lain (jika ada) sebagai default
            if ($wasDefault) {
                $next = $this->where('chemical_id', $chemicalId)
                    ->where('status', 'Active')
                    ->orderBy('id', 'ASC')
                    ->first();
                if ($next) {
                    $this->update($next['id'], ['is_default' => 1]);
                }
            }

            $this->db->transComplete();
            return ['status' => 'success', 'message' => 'Varian berhasil dihapus'];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'deleteVariant: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal menghapus varian: ' . $e->getMessage()];
        }
    }

    // ============================================================
    // SET DEFAULT VARIANT
    // ============================================================

    public function setDefault(int $variantId, int $chemicalId): array
    {
        $existing = $this->where('chemical_id', $chemicalId)->find($variantId);
        if (!$existing) {
            return ['status' => 'error', 'message' => 'Varian tidak ditemukan'];
        }

        // Cek apakah varian active
        if ($existing['status'] !== 'Active') {
            return ['status' => 'error', 'message' => 'Hanya varian Active yang bisa dijadikan default'];
        }

        $this->db->transStart();

        try {
            $this->where('chemical_id', $chemicalId)->set(['is_default' => 0])->update();
            $this->update($variantId, ['is_default' => 1]);

            $this->db->transComplete();
            return ['status' => 'success', 'message' => 'Varian default berhasil diatur'];
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'setDefault: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Gagal mengatur varian default: ' . $e->getMessage()];
        }
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function isDuplicateVariantName(int $chemicalId, string $name, ?int $excludeId = null): bool
    {
        $q = $this->where('chemical_id', $chemicalId)
            ->where('LOWER(variant_name)', strtolower(trim($name)));
        if ($excludeId) {
            $q->where('id !=', $excludeId);
        }
        return $q->countAllResults() > 0;
    }
}
