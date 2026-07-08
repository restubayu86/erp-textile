<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChemicalVariantsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'chemical_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'variant_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'comment'    => 'cth: Drum 200L, Jerigen 20L, Karung 25kg',
            ],
            'packaging' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'Jenis kemasan: Drum, Jerigen, Karung, Botol, dll',
            ],
            'packaging_size' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
                'null'       => true,
                'comment'    => 'Ukuran per kemasan (isi bersih)',
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'comment'    => 'Satuan: kg, liter, gram, ml, pcs',
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
                'comment'    => 'Harga per kemasan (Rupiah)',
            ],
            'is_default' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '1 = varian utama/default untuk chemical ini',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Active', 'Inactive'],
                'default'    => 'Active',
            ],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('chemical_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('chemical_id', 'chemicals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('chemical_variants', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('chemical_variants', true);
    }
}
