<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyChemicalsMultiCategory extends Migration
{
    public function up()
    {
        // Pivot table: chemical <-> category (many-to-many)
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'chemical_id' => ['type' => 'INT', 'unsigned' => true],
            'category_id' => ['type' => 'INT', 'unsigned' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['chemical_id', 'category_id']);
        $this->forge->addForeignKey('chemical_id', 'chemicals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'chemical_categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('chemical_category_map', true);

        // Migrate existing single category_id data into pivot (if column exists)
        $columnCheck = $this->db->query("SHOW COLUMNS FROM `chemicals` LIKE 'category_id'")->getResultArray();

        if (!empty($columnCheck)) {
            $rows = $this->db->table('chemicals')
                ->select('id, category_id')
                ->where('category_id IS NOT NULL')
                ->get()->getResultArray();

            foreach ($rows as $row) {
                $this->db->table('chemical_category_map')->insert([
                    'chemical_id' => $row['id'],
                    'category_id' => $row['category_id'],
                    'created_at'  => date('Y-m-d H:i:s'),
                ]);
            }

            $this->forge->dropForeignKey('chemicals', 'chemicals_category_id_foreign');
            $this->forge->dropColumn('chemicals', 'category_id');
        }

        // Auto-numbering sequence table for chemical_code (CH-00001, CH-00002, ...)
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'last_number' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        // Parameter kedua (true) = "IF NOT EXISTS", jadi aman dipanggil berkali-kali
        $this->forge->createTable('chemical_code_sequence', true);

        // Seed baris awal hanya jika tabel masih kosong
        if ($this->db->table('chemical_code_sequence')->countAllResults() === 0) {
            $this->db->table('chemical_code_sequence')->insert(['last_number' => 0]);
        }
    }

    public function down()
    {
        $this->forge->addColumn('chemicals', [
            'category_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'chemical_name'],
        ]);
        $this->forge->dropTable('chemical_category_map', true);
        $this->forge->dropTable('chemical_code_sequence', true);
    }
}
