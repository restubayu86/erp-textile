<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDesignAndFlowProcessTables extends Migration
{
    public function up()
    {
        // ============================================================
        // DESIGN_MASTER — kerangka dasar
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'design_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'design_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
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
        $this->forge->addUniqueKey('design_code');
        $this->forge->createTable('design_master');

        // ============================================================
        // FLOW_PROCESSES — header template proses per design
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'design_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'flow_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'segment' => [
                'type'       => 'ENUM',
                'constraint' => ['Interior', 'Otomotif', 'Lain-Lain'],
                'null'       => false,
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
        $this->forge->addKey('design_id');
        $this->forge->addUniqueKey(['design_id', 'flow_name']);
        $this->forge->addForeignKey('design_id', 'design_master', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('flow_processes');

        // ============================================================
        // FLOW_PROCESS_STEPS — detail step (step_no + process_name/chemical_code)
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'flow_process_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'step_no' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'process_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'step_type' => [
                'type'       => 'ENUM',
                'constraint' => ['process', 'chemical'],
                'default'    => 'process',
            ],
            'chemical_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
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
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('flow_process_id');
        $this->forge->addUniqueKey(['flow_process_id', 'step_no']);
        $this->forge->addForeignKey('flow_process_id', 'flow_processes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('flow_process_steps');
    }

    public function down()
    {
        $this->forge->dropTable('flow_process_steps', true);
        $this->forge->dropTable('flow_processes', true);
        $this->forge->dropTable('design_master', true);
    }
}
