<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

class FormulationModel extends Model
{
    protected $table            = 'formulations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'formulation_code',
        'formulation_name',
        'process_type',
        'output_quantity',
        'output_unit',
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
        $q = $this->db->table('formulations')
            ->where('LOWER(formulation_code)', strtolower(trim($code)))
            ->where('deleted_at', null);
        if ($excludeId) $q->where('id !=', $excludeId);
        return $q->countAllResults() > 0;
    }

    // ============================================================
    // CRUD
    // ============================================================

    public function createData(array $data, array $items): array
    {
        if ($this->isDuplicateCode($data['formulation_code'] ?? '')) {
            return ['status' => 'error', 'errors' => ['formulation_code' => 'Kode formulasi sudah digunakan']];
        }
        if (empty($items)) {
            return ['status' => 'error', 'errors' => ['items' => 'Minimal 1 bahan kimia harus diisi dalam resep']];
        }

        $this->db->transStart();

        if (!$this->insert($data)) {
            $this->db->transRollback();
            return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
        }

        $formulationId = $this->getInsertID();
        $this->saveItems($formulationId, $items);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['status' => 'error', 'message' => 'Gagal menyimpan formulasi'];
        }

        return ['status' => 'success', 'message' => 'Formulasi berhasil ditambahkan', 'id' => $formulationId];
    }

    public function updateData(int $id, array $data, array $items): array
    {
        if (!$this->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        if ($this->isDuplicateCode($data['formulation_code'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['formulation_code' => 'Kode formulasi sudah digunakan']];
        }
        if (empty($items)) {
            return ['status' => 'error', 'errors' => ['items' => 'Minimal 1 bahan kimia harus diisi dalam resep']];
        }

        $this->db->transStart();

        if (!$this->update($id, $data)) {
            $this->db->transRollback();
            return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
        }

        $this->saveItems($id, $items);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['status' => 'error', 'message' => 'Gagal memperbarui formulasi'];
        }

        return ['status' => 'success', 'message' => 'Formulasi berhasil diperbarui'];
    }

    public function getData(int $id): array
    {
        $data = $this->db->table('formulations')
            ->where('id', $id)
            ->where('deleted_at', null)
            ->get()->getRowArray();

        if (!$data) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];

        $data['items'] = $this->getItems($id);

        return ['status' => 'success', 'data' => $data];
    }

    public function deleteData(int $id, int $userId): array
    {
        if (!$this->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        $this->update($id, ['status' => 'Archived', 'deleted_by' => $userId]);
        $this->delete($id);
        return ['status' => 'success', 'message' => 'Formulasi dipindahkan ke sampah'];
    }

    public function restoreData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        $this->db->table($this->table)->where('id', $id)->update(['deleted_at' => null, 'deleted_by' => null, 'status' => 'Draft']);
        return ['status' => 'success', 'message' => 'Formulasi berhasil dipulihkan'];
    }

    public function forceDeleteData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];

        $used = $this->db->table('formulation_stock_openings')->where('formulation_id', $id)->countAllResults();
        if ($used > 0) {
            return ['status' => 'error', 'message' => "Formulasi tidak dapat dihapus permanen karena sudah memiliki {$used} data stok"];
        }

        $this->db->table('formulation_items')->where('formulation_id', $id)->delete();

        if (!$this->delete($id, true)) return ['status' => 'error', 'message' => 'Gagal menghapus permanen'];
        return ['status' => 'success', 'message' => 'Formulasi berhasil dihapus permanen'];
    }

    // ============================================================
    // ITEMS (resep kimia)
    // ============================================================

    public function getItems(int $formulationId): array
    {
        return $this->db->table('formulation_items fi')
            ->select('fi.*, c.chemical_code, c.chemical_name, cv.variant_name, cv.packaging')
            ->join('chemicals c', 'c.id = fi.chemical_id', 'left')
            ->join('chemical_variants cv', 'cv.id = fi.variant_id', 'left')
            ->where('fi.formulation_id', $formulationId)
            ->orderBy('fi.sort_order', 'ASC')
            ->orderBy('fi.id', 'ASC')
            ->get()->getResultArray();
    }

    public function saveItems(int $formulationId, array $items): void
    {
        $this->db->table('formulation_items')->where('formulation_id', $formulationId)->delete();

        if (empty($items)) return;

        $rows = [];
        foreach ($items as $i => $item) {
            $chemicalId = (int) ($item['chemical_id'] ?? 0);
            if (!$chemicalId) continue;

            $rows[] = [
                'formulation_id' => $formulationId,
                'chemical_id'    => $chemicalId,
                'variant_id'     => !empty($item['variant_id']) ? (int) $item['variant_id'] : null,
                'quantity'       => is_numeric($item['quantity'] ?? '') ? $item['quantity'] : 0,
                'unit'           => trim($item['unit'] ?? '') ?: null,
                'notes'          => trim($item['notes'] ?? '') ?: null,
                'sort_order'     => $i,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($rows)) {
            $this->db->table('formulation_items')->insertBatch($rows);
        }
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function getStats(): array
    {
        $rows = $this->db->table('formulations')
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
        $stats['trash'] = $this->db->table('formulations')->where('deleted_at IS NOT NULL')->countAllResults();
        return $stats;
    }

    /**
     * Hitung kebutuhan kimia untuk sejumlah batch tertentu.
     * Dipakai saat order produksi mengalokasikan stok dari formulasi.
     */
    public function calculateRequirement(int $formulationId, float $batchQty): array
    {
        $formulation = $this->find($formulationId);
        if (!$formulation) return [];

        $baseQty = (float) $formulation['output_quantity'] ?: 1;
        $ratio   = $batchQty / $baseQty;

        $items = $this->getItems($formulationId);
        $result = [];
        foreach ($items as $item) {
            $result[] = [
                'chemical_id'   => $item['chemical_id'],
                'chemical_name' => $item['chemical_name'],
                'quantity'      => round(((float) $item['quantity']) * $ratio, 4),
                'unit'          => $item['unit'],
            ];
        }
        return $result;
    }
}
