<?php

namespace App\Modules\Production\Models;

use CodeIgniter\Model;

class FabricModel extends Model
{
    protected $table            = 'fabrics';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'fabric_code',
        'fabric_name',
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
    // DUPLICATE CHECKS — global (sama seperti Department)
    // ============================================================

    private function isDuplicateCode(string $code, ?int $excludeId = null): bool
    {
        $q = $this->db->table('fabrics')
            ->where('LOWER(fabric_code)', strtolower(trim($code)))
            ->where('deleted_at', null);

        if ($excludeId) {
            $q->where('id !=', $excludeId);
        }

        return $q->countAllResults() > 0;
    }

    private function isDuplicateName(string $name, ?int $excludeId = null): bool
    {
        $q = $this->db->table('fabrics')
            ->where('LOWER(fabric_name)', strtolower(trim($name)))
            ->where('deleted_at', null);

        if ($excludeId) {
            $q->where('id !=', $excludeId);
        }

        return $q->countAllResults() > 0;
    }

    // ============================================================
    // CRUD
    // ============================================================

    public function createData(array $data): array
    {
        if ($this->isDuplicateCode($data['fabric_code'] ?? '')) {
            return ['status' => 'error', 'errors' => ['fabric_code' => 'Kode fabric sudah digunakan']];
        }

        if ($this->isDuplicateName($data['fabric_name'] ?? '')) {
            return ['status' => 'error', 'errors' => ['fabric_name' => 'Nama fabric sudah digunakan']];
        }

        if (!$this->insert($data)) {
            return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
        }

        return ['status' => 'success', 'message' => 'Fabric berhasil ditambahkan', 'id' => $this->getInsertID()];
    }

    public function updateData(int $id, array $data): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        if ($this->isDuplicateCode($data['fabric_code'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['fabric_code' => 'Kode fabric sudah digunakan']];
        }

        if ($this->isDuplicateName($data['fabric_name'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['fabric_name' => 'Nama fabric sudah digunakan']];
        }

        if (!$this->update($id, $data)) {
            return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
        }

        return ['status' => 'success', 'message' => 'Fabric berhasil diperbarui'];
    }

    public function getData(int $id): array
    {
        $data = $this->find($id);

        if (!$data) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        return ['status' => 'success', 'data' => $data];
    }

    public function deleteData(int $id, int $userId): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        $this->update($id, [
            'status'     => 'Archived',
            'deleted_by' => $userId,
        ]);

        $this->delete($id);

        return ['status' => 'success', 'message' => 'Fabric dipindahkan ke sampah'];
    }

    // ============================================================
    // TRASH
    // ============================================================

    public function restoreData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        }

        $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->update([
                'deleted_at' => null,
                'deleted_by' => null,
                'status'     => 'Draft',
            ]);

        return ['status' => 'success', 'message' => 'Fabric berhasil dipulihkan'];
    }

    public function forceDeleteData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        }

        if (!$this->delete($id, true)) {
            return ['status' => 'error', 'message' => 'Gagal menghapus permanen'];
        }

        return ['status' => 'success', 'message' => 'Fabric berhasil dihapus permanen'];
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function getStats(): array
    {
        $rows = $this->db->table('fabrics')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $stats = ['total' => 0, 'active' => 0, 'draft' => 0, 'archived' => 0];

        foreach ($rows as $row) {
            $stats['total'] += (int) $row['count'];
            $key = strtolower($row['status']);
            if (isset($stats[$key])) {
                $stats[$key] = (int) $row['count'];
            }
        }

        $stats['trash'] = $this->db->table('fabrics')
            ->where('deleted_at IS NOT NULL')
            ->countAllResults();

        return $stats;
    }

    public function getAllActive(): array
    {
        return $this->where('status', 'Active')
            ->orderBy('fabric_name', 'ASC')
            ->findAll();
    }

    /**
     * Cek apakah fabric sedang dipakai di flow_processes
     * (untuk validasi sebelum delete)
     */
    public function isUsedByFlowProcesses(int $id): int
    {
        return $this->db->table('flow_processes')
            ->where('fabric_id', $id)
            ->where('deleted_at', null)
            ->countAllResults();
    }
}
