<?php

namespace App\Modules\HRM\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeesTable extends Migration
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
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'fullname' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nickname' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'position_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'work_area' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'Area kerja fisik, misal: Dyeing Floor, Finishing, QC Lab',
            ],
            'shift' => [
                'type'       => 'ENUM',
                'constraint' => ['NS', 'A', 'B', 'C', 'D', 'E'],
                'default'    => 'NS',
                'comment'    => 'NS=Non-Shift, A/B/C=3-shift, D/E=extended',
            ],
            'employment_status' => [
                'type'       => 'ENUM',
                'constraint' => ['tetap', 'kontrak', 'magang'],
                'default'    => 'tetap',
            ],
            'join_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
            ],

            // Audit fields
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => true,
                'comment'  => 'FK → users.id (Shield)',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => true,
                'comment'  => 'FK → users.id (Shield)',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Soft delete — null = aktif',
            ],
            'deleted_by' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => true,
                'comment'  => 'FK → users.id (Shield)',
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('nik');
        $this->forge->addKey('position_id');
        $this->forge->addKey('status');
        $this->forge->addKey('shift');

        $this->forge->addForeignKey(
            'position_id',
            'positions',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->forge->createTable('employees', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down(): void
    {
        $this->forge->dropTable('employees', true);
    }
}
