<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

class WarehouseModel extends Model
{
    protected $table            = 'warehouses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'warehouse_code',
        'warehouse_name',
        'department_id',
        'location',
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
        $q = $this->db->table('warehouses')
            ->where('LOWER(warehouse_code)', strtolower(trim($code)))
            ->where('deleted_at', null);
        if ($excludeId) $q->where('id !=', $excludeId);
        return $q->countAllResults() > 0;
    }

    private function isDuplicateName(string $name, ?int $excludeId = null): bool
    {
        $q = $this->db->table('warehouses')
            ->where('LOWER(warehouse_name)', strtolower(trim($name)))
            ->where('deleted_at', null);
        if ($excludeId) $q->where('id !=', $excludeId);
        return $q->countAllResults() > 0;
    }

    // ============================================================
    // CRUD
    // ============================================================

    public function createData(array $data): array
    {
        if ($this->isDuplicateCode($data['warehouse_code'] ?? '')) {
            return ['status' => 'error', 'errors' => ['warehouse_code' => 'Kode gudang sudah digunakan']];
        }
        if ($this->isDuplicateName($data['warehouse_name'] ?? '')) {
            return ['status' => 'error', 'errors' => ['warehouse_name' => 'Nama gudang sudah digunakan']];
        }
        if (!$this->insert($data)) {
            return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
        }
        return ['status' => 'success', 'message' => 'Gudang berhasil ditambahkan', 'id' => $this->getInsertID()];
    }

    public function updateData(int $id, array $data): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }
        if ($this->isDuplicateCode($data['warehouse_code'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['warehouse_code' => 'Kode gudang sudah digunakan']];
        }
        if ($this->isDuplicateName($data['warehouse_name'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['warehouse_name' => 'Nama gudang sudah digunakan']];
        }
        if (!$this->update($id, $data)) {
            return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
        }
        return ['status' => 'success', 'message' => 'Gudang berhasil diperbarui'];
    }

    public function getData(int $id): array
    {
        $data = $this->db->table('warehouses w')
            ->select('w.*, d.department_name')
            ->join('departments d', 'd.id = w.department_id', 'left')
            ->where('w.id', $id)
            ->where('w.deleted_at', null)
            ->get()->getRowArray();

        if (!$data) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        return ['status' => 'success', 'data' => $data];
    }

    public function deleteData(int $id, int $userId): array
    {
        if (!$this->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        $this->update($id, ['status' => 'Archived', 'deleted_by' => $userId]);
        $this->delete($id);
        return ['status' => 'success', 'message' => 'Gudang dipindahkan ke sampah'];
    }

    public function restoreData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        $this->db->table($this->table)->where('id', $id)->update(['deleted_at' => null, 'deleted_by' => null, 'status' => 'Draft']);
        return ['status' => 'success', 'message' => 'Gudang berhasil dipulihkan'];
    }

    public function forceDeleteData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];

        $used = $this->db->table('chemical_stock_openings')->where('warehouse_id', $id)->countAllResults();
        if ($used > 0) {
            return ['status' => 'error', 'message' => "Gudang tidak dapat dihapus permanen karena sudah memiliki {$used} data stok awal"];
        }

        if (!$this->delete($id, true)) return ['status' => 'error', 'message' => 'Gagal menghapus permanen'];
        return ['status' => 'success', 'message' => 'Gudang berhasil dihapus permanen'];
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function getStats(): array
    {
        $rows = $this->db->table('warehouses')
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
        $stats['trash'] = $this->db->table('warehouses')->where('deleted_at IS NOT NULL')->countAllResults();
        return $stats;
    }
}
