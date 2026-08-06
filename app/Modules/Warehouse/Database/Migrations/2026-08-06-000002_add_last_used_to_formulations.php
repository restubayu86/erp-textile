<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLastUsedToFormulations extends Migration
{
    public function up()
    {
        $columns = $this->db->query("SHOW COLUMNS FROM formulations")->getResultArray();
        $existingColumns = array_column($columns, 'Field');

        if (!in_array('last_used_at', $existingColumns)) {
            $this->forge->addColumn('formulations', [
                'last_used_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'description',
                    'comment' => 'Tanggal terakhir formulasi digunakan dalam produksi',
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('formulations', 'last_used_at');
    }
}
