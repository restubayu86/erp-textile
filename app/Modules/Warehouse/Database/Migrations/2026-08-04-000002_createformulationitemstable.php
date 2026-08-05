<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFormulationItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'formulation_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
            'chemical_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
            'variant_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'default' => null],
            'quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,4',
                'default'    => 0,
                'null'       => false,
                'comment'    => 'Takaran kimia untuk 1 batch resep (sesuai output_quantity formulasi)',
            ],
            'unit' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'notes' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0, 'null' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('formulation_id');
        $this->forge->addKey('chemical_id');
        $this->forge->addUniqueKey(['formulation_id', 'chemical_id'], 'uniq_formulation_chemical');

        $this->forge->addForeignKey('formulation_id', 'formulations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('chemical_id', 'chemicals', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('variant_id', 'chemical_variants', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('formulation_items', true);
    }

    public function down()
    {
        $this->forge->dropTable('formulation_items', true);
    }
}
