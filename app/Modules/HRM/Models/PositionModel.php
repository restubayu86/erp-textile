<?php

namespace App\Modules\HRM\Models;

use CodeIgniter\Model;

class PositionModel extends Model
{
    protected $table            = 'positions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'position_code',
        'position_name',
        'position_level',
        'department_id',
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
        $q = $this->db->table('positions')
            ->where('LOWER(position_code)', strtolower(trim($code)))
            ->where('deleted_at', null);

        if ($excludeId) {
            $q->where('id !=', $excludeId);
        }

        return $q->countAllResults() > 0;
    }

    private function isDuplicateName(string $name, ?int $excludeId = null): bool
    {
        $q = $this->db->table('positions')
            ->where('LOWER(position_name)', strtolower(trim($name)))
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
        if ($this->isDuplicateCode($data['position_code'] ?? '')) {
            return ['status' => 'error', 'errors' => ['position_code' => 'Kode posisi sudah digunakan']];
        }

        if ($this->isDuplicateName($data['position_name'] ?? '')) {
            return ['status' => 'error', 'errors' => ['position_name' => 'Nama posisi sudah digunakan']];
        }

        if (!$this->insert($data)) {
            return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
        }

        return ['status' => 'success', 'message' => 'Posisi berhasil ditambahkan', 'id' => $this->getInsertID()];
    }

    public function updateData(int $id, array $data): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        if ($this->isDuplicateCode($data['position_code'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['position_code' => 'Kode posisi sudah digunakan']];
        }

        if ($this->isDuplicateName($data['position_name'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['position_name' => 'Nama posisi sudah digunakan']];
        }

        if (!$this->update($id, $data)) {
            return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
        }

        return ['status' => 'success', 'message' => 'Posisi berhasil diperbarui'];
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

        return ['status' => 'success', 'message' => 'Posisi dipindahkan ke sampah'];
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

        return ['status' => 'success', 'message' => 'Posisi berhasil dipulihkan'];
    }

    public function forceDeleteData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        }

        if (!$this->delete($id, true)) {
            return ['status' => 'error', 'message' => 'Gagal menghapus permanen'];
        }

        return ['status' => 'success', 'message' => 'Posisi berhasil dihapus permanen'];
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function getStats(): array
    {
        $rows = $this->db->table('positions')
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

        $stats['trash'] = $this->db->table('positions')
            ->where('deleted_at IS NOT NULL')
            ->countAllResults();

        return $stats;
    }

    public function getAllActive(): array
    {
        return $this->select('positions.*, departments.department')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->where('positions.status', 'Active')
            ->orderBy('positions.position_name', 'ASC')
            ->findAll();
    }

    public function getByDepartment(int $departmentId): array
    {
        return $this->where('department_id', $departmentId)
            ->where('status', 'Active')
            ->where('deleted_at', null)
            ->orderBy('position_name', 'ASC')
            ->findAll();
    }
}
