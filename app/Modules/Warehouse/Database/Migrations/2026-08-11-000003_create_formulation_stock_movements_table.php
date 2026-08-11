<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFormulationStockMovementsTable extends Migration
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
            'formulation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'movement_type' => [
                'type' => 'ENUM',
                'constraint' => ['MixingIn', 'AppliedOut', 'TransferIn', 'TransferOut', 'AdjustmentIn', 'AdjustmentOut'],
                'null' => false,
                'comment' => 'MixingIn & AppliedOut disiapkan untuk modul mixing/aplikasi produksi menyusul',
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
        $this->forge->addKey(['period_id', 'warehouse_id', 'formulation_id']);
        $this->forge->addKey('formulation_id');
        $this->forge->addKey('movement_type');
        $this->forge->addKey(['reference_type', 'reference_id']);
        $this->forge->addKey('movement_date');

        $this->forge->addForeignKey('period_id', 'periods', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('formulation_id', 'formulations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('formulation_stock_movements');
    }

    public function down()
    {
        $this->forge->dropTable('formulation_stock_movements', true);
    }
}
