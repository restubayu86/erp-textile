<?php

namespace App\Modules\Production\Models;

use CodeIgniter\Model;

class MachineModel extends Model
{
    protected $table            = 'machines';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'machine_code',
        'machine_name',
        'department_id',
        'capacity',
        'capacity_unit',
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
    // DUPLICATE CHECKS — scoped per department (sama seperti positions)
    // ============================================================

    private function isDuplicateCode(string $code, ?int $departmentId, ?int $excludeId = null): bool
    {
        $q = $this->db->table('machines')
            ->where('LOWER(machine_code)', strtolower(trim($code)))
            ->where('deleted_at', null);

        if ($departmentId === null) {
            $q->where('department_id', null);
        } else {
            $q->where('department_id', $departmentId);
        }

        if ($excludeId) {
            $q->where('id !=', $excludeId);
        }

        return $q->countAllResults() > 0;
    }

    private function isDuplicateName(string $name, ?int $departmentId, ?int $excludeId = null): bool
    {
        $q = $this->db->table('machines')
            ->where('LOWER(machine_name)', strtolower(trim($name)))
            ->where('deleted_at', null);

        if ($departmentId === null) {
            $q->where('department_id', null);
        } else {
            $q->where('department_id', $departmentId);
        }

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
        $departmentId = !empty($data['department_id']) ? (int) $data['department_id'] : null;

        if ($this->isDuplicateCode($data['machine_code'] ?? '', $departmentId)) {
            return ['status' => 'error', 'errors' => ['machine_code' => 'Kode mesin sudah digunakan di departemen ini']];
        }

        if ($this->isDuplicateName($data['machine_name'] ?? '', $departmentId)) {
            return ['status' => 'error', 'errors' => ['machine_name' => 'Nama mesin sudah digunakan di departemen ini']];
        }

        if (!$this->insert($data)) {
            return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
        }

        return ['status' => 'success', 'message' => 'Mesin berhasil ditambahkan', 'id' => $this->getInsertID()];
    }

    public function updateData(int $id, array $data): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        $departmentId = !empty($data['department_id']) ? (int) $data['department_id'] : null;

        if ($this->isDuplicateCode($data['machine_code'] ?? '', $departmentId, $id)) {
            return ['status' => 'error', 'errors' => ['machine_code' => 'Kode mesin sudah digunakan di departemen ini']];
        }

        if ($this->isDuplicateName($data['machine_name'] ?? '', $departmentId, $id)) {
            return ['status' => 'error', 'errors' => ['machine_name' => 'Nama mesin sudah digunakan di departemen ini']];
        }

        if (!$this->update($id, $data)) {
            return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
        }

        return ['status' => 'success', 'message' => 'Mesin berhasil diperbarui'];
    }

    public function getData(int $id): array
    {
        $data = $this->db->table($this->table . ' m')
            ->select('m.*, d.department as department_name')
            ->join('departments d', 'd.id = m.department_id', 'left')
            ->where('m.id', $id)
            ->where('m.deleted_at', null)
            ->get()
            ->getRowArray();

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

        return ['status' => 'success', 'message' => 'Mesin dipindahkan ke sampah'];
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

        return ['status' => 'success', 'message' => 'Mesin berhasil dipulihkan'];
    }

    public function forceDeleteData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        }

        if (!$this->delete($id, true)) {
            return ['status' => 'error', 'message' => 'Gagal menghapus permanen'];
        }

        return ['status' => 'success', 'message' => 'Mesin berhasil dihapus permanen'];
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function getStats(): array
    {
        $rows = $this->db->table('machines')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $stats = ['total' => 0, 'active' => 0, 'draft' => 0, 'maintenance' => 0, 'archived' => 0];

        foreach ($rows as $row) {
            $stats['total'] += (int) $row['count'];
            $key = strtolower($row['status']);
            if (isset($stats[$key])) {
                $stats[$key] = (int) $row['count'];
            }
        }

        $stats['trash'] = $this->db->table('machines')
            ->where('deleted_at IS NOT NULL')
            ->countAllResults();

        return $stats;
    }

    public function getAllActive(): array
    {
        return $this->where('status', 'Active')
            ->orderBy('machine_name', 'ASC')
            ->findAll();
    }

    /**
     * Cek apakah mesin sedang dipakai di work_orders atau checksheets
     * (untuk validasi sebelum delete — sesuaikan nama tabel jika berbeda)
     */
    public function isUsedByWorkOrders(int $id): int
    {
        if (!$this->db->tableExists('work_orders')) {
            return 0;
        }

        return $this->db->table('work_orders')
            ->where('machine_id', $id)
            ->where('deleted_at', null)
            ->countAllResults();
    }

    public function isUsedByChecksheets(int $id): int
    {
        if (!$this->db->tableExists('checksheets')) {
            return 0;
        }

        return $this->db->table('checksheets')
            ->where('machine_id', $id)
            ->where('deleted_at', null)
            ->countAllResults();
    }
}
