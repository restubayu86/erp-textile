<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFormulationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'formulation_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'formulation_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'process_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Dyeing', 'Finishing', 'Other'],
                'default'    => 'Dyeing',
                'null'       => false,
            ],
            'output_quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,3',
                'default'    => 0,
                'null'       => false,
                'comment'    => 'Hasil premix per 1 batch resep (basis takaran)',
            ],
            'output_unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'comment'    => 'Satuan hasil premix, mis: kg, liter',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Active', 'Draft', 'Archived'],
                'default'    => 'Draft',
                'null'       => false,
            ],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'default' => null],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'default' => null],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'default' => null],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('formulation_code', 'uniq_formulation_code');
        $this->forge->addKey('status');
        $this->forge->addKey('process_type');
        $this->forge->addKey('deleted_at');

        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('updated_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('deleted_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('formulations', true);
    }

    public function down()
    {
        $this->forge->dropTable('formulations', true);
    }
}
