<?php

/**
 * warehouse_helper.php
 *
 * Helper untuk pembatasan akses gudang berdasarkan role & departemen user.
 *
 * Konsep:
 *  - Role "Warehouse Operator" (grup Shield: warehouse_operator) HANYA boleh
 *    melihat/transaksi di gudang yang department_id-nya SAMA dengan departemen
 *    dia sendiri (lewat employees.position_id -> positions.department_id).
 *  - Role lain (superadmin, admin, warehouse_manager, dst) TIDAK dibatasi —
 *    tetap lihat semua gudang seperti biasa, sesuai permission masing-masing.
 *
 * Dipanggil dari BaseController::initController() sekali per-request, hasilnya
 * dipakai untuk:
 *   1. Auto-filter endpoint select2 gudang (WarehouseController::select2)
 *   2. Guard di controller transaksi (StockController) sebelum simpan/baca data
 *   3. Dikirim ke frontend (warehouse_scope) untuk auto-select & lock dropdown
 *
 * Lokasi: app/Helpers/warehouse_helper.php
 * Cara load: helper('warehouse')
 */

if (! function_exists('userWarehouseScope')) {
    /**
     * Hitung daftar id gudang yang boleh diakses user yang sedang login.
     *
     * @param  \CodeIgniter\Shield\Entities\User|null $user      auth()->getUser()
     * @param  array|null                              $employee row employees (+department_id) milik user, dari BaseController
     * @return array<int>|null   null = TIDAK dibatasi (lihat semua gudang).
     *                           array = HANYA boleh gudang dengan id di dalamnya (bisa array kosong).
     */
    function userWarehouseScope($user, ?array $employee): ?array
    {
        if ($user === null) {
            return [];
        }

        // Role dengan akses penuh — cek lebih dulu supaya user yang kebetulan
        // punya >1 grup (mis. warehouse_manager + warehouse_operator) tetap
        // dianggap tidak dibatasi.
        foreach (['superadmin', 'admin', 'warehouse_manager'] as $unrestrictedGroup) {
            if ($user->inGroup($unrestrictedGroup)) {
                return null;
            }
        }

        // Selain Warehouse Operator, fitur ini tidak mengatur apa-apa —
        // biarkan permission per-menu (canDo) yang menentukan.
        if (! $user->inGroup('warehouse_operator')) {
            return null;
        }

        // Operator tapi akun belum terhubung ke data karyawan/departemen —
        // JANGAN kasih akses buta ke semua gudang, lebih aman kembalikan kosong
        // (operator akan lihat "Anda belum terhubung ke departemen manapun").
        if (empty($employee['department_id'])) {
            return [];
        }

        $db = \Config\Database::connect();
        $rows = $db->table('warehouses')
            ->select('id')
            ->where('department_id', (int) $employee['department_id'])
            ->where('status', 'Active')
            ->where('deleted_at', null)
            ->get()->getResultArray();

        return array_map('intval', array_column($rows, 'id'));
    }
}

if (! function_exists('warehouseAccessAllowed')) {
    /**
     * Cek apakah 1 id gudang termasuk yang boleh diakses, berdasarkan hasil userWarehouseScope().
     *
     * @param  array<int>|null $scope        Hasil userWarehouseScope() — null = unrestricted.
     * @param  int              $warehouseId
     */
    function warehouseAccessAllowed(?array $scope, int $warehouseId): bool
    {
        if ($scope === null) {
            return true;
        }

        return in_array($warehouseId, $scope, true);
    }
}
