<?php

namespace App\Modules\Production\Models;

use CodeIgniter\Model;

class FlowProcessModel extends Model
{
    protected $table            = 'flow_processes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'design_id',
        'flow_name',
        'segment',          // ← tambahan
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

    private function isDuplicateName(string $name, int $designId, ?int $excludeId = null): bool
    {
        $q = $this->db->table('flow_processes')
            ->where('LOWER(flow_name)', strtolower(trim($name)))
            ->where('design_id', $designId)
            ->where('deleted_at', null);

        if ($excludeId) {
            $q->where('id !=', $excludeId);
        }

        return $q->countAllResults() > 0;
    }

    // ============================================================
    // CRUD — header + steps (pakai transaction)
    // ============================================================

    public function createWithSteps(array $data, array $steps): array
    {
        $designId = (int) ($data['design_id'] ?? 0);

        if ($this->isDuplicateName($data['flow_name'] ?? '', $designId)) {
            return ['status' => 'error', 'errors' => ['flow_name' => 'Nama flow sudah digunakan di design ini']];
        }

        if (empty($steps)) {
            return ['status' => 'error', 'errors' => ['steps' => 'Minimal 1 step harus diisi']];
        }

        $this->db->transStart();

        if (!$this->insert($data)) {
            $this->db->transRollback();
            return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
        }

        $flowProcessId = $this->getInsertID();
        $this->insertSteps($flowProcessId, $steps);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['status' => 'error', 'message' => 'Gagal menyimpan flow process'];
        }

        return ['status' => 'success', 'message' => 'Flow process berhasil ditambahkan', 'id' => $flowProcessId];
    }

    public function updateWithSteps(int $id, array $data, array $steps): array
    {
        $existing = $this->find($id);
        if (!$existing) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        $designId = (int) ($data['design_id'] ?? $existing['design_id']);

        if ($this->isDuplicateName($data['flow_name'] ?? '', $designId, $id)) {
            return ['status' => 'error', 'errors' => ['flow_name' => 'Nama flow sudah digunakan di design ini']];
        }

        if (empty($steps)) {
            return ['status' => 'error', 'errors' => ['steps' => 'Minimal 1 step harus diisi']];
        }

        $this->db->transStart();

        if (!$this->update($id, $data)) {
            $this->db->transRollback();
            return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
        }

        $this->db->table('flow_process_steps')->where('flow_process_id', $id)->delete();
        $this->insertSteps($id, $steps);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['status' => 'error', 'message' => 'Gagal memperbarui flow process'];
        }

        return ['status' => 'success', 'message' => 'Flow process berhasil diperbarui'];
    }

    private function insertSteps(int $flowProcessId, array $steps): void
    {
        $rows = [];
        foreach ($steps as $step) {
            $stepType = ($step['step_type'] ?? 'process') === 'chemical' ? 'chemical' : 'process';

            $rows[] = [
                'flow_process_id' => $flowProcessId,
                'step_no'         => (int) $step['step_no'],
                'process_name'    => $stepType === 'process' ? trim((string) ($step['process_name'] ?? '')) : null,
                'step_type'       => $stepType,
                'chemical_code'   => $stepType === 'chemical' ? trim((string) ($step['chemical_code'] ?? '')) : null,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($rows)) {
            $this->db->table('flow_process_steps')->insertBatch($rows);
        }
    }

    public function getData(int $id): array
    {
        $header = $this->find($id);

        if (!$header) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        $steps = $this->db->table('flow_process_steps')
            ->where('flow_process_id', $id)
            ->orderBy('step_no', 'ASC')
            ->get()
            ->getResultArray();

        $header['steps'] = $steps;

        return ['status' => 'success', 'data' => $header];
    }

    public function deletePermanent(int $id): array
    {
        $existing = $this->find($id);
        if (!$existing) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        if (!$this->delete($id, true)) {
            return ['status' => 'error', 'message' => 'Gagal menghapus flow process'];
        }

        return ['status' => 'success', 'message' => 'Flow process berhasil dihapus'];
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function getDistinctProcessNames(): array
    {
        $rows = $this->db->table('flow_process_steps')
            ->select('DISTINCT process_name')
            ->where('step_type', 'process')
            ->where('process_name IS NOT NULL')
            ->orderBy('process_name', 'ASC')
            ->get()
            ->getResultArray();

        return array_column($rows, 'process_name');
    }

    public function getStepCount(int $flowProcessId): int
    {
        return $this->db->table('flow_process_steps')
            ->where('flow_process_id', $flowProcessId)
            ->countAllResults();
    }

    public function getDistinctChemicalCodes(): array
    {
        $rows = $this->db->table('flow_process_steps')
            ->select('DISTINCT chemical_code')
            ->where('step_type', 'chemical')
            ->where('chemical_code IS NOT NULL')
            ->orderBy('chemical_code', 'ASC')
            ->get()
            ->getResultArray();

        return array_column($rows, 'chemical_code');
    }
}
