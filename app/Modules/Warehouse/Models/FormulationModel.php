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

    /**
     * Generate kode formulasi otomatis dengan format:
     * F[MM][YY]XXXX
     * 
     * Contoh: F08260001 (Agustus 2026, nomor urut 0001)
     * - F: Prefix Formulasi
     * - MM: Bulan (2 digit, 01-12)
     * - YY: Tahun (2 digit, 24, 25, 26, dst)
     * - XXXX: Nomor urut (4 digit, 0001-9999)
     */
    public function generateCode(): string
    {
        $month = date('m'); // 01-12
        $year = date('y');  // 24, 25, 26, dst

        // Cari nomor urut terakhir untuk bulan/tahun ini
        $prefix = 'F' . $month . $year;

        $builder = $this->db->table('formulations')
            ->select('formulation_code')
            ->where('deleted_at', null)
            ->like('formulation_code', $prefix, 'after')
            ->orderBy('formulation_code', 'DESC')
            ->limit(1);

        $last = $builder->get()->getRowArray();

        if ($last && !empty($last['formulation_code'])) {
            // Ambil 4 digit terakhir
            $lastNumber = (int) substr($last['formulation_code'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format dengan 4 digit (0001, 0002, dst)
        $code = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Jika kode sudah ada (kemungkinan duplikat), increment sampai unik
        $counter = 0;
        while ($this->isDuplicateCode($code)) {
            $counter++;
            $newNumber = $newNumber + $counter;
            $code = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    /**
     * Generate next code untuk preview
     */
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

    /**
     * Validasi baris komposisi:
     * - chemical  -> chemical_id wajib
     * - softener_water -> custom_label wajib, tidak butuh chemical_id (tanpa alur stok)
     */
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

        return null; // valid
    }

    // ============================================================
    // CRUD FORMULASI (identitas) + VERSIONING
    // ============================================================

    /**
     * Buat formulasi baru sekaligus versi pertamanya (v1).
     */
    public function createData(array $data, array $items, array $versionMeta = []): array
    {
        // Auto generate code jika tidak diset atau kosong
        if (empty($data['formulation_code'])) {
            $data['formulation_code'] = $this->generateCode();
        }

        // Validate duplicate
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

    /**
     * "Update" formulasi = buat VERSI BARU (tidak menimpa versi lama).
     * Identitas formulasi (kode/nama/group/process_type) tetap boleh diedit langsung
     * di tabel formulations, tapi resep & hasil/batch selalu jadi versi baru.
     */
    public function updateData(int $id, array $data, array $items, array $versionMeta = []): array
    {
        $formulation = $this->find($id);
        if (!$formulation) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];

        // Auto generate code jika tidak diset atau kosong
        if (empty($data['formulation_code'])) {
            // Jika kode kosong, pertahankan kode lama (tidak diganti)
            $data['formulation_code'] = $formulation['formulation_code'];
        }

        // Validate duplicate
        if ($this->isDuplicateCode($data['formulation_code'] ?? '', $id)) {
            return ['status' => 'error', 'errors' => ['formulation_code' => 'Kode formulasi sudah digunakan']];
        }

        if ($err = $this->validateCompositionItems($items)) {
            return ['status' => 'error', 'errors' => ['items' => $err]];
        }

        $this->db->transStart();

        // identitas formulasi (bukan bagian dari versi)
        unset($data['current_version_id']); // dikelola otomatis oleh createNewVersion()
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

    /**
     * Inti dari versioning:
     * - Hitung version_no berikutnya
     * - Kalau versi baru langsung dibuat 'Active', arsipkan versi 'Active' sebelumnya
     * - Simpan komposisi (formulation_items) terikat ke formulation_version_id yang baru
     * - Update formulations.current_version_id supaya query cepat tahu versi mana yang dipakai
     */
    public function createNewVersion(int $formulationId, array $items, array $meta = []): int
    {
        $versionModel = $this->db->table('formulation_versions');

        $lastVersion = $versionModel->where('formulation_id', $formulationId)
            ->orderBy('version_no', 'DESC')
            ->get(1)->getRowArray();
        $nextVersionNo = $lastVersion ? ((int) $lastVersion['version_no'] + 1) : 1;

        $status = $meta['status'] ?? 'Draft';

        if ($status === 'Active') {
            // hanya boleh 1 versi Active per formulasi
            $this->db->table('formulation_versions')
                ->where('formulation_id', $formulationId)
                ->where('status', 'Active')
                ->update(['status' => 'Archived']);
        }

        $versionModel->insert([
            'formulation_id'    => $formulationId,
            'version_no'        => $nextVersionNo,
            'status'            => $status,
            'output_percentage' => $meta['output_percentage'] ?? 100,
            'notes'             => $meta['notes'] ?? null,
            'created_by'        => $meta['created_by'] ?? null,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        $versionId = (int) $this->db->insertID();

        $this->saveItems($versionId, $items);

        // versi terbaru otomatis jadi acuan tampilan/pemakaian, apapun statusnya
        $this->db->table('formulations')->where('id', $formulationId)->update([
            'current_version_id' => $versionId,
        ]);

        return $versionId;
    }

    /**
     * Aktifkan versi tertentu (mis. rollback ke versi lama, atau publish versi draft).
     */
    public function activateVersion(int $formulationId, int $versionId): array
    {
        $version = $this->db->table('formulation_versions')
            ->where('id', $versionId)
            ->where('formulation_id', $formulationId)
            ->get()->getRowArray();

        if (!$version) return ['status' => 'error', 'message' => 'Versi tidak ditemukan'];

        $this->db->transStart();

        $this->db->table('formulation_versions')
            ->where('formulation_id', $formulationId)
            ->where('status', 'Active')
            ->update(['status' => 'Archived']);

        $this->db->table('formulation_versions')->where('id', $versionId)->update(['status' => 'Active']);
        $this->db->table('formulations')->where('id', $formulationId)->update(['current_version_id' => $versionId]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['status' => 'error', 'message' => 'Gagal mengaktifkan versi'];
        }

        return ['status' => 'success', 'message' => "Versi #{$version['version_no']} berhasil diaktifkan"];
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

        $data['items']    = $data['version_id'] ? $this->getItems((int) $data['version_id']) : [];
        $data['versions'] = $this->getVersions($id);

        return ['status' => 'success', 'data' => $data];
    }

    public function getVersions(int $formulationId): array
    {
        return $this->db->table('formulation_versions v')
            ->select('v.id, v.version_no, v.status, v.output_percentage, v.notes, v.created_at, u.username as created_by_name')
            ->join('users u', 'u.id = v.created_by', 'left')
            ->where('v.formulation_id', $formulationId)
            ->orderBy('v.version_no', 'DESC')
            ->get()->getResultArray();
    }

    public function deleteData(int $id, int $userId): array
    {
        if (!$this->find($id)) return ['status' => 'error', 'message' => 'Data tidak ditemukan'];

        // arsipkan versi aktif (kalau ada) supaya tidak lagi terpakai di modul lain
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

        $used = $this->db->table('formulation_stock_openings')->where('formulation_id', $id)->countAllResults();
        if ($used > 0) {
            return ['status' => 'error', 'message' => "Formulasi tidak dapat dihapus permanen karena sudah memiliki {$used} data stok"];
        }

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

    // ============================================================
    // ITEMS (komposisi resep, per versi)
    // ============================================================

    public function getItems(int $formulationVersionId): array
    {
        return $this->db->table('formulation_items fi')
            ->select('fi.*, c.chemical_code, c.chemical_name, cv.variant_name, cv.packaging')
            ->join('chemicals c', 'c.id = fi.chemical_id', 'left')
            ->join('chemical_variants cv', 'cv.id = fi.variant_id', 'left')
            ->where('fi.formulation_version_id', $formulationVersionId)
            ->orderBy('fi.sort_order', 'ASC')
            ->orderBy('fi.id', 'ASC')
            ->get()->getResultArray();
    }

    public function saveItems(int $formulationVersionId, array $items): void
    {
        if (empty($items)) return;

        $rows = [];
        foreach ($items as $i => $item) {
            $type = ($item['composition_type'] ?? 'chemical') === 'softener_water' ? 'softener_water' : 'chemical';

            $rows[] = [
                'formulation_version_id' => $formulationVersionId,
                'composition_type'       => $type,
                // softener_water tidak terikat item master / stok
                'chemical_id'            => $type === 'chemical' ? (int) ($item['chemical_id'] ?? 0) : null,
                'variant_id'             => !empty($item['variant_id']) ? (int) $item['variant_id'] : null,
                'custom_label'           => $type === 'softener_water' ? trim($item['custom_label'] ?? '') : null,
                'percentage'             => is_numeric($item['percentage'] ?? '') ? $item['percentage'] : 0,
                'notes'                  => trim($item['notes'] ?? '') ?: null,
                'sort_order'             => $i,
                'created_at'             => date('Y-m-d H:i:s'),
                'updated_at'             => date('Y-m-d H:i:s'),
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
        $stats['trash'] = $this->db->table('formulations')->where('deleted_at IS NOT NULL')->countAllResults();
        return $stats;
    }

    /**
     * Hitung kebutuhan bahan untuk sejumlah berat batch tertentu (basis %).
     * Dipisah dua kelompok:
     * - 'chemical'       -> dipakai modul stok/produksi untuk memotong stok
     * - 'softener_water' -> HANYA untuk info/laporan, TIDAK PERNAH memotong stok
     *
     * @param int   $formulationVersionId
     * @param float $batchWeight berat batch/kain (basis dari mana % dihitung)
     */
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
                    // baris ini WAJIB dipotong dari stok saat produksi berjalan
                    'affects_stock' => true,
                ];
            } else {
                $result['softener_water'][] = [
                    'label'         => $item['custom_label'],
                    'percentage'    => $item['percentage'],
                    'qty_actual'    => $qtyActual,
                    // baris ini tidak pernah menyentuh stok/item master
                    'affects_stock' => false,
                ];
            }
        }

        return $result;
    }
}
