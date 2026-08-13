<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVersionToFormulationStockMovementsTable extends Migration
{
    private string $table      = 'formulation_stock_movements';
    private string $versionIdx = 'idx_fsm_version';
    private string $fkName     = 'formulation_stock_movements_formulation_version_id_foreign';

    public function up()
    {
        // 1) Tambah kolom formulation_version_id (idempotent)
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
                    'comment'    => 'Versi resep yang berlaku saat transaksi ini dicatat — dipakai untuk breakdown kimia penyusun',
                ],
            ]);
        }

        // 2) Backfill data lama dengan current_version_id formulasi terkait (best-effort, aman diulang)
        $this->db->query("
            UPDATE {$this->table} fsm
            INNER JOIN formulations f ON f.id = fsm.formulation_id
            SET fsm.formulation_version_id = f.current_version_id
            WHERE fsm.formulation_version_id IS NULL
        ");

        // 3) Index untuk kolom baru (idempotent)
        $indexExists = $this->db->query("
            SELECT INDEX_NAME
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = '{$this->table}'
              AND INDEX_NAME = '{$this->versionIdx}'
        ")->getRowArray();

        if (!$indexExists) {
            $this->forge->addKey('formulation_version_id', false, false, $this->versionIdx);
            $this->forge->processIndexes($this->table);
        }

        // 4) Foreign key ke formulation_versions
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

        $this->forge->dropKey($this->table, $this->versionIdx, false);
        $this->forge->dropColumn($this->table, 'formulation_version_id');
    }
}
