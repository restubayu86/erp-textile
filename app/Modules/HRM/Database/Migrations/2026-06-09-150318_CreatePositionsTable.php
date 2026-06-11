<?php

namespace App\Modules\HRM\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePositionsTable extends Migration
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
            'position_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                // TIDAK unique global — uniqueness scoped per department_id
                // dijaga di level aplikasi (PositionModel::isDuplicateCode)
            ],
            'position_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'position_level' => [
                'type'       => 'INT',
                'constraint' => 2,
                'default'    => 0,
            ],
            'department_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Active', 'Draft', 'Archived'],
                'default'    => 'Draft',
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'deleted_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'deleted_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('department_id');
        $this->forge->addKey('status');

        // Composite index — basis uniqueness per departemen (dicek di PositionModel)
        $this->forge->addKey(['position_code', 'department_id'], false, false, 'idx_code_dept');
        $this->forge->addKey(['position_name', 'department_id'], false, false, 'idx_name_dept');

        $this->forge->addForeignKey('department_id', 'departments', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('positions', true);
    }

    public function down()
    {
        $this->forge->dropTable('positions', true);
    }
}
