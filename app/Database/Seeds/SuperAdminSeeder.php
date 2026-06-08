<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Models\UserModel;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $users = model(UserModel::class);

        // Cegah duplikat
        if ($users->findByCredentials(['email' => 'admin@erp-textile.local'])) {
            echo "  [SKIP] User superadmin sudah ada.\n";
            return;
        }

        // Buat user
        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => 'superadmin',
            'active'   => 1,
        ]);
        $user->setEmail('admin@erp-textile.local');
        $user->setPassword('Admin@1234');

        $users->save($user);
        $userId = $users->getInsertID();
        $user   = $users->findById($userId);

        // Assign group — Shield simpan di auth_groups_users, bukan tabel custom
        $user->addGroup('superadmin');

        echo "  [+] User superadmin dibuat (ID: {$userId}).\n";
        echo "\n[OK] SuperAdminSeeder selesai.\n";
        echo "     Email    : admin@erp-textile.local\n";
        echo "     Password : Admin@1234\n";
        echo "     (Segera ganti password setelah login pertama!)\n";
    }
}
