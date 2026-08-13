<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVersionToFormulationStockOpeningsTable extends Migration
{
    private string $table        = 'formulation_stock_openings';
    private string $oldUniqueKey = 'formulation_stock_openings_period_id_warehouse_id_formulation_id';
    private string $newUniqueKey = 'uk_fso_period_wh_formulation_version';
    private string $versionIdx   = 'idx_fso_version';
    private string $fkName       = 'formulation_stock_openings_formulation_version_id_foreign';

    public function up()
    {
        // 1) Tambah kolom formulation_version_id (idempotent — aman kalau migration ini
        // sebelumnya sempat gagal di tengah jalan dan kolom sudah terlanjur ada)
        $columnExists = $this->db->query("
            SELECT COLUMN_NAME
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = '{$this->table}'
              AND COLUMN_NAME = 'formulation_version_id'
        ")->getRowArray();

        if (!$columnExists) {
            $this->forge->addColumn($this->table, [
                'formulation_version_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'formulation_id',
                    'comment'    => 'Versi resep yang berlaku saat stok ini dicatat — dipakai untuk breakdown kimia penyusun',
                ],
            ]);
        }

        // 2) Backfill data lama dengan current_version_id formulasi terkait (best-effort, aman diulang)
        $this->db->query("
            UPDATE {$this->table} fso
            INNER JOIN formulations f ON f.id = fso.formulation_id
            SET fso.formulation_version_id = f.current_version_id
            WHERE fso.formulation_version_id IS NULL
        ");

        // 3) Ganti unique key: (period,warehouse,formulation) -> (period,warehouse,formulation,version)
        // Cari nama index unique asli (bisa berbeda dari nama "seharusnya" karena MySQL
        // memotong nama identifier yang lebih dari 64 karakter)
        $oldKeyRow = $this->db->query("
            SELECT INDEX_NAME
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = '{$this->table}'
              AND NON_UNIQUE = 0
              AND INDEX_NAME != 'PRIMARY'
            GROUP BY INDEX_NAME
            LIMIT 1
        ")->getRowArray();

        if ($oldKeyRow) {
            $this->forge->dropKey($this->table, $oldKeyRow['INDEX_NAME'], false);
        }

        $existingKeys = $this->db->query("
            SELECT DISTINCT INDEX_NAME
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = '{$this->table}'
              AND INDEX_NAME IN ('{$this->newUniqueKey}', '{$this->versionIdx}')
        ")->getResultArray();
        $existingKeyNames = array_column($existingKeys, 'INDEX_NAME');

        if (!in_array($this->newUniqueKey, $existingKeyNames, true)) {
            $this->forge->addKey(['period_id', 'warehouse_id', 'formulation_id', 'formulation_version_id'], false, true, $this->newUniqueKey);
        }
        if (!in_array($this->versionIdx, $existingKeyNames, true)) {
            $this->forge->addKey('formulation_version_id', false, false, $this->versionIdx);
        }
        $this->forge->processIndexes($this->table);

        // 4) Foreign key ke formulation_versions (raw SQL, konsisten dengan pola migration lain di project ini)
        try {
            $this->db->query("
                ALTER TABLE {$this->table}
                ADD CONSTRAINT {$this->fkName}
                FOREIGN KEY (formulation_version_id)
                REFERENCES formulation_versions(id)
                ON DELETE SET NULL
            ");
        } catch (\Exception $e) {
            // FK mungkin sudah ada
        }
    }

    public function down()
    {
        try {
            $this->db->query("ALTER TABLE {$this->table} DROP FOREIGN KEY {$this->fkName}");
        } catch (\Exception $e) {
            // FK mungkin tidak ada
        }

        $this->forge->dropKey($this->table, $this->newUniqueKey, false);
        $this->forge->dropKey($this->table, $this->versionIdx, false);
        $this->forge->addKey(['period_id', 'warehouse_id', 'formulation_id'], false, true, $this->oldUniqueKey);
        $this->forge->processIndexes($this->table);
        $this->forge->dropColumn($this->table, 'formulation_version_id');
    }
}
