<?php

namespace App\Modules\HRM\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table            = 'employees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'nik',
        'fullname',
        'nickname',
        'gender',
        'phone',
        'photo',
        'position_id',
        'work_area',
        'shift',
        'employment_status',
        'join_date',
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

    private function isDuplicateNik(string $nik, ?int $excludeId = null): bool
    {
        $q = $this->db->table('employees')
            ->where('nik', strtoupper(trim($nik)))
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
        if ($this->isDuplicateNik($data['nik'] ?? '')) {
            return ['status' => 'error', 'errors' => ['nik' => 'NIK sudah terdaftar']];
        }

        if (!$this->insert($data)) {
            return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
        }

        return ['status' => 'success', 'message' => 'Karyawan berhasil ditambahkan', 'id' => $this->getInsertID()];
    }

    public function updateData(int $id, array $data): array
    {
        if (!$this->find($id)) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        if (isset($data['nik']) && $this->isDuplicateNik($data['nik'], $id)) {
            return ['status' => 'error', 'errors' => ['nik' => 'NIK sudah terdaftar']];
        }

        if (!$this->update($id, $data)) {
            return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
        }

        return ['status' => 'success', 'message' => 'Data karyawan berhasil diperbarui'];
    }

    public function getData(int $id): array
    {
        $data = $this->db->table('employees')
            ->select([
                'employees.*',
                'positions.position_name',
                'positions.position_code',
                'departments.id as department_id',
                'departments.department as department_name',
            ])
            ->join('positions', 'positions.id = employees.position_id', 'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->where('employees.id', $id)
            ->where('employees.deleted_at', null)
            ->get()->getRowArray();

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
            'status'     => 'inactive',
            'deleted_by' => $userId,
        ]);

        $this->delete($id);

        return ['status' => 'success', 'message' => 'Karyawan dipindahkan ke sampah'];
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
                'status'     => 'active',
            ]);

        return ['status' => 'success', 'message' => 'Karyawan berhasil dipulihkan'];
    }

    public function forceDeleteData(int $id): array
    {
        $row = $this->onlyDeleted()->find($id);

        if (!$row) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        }

        // Hapus foto fisik jika ada
        if (!empty($row['photo'])) {
            $photoPath = FCPATH . 'uploads/employees/' . $row['photo'];
            if (file_exists($photoPath)) {
                @unlink($photoPath);
            }
        }

        if (!$this->delete($id, true)) {
            return ['status' => 'error', 'message' => 'Gagal menghapus permanen'];
        }

        return ['status' => 'success', 'message' => 'Karyawan berhasil dihapus permanen'];
    }

    // ============================================================
    // HELPERS & LOOKUPS
    // ============================================================

    public function getStats(): array
    {
        $rows = $this->db->table('employees')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get()->getResultArray();

        $stats = ['total' => 0, 'active' => 0, 'inactive' => 0];

        foreach ($rows as $row) {
            $stats['total'] += (int) $row['count'];
            $key = strtolower($row['status']);
            if (isset($stats[$key])) {
                $stats[$key] = (int) $row['count'];
            }
        }

        $stats['trash'] = $this->db->table('employees')
            ->where('deleted_at IS NOT NULL')
            ->countAllResults();

        // Gender breakdown (aktif saja)
        $genderRows = $this->db->table('employees')
            ->select('gender, COUNT(*) as count')
            ->where('deleted_at', null)
            ->where('status', 'active')
            ->groupBy('gender')
            ->get()->getResultArray();

        $stats['male']   = 0;
        $stats['female'] = 0;
        foreach ($genderRows as $row) {
            if ($row['gender'] === 'L') $stats['male']   = (int) $row['count'];
            if ($row['gender'] === 'P') $stats['female'] = (int) $row['count'];
        }

        // Per shift
        $shiftRows = $this->db->table('employees')
            ->select('shift, COUNT(*) as count')
            ->where('deleted_at', null)
            ->where('status', 'active')
            ->groupBy('shift')
            ->get()->getResultArray();

        $stats['per_shift'] = [];
        foreach ($shiftRows as $row) {
            $stats['per_shift'][$row['shift']] = (int) $row['count'];
        }

        // Per employment_status
        $empRows = $this->db->table('employees')
            ->select('employment_status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->where('status', 'active')
            ->groupBy('employment_status')
            ->get()->getResultArray();

        $stats['per_employment'] = [];
        foreach ($empRows as $row) {
            $stats['per_employment'][$row['employment_status']] = (int) $row['count'];
        }

        return $stats;
    }

    public function getAllActive(): array
    {
        return $this->db->table('employees')
            ->select([
                'employees.id',
                'employees.nik',
                'employees.fullname',
                'employees.nickname',
                'employees.shift',
                'employees.work_area',
                'positions.position_name',
                'departments.department as department_name',
            ])
            ->join('positions', 'positions.id = employees.position_id', 'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->where('employees.status', 'active')
            ->where('employees.deleted_at', null)
            ->orderBy('employees.fullname', 'ASC')
            ->get()->getResultArray();
    }

    public function getByPosition(int $positionId): array
    {
        return $this->db->table('employees')
            ->select('id, nik, fullname, nickname, shift, work_area, status')
            ->where('position_id', $positionId)
            ->where('status', 'active')
            ->where('deleted_at', null)
            ->orderBy('fullname', 'ASC')
            ->get()->getResultArray();
    }

    public function getByDepartment(int $departmentId): array
    {
        return $this->db->table('employees')
            ->select([
                'employees.id',
                'employees.nik',
                'employees.fullname',
                'employees.nickname',
                'employees.shift',
                'employees.work_area',
                'positions.position_name',
            ])
            ->join('positions', 'positions.id = employees.position_id', 'left')
            ->where('positions.department_id', $departmentId)
            ->where('employees.status', 'active')
            ->where('employees.deleted_at', null)
            ->orderBy('employees.fullname', 'ASC')
            ->get()->getResultArray();
    }

    public function getByShift(string $shift): array
    {
        return $this->db->table('employees')
            ->select([
                'employees.id',
                'employees.nik',
                'employees.fullname',
                'employees.nickname',
                'employees.work_area',
                'positions.position_name',
                'departments.department as department_name',
            ])
            ->join('positions', 'positions.id = employees.position_id', 'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->where('employees.shift', $shift)
            ->where('employees.status', 'active')
            ->where('employees.deleted_at', null)
            ->orderBy('employees.fullname', 'ASC')
            ->get()->getResultArray();
    }

    public function getByWorkArea(string $workArea): array
    {
        return $this->db->table('employees')
            ->select([
                'employees.id',
                'employees.nik',
                'employees.fullname',
                'employees.nickname',
                'employees.shift',
                'positions.position_name',
            ])
            ->join('positions', 'positions.id = employees.position_id', 'left')
            ->where('employees.work_area', $workArea)
            ->where('employees.status', 'active')
            ->where('employees.deleted_at', null)
            ->orderBy('employees.fullname', 'ASC')
            ->get()->getResultArray();
    }

    public function getDistinctWorkAreas(): array
    {
        return $this->db->table('employees')
            ->select('work_area')
            ->where('deleted_at', null)
            ->where('work_area IS NOT NULL')
            ->groupBy('work_area')
            ->orderBy('work_area', 'ASC')
            ->get()->getResultArray();
    }
}
