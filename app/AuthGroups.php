<?php

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    public string $defaultGroup = 'viewer';

    public array $groups = [
        'superadmin'          => ['title' => 'Super Admin',          'description' => 'Akses penuh semua modul'],
        'admin'               => ['title' => 'Admin',                'description' => 'Akses penuh kecuali setting sistem'],
        'hrm_manager'         => ['title' => 'HRM Manager',          'description' => 'Kelola HRM'],
        'production_manager'  => ['title' => 'Production Manager',   'description' => 'Kelola Production'],
        'production_operator' => ['title' => 'Production Operator',  'description' => 'Input WO & checksheet'],
        'warehouse_manager'   => ['title' => 'Warehouse Manager',    'description' => 'Kelola Warehouse'],
        'warehouse_operator'  => ['title' => 'Warehouse Operator',   'description' => 'Input transaksi stok'],
        'viewer'              => ['title' => 'Viewer',               'description' => 'Read-only semua modul'],
    ];

    public array $permissions = [
        'hrm.departments.view'   => 'Lihat departemen',
        'hrm.departments.create' => 'Tambah departemen',
        'hrm.departments.edit'   => 'Edit departemen',
        'hrm.departments.delete' => 'Hapus departemen',

        'hrm.positions.view'     => 'Lihat jabatan',
        'hrm.positions.create'   => 'Tambah jabatan',
        'hrm.positions.edit'     => 'Edit jabatan',
        'hrm.positions.delete'   => 'Hapus jabatan',

        'hrm.employees.view'     => 'Lihat karyawan',
        'hrm.employees.create'   => 'Tambah karyawan',
        'hrm.employees.edit'     => 'Edit karyawan',
        'hrm.employees.delete'   => 'Hapus karyawan',

        'production.work_orders.view'   => 'Lihat work order',
        'production.work_orders.create' => 'Buat work order',
        'production.work_orders.edit'   => 'Edit work order',
        'production.work_orders.delete' => 'Hapus work order',

        'production.checksheets.view'   => 'Lihat checksheet',
        'production.checksheets.submit' => 'Submit checksheet',

        'production.machines.view'      => 'Lihat mesin',
        'production.machines.manage'    => 'Kelola mesin',

        'production.reports.view'       => 'Lihat laporan produksi',

        'warehouse.chemical_categories.view'   => 'Lihat kategori kimia',
        'warehouse.chemical_categories.create' => 'Tambah kategori kimia',
        'warehouse.chemical_categories.edit'   => 'Edit kategori kimia',
        'warehouse.chemical_categories.delete' => 'Hapus kategori kimia',

        'warehouse.chemicals.view'      => 'Lihat bahan kimia',
        'warehouse.chemicals.create'    => 'Tambah bahan kimia',
        'warehouse.chemicals.edit'      => 'Edit bahan kimia',
        'warehouse.chemicals.delete'    => 'Hapus bahan kimia',
        'warehouse.chemicals.manage'    => 'Kelola bahan kimia',
        'warehouse.chemicals.adjust'    => 'Adjust stok kimia',

        'warehouse.warehouses.view'     => 'Lihat gudang',
        'warehouse.warehouses.create'   => 'Tambah gudang',
        'warehouse.warehouses.edit'     => 'Edit gudang',
        'warehouse.warehouses.delete'   => 'Hapus gudang',

        'warehouse.periods.view'        => 'Lihat periode produksi',
        'warehouse.periods.create'      => 'Tambah periode produksi',
        'warehouse.periods.edit'        => 'Edit periode produksi',
        'warehouse.periods.delete'      => 'Hapus periode produksi',

        'warehouse.stock_opening.view'   => 'Lihat stok awal periode',
        'warehouse.stock_opening.create' => 'Input/ubah stok awal periode',

        'warehouse.formulations.view'   => 'Lihat formulasi',
        'warehouse.formulations.manage' => 'Kelola formulasi',

        'warehouse.stocks.view'         => 'Lihat stok',
        'warehouse.stocks.receive'      => 'Terima stok',
        'warehouse.stocks.issue'        => 'Keluarkan stok',
        'warehouse.stocks.transfer'     => 'Transfer stok antar gudang',

        'access.users.view'   => 'Lihat daftar user',
        'access.users.create' => 'Tambah user',
        'access.users.edit'   => 'Edit user & assign group',
        'access.users.delete' => 'Hapus user',
    ];

    public array $matrix = [
        'superadmin'          => ['*'],
        'admin'               => ['hrm.*', 'production.*', 'warehouse.*'],
        'hrm_manager'         => ['hrm.departments.*', 'hrm.positions.*', 'hrm.employees.*'],
        'production_manager'  => ['production.*'],
        'production_operator' => [
            'production.work_orders.view',
            'production.checksheets.view',
            'production.checksheets.submit',
            'production.machines.view',
        ],
        'warehouse_manager'   => ['warehouse.*'],
        'warehouse_operator'  => [
            'warehouse.chemicals.view',
            'warehouse.chemical_categories.view',
            'warehouse.warehouses.view',
            'warehouse.periods.view',
            'warehouse.stock_opening.view',
            'warehouse.formulations.view',
            'warehouse.stocks.view',
            'warehouse.stocks.receive',
            'warehouse.stocks.issue',
            'warehouse.stocks.transfer',
        ],
        'viewer' => [
            'hrm.departments.view',
            'hrm.positions.view',
            'hrm.employees.view',
            'production.work_orders.view',
            'production.checksheets.view',
            'production.machines.view',
            'production.reports.view',
            'warehouse.chemicals.view',
            'warehouse.chemical_categories.view',
            'warehouse.warehouses.view',
            'warehouse.periods.view',
            'warehouse.stock_opening.view',
            'warehouse.formulations.view',
            'warehouse.stocks.view',
        ],
    ];
}
