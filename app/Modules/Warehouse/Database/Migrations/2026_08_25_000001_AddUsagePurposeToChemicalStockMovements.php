<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Idempotent: aman dijalankan ulang meski kolomnya sudah ada di database.
 */
class AddUsagePurposeToChemicalStockMovements extends Migration
{
    private string $table = 'chemical_stock_movements';

    public function up()
    {
        if ($this->columnExists('usage_purpose')) return;

        $this->forge->addColumn($this->table, [
            'usage_purpose' => [
                'type'       => 'ENUM',
                'constraint' => ['Sample', 'Litbang', 'SamplingProduksi', 'Perbaikan'],
                'null'       => true,
                'after'      => 'movement_type',
            ],
        ]);
    }

    public function down()
    {
        if (!$this->columnExists('usage_purpose')) return;
        $this->forge->dropColumn($this->table, ['usage_purpose']);
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
