<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChemicalStockIfsTable extends Migration
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
            'ifs_qty' => [
                'type' => 'DECIMAL',
                'constraint' => '15,3',
                'default' => 0.000,
                'null' => false,
                'comment' => 'Stok akhir kimia menurut sistem akunting IFS (dicatat manual, untuk rekonsiliasi)',
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
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
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['period_id', 'warehouse_id', 'chemical_id'], false, true, 'uk_csi_period_wh_chemical');
        $this->forge->addKey('period_id');
        $this->forge->addKey('warehouse_id');
        $this->forge->addKey('chemical_id');

        $this->forge->addForeignKey('period_id', 'periods', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('chemical_id', 'chemicals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('updated_by', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('chemical_stock_ifs');
    }

    public function down()
    {
        $this->forge->dropTable('chemical_stock_ifs', true);
    }
}
