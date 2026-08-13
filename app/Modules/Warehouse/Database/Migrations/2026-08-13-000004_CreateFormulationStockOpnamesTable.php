<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFormulationStockOpnamesTable extends Migration
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
            'formulation_version_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Versi resep yang berlaku saat opname ini dicatat — dipakai untuk breakdown kimia penyusun',
            ],
            'opname_qty' => [
                'type' => 'DECIMAL',
                'constraint' => '15,3',
                'default' => 0.000,
                'null' => false,
                'comment' => 'Hasil hitung fisik stok formulasi (premix) di akhir periode',
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'opname_date' => [
                'type' => 'DATE',
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
        $this->forge->addKey(['period_id', 'warehouse_id', 'formulation_id', 'formulation_version_id'], false, true, 'uk_fso2_period_wh_formulation_version');
        $this->forge->addKey('period_id');
        $this->forge->addKey('warehouse_id');
        $this->forge->addKey('formulation_id');
        $this->forge->addKey('formulation_version_id');

        $this->forge->addForeignKey('period_id', 'periods', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('formulation_id', 'formulations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('formulation_version_id', 'formulation_versions', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('updated_by', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('formulation_stock_opnames');
    }

    public function down()
    {
        $this->forge->dropTable('formulation_stock_opnames', true);
    }
}
