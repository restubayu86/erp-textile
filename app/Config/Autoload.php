<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTO-LOADER CONFIGURATION
 * -------------------------------------------------------------------
 * Namespace PSR-4 dan classmap untuk CodeIgniter 4.
 * Tambahan: namespace untuk setiap modul HMVC ERP Textile.
 */
class Autoload extends AutoloadConfig
{
    /**
     * PSR-4 Namespaces
     * Format: 'Namespace' => '/path/to/folder'
     */
    public $psr4 = [
        APP_NAMESPACE            => APPPATH,
        'Config'                 => APPPATH . 'Config',

        // ── Modul HMVC ERP Textile ──────────────────────────────
        'App\Modules\HRM'        => APPPATH . 'Modules/HRM',
        'App\Modules\Production' => APPPATH . 'Modules/Production',
        'App\Modules\Warehouse'  => APPPATH . 'Modules/Warehouse',

        // ── Database Migrations for Modules ──────────────────────
        'App\Modules\HRM\Database\Migrations'        => APPPATH . 'Modules/HRM/Database/Migrations',
        'App\Modules\Production\Database\Migrations' => APPPATH . 'Modules/Production/Database/Migrations',
        'App\Modules\Warehouse\Database\Migrations'  => APPPATH . 'Modules/Warehouse/Database/Migrations',

        // Tambahkan modul baru di sini mengikuti pola yang sama:
        // 'App\Modules\NamaModul' => APPPATH . 'Modules/NamaModul',
    ];

    /**
     * Class Map
     * Untuk class yang tidak mengikuti standar PSR-4.
     * Kosongkan jika tidak ada.
     */
    public $classmap = [];

    /**
     * Files Map
     * File PHP yang di-include secara otomatis (bukan class).
     */
    public $files = [];

    /**
     * Helpers yang di-load otomatis di seluruh aplikasi.
     * 'auth' → app/Helpers/auth_helper.php (canDo, canAny, dll)
     */
    public $helpers = [
        'url',
        'auth',     // helper canDo(), canAny(), currentUserName()
        'setting',
    ];
}
