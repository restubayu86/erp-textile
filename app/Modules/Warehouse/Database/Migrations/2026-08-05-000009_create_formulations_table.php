<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFormulationsTable extends Migration
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
            'formulation_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'formulation_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'group_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Kelompok/label khusus formulasi (opsional)',
            ],
            'process_type' => [
                'type' => 'ENUM',
                'constraint' => ['Dyeing', 'Finishing', 'Other'],
                'default' => 'Dyeing',
                'null' => false,
            ],
            'process_sub_type' => [
                'type' => 'ENUM',
                'constraint' => ['Dyeing', 'Dipping', 'Coating', 'Spray', 'Coating_Foam', 'Finishing', 'Other'],
                'default' => 'Other',
                'null' => false,
            ],
            'process_sub_type_label' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Label custom untuk sub proses jika diperlukan',
            ],
            'current_version_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID versi terakhir/aktif yang sedang dipakai',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'deleted_by' => [
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('formulation_code', false, true);
        $this->forge->addKey('group_id');
        $this->forge->addKey('process_type');
        $this->forge->addKey('process_sub_type');
        $this->forge->addKey('current_version_id');
        $this->forge->addKey('deleted_at');

        $this->forge->addForeignKey('group_id', 'formulation_groups', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('updated_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('deleted_by', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('formulations');
    }

    public function down()
    {
        $this->forge->dropTable('formulations', true);
    }
}
