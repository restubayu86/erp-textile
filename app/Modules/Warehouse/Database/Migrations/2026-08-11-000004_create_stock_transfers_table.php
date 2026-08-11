<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockTransfersTable extends Migration
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
            'transfer_no' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => false,
            ],
            'transfer_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'item_type' => [
                'type' => 'ENUM',
                'constraint' => ['chemical', 'formulation'],
                'null' => false,
                'comment' => '1 dokumen transfer hanya untuk 1 jenis item (kimia ATAU formulasi)',
            ],
            'period_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'source_warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'destination_warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Draft', 'Posted', 'Cancelled'],
                'default' => 'Draft',
                'null' => false,
                'comment' => 'Draft = belum mempengaruhi stok. Posted = sudah menulis ke ledger movements.',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'posted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'posted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'cancelled_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'cancelled_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addKey('transfer_no', false, true);
        $this->forge->addKey('item_type');
        $this->forge->addKey('period_id');
        $this->forge->addKey('source_warehouse_id');
        $this->forge->addKey('destination_warehouse_id');
        $this->forge->addKey('status');
        $this->forge->addKey('deleted_at');

        $this->forge->addForeignKey('period_id', 'periods', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('source_warehouse_id', 'warehouses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('destination_warehouse_id', 'warehouses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('posted_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('cancelled_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('updated_by', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('stock_transfers');
    }

    public function down()
    {
        $this->forge->dropTable('stock_transfers', true);
    }
}
