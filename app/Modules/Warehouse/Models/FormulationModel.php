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
        'group_id',
        'process_type',
        'process_sub_type',
        'process_sub_type_label',
        'description',
        'current_version_id',
        'last_used_at',
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
    // AUTO GENERATE CODE - Format FMMYY0001
    // ============================================================

    public function generateCode(): string
    {
        $month = date('m');
        $year = date('y');
        $prefix = 'F' . $month . $year;

        $builder = $this->db->table('formulations')
            ->select('formulation_code')
            ->where('deleted_at', null)
            ->like('formulation_code', $prefix, 'after')
            ->orderBy('formulation_code', 'DESC')
            ->limit(1);

        $last = $builder->get()->getRowArray();

        if ($last && !empty($last['formulation_code'])) {
            $lastNumber = (int) substr($last['formulation_code'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $code = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        $counter = 0;
        while ($this->isDuplicateCode($code)) {
            $counter++;
            $newNumber = $newNumber + $counter;
            $code = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    public function generateNextCode(): string
    {
        return $this->generateCode();
    }

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

    private function validateCompositionItems(array $items): ?string
    {
        if (empty($items)) return 'Minimal 1 baris komposisi harus diisi dalam resep';

        foreach ($items as $i => $item) {
            $type = $item['composition_type'] ?? 'chemical';
            $no   = $i + 1;

            if (!in_array($type, ['chemical', 'softener_water'], true)) {
                return "Baris #{$no}: jenis komposisi tidak valid";
            }
            if ($type === 'chemical' && empty($item['chemical_id'])) {
                return "Baris #{$no}: bahan kimia wajib dipilih";
            }
            if ($type === 'softener_water' && trim($item['custom_label'] ?? '') === '') {
                return "Baris #{$no}: nama softener water wajib diisi";
            }
            if (!isset($item['percentage']) || !is_numeric($item['percentage']) || (float) $item['percentage'] < 0) {
                return "Baris #{$no}: persentase harus berupa angka >= 0";
            }
        }

        return null;
    }

    // ============================================================
    // CRUD
    // ============================================================

    public function createData(array $data, array $items, array $versionMeta = []): array
    {
        if (empty($data['formulation_code'])) {
            $data['formulation_code'] = $this->generateCode();
        }

        if ($this->isDuplicateCode($data['formulation_code'] ?? '')) {
            return ['status' => 'error', 'errors' => ['formulation_code' => 'Kode formulasi sudah digunakan']];
        }

        if ($err = $this->validateCompositionItems($items)) {
            return ['status' => 'error', 'errors' => ['items' => $err]];
        }

        $this->db->transStart();

        if (!$this->insert($data)) {
            $this->db->transRollback();
            return ['status' => 'error', 'message' => 'Gagal menyimpan data', 'errors' => $this->errors()];
        }

        $formulationId = $this->getInsertID();

        $this->createNewVersion($formulationId, $items, array_merge($versionMeta, [
            'status'     => $versionMeta['status'] ?? 'Draft',
            'created_by' => $data['created_by'] ?? null,
        ]));

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['status' => 'error', 'message' => 'Gagal menyimpan formulasi'];
        }

        return ['status' => 'success', 'message' => 'Formulasi berhasil ditambahkan', 'id' => $formulationId];
    }

    public function updateDataWithoutVersion(int $id, array $data, array $items, array $versionMeta = []): array
    {
        $formulation = $this->find($id);
        if (!$formulation) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];

        if (empty($data['formulation_code'])) {
            $data['formulation_code'] = $formulation['formulation_code'];
        }

        if ($this->isDuplicateCode($data['formulation_code'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['formulation_code' => 'Kode formulasi sudah digunakan']];
        }

        if ($err = $this->validateCompositionItems($items)) {
            return ['status' => 'error', 'errors' => ['items' => $err]];
        }

        $this->db->transStart();

        unset($data['current_version_id']);
        if (!$this->update($id, $data)) {
            $this->db->transRollback();
            return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
        }

        $currentVersionId = $formulation['current_version_id'];
        if ($currentVersionId) {
            $versionData = [];
            if (isset($versionMeta['output_percentage'])) {
                $versionData['output_percentage'] = $versionMeta['output_percentage'];
            }
            if (isset($versionMeta['status'])) {
                $versionData['status'] = $versionMeta['status'];
            }
            if (isset($versionMeta['notes'])) {
                $versionData['notes'] = $versionMeta['notes'];
            }
            if (!empty($versionData)) {
                $versionData['updated_at'] = date('Y-m-d H:i:s');
                $this->db->table('formulation_versions')
                    ->where('id', $currentVersionId)
                    ->update($versionData);
            }

            $this->db->table('formulation_items')
                ->where('formulation_version_id', $currentVersionId)
                ->delete();

            $this->saveItems($currentVersionId, $items);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['status' => 'error', 'message' => 'Gagal memperbarui formulasi'];
        }

        return ['status' => 'success', 'message' => 'Formulasi berhasil diperbarui (tanpa versi baru)'];
    }

    public function updateData(int $id, array $data, array $items, array $versionMeta = []): array
    {
        $formulation = $this->find($id);
        if (!$formulation) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];

        if (empty($data['formulation_code'])) {
            $data['formulation_code'] = $formulation['formulation_code'];
        }

        if ($this->isDuplicateCode($data['formulation_code'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['formulation_code' => 'Kode formulasi sudah digunakan']];
        }

        if ($err = $this->validateCompositionItems($items)) {
            return ['status' => 'error', 'errors' => ['items' => $err]];
        }

        $this->db->transStart();

        unset($data['current_version_id']);
        if (!$this->update($id, $data)) {
            $this->db->transRollback();
            return ['status' => 'error', 'message' => 'Gagal memperbarui data', 'errors' => $this->errors()];
        }

        $this->createNewVersion($id, $items, array_merge($versionMeta, [
            'status'     => $versionMeta['status'] ?? 'Draft',
            'created_by' => $data['updated_by'] ?? null,
        ]));

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['status' => 'error', 'message' => 'Gagal memperbarui formulasi'];
        }

        return ['status' => 'success', 'message' => 'Formulasi berhasil disimpan sebagai versi baru'];
    }

    public function createNewVersion(int $formulationId, array $items, array $meta = []): int
    {
        $versionModel = $this->db->table('formulation_versions');

        $lastVersion = $versionModel->where('formulation_id', $formulationId)
            ->orderBy('version_no', 'DESC')
            ->get(1)->getRowArray();
        $nextVersionNo = $lastVersion ? ((int) $lastVersion['version_no'] + 1) : 1;

        $status = $meta['status'] ?? 'Draft';

        // Versi aktif boleh lebih dari 1 - tidak ada auto archive

        $outputPercentage = $meta['output_percentage'] ?? null;
        if ($outputPercentage !== null && $outputPercentage !== '') {
            $outputPercentage = (float) $outputPercentage;
        }

        $versionModel->insert([
            'formulation_id'    => $formulationId,
            'version_no'        => $nextVersionNo,
            'status'            => $status,
            'output_percentage' => $outputPercentage,
            'notes'             => $meta['notes'] ?? null,
            'created_by'        => $meta['created_by'] ?? null,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        $versionId = (int) $this->db->insertID();

        $this->saveItems($versionId, $items);

        // Sinkronkan pointer versi utama sesuai prioritas (Active terbaru,
        // fallback ke versi terbaru) - bukan selalu menunjuk versi baru ini,
        // supaya versi Draft yang baru dibuat tidak menggantikan versi
        // Active yang masih berlaku.
        $this->refreshCurrentVersionPointer($formulationId);

        return $versionId;
    }

    /**
     * Sinkronkan ulang f.current_version_id agar selalu konsisten dengan
     * aturan prioritas: versi Active dengan version_no terbesar; kalau
     * tidak ada versi Active sama sekali, fallback ke version_no terbesar
     * apa pun statusnya. Dipanggil setiap kali status versi berubah supaya
     * pointer ini tidak basi (lihat juga FormulationController::datatables
     * yang menghitung ulang versi representatif secara dinamis untuk daftar).
     */
    private function refreshCurrentVersionPointer(int $formulationId): void
    {
        $row = $this->db->table('formulation_versions')
            ->select('id')
            ->where('formulation_id', $formulationId)
            ->orderBy("status = 'Active'", 'DESC', false)
            ->orderBy('version_no', 'DESC')
            ->get(1)->getRowArray();

        $this->db->table('formulations')
            ->where('id', $formulationId)
            ->update(['current_version_id' => $row['id'] ?? null]);
    }

    public function activateVersion(int $formulationId, int $versionId): array
    {
        $version = $this->db->table('formulation_versions')
            ->where('id', $versionId)
            ->where('formulation_id', $formulationId)
            ->get()->getRowArray();

        if (!$version) return ['status' => 'error', 'message' => 'Versi tidak ditemukan'];

        $this->db->transStart();

        // Versi aktif boleh lebih dari 1 - tidak ada auto archive

        $this->db->table('formulation_versions')->where('id', $versionId)->update(['status' => 'Active']);
        $this->refreshCurrentVersionPointer($formulationId);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['status' => 'error', 'message' => 'Gagal mengaktifkan versi'];
        }

        return ['status' => 'success', 'message' => "Versi #{$version['version_no']} berhasil diaktifkan"];
    }

    public function toggleVersionActive(int $formulationId, int $versionId, bool $active): array
    {
        $version = $this->db->table('formulation_versions')
            ->where('id', $versionId)
            ->where('formulation_id', $formulationId)
            ->get()->getRowArray();

        if (!$version) return ['status' => 'error', 'message' => 'Versi tidak ditemukan'];

        $newStatus = $active ? 'Active' : 'Archived';

        $this->db->transStart();

        $this->db->table('formulation_versions')->where('id', $versionId)->update(['status' => $newStatus]);

        // Selalu sinkronkan ulang pointer, baik saat diaktifkan maupun
        // dinonaktifkan, supaya current_version_id tidak pernah menunjuk
        // ke versi yang sudah Archived selama masih ada versi Active lain.
        $this->refreshCurrentVersionPointer($formulationId);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['status' => 'error', 'message' => 'Gagal mengubah status versi'];
        }

        $message = $active
            ? "Versi #{$version['version_no']} diaktifkan"
            : "Versi #{$version['version_no']} dinonaktifkan";

        return ['status' => 'success', 'message' => $message];
    }

    public function getData(int $id): array
    {
        $data = $this->db->table('formulations f')
            ->select([
                'f.*',
                'g.group_name',
                'v.id as version_id',
                'v.version_no',
                'v.status',
                'v.output_percentage',
                'v.notes as version_notes',
            ])
            ->join('formulation_groups g', 'g.id = f.group_id', 'left')
            ->join('formulation_versions v', 'v.id = f.current_version_id', 'left')
            ->where('f.id', $id)
            ->where('f.deleted_at', null)
            ->get()->getRowArray();

        if (!$data) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];

        if ($data['output_percentage'] === null) {
            $data['output_percentage'] = '';
        }

        $data['items']    = $data['version_id'] ? $this->getItems((int) $data['version_id']) : [];
        $data['versions'] = $this->getVersions($id);

        return ['status' => 'success', 'data' => $data];
    }

    public function getVersions(int $formulationId): array
    {
        $versions = $this->db->table('formulation_versions v')
            ->select('v.*, u.username as created_by_name')
            ->join('users u', 'u.id = v.created_by', 'left')
            ->where('v.formulation_id', $formulationId)
            ->orderBy('v.version_no', 'DESC')
            ->get()->getResultArray();

        foreach ($versions as &$version) {
            if ($version['output_percentage'] === null) {
                $version['output_percentage'] = '-';
            }
        }

        return $versions;
    }

    public function saveItems(int $formulationVersionId, array $items): void
    {
        if (empty($items)) return;

        $rows = [];
        foreach ($items as $i => $item) {
            $type = ($item['composition_type'] ?? 'chemical') === 'softener_water' ? 'softener_water' : 'chemical';

            // Debug: log untuk memastikan unit ada
            log_message('debug', 'Saving item - unit: ' . ($item['unit'] ?? 'null'));

            $rows[] = [
                'formulation_version_id' => $formulationVersionId,
                'composition_type'       => $type,
                'chemical_id'            => $type === 'chemical' ? (int) ($item['chemical_id'] ?? 0) : null,
                'variant_id'             => !empty($item['variant_id']) ? (int) $item['variant_id'] : null,
                'custom_label'           => $type === 'softener_water' ? trim($item['custom_label'] ?? '') : null,
                'percentage'             => is_numeric($item['percentage'] ?? '') ? $item['percentage'] : 0,
                'unit'                   => $item['unit'] ?? null, // Pastikan ini ada
                'notes'                  => trim($item['notes'] ?? '') ?: null,
                'sort_order'             => $i,
                'created_at'             => date('Y-m-d H:i:s'),
                'updated_at'             => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($rows)) {
            // Debug: log rows yang akan diinsert
            log_message('debug', 'Inserting rows: ' . json_encode($rows));
            $this->db->table('formulation_items')->insertBatch($rows);
        }
    }

    public function getItems(int $formulationVersionId): array
    {
        $items = $this->db->table('formulation_items fi')
            ->select('fi.*, c.chemical_code, c.chemical_name, cv.variant_name, cv.packaging')
            ->join('chemicals c', 'c.id = fi.chemical_id', 'left')
            ->join('chemical_variants cv', 'cv.id = fi.variant_id', 'left')
            ->where('fi.formulation_version_id', $formulationVersionId)
            ->orderBy('fi.sort_order', 'ASC')
            ->orderBy('fi.id', 'ASC')
            ->get()->getResultArray();

        // Debug: log items yang diambil
        log_message('debug', 'Retrieved items: ' . json_encode($items));

        return $items;
    }

    public function deleteData(int $id, int $userId): array
    {
        if (!$this->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];

        $this->db->table('formulation_versions')
            ->where('formulation_id', $id)
            ->where('status', 'Active')
            ->update(['status' => 'Archived']);

        $this->update($id, ['deleted_by' => $userId]);
        $this->delete($id);
        return ['status' => 'success', 'message' => 'Formulasi dipindahkan ke sampah'];
    }

    public function restoreData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];
        $this->db->table($this->table)->where('id', $id)->update(['deleted_at' => null, 'deleted_by' => null]);
        return ['status' => 'success', 'message' => 'Formulasi berhasil dipulihkan'];
    }

    public function forceDeleteData(int $id): array
    {
        if (!$this->onlyDeleted()->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan di sampah'];

        $versionIds = array_column(
            $this->db->table('formulation_versions')->select('id')->where('formulation_id', $id)->get()->getResultArray(),
            'id'
        );
        if (!empty($versionIds)) {
            $this->db->table('formulation_items')->whereIn('formulation_version_id', $versionIds)->delete();
            $this->db->table('formulation_versions')->where('formulation_id', $id)->delete();
        }

        if (!$this->delete($id, true)) return ['status' => 'error', 'message' => 'Gagal menghapus permanen'];
        return ['status' => 'success', 'message' => 'Formulasi berhasil dihapus permanen'];
    }

    public function updateLastUsed(int $id): bool
    {
        return $this->db->table('formulations')
            ->where('id', $id)
            ->update(['last_used_at' => date('Y-m-d H:i:s')]);
    }

    public function getStats(): array
    {
        $rows = $this->db->table('formulations f')
            ->select('v.status, COUNT(*) as count')
            ->join('formulation_versions v', 'v.id = f.current_version_id', 'left')
            ->where('f.deleted_at', null)
            ->groupBy('v.status')
            ->get()->getResultArray();

        $stats = ['total' => 0, 'active' => 0, 'draft' => 0, 'archived' => 0];
        foreach ($rows as $row) {
            $stats['total'] += (int) $row['count'];
            $key = strtolower($row['status'] ?? 'draft');
            if (isset($stats[$key])) $stats[$key] = (int) $row['count'];
        }

        $withoutVersion = $this->db->table('formulations')
            ->where('deleted_at', null)
            ->where('current_version_id', null)
            ->countAllResults();
        if ($withoutVersion > 0) {
            $stats['total'] += $withoutVersion;
            $stats['draft'] += $withoutVersion;
        }

        $stats['trash'] = $this->db->table('formulations')->where('deleted_at IS NOT NULL')->countAllResults();
        return $stats;
    }

    public function calculateRequirement(int $formulationVersionId, float $batchWeight): array
    {
        $items = $this->getItems($formulationVersionId);
        $result = ['chemical' => [], 'softener_water' => []];

        foreach ($items as $item) {
            $qtyActual = round(((float) $item['percentage'] / 100) * $batchWeight, 4);

            if ($item['composition_type'] === 'chemical') {
                $result['chemical'][] = [
                    'chemical_id'   => $item['chemical_id'],
                    'chemical_name' => $item['chemical_name'],
                    'percentage'    => $item['percentage'],
                    'qty_actual'    => $qtyActual,
                    'affects_stock' => true,
                ];
            } else {
                $result['softener_water'][] = [
                    'label'         => $item['custom_label'],
                    'percentage'    => $item['percentage'],
                    'qty_actual'    => $qtyActual,
                    'affects_stock' => false,
                ];
            }
        }

        return $result;
    }
}
