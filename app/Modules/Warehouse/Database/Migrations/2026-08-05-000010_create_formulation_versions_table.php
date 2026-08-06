<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFormulationVersionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'formulation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'version_no' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Nomor urut versi per formulasi: 1, 2, 3, dst',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Draft', 'Archived'],
                'default' => 'Draft',
                'null' => false,
            ],
            'output_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '8,3',
                'default' => 100.000,
                'null' => true,
                'comment' => 'Hasil/batch dalam %, tidak dibatasi harus 100 (boleh > 100%)',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Catatan perubahan pada versi ini (changelog)',
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['formulation_id', 'version_no'], false, true);
        $this->forge->addKey('formulation_id');
        $this->forge->addKey('status');

        $this->forge->addForeignKey('formulation_id', 'formulations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('formulation_versions');
    }

    public function down()
    {
        $this->forge->dropTable('formulation_versions', true);
    }
}
