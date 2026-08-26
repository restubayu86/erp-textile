<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQtyFieldsToChemicalStockMovements extends Migration
{
    public function up()
    {
        $this->forge->addColumn('chemical_stock_movements', [
            'qty_unit' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
                'after'      => 'quantity_in',
            ],
            'qty_berat' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
                'after'      => 'qty_unit',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('chemical_stock_movements', ['qty_unit', 'qty_berat']);
    }
}
