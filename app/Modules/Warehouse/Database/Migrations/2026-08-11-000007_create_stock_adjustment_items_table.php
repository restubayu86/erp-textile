<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockAdjustmentItemsTable extends Migration
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
            'adjustment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'chemical_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'variant_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'formulation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'system_quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '15,3',
                'default' => 0.000,
                'null' => false,
                'comment' => 'Stok menurut sistem (hasil sum on-the-fly) saat adjustment dibuat',
            ],
            'physical_quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '15,3',
                'default' => 0.000,
                'null' => false,
                'comment' => 'Stok fisik hasil stock opname / pengecekan',
            ],
            'difference_quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '15,3',
                'default' => 0.000,
                'null' => false,
                'comment' => 'physical_quantity - system_quantity (positif = AdjustmentIn, negatif = AdjustmentOut)',
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'notes' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
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
        $this->forge->addKey('adjustment_id');
        $this->forge->addKey('chemical_id');
        $this->forge->addKey('formulation_id');

        $this->forge->addForeignKey('adjustment_id', 'stock_adjustments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('chemical_id', 'chemicals', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('variant_id', 'chemical_variants', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('formulation_id', 'formulations', 'id', 'RESTRICT', 'CASCADE');

        $this->forge->createTable('stock_adjustment_items');
    }

    public function down()
    {
        $this->forge->dropTable('stock_adjustment_items', true);
    }
}
