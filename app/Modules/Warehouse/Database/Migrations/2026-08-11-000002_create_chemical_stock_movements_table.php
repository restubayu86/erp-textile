<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChemicalStockMovementsTable extends Migration
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
            'period_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'chemical_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'variant_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'movement_type' => [
                'type' => 'ENUM',
                'constraint' => ['Receipt', 'Issue', 'TransferIn', 'TransferOut', 'AdjustmentIn', 'AdjustmentOut'],
                'null' => false,
                'comment' => 'Jenis pergerakan stok kimia (stok awal disimpan terpisah di chemical_stock_openings)',
            ],
            'quantity_in' => [
                'type' => 'DECIMAL',
                'constraint' => '15,3',
                'default' => 0.000,
                'null' => false,
            ],
            'quantity_out' => [
                'type' => 'DECIMAL',
                'constraint' => '15,3',
                'default' => 0.000,
                'null' => false,
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'reference_type' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
                'comment' => 'Sumber transaksi: stock_transfer, stock_adjustment, manual, dll',
            ],
            'reference_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID baris di tabel sumber (stock_transfer_items.id / stock_adjustment_items.id)',
            ],
            'movement_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['period_id', 'warehouse_id', 'chemical_id']);
        $this->forge->addKey('chemical_id');
        $this->forge->addKey('variant_id');
        $this->forge->addKey('movement_type');
        $this->forge->addKey(['reference_type', 'reference_id']);
        $this->forge->addKey('movement_date');

        $this->forge->addForeignKey('period_id', 'periods', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('chemical_id', 'chemicals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('variant_id', 'chemical_variants', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('chemical_stock_movements');
    }

    public function down()
    {
        $this->forge->dropTable('chemical_stock_movements', true);
    }
}
