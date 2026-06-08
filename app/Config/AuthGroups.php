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

        'warehouse.chemicals.view'      => 'Lihat bahan kimia',
        'warehouse.chemicals.manage'    => 'Kelola bahan kimia',
        'warehouse.chemicals.adjust'    => 'Adjust stok kimia',

        'warehouse.formulations.view'   => 'Lihat formulasi',
        'warehouse.formulations.manage' => 'Kelola formulasi',

        'warehouse.stocks.view'         => 'Lihat stok',
        'warehouse.stocks.receive'      => 'Terima stok',
        'warehouse.stocks.issue'        => 'Keluarkan stok',
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
            'warehouse.stocks.view',
            'warehouse.stocks.receive',
            'warehouse.stocks.issue',
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
            'warehouse.formulations.view',
            'warehouse.stocks.view',
        ],
    ];
}
