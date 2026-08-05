<?php

namespace App\Modules\Warehouse\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyCurrentVersion extends Migration
{
    public function up()
    {
        // Tambahkan foreign key untuk current_version_id
        try {
            $this->db->query("
                ALTER TABLE formulations 
                ADD CONSTRAINT formulations_current_version_id_foreign 
                FOREIGN KEY (current_version_id) 
                REFERENCES formulation_versions(id) 
                ON DELETE SET NULL
            ");
        } catch (\Exception $e) {
            // Foreign key mungkin sudah ada
        }
    }

    public function down()
    {
        try {
            $this->db->query("ALTER TABLE formulations DROP FOREIGN KEY formulations_current_version_id_foreign");
        } catch (\Exception $e) {
            // Foreign key mungkin tidak ada
        }
    }
}
