<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFabricAndFlowProcessTables extends Migration
{
    public function up()
    {
        // ============================================================
        // FABRICS — kerangka dasar
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'fabric_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'fabric_name' => [
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
        $this->forge->addUniqueKey('fabric_code');
        $this->forge->createTable('fabrics');

        // ============================================================
        // FLOW_PROCESSES — header template proses per fabric
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'fabric_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'flow_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'flow_name' => [
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
        $this->forge->addKey('fabric_id');
        $this->forge->addUniqueKey(['fabric_id', 'flow_code']);
        $this->forge->addForeignKey('fabric_id', 'fabrics', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('flow_processes');

        // ============================================================
        // FLOW_PROCESS_STEPS — detail step (step_no + process_name)
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
        $this->forge->dropTable('fabrics', true);
    }
}
