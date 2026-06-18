<?php

namespace App\Modules\Production\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMachinesTable extends Migration
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
            'machine_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                // Tidak unique global — scoped per department, sama seperti positions
            ],
            'machine_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'department_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'capacity' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
                'comment'    => 'Kapasitas mesin (kg/jam, meter/menit, dll — satuan bebas sesuai jenis mesin)',
            ],
            'capacity_unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'comment'    => 'cth: kg/jam, m/menit, pcs/jam',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Active', 'Draft', 'Maintenance', 'Archived'],
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
        $this->forge->addKey('department_id');
        $this->forge->addKey('status');

        // Composite index — basis uniqueness per departemen di level aplikasi
        $this->forge->addKey(['machine_code', 'department_id'], false, false, 'idx_code_dept');
        $this->forge->addKey(['machine_name', 'department_id'], false, false, 'idx_name_dept');

        $this->forge->addForeignKey('department_id', 'departments', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('machines', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('machines', true);
    }
}
