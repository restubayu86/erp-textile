<?php

/**
 * horizontalbar.php
 * Top navigation bar — ERP Textile Dyeing & Finishing
 * Variabel yang dibutuhkan dari Controller / BaseController:
 *   $user              → auth()->getUser()
 *   $user_identities   → $user->getIdentities() atau hasil query identitas
 */
$currentUser       = auth()->getUser();
$displayName       = $user_identities[0]->name ?? $currentUser->username ?? 'User';
?>
<nav class="navbar navbar-top navbar-slim fixed-top navbar-expand" id="topNavSlim" data-navbar-appearance="darker">
    <div class="collapse navbar-collapse justify-content-between">

        <!-- ── Logo & Hamburger ── -->
        <div class="navbar-logo">
            <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarVerticalCollapse"
                aria-controls="navbarVerticalCollapse"
                aria-expanded="false"
                aria-label="Toggle Navigation">
                <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
            </button>
            <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
                ERP <span class="text-body-highlight d-none d-sm-inline">Textile</span>
            </a>
        </div>

        <!-- ── Right Icons ── -->
        <ul class="navbar-nav navbar-nav-icons flex-row">

            <!-- Dark / Light Toggle -->
            <li class="nav-item">
                <div class="theme-control-toggle fa-ion-wait pe-2 theme-control-toggle-slim">
                    <input class="form-check-input ms-0 theme-control-toggle-input"
                        id="themeControlToggle"
                        type="checkbox"
                        data-theme-control="phoenixTheme"
                        value="dark">
                    <label class="mb-0 theme-control-toggle-label theme-control-toggle-light"
                        for="themeControlToggle"
                        data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to Dark">
                        <span class="d-none d-sm-flex flex-center" style="height:16px;width:16px;">
                            <span class="me-1 icon" data-feather="moon"></span>
                        </span>
                        <span class="fs-9 fw-bold">Dark</span>
                    </label>
                    <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark"
                        for="themeControlToggle"
                        data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to Light">
                        <span class="d-none d-sm-flex flex-center" style="height:16px;width:16px;">
                            <span class="me-1 icon" data-feather="sun"></span>
                        </span>
                        <span class="fs-9 fw-bold">Light</span>
                    </label>
                </div>
            </li>

            <!-- Notifikasi (placeholder — bisa dihubungkan ke sistem notifikasi nanti) -->
            <li class="nav-item dropdown">
                <a class="nav-link" id="navbarDropdownNotification" href="#"
                    role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="d-inline-block" style="height:12px;width:12px;">
                        <span data-feather="bell" style="height:12px;width:12px;"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border navbar-dropdown-caret"
                    aria-labelledby="navbarDropdownNotification">
                    <div class="card position-relative border-0">
                        <div class="card-header p-2">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-body-emphasis mb-0">Notifikasi</h5>
                                <button class="btn btn-link p-0 fs-9 fw-normal" type="button">
                                    Tandai semua dibaca
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="scrollbar-overlay" style="height:14rem;">
                                <div class="px-3 py-4 text-center text-body-tertiary fs-9">
                                    <span data-feather="bell-off" class="mb-2"></span>
                                    <p class="mb-0">Belum ada notifikasi</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-0 border-top">
                            <div class="my-2 text-center fw-bold fs-10 text-body-tertiary">
                                <a class="fw-bolder" href="#">Lihat semua notifikasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link lh-1 pe-0 white-space-nowrap"
                    id="navbarDropdownUser" href="#!"
                    role="button" data-bs-toggle="dropdown"
                    aria-haspopup="true" data-bs-auto-close="outside" aria-expanded="false">
                    <?= esc((string)$displayName) ?>
                    <span class="d-inline-block" style="height:10.2px;width:10.2px;">
                        <span class="fa-solid fa-chevron-down fs-10"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border"
                    aria-labelledby="navbarDropdownUser">
                    <div class="card position-relative border-0">
                        <div class="card-body p-0">
                            <div class="text-center pt-4 pb-3">
                                <div class="avatar avatar-xl">
                                    <div class="avatar-name rounded-circle bg-soft-primary">
                                        <span class="fs-6 fw-bold text-primary">
                                            <?= strtoupper(substr($displayName, 0, 1)) ?>
                                        </span>
                                    </div>
                                </div>
                                <h6 class="mt-2 text-body-emphasis"><?= esc((string)$displayName) ?></h6>
                                <p class="fs-10 text-body-tertiary mb-0"><?= esc((string)$currentUser->email ?? '') ?></p>
                            </div>
                        </div>
                        <div class="overflow-auto scrollbar" style="height:6rem;">
                            <ul class="nav d-flex flex-column mb-2 pb-1">
                                <li class="nav-item">
                                    <a class="nav-link px-3 d-block" href="<?= base_url('profile') ?>">
                                        <span class="me-2 text-body align-bottom" data-feather="user"></span>
                                        <span>Profil Saya</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-3 d-block" href="<?= base_url('profile/password') ?>">
                                        <span class="me-2 text-body align-bottom" data-feather="lock"></span>
                                        <span>Ganti Password</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer p-0 border-top border-translucent">
                            <div class="p-3">
                                <form action="<?= site_url('logout') ?>" method="post" class="w-100">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-phoenix-secondary d-flex flex-center w-100">
                                        <span class="me-2" data-feather="log-out"></span>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</nav>