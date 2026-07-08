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
        'category_id',
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
    // DUPLICATE CHECKS
    // ============================================================

    private function isDuplicateCode(string $code, ?int $excludeId = null): bool
    {
        $q = $this->db->table('chemicals')
            ->where('LOWER(chemical_code)', strtolower(trim($code)))
            ->where('deleted_at', null);
        if ($excludeId) $q->where('id !=', $excludeId);
        return $q->countAllResults() > 0;
    }

    private function isDuplicateName(string $name, ?int $excludeId = null): bool
    {
        $q = $this->db->table('chemicals')
            ->where('LOWER(chemical_name)', strtolower(trim($name)))
            ->where('deleted_at', null);
        if ($excludeId) $q->where('id !=', $excludeId);
        return $q->countAllResults() > 0;
    }

    // ============================================================
    // CRUD (main chemical)
    // ============================================================

    public function createData(array $data): array
    {
        if ($this->isDuplicateCode($data['chemical_code'] ?? '')) {
            return ['status' => 'error', 'errors' => ['chemical_code' => 'Kode bahan kimia sudah digunakan']];
        }
        if ($this->isDuplicateName($data['chemical_name'] ?? '')) {
            return ['status' => 'error', 'errors' => ['chemical_name' => 'Nama bahan kimia sudah digunakan']];
        }
        if (!$this->insert($data)) {
            return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
        }
        return ['status' => 'success', 'message' => 'Bahan kimia berhasil ditambahkan', 'id' => $this->getInsertID()];
    }

    public function updateData(int $id, array $data): array
    {
        if (!$this->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        if ($this->isDuplicateCode($data['chemical_code'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['chemical_code' => 'Kode bahan kimia sudah digunakan']];
        }
        if ($this->isDuplicateName($data['chemical_name'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['chemical_name' => 'Nama bahan kimia sudah digunakan']];
        }
        if (!$this->update($id, $data)) {
            return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
        }
        return ['status' => 'success', 'message' => 'Bahan kimia berhasil diperbarui'];
    }

    public function getData(int $id): array
    {
        $data = $this->db->table('chemicals c')
            ->select('c.*, cc.category_name, cc.category_code')
            ->join('chemical_categories cc', 'cc.id = c.category_id', 'left')
            ->where('c.id', $id)
            ->where('c.deleted_at', null)
            ->get()->getRowArray();

        if (!$data) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];

        // Sertakan varian
        $data['variants'] = $this->getVariants($id);

        return ['status' => 'success', 'data' => $data];
    }

    public function deleteData(int $id, int $userId): array
    {
        if (!$this->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        $this->update($id, ['status' => 'Archived', 'deleted_by' => $userId]);
        $this->delete($id);
        return ['status' => 'success', 'message' => 'Bahan kimia dipindahkan ke sampah'];
    }

    public function restoreData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        $this->db->table($this->table)->where('id', $id)->update(['deleted_at' => null, 'deleted_by' => null, 'status' => 'Draft']);
        return ['status' => 'success', 'message' => 'Bahan kimia berhasil dipulihkan'];
    }

    public function forceDeleteData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];

        // Hapus varian dulu
        $this->db->table('chemical_variants')->where('chemical_id', $id)->delete();

        if (!$this->delete($id, true)) return ['status' => 'error', 'message' => 'Gagal menghapus permanen'];
        return ['status' => 'success', 'message' => 'Bahan kimia berhasil dihapus permanen'];
    }

    // ============================================================
    // VARIANTS CRUD
    // ============================================================

    public function getVariants(int $chemicalId): array
    {
        return $this->db->table('chemical_variants')
            ->where('chemical_id', $chemicalId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('variant_name', 'ASC')
            ->get()->getResultArray();
    }

    public function saveVariants(int $chemicalId, array $variants, int $userId): void
    {
        // Hapus semua varian lama, replace dengan yang baru
        $this->db->table('chemical_variants')->where('chemical_id', $chemicalId)->delete();

        if (empty($variants)) return;

        $rows       = [];
        $hasDefault = false;

        foreach ($variants as $i => $v) {
            $isDefault = !$hasDefault && (!empty($v['is_default']) || $i === 0);
            if ($isDefault) $hasDefault = true;

            $rows[] = [
                'chemical_id'    => $chemicalId,
                'variant_name'   => trim($v['variant_name'] ?? ''),
                'packaging'      => trim($v['packaging'] ?? '') ?: null,
                'packaging_size' => is_numeric($v['packaging_size'] ?? '') ? $v['packaging_size'] : null,
                'unit'           => trim($v['unit'] ?? '') ?: null,
                'price'          => is_numeric($v['price'] ?? '') ? $v['price'] : null,
                'is_default'     => $isDefault ? 1 : 0,
                'status'         => $v['status'] ?? 'Active',
                'created_by'     => $userId,
                'updated_by'     => $userId,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($rows)) {
            $this->db->table('chemical_variants')->insertBatch($rows);
        }
    }

    public function deleteVariant(int $variantId): array
    {
        $variant = $this->db->table('chemical_variants')->where('id', $variantId)->get()->getRowArray();
        if (!$variant) return ['status' => 'error', 'message' => 'Varian tidak ditemukan'];

        $this->db->table('chemical_variants')->where('id', $variantId)->delete();
        return ['status' => 'success', 'message' => 'Varian berhasil dihapus'];
    }

    public function setDefaultVariant(int $chemicalId, int $variantId): array
    {
        // Reset semua is_default dulu
        $this->db->table('chemical_variants')
            ->where('chemical_id', $chemicalId)
            ->update(['is_default' => 0]);

        $this->db->table('chemical_variants')
            ->where('id', $variantId)
            ->where('chemical_id', $chemicalId)
            ->update(['is_default' => 1]);

        return ['status' => 'success', 'message' => 'Varian default berhasil diatur'];
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function getStats(): array
    {
        $rows = $this->db->table('chemicals')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get()->getResultArray();

        $stats = ['total' => 0, 'active' => 0, 'draft' => 0, 'archived' => 0];
        foreach ($rows as $row) {
            $stats['total'] += (int) $row['count'];
            $key = strtolower($row['status']);
            if (isset($stats[$key])) $stats[$key] = (int) $row['count'];
        }
        $stats['trash']    = $this->db->table('chemicals')->where('deleted_at IS NOT NULL')->countAllResults();
        $stats['variants'] = $this->db->table('chemical_variants')->countAllResults();
        return $stats;
    }
}
