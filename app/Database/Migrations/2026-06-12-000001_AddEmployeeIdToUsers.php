<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmployeeIdToUsers extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'employee_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'username',
            ],
        ]);

        $this->forge->addForeignKey('employee_id', 'employees', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addKey('employee_id');
        $this->forge->processIndexes('users');
    }

    public function down(): void
    {
        // Disable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->forge->dropForeignKey('users', 'users_employee_id_foreign');
        $this->forge->dropColumn('users', 'employee_id');

        // Enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
