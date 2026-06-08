<?php

/**
 * auth_helper.php
 * Helper functions untuk CodeIgniter Shield
 *
 * Lokasi: app/Helpers/auth_helper.php
 *
 * Cara load (di BaseController atau autoload):
 *   helper('auth');
 *
 * Atau tambahkan di app/Config/Autoload.php:
 *   public $helpers = ['auth'];
 */

if (! function_exists('canDo')) {
    /**
     * Cek apakah user yang sedang login memiliki permission tertentu.
     * Shorthand untuk auth()->user()->can($permission)
     *
     * @param  string $permission  Format: 'modul.resource.action'
     * @return bool
     *
     * @example
     *   canDo('hrm.employees.view')         → true/false
     *   canDo('warehouse.stocks.receipt')   → true/false
     *   canDo('user.view')                  → true/false
     */
    function canDo(string $permission): bool
    {
        $user = auth()->user();

        if ($user === null) {
            return false;
        }

        // Superadmin selalu bisa melakukan apapun
        if ($user->inGroup('superadmin')) {
            return true;
        }

        return $user->can($permission);
    }
}

if (! function_exists('canAny')) {
    /**
     * Cek apakah user memiliki salah satu dari beberapa permission.
     * Berguna untuk menampilkan section menu jika ada minimal satu akses.
     *
     * @param  string[] $permissions  Array of permission strings
     * @return bool
     *
     * @example
     *   canAny(['hrm.employees.view', 'hrm.departments.view'])
     */
    function canAny(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (canDo($permission)) {
                return true;
            }
        }
        return false;
    }
}

if (! function_exists('canAll')) {
    /**
     * Cek apakah user memiliki semua permission yang diberikan.
     *
     * @param  string[] $permissions
     * @return bool
     */
    function canAll(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (! canDo($permission)) {
                return false;
            }
        }
        return true;
    }
}

if (! function_exists('currentUserName')) {
    /**
     * Ambil nama display user yang sedang login.
     * Prioritas: full name dari identitas → username → 'Guest'
     *
     * @return string
     */
    function currentUserName(): string
    {
        $user = auth()->user();

        if ($user === null) {
            return 'Guest';
        }

        // Coba ambil dari email_first identity (nama lengkap)
        $identities = $user->getIdentities('email');
        if (! empty($identities) && ! empty($identities[0]->name)) {
            return $identities[0]->name;
        }

        return $user->username ?? 'User';
    }
}
