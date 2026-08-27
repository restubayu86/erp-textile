<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Idempotent: aman dijalankan ulang meski kolom/index-nya sudah ada di database.
 */
class AddOrderRefToFormulationStockMovements extends Migration
{
    private string $table = 'formulation_stock_movements';

    public function up()
    {
        $columns = [];

        if (!$this->columnExists('order_no')) {
            $columns['order_no'] = [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'reference_id',
            ];
        }
        if (!$this->columnExists('customer_name')) {
            $columns['customer_name'] = [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
                'after' => $columns ? 'order_no' : 'reference_id',
            ];
        }
        if (!empty($columns)) {
            $this->forge->addColumn($this->table, $columns);
        }

        if (!$this->indexExists('idx_fsm_order_no')) {
            $this->forge->addKey('order_no', false, false, 'idx_fsm_order_no');
            $this->forge->processIndexes($this->table);
        }
    }

    public function down()
    {
        if ($this->indexExists('idx_fsm_order_no')) {
            $this->forge->dropKey($this->table, 'idx_fsm_order_no', false);
        }
        $drop = array_filter(['order_no', 'customer_name'], fn($c) => $this->columnExists($c));
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

    private function indexExists(string $indexName): bool
    {
        $row = $this->db->query("
            SELECT INDEX_NAME FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '{$this->table}' AND INDEX_NAME = '{$indexName}'
        ")->getRowArray();
        return (bool) $row;
    }
}
