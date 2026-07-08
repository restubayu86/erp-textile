<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChemicalsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'chemical_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'chemical_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Active', 'Draft', 'Archived'],
                'default'    => 'Draft',
            ],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('category_id');
        $this->forge->addKey('status');
        $this->forge->addKey(['chemical_code'], false, false, 'idx_chem_code');
        $this->forge->addKey(['chemical_name'], false, false, 'idx_chem_name');
        $this->forge->addForeignKey('category_id', 'chemical_categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('chemicals', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('chemicals', true);
    }
}
