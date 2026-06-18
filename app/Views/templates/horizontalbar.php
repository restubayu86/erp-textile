<?php

/**
 * horizontalbar.php
 */

// Load helpers
helper(['text', 'string']);

$currentUser = $user ?? auth()->getUser();
$employeeData = $user_employee ?? null;

// Jika employeeData null, coba load langsung
if (empty($employeeData) && $currentUser && !empty($currentUser->employee_id)) {
    $db = \Config\Database::connect();
    $query = $db->table('employees')
        ->select('employees.*, departments.department as department_name, positions.position as position_name')
        ->join('departments', 'departments.id = employees.department_id', 'left')
        ->join('positions', 'positions.id = employees.position_id', 'left')
        ->where('employees.id', $currentUser->employee_id)
        ->get();

    $employeeData = $query->getRowArray();
}

// Get user groups
$userGroups = [];
if ($currentUser) {
    $db = \Config\Database::connect();
    $groups = $db->table('auth_groups_users')
        ->select('group')
        ->where('user_id', $currentUser->id)
        ->get()
        ->getResultArray();

    $userGroups = array_column($groups, 'group');
}

// Get display name
$displayName = 'User';

// Priority 1: Employee fullname
if ($employeeData && !empty($employeeData['fullname'])) {
    $displayName = ucwords(strtolower($employeeData['fullname']));
}
// Priority 2: Username
elseif ($currentUser && !empty($currentUser->username)) {
    $displayName = ucwords(str_replace(['_', '-'], ' ', $currentUser->username));
}

// Avatar
$avatarPhoto = '';
if ($employeeData && !empty($employeeData['photo'])) {
    $avatarPhoto = base_url('uploads/employees/' . $employeeData['photo']);
}

// Department & Position
$deptName = $employeeData['department_name'] ?? '';
$positionName = $employeeData['position_name'] ?? '';

// Format groups untuk display
$groupLabels = [
    'superadmin' => 'Super Admin',
    'admin' => 'Administrator',
    'manager' => 'Manager',
    'user' => 'User',
    'operator' => 'Operator',
    'staff' => 'Staff'
];

$groupDisplay = array_map(function ($g) use ($groupLabels) {
    return $groupLabels[$g] ?? ucfirst($g);
}, $userGroups);

$groupText = implode(' • ', $groupDisplay);

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

            <!-- Notifikasi -->
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
                <a class="nav-link lh-1 pe-0 white-space-nowrap fs-8 fw-semibold"
                    id="navbarDropdownUser" href="#!"
                    role="button" data-bs-toggle="dropdown"
                    aria-haspopup="true" data-bs-auto-close="outside" aria-expanded="false">

                    <span class="fw-bold fs-8"><?= esc((string)$displayName) ?></span>

                    <span class="d-inline-block" style="height:10.2px;width:10.2px;">
                        <span class="fa-solid fa-chevron-down fs-10"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border"
                    aria-labelledby="navbarDropdownUser">
                    <div class="card position-relative border-0">
                        <div class="card-body p-0">
                            <div class="text-center pt-4 pb-3">
                                <!-- Avatar -->
                                <div style="height:80px; display:flex; align-items:center; justify-content:center; margin-bottom:8px;">
                                    <?php if ($avatarPhoto): ?>
                                        <img src="<?= $avatarPhoto ?>" alt="<?= esc((string)$displayName) ?>"
                                            class="rounded-circle" style="width:80px;height:80px;object-fit:cover;">
                                    <?php else: ?>
                                        <div class="avatar-name rounded-circle bg-soft-primary d-flex align-items-center justify-content-center"
                                            style="width:80px;height:80px;">
                                            <span class="fs-6 fw-bold text-primary">
                                                <?= strtoupper(substr($displayName, 0, 1)) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Nama -->
                                <h5 class="text-body-emphasis fw-bold fs-6 mb-1"><?= esc((string)$displayName) ?></h5>

                                <!-- GROUP -->
                                <?php if (!empty($groupText)): ?>
                                    <div class="mb-1">
                                        <span class="badge bg-soft-primary text-primary fs-9 fw-semibold px-3 py-1">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            <?= esc((string)$groupText) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <!-- Info Employee -->
                                <?php if ($employeeData): ?>
                                    <p class="fs-9 text-body-tertiary mb-1 fw-medium">
                                        <?= esc((string)$positionName ?: 'Posisi tidak tersedia') ?>
                                    </p>
                                    <p class="fs-9 text-body-tertiary mb-0">
                                        <span class="badge bg-soft-info text-info fs-10">
                                            <i class="fas fa-id-card me-1"></i>
                                            <?= esc((string)$employeeData['nik'] ?? '') ?>
                                        </span>
                                        <?php if ($deptName): ?>
                                            <span class="badge bg-soft-primary text-primary fs-10 ms-1">
                                                <i class="fas fa-building me-1"></i>
                                                <?= esc((string)$deptName) ?>
                                            </span>
                                        <?php endif; ?>
                                    </p>
                                <?php else: ?>
                                    <p class="fs-8 text-body-tertiary mb-1">
                                        <?= esc((string)$currentUser->email ?? '') ?>
                                    </p>
                                    <p class="fs-9 text-body-tertiary mb-0">
                                        <span class="badge bg-soft-warning text-warning fs-10">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Belum terhubung ke employee
                                        </span>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="overflow-auto scrollbar" style="height:6rem;">
                            <ul class="nav d-flex flex-column mb-2 pb-1">
                                <li class="nav-item">
                                    <a class="nav-link px-3 d-block fs-8" href="<?= base_url('profile') ?>">
                                        <span class="me-2 text-body align-bottom" data-feather="user"></span>
                                        <span>Profil Saya</span>
                                    </a>
                                </li>
                                <?php if ($employeeData): ?>
                                    <li class="nav-item">
                                        <a class="nav-link px-3 d-block fs-8" href="<?= base_url('hrm/employees/show/' . $employeeData['id']) ?>">
                                            <span class="me-2 text-body align-bottom" data-feather="briefcase"></span>
                                            <span>Data Karyawan</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item">
                                    <a class="nav-link px-3 d-block fs-8" href="<?= base_url('profile/password') ?>">
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
                                    <button type="submit" class="btn btn-phoenix-secondary d-flex flex-center w-100 fs-8 fw-semibold">
                                        <span class="me-2" data-feather="log-out"></span>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </li><!-- User Dropdown -->

        </ul>
    </div>
</nav>