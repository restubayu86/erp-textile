<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Idempotent: aman dijalankan ulang meski kolomnya sudah ada di database.
 */
class AddQtyFieldsToChemicalStockMovements extends Migration
{
    private string $table = 'chemical_stock_movements';

    public function up()
    {
        $columns = [];

        if (!$this->columnExists('qty_unit')) {
            $columns['qty_unit'] = [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
                'after'      => 'quantity_in',
            ];
        }

        if (!$this->columnExists('qty_berat')) {
            $columns['qty_berat'] = [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
                'after'      => $columns ? 'qty_unit' : 'quantity_in',
            ];
        }

        if (!empty($columns)) {
            $this->forge->addColumn($this->table, $columns);
        }
    }

    public function down()
    {
        $drop = array_filter(['qty_unit', 'qty_berat'], fn($c) => $this->columnExists($c));
        if (!empty($drop)) {
            $this->forge->dropColumn($this->table, $drop);
        }
    }

    private function columnExists(string $column): bool
    {
        $row = $this->db->query("
            SELECT COLUMN_NAME FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '{$this->table}' AND COLUMN_NAME = '{$column}'
        ")->getRowArray();
        return (bool) $row;
    }
}
