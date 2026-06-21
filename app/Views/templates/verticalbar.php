<?php

/**
 * verticalbar.php
 * Sidebar navigasi vertikal — ERP Textile Dyeing & Finishing
 *
 * Sidebar dibuat DINAMIS: setiap section modul hanya tampil
 * jika user memiliki minimal satu permission di modul tersebut.
 *
 * Helper canDo($permission) → auth()->user()->can($permission)
 * Didefinisikan di app/Helpers/auth_helper.php
 *
 * Konvensi active link: cek URI segment dengan service('uri')
 */

$uri        = service('uri');
$segment1   = $uri->getSegment(1); // hrm | production | warehouse | access
$segment2   = $uri->getSegment(2); // employees | departments | dll

/**
 * Helper inline: cek apakah link aktif
 * Mengembalikan class 'active' jika segment cocok
 */
function navActive(string $seg1, string $seg2 = ''): string
{
    $uri = service('uri');
    $match1 = $uri->getSegment(1) === $seg1;
    $match2 = $seg2 === '' || $uri->getSegment(2) === $seg2;
    return ($match1 && $match2) ? ' active' : '';
}
?>

<nav class="navbar navbar-vertical navbar-expand-lg" style="display:none;">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">

                <!-- ================================================
                     DASHBOARD UTAMA
                ================================================ -->
                <li class="nav-item">
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1<?= navActive('dashboard') ?>"
                            href="<?= base_url('dashboard') ?>" role="button">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon">
                                    <span data-feather="home"></span>
                                </span>
                                <span class="nav-link-text-wrapper">
                                    <span class="nav-link-text">Dashboard</span>
                                </span>
                            </div>
                        </a>
                    </div>
                </li>

                <!-- ================================================
                     MODUL: HRM
                     Tampil jika user punya permission hrm.*
                ================================================ -->
                <?php if (canDo('hrm.employees.view') || canDo('hrm.departments.view') || canDo('hrm.positions.view')): ?>
                    <li class="nav-item">
                        <p class="navbar-vertical-label">HRM</p>
                        <hr class="navbar-vertical-line">

                        <!-- Karyawan -->
                        <?php if (canDo('hrm.employees.view')): ?>
                            <div class="nav-item-wrapper">
                                <a class="nav-link label-1<?= navActive('hrm', 'employees') ?>"
                                    href="<?= base_url('hrm/employees') ?>">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span data-feather="users"></span></span>
                                        <span class="nav-link-text-wrapper">
                                            <span class="nav-link-text">Karyawan</span>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Departemen & Jabatan (grouped) -->
                        <?php if (canDo('hrm.departments.view') || canDo('hrm.positions.view')): ?>
                            <div class="nav-item-wrapper">
                                <a class="nav-link dropdown-indicator label-1"
                                    href="#nv-hrm-master"
                                    data-bs-toggle="collapse" aria-expanded="<?= ($segment1 === 'hrm' && in_array($segment2, ['departments', 'positions'])) ? 'true' : 'false' ?>"
                                    aria-controls="nv-hrm-master">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon-wrapper">
                                            <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                        </div>
                                        <span class="nav-link-icon"><span data-feather="folder"></span></span>
                                        <span class="nav-link-text">Master Data</span>
                                    </div>
                                </a>
                                <div class="parent-wrapper label-1">
                                    <ul class="nav collapse parent <?= ($segment1 === 'hrm' && in_array($segment2, ['departments', 'positions'])) ? 'show' : '' ?>"
                                        data-bs-parent="#navbarVerticalCollapse"
                                        id="nv-hrm-master">
                                        <li class="collapsed-nav-item-title d-none">Master Data HRM</li>

                                        <?php if (canDo('hrm.departments.view')): ?>
                                            <li class="nav-item">
                                                <a class="nav-link<?= navActive('hrm', 'departments') ?>"
                                                    href="<?= base_url('hrm/departments') ?>">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Departemen</span>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if (canDo('hrm.positions.view')): ?>
                                            <li class="nav-item">
                                                <a class="nav-link<?= navActive('hrm', 'positions') ?>"
                                                    href="<?= base_url('hrm/positions') ?>">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Jabatan</span>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                    </li>
                <?php endif; ?>

                <!-- ================================================
                     MODUL: PRODUCTION
                     Tampil jika user punya permission production.*
                ================================================ -->
                <?php if (canDo('production.work_orders.view') || canDo('production.machines.view') || canDo('production.checksheets.view')): ?>
                    <li class="nav-item">
                        <p class="navbar-vertical-label">PRODUCTION</p>
                        <hr class="navbar-vertical-line">

                        <!-- Dashboard Production -->
                        <div class="nav-item-wrapper">
                            <a class="nav-link label-1<?= navActive('production', 'dashboard') ?>"
                                href="<?= base_url('production/dashboard') ?>">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span data-feather="pie-chart"></span></span>
                                    <span class="nav-link-text-wrapper">
                                        <span class="nav-link-text">Dashboard</span>
                                    </span>
                                </div>
                            </a>
                        </div>

                        <!-- Work Order -->
                        <?php if (canDo('production.work_orders.view')): ?>
                            <div class="nav-item-wrapper">
                                <a class="nav-link dropdown-indicator label-1"
                                    href="#nv-work-order"
                                    data-bs-toggle="collapse"
                                    aria-expanded="<?= ($segment1 === 'production' && $segment2 === 'work-orders') ? 'true' : 'false' ?>"
                                    aria-controls="nv-work-order">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon-wrapper">
                                            <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                        </div>
                                        <span class="nav-link-icon"><span data-feather="clipboard"></span></span>
                                        <span class="nav-link-text">Work Order</span>
                                    </div>
                                </a>
                                <div class="parent-wrapper label-1">
                                    <ul class="nav collapse parent <?= ($segment1 === 'production' && $segment2 === 'work-orders') ? 'show' : '' ?>"
                                        data-bs-parent="#navbarVerticalCollapse"
                                        id="nv-work-order">
                                        <li class="collapsed-nav-item-title d-none">Work Order</li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= base_url('production/work-orders') ?>">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text">Daftar WO</span>
                                                </div>
                                            </a>
                                        </li>
                                        <?php if (canDo('production.work_orders.create')): ?>
                                            <li class="nav-item">
                                                <a class="nav-link" href="<?= base_url('production/work-orders/create') ?>">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Buat WO</span>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Checksheet -->
                        <?php if (canDo('production.checksheets.view')): ?>
                            <div class="nav-item-wrapper">
                                <a class="nav-link label-1<?= navActive('production', 'checksheets') ?>"
                                    href="<?= base_url('production/checksheets') ?>">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span data-feather="check-square"></span></span>
                                        <span class="nav-link-text-wrapper">
                                            <span class="nav-link-text">Checksheet</span>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Laporan Produksi -->
                        <?php if (canDo('production.reports.view')): ?>
                            <div class="nav-item-wrapper">
                                <a class="nav-link label-1<?= navActive('production', 'reports') ?>"
                                    href="<?= base_url('production/reports') ?>">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span data-feather="file-text"></span></span>
                                        <span class="nav-link-text-wrapper">
                                            <span class="nav-link-text">Laporan</span>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Master Data Production -->
                        <?php if (canDo('production.machines.view')): ?>
                            <hr class="navbar-vertical-line">
                            <div class="nav-item-wrapper">
                                <a class="nav-link dropdown-indicator label-1"
                                    href="#nv-production-master"
                                    data-bs-toggle="collapse"
                                    aria-expanded="<?= ($segment1 === 'production' && $segment2 === 'master') ? 'true' : 'false' ?>"
                                    aria-controls="nv-production-master">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon-wrapper">
                                            <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                        </div>
                                        <span class="nav-link-icon"><span data-feather="folder"></span></span>
                                        <span class="nav-link-text">Master Data</span>
                                    </div>
                                </a>
                                <div class="parent-wrapper label-1">
                                    <ul class="nav collapse parent <?= ($segment1 === 'production' && $segment2 === 'master') ? 'show' : '' ?>"
                                        data-bs-parent="#navbarVerticalCollapse"
                                        id="nv-production-master">
                                        <li class="collapsed-nav-item-title d-none">Master Production</li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= base_url('production/master/machines') ?>">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text">Mesin</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= base_url('production/master/fabrics') ?>">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text">Kain / Desain</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= base_url('production/master/processes') ?>">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text">Proses Produksi</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                    </li>
                <?php endif; ?>

                <!-- ================================================
                     MODUL: WAREHOUSE
                     Tampil jika user punya permission warehouse.*
                ================================================ -->
                <?php if (canDo('warehouse.chemicals.view') || canDo('warehouse.formulations.view') || canDo('warehouse.stocks.view')): ?>
                    <li class="nav-item">
                        <p class="navbar-vertical-label">WAREHOUSE</p>
                        <hr class="navbar-vertical-line">

                        <!-- Dashboard Warehouse -->
                        <div class="nav-item-wrapper">
                            <a class="nav-link label-1<?= navActive('warehouse', 'dashboard') ?>"
                                href="<?= base_url('warehouse/dashboard') ?>">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span data-feather="pie-chart"></span></span>
                                    <span class="nav-link-text-wrapper">
                                        <span class="nav-link-text">Dashboard</span>
                                    </span>
                                </div>
                            </a>
                        </div>

                        <!-- Stok Kimia -->
                        <?php if (canDo('warehouse.stocks.view')): ?>
                            <div class="nav-item-wrapper">
                                <a class="nav-link dropdown-indicator label-1"
                                    href="#nv-wh-stock"
                                    data-bs-toggle="collapse"
                                    aria-expanded="<?= ($segment1 === 'warehouse' && $segment2 === 'stocks') ? 'true' : 'false' ?>"
                                    aria-controls="nv-wh-stock">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon-wrapper">
                                            <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                        </div>
                                        <span class="nav-link-icon"><span data-feather="box"></span></span>
                                        <span class="nav-link-text">Stok Kimia</span>
                                    </div>
                                </a>
                                <div class="parent-wrapper label-1">
                                    <ul class="nav collapse parent <?= ($segment1 === 'warehouse' && $segment2 === 'stocks') ? 'show' : '' ?>"
                                        data-bs-parent="#navbarVerticalCollapse"
                                        id="nv-wh-stock">
                                        <li class="collapsed-nav-item-title d-none">Stok Kimia</li>

                                        <!-- Transaksi -->
                                        <li class="nav-item">
                                            <a class="nav-link dropdown-indicator"
                                                href="#nv-wh-stock-transaksi"
                                                data-bs-toggle="collapse" aria-expanded="false"
                                                aria-controls="nv-wh-stock-transaksi">
                                                <div class="d-flex align-items-center">
                                                    <div class="dropdown-indicator-icon-wrapper">
                                                        <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                                    </div>
                                                    <span class="nav-link-text">Transaksi</span>
                                                </div>
                                            </a>
                                            <div class="parent-wrapper">
                                                <ul class="nav collapse parent" id="nv-wh-stock-transaksi">
                                                    <?php if (canDo('warehouse.stocks.receipt')): ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="<?= base_url('warehouse/stocks/receipt') ?>">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="nav-link-text">Penerimaan</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (canDo('warehouse.stocks.issue')): ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="<?= base_url('warehouse/stocks/issue') ?>">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="nav-link-text">Pengeluaran</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (canDo('warehouse.stocks.adjust')): ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="<?= base_url('warehouse/stocks/adjustment') ?>">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="nav-link-text">Penyesuaian</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </li>

                                        <!-- Laporan Stok -->
                                        <li class="nav-item">
                                            <a class="nav-link dropdown-indicator"
                                                href="#nv-wh-stock-laporan"
                                                data-bs-toggle="collapse" aria-expanded="false"
                                                aria-controls="nv-wh-stock-laporan">
                                                <div class="d-flex align-items-center">
                                                    <div class="dropdown-indicator-icon-wrapper">
                                                        <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                                    </div>
                                                    <span class="nav-link-text">Laporan</span>
                                                </div>
                                            </a>
                                            <div class="parent-wrapper">
                                                <ul class="nav collapse parent" id="nv-wh-stock-laporan">
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="<?= base_url('warehouse/stocks/stock-card') ?>">
                                                            <div class="d-flex align-items-center">
                                                                <span class="nav-link-text">Kartu Stok</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="<?= base_url('warehouse/stocks/position') ?>">
                                                            <div class="d-flex align-items-center">
                                                                <span class="nav-link-text">Posisi Stok</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Formulasi -->
                        <?php if (canDo('warehouse.formulations.view')): ?>
                            <div class="nav-item-wrapper">
                                <a class="nav-link dropdown-indicator label-1"
                                    href="#nv-wh-formulation"
                                    data-bs-toggle="collapse"
                                    aria-expanded="<?= ($segment1 === 'warehouse' && $segment2 === 'formulations') ? 'true' : 'false' ?>"
                                    aria-controls="nv-wh-formulation">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon-wrapper">
                                            <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                        </div>
                                        <span class="nav-link-icon"><span data-feather="git-merge"></span></span>
                                        <span class="nav-link-text">Formulasi</span>
                                    </div>
                                </a>
                                <div class="parent-wrapper label-1">
                                    <ul class="nav collapse parent <?= ($segment1 === 'warehouse' && $segment2 === 'formulations') ? 'show' : '' ?>"
                                        data-bs-parent="#navbarVerticalCollapse"
                                        id="nv-wh-formulation">
                                        <li class="collapsed-nav-item-title d-none">Formulasi</li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= base_url('warehouse/formulations') ?>">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text">Daftar Formulasi</span>
                                                </div>
                                            </a>
                                        </li>
                                        <?php if (canDo('warehouse.formulations.create')): ?>
                                            <li class="nav-item">
                                                <a class="nav-link" href="<?= base_url('warehouse/formulations/create') ?>">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Buat Formulasi</span>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= base_url('warehouse/formulations/categories') ?>">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text">Kategori Formula</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Master Data Warehouse -->
                        <?php if (canDo('warehouse.chemicals.view')): ?>
                            <hr class="navbar-vertical-line">
                            <div class="nav-item-wrapper">
                                <a class="nav-link dropdown-indicator label-1"
                                    href="#nv-wh-master"
                                    data-bs-toggle="collapse"
                                    aria-expanded="<?= ($segment1 === 'warehouse' && $segment2 === 'master') ? 'true' : 'false' ?>"
                                    aria-controls="nv-wh-master">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon-wrapper">
                                            <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                        </div>
                                        <span class="nav-link-icon"><span data-feather="folder"></span></span>
                                        <span class="nav-link-text">Master Data</span>
                                    </div>
                                </a>
                                <div class="parent-wrapper label-1">
                                    <ul class="nav collapse parent <?= ($segment1 === 'warehouse' && $segment2 === 'master') ? 'show' : '' ?>"
                                        data-bs-parent="#navbarVerticalCollapse"
                                        id="nv-wh-master">
                                        <li class="collapsed-nav-item-title d-none">Master Warehouse</li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= base_url('warehouse/master/chemicals') ?>">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text">Data Kimia</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= base_url('warehouse/master/chemical-categories') ?>">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text">Kategori Kimia</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= base_url('warehouse/master/warehouses') ?>">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text">Gudang</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= base_url('warehouse/master/units') ?>">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text">Satuan</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                    </li>
                <?php endif; ?>

                <!-- ================================================
                     MANAJEMEN HAK AKSES
                     Tampil jika user punya permission user.view
                ================================================ -->
                <?php if (canDo('user.view')): ?>
                    <li class="nav-item">
                        <p class="navbar-vertical-label">HAK AKSES</p>
                        <hr class="navbar-vertical-line">

                        <div class="nav-item-wrapper">
                            <a class="nav-link dropdown-indicator label-1"
                                href="#nv-access"
                                data-bs-toggle="collapse"
                                aria-expanded="<?= ($segment1 === 'access') ? 'true' : 'false' ?>"
                                aria-controls="nv-access">
                                <div class="d-flex align-items-center">
                                    <div class="dropdown-indicator-icon-wrapper">
                                        <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                    </div>
                                    <span class="nav-link-icon"><span data-feather="shield"></span></span>
                                    <span class="nav-link-text">Manajemen Akses</span>
                                </div>
                            </a>
                            <div class="parent-wrapper label-1">
                                <ul class="nav collapse parent <?= ($segment1 === 'access') ? 'show' : '' ?>"
                                    data-bs-parent="#navbarVerticalCollapse"
                                    id="nv-access">
                                    <li class="collapsed-nav-item-title d-none">Manajemen Akses</li>

                                    <li class="nav-item">
                                        <a class="nav-link<?= navActive('access', 'users') ?>"
                                            href="<?= base_url('access/users') ?>">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text">Pengguna</span>
                                            </div>
                                        </a>
                                    </li>

                                    <?php if (canDo('permission.view')): ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?= navActive('access', 'permissions') ?>"
                                                href="<?= base_url('access/permissions') ?>">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-text">Permissions</span>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text">Log Login</span>
                                                <span class="badge ms-2 badge-phoenix badge-phoenix-warning nav-link-badge">Soon</span>
                                            </div>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>

    <!-- Collapse Button -->
    <div class="navbar-vertical-footer">
        <button class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center">
            <span class="uil uil-left-arrow-to-left fs-8"></span>
            <span class="uil uil-arrow-from-right fs-8"></span>
            <span class="navbar-vertical-footer-text ms-2">Collapsed View</span>
        </button>
    </div>
</nav>