<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFormulationItemsTable extends Migration
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
            'formulation_version_id' => [
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
            'composition_type' => [
                'type' => 'ENUM',
                'constraint' => ['chemical', 'softener_water'],
                'default' => 'chemical',
                'null' => false,
                'comment' => 'chemical = konsumsi stok bahan kimia, softener_water = tanpa alur stok',
            ],
            'variant_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'custom_label' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
                'comment' => 'Nama bebas untuk softener_water, mis. "Air Proses/Softening"',
            ],
            'percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '8,3',
                'default' => 0.000,
                'null' => false,
                'comment' => 'Dosis dalam % terhadap berat batch, tidak dibatasi total 100%',
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
        $this->forge->addKey(['formulation_version_id', 'chemical_id'], false, true);
        $this->forge->addKey('formulation_version_id');
        $this->forge->addKey('chemical_id');
        $this->forge->addKey('variant_id');
        $this->forge->addKey('composition_type');

        $this->forge->addForeignKey('formulation_version_id', 'formulation_versions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('chemical_id', 'chemicals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('variant_id', 'chemical_variants', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('formulation_items');
    }

    public function down()
    {
        $this->forge->dropTable('formulation_items', true);
    }
}
