<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    body {
        overflow-x: hidden;
    }

    /* ── Stat Cards ───────────────────────────────────────────── */
    .stat-card {
        border: none;
        border-radius: 1rem;
        transition: all .2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .info-label {
        font-size: .65rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--phoenix-secondary-color);
        margin-bottom: .2rem;
    }

    .info-value {
        font-weight: 700;
        font-size: 1.5rem;
        line-height: 1;
    }

    /* ── Avatar ───────────────────────────────────────────────── */
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .85rem;
        flex-shrink: 0;
        background: rgba(var(--phoenix-primary-rgb), .12);
        color: var(--phoenix-primary);
    }

    /* ── Badges ───────────────────────────────────────────────── */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .25rem .55rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-status.active {
        background: rgba(var(--phoenix-success-rgb), .12);
        color: var(--phoenix-success);
        border: 1px solid rgba(var(--phoenix-success-rgb), .25);
    }

    .badge-status.inactive {
        background: rgba(var(--phoenix-secondary-rgb), .12);
        color: var(--phoenix-secondary);
        border: 1px solid rgba(var(--phoenix-secondary-rgb), .25);
    }

    .badge-employee {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .2rem .5rem;
        border-radius: 14px;
        font-size: .7rem;
        font-weight: 600;
        background: rgba(var(--phoenix-success-rgb), .1);
        color: var(--phoenix-success);
        border: 1px solid rgba(var(--phoenix-success-rgb), .2);
        white-space: nowrap;
        max-width: 160px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .badge-group {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        padding: .2rem .5rem;
        border-radius: 14px;
        font-size: .68rem;
        font-weight: 600;
        background: rgba(var(--phoenix-info-rgb), .12);
        color: var(--phoenix-info);
        border: 1px solid rgba(var(--phoenix-info-rgb), .25);
        white-space: nowrap;
    }

    .badge-group.superadmin {
        background: rgba(var(--phoenix-danger-rgb), .12);
        color: var(--phoenix-danger);
        border-color: rgba(var(--phoenix-danger-rgb), .25);
    }

    .badge-group.admin {
        background: rgba(var(--phoenix-warning-rgb), .12);
        color: var(--phoenix-warning);
        border-color: rgba(var(--phoenix-warning-rgb), .25);
    }

    /* ── Filter toggle ────────────────────────────────────────── */
    .filter-toggle {
        position: fixed;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        z-index: 1040;
        border-radius: .75rem 0 0 .75rem !important;
        box-shadow: -2px 0 12px rgba(0, 0, 0, .12);
        text-decoration: none;
        border: 1px solid var(--phoenix-border-color) !important;
        border-right: none !important;
    }

    .filter-toggle .card-body {
        padding: .9rem .5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .45rem;
    }

    .filter-toggle .filter-label {
        writing-mode: vertical-rl;
        font-size: .6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--phoenix-body-color);
        opacity: .5;
    }

    .filter-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--phoenix-danger);
        display: none;
    }

    .filter-toggle.has-filter .filter-dot {
        display: block;
    }

    /* ── DataTables layout ────────────────────────────────────── */
    #user-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #user-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: .375rem 1rem;
        text-align: center;
    }

    #user-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #user-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #user-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #user-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #user-table_wrapper .dataTables_filter label,
    #user-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #user-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 .5rem;
        border-radius: .375rem;
    }

    #user-table_wrapper .dataTables_paginate .paginate_button {
        padding: .375rem .75rem;
        margin: 0 .25rem;
        border-radius: .375rem;
    }

    #user-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #user-table {
        width: 100% !important;
    }

    @media (max-width: 768px) {
        #user-table_wrapper .bottom {
            flex-direction: column;
            align-items: stretch;
        }

        #user-table_wrapper .bottom .dataTables_length,
        #user-table_wrapper .bottom .dataTables_paginate,
        #user-table_wrapper .bottom .dataTables_info {
            text-align: center;
            flex: auto;
        }
    }

    /* ── Validation (is-valid / is-invalid) ──────────────────────── */
    .invalid-feedback {
        display: none;
    }

    .valid-feedback {
        display: none;
    }

    .is-invalid~.invalid-feedback,
    .is-invalid+.invalid-feedback {
        display: block;
    }

    .is-valid~.valid-feedback,
    .is-valid+.valid-feedback {
        display: block;
    }

    .select2-container.s2-is-invalid+.invalid-feedback {
        display: block;
    }

    .select2-container.s2-is-invalid .select2-selection {
        border-color: var(--phoenix-danger) !important;
    }

    .select2-container.s2-is-valid .select2-selection {
        border-color: var(--phoenix-success) !important;
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: 31px;
        font-size: .875rem;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 29px;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 29px;
    }

    .select2-dropdown {
        font-size: .875rem;
    }

    /* ── Groups checklist ─────────────────────────────────────── */
    .group-check-item {
        display: flex;
        align-items: flex-start;
        gap: .6rem;
        padding: .6rem .75rem;
        border: 1px solid var(--phoenix-border-color);
        border-radius: .5rem;
        cursor: pointer;
        transition: all .15s;
    }

    .group-check-item:hover {
        border-color: var(--phoenix-primary);
        background: rgba(var(--phoenix-primary-rgb), .04);
    }

    .group-check-item.checked {
        border-color: var(--phoenix-primary);
        background: rgba(var(--phoenix-primary-rgb), .06);
    }

    .group-check-item input:checked {
        accent-color: var(--phoenix-primary);
    }

    .group-check-title {
        font-weight: 600;
        font-size: .8rem;
    }

    .group-check-item.checked .group-check-title {
        color: var(--phoenix-primary);
    }

    .group-check-desc {
        font-size: .7rem;
        color: var(--phoenix-secondary-color);
    }

    .group-check-disabled {
        opacity: .55;
        cursor: not-allowed;
    }

    /* ── Activity timeline ────────────────────────────────────── */
    .activity-item {
        display: flex;
        gap: .75rem;
        padding: .6rem 0;
        border-bottom: 1px solid var(--phoenix-border-color);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .8rem;
    }

    .activity-icon.success {
        background: rgba(var(--phoenix-success-rgb), .12);
        color: var(--phoenix-success);
    }

    .activity-icon.failed {
        background: rgba(var(--phoenix-danger-rgb), .12);
        color: var(--phoenix-danger);
    }

    .btn-group-sm .btn {
        padding: .5rem .75rem;
        font-size: .7rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$canCreate = canDo('access.users.create');
$canEdit   = canDo('access.users.edit');
$canDelete = canDo('access.users.delete');
?>
<div class="w-100">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <?php foreach ($breadcrumbs as $crumb): ?>
                        <?php if (!empty($crumb['active'])): ?>
                            <li class="breadcrumb-item active"><?= esc((string)(string) $crumb['name']) ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= esc((string)(string) $crumb['name']) ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-1"><?= esc((string)$page_title) ?></h1>
            <p class="text-body-tertiary mb-0"><?= esc((string)$page_description) ?></p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <?php foreach (
            [
                ['id' => 'stat-total',    'label' => 'Total User', 'icon' => 'fa-users',        'color' => 'primary'],
                ['id' => 'stat-active',   'label' => 'Aktif',       'icon' => 'fa-check-circle', 'color' => 'success'],
                ['id' => 'stat-inactive', 'label' => 'Nonaktif',    'icon' => 'fa-times-circle', 'color' => 'secondary'],
                ['id' => 'stat-admin',    'label' => 'Admin & Superadmin', 'icon' => 'fa-user-shield', 'color' => 'danger'],
            ] as $s
        ): ?>
            <div class="col-md-3 col-6">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="info-label"><?= $s['label'] ?></div>
                                <div class="info-value text-<?= $s['color'] ?>" id="<?= $s['id'] ?>">—</div>
                            </div>
                            <div class="stat-icon bg-<?= $s['color'] ?> bg-opacity-10 text-<?= $s['color'] ?>">
                                <span class="fas <?= $s['icon'] ?>"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <div class="d-flex gap-2">
            <?php if ($canDelete): ?>
                <a href="<?= site_url('access/users/trash') ?>" class="btn btn-subtle-danger btn-sm">
                    <span class="fas fa-trash-alt me-1"></span>Sampah
                    <span class="badge bg-danger ms-1 d-none" id="trash-badge">0</span>
                </a>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <?php if ($canCreate): ?>
                <button class="btn btn-primary btn-sm" id="btn-create">
                    <span class="fas fa-user-plus me-1"></span>Tambah User
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="user-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Karyawan</th>
                    <th>Groups</th>
                    <th>Status</th>
                    <th>Terakhir Aktif</th>
                    <th>Dibuat</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- ═══ Filter Toggle ═══════════════════════════════════════════ -->
<a class="card filter-toggle" href="#filter-offcanvas" data-bs-toggle="offcanvas" id="filter-toggle">
    <div class="card-body">
        <span class="fas fa-filter text-primary"></span>
        <span class="filter-label">Filter</span>
        <span class="filter-dot"></span>
    </div>
</a>

<!-- ═══ Filter Offcanvas ════════════════════════════════════════ -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="filter-offcanvas" style="width:320px">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title"><span class="fas fa-filter me-2 text-primary"></span>Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="flex-grow-1">
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Username / Email / Karyawan</label>
                <input type="text" class="form-control form-control-sm" id="filter-name" placeholder="Cari...">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Group</label>
                <select class="form-select form-select-sm" id="filter-group" style="width:100%">
                    <option value="">Semua Group</option>
                    <?php foreach ($groups as $g): ?>
                        <option value="<?= esc((string)$g['key']) ?>"><?= esc((string)$g['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Status</label>
                <select class="form-select form-select-sm" id="filter-status">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
        </div>
        <div id="filter-summary" class="mb-3 d-none">
            <div class="alert alert-subtle-info py-2 px-3 mb-0 fs-10">
                <span class="fas fa-info-circle me-1"></span>
                <span id="filter-summary-text"></span>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary btn-sm" id="btn-apply-filter"><span class="fas fa-search me-1"></span>Terapkan</button>
            <button class="btn btn-subtle-secondary btn-sm" id="btn-reset-filter"><span class="fas fa-times me-1"></span>Reset</button>
        </div>
    </div>
</div>

<!-- ═══ Modal Tambah / Edit User ════════════════════════════════ -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <span class="fas fa-user-cog"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modal-title">Tambah User</h5>
                        <p class="text-muted fs-10 mb-0" id="modal-subtitle">Buat akun pengguna baru</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-subtle-danger py-2 px-3 fs-10 d-none" id="modal-alert">
                    <span class="fas fa-exclamation-triangle me-1"></span>
                    <span id="modal-alert-text"></span>
                </div>
                <form id="user-form" action="#" method="post">
                    <div class="card border mb-3" style="border-radius:.75rem">
                        <div class="card-body p-3">
                            <p class="fs-10 fw-bold text-uppercase text-primary mb-3" style="letter-spacing:.08em">
                                <span class="fas fa-id-card me-1"></span>Informasi Akun
                            </p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-username">
                                        Username <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" id="f-username"
                                        placeholder="cth: budi.santoso" maxlength="30" autocomplete="off">
                                    <div class="invalid-feedback" id="err-username"></div>
                                    <div class="valid-feedback">Username valid</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-email">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control form-control-sm" id="f-email"
                                        placeholder="user@erp-textile.local" maxlength="255" autocomplete="off">
                                    <div class="invalid-feedback" id="err-email"></div>
                                    <div class="valid-feedback">Email valid</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-employee">
                                        Hubungkan ke Karyawan
                                        <span class="badge badge-phoenix badge-phoenix-secondary fs-10 ms-1">opsional</span>
                                    </label>
                                    <select class="form-select form-select-sm" id="f-employee" style="width:100%"></select>
                                    <div class="form-text fs-10">Hanya menampilkan karyawan yang belum terhubung ke user lain</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border mb-0" style="border-radius:.75rem">
                        <div class="card-body p-3">
                            <p class="fs-10 fw-bold text-uppercase text-primary mb-3" style="letter-spacing:.08em">
                                <span class="fas fa-key me-1"></span>Password
                            </p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-password">
                                        Password <span class="text-danger" id="pass-required">*</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <input type="password" class="form-control form-control-sm" id="f-password"
                                            placeholder="Minimal 8 karakter" autocomplete="new-password">
                                        <span class="input-group-text" id="toggle-password" style="cursor:pointer">
                                            <span class="fas fa-eye"></span>
                                        </span>
                                    </div>
                                    <div class="form-text fs-10" id="pass-hint">Minimal 8 karakter</div>
                                    <div class="invalid-feedback" id="err-password"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-password-confirm">
                                        Ulangi Password <span class="text-danger" id="pass-confirm-required">*</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <input type="password" class="form-control form-control-sm" id="f-password-confirm"
                                            placeholder="Ulangi password" autocomplete="new-password">
                                        <span class="input-group-text" id="toggle-password-confirm" style="cursor:pointer">
                                            <span class="fas fa-eye"></span>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback" id="err-password-confirm"></div>
                                    <div class="valid-feedback">Password cocok</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Batal
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save">
                    <span class="fas fa-save me-1" id="save-icon"></span>
                    <span id="save-text">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══ Modal Assign Groups ══════════════════════════════════════ -->
<div class="modal fade" id="groupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <span class="fas fa-users-cog"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Assign Group</h5>
                        <p class="text-muted fs-10 mb-0">Atur role untuk <strong id="group-modal-username">—</strong></p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-subtle-danger py-2 px-3 fs-10 d-none" id="group-modal-alert">
                    <span class="fas fa-exclamation-triangle me-1"></span>
                    <span id="group-modal-alert-text"></span>
                </div>

                <div class="d-flex flex-column gap-2" id="group-checklist" style="max-height:340px;overflow-y:auto">
                    <?php foreach ($groups as $g): ?>
                        <label class="group-check-item" data-group="<?= esc((string)$g['key']) ?>">
                            <input type="checkbox" class="form-check-input mt-1" name="groups[]" value="<?= esc((string)$g['key']) ?>">
                            <span class="group-check-label flex-grow-1">
                                <span class="group-check-title d-block"><?= esc((string)$g['title']) ?></span>
                                <span class="group-check-desc"><?= esc((string)$g['description']) ?></span>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Batal
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save-groups">
                    <span class="fas fa-save me-1" id="group-save-icon"></span>
                    <span id="group-save-text">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══ Modal Activity Log ══════════════════════════════════════ -->
<div class="modal fade" id="activityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <span class="fas fa-history"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Riwayat Aktivitas</h5>
                        <p class="text-muted fs-10 mb-0">Login terakhir <strong id="activity-username">—</strong></p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3" style="max-height:420px;overflow-y:auto">
                <div id="activity-loading" class="text-center py-4">
                    <span class="spinner-border spinner-border-sm text-primary"></span>
                </div>
                <div id="activity-empty" class="text-center text-muted py-4 d-none">
                    <span class="fas fa-inbox fa-2x mb-2 d-block"></span>
                    Belum ada riwayat aktivitas
                </div>
                <div id="activity-list"></div>
            </div>

            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CAN_EDIT = <?= json_encode($canEdit) ?>;
    const CAN_DELETE = <?= json_encode($canDelete) ?>;
    const CURRENT_USER_ID = <?= (int) (auth()->id() ?? 0) ?>;

    const UserMgmt = {
        BASE: '<?= base_url() ?>',
        dt: null,
        editId: null,
        groupEditId: null,
        filters: {
            name: '',
            group: '',
            status: ''
        },

        init() {
            this.initSelect2();
            this.initDatatable();
            this.initEvents();
            this.loadStats();
        },

        /* ── CSRF ─────────────────────────────────────────────────── */
        csrfName: () => document.querySelector('meta[name="csrf-name"]')?.content ?? '',
        csrfToken: () => document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        updateCsrf(h) {
            const m = document.querySelector('meta[name="csrf-token"]');
            if (m && h) m.content = h;
        },
        async post(url, fd) {
            fd.set(this.csrfName(), this.csrfToken());
            const r = await fetch(url, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.csrfToken()
                }
            });
            if (r.status === 403) throw new Error('Sesi habis, muat ulang halaman');
            const d = await r.json();
            if (d?.csrfHash) this.updateCsrf(d.csrfHash);
            return d;
        },
        async get(url) {
            const r = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            return r.json();
        },

        /* ── Stats ────────────────────────────────────────────────── */
        async loadStats() {
            try {
                const d = await this.get(this.BASE + 'access/users/stats');
                if (d.status !== 'success') return;
                document.getElementById('stat-total').textContent = d.data.total ?? 0;
                document.getElementById('stat-active').textContent = d.data.active ?? 0;
                document.getElementById('stat-inactive').textContent = d.data.inactive ?? 0;
                document.getElementById('stat-admin').textContent = d.data.admin ?? 0;
                const badge = document.getElementById('trash-badge');
                if (badge) {
                    badge.textContent = d.data.trash ?? 0;
                    badge.classList.toggle('d-none', !d.data.trash);
                }
            } catch {}
        },

        /* ── Select2: Karyawan ────────────────────────────────────── */
        initSelect2() {
            const self = this;
            $('#f-employee').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '— Tidak terhubung —',
                allowClear: true,
                minimumInputLength: 0,
                dropdownParent: $('#userModal'),
                ajax: {
                    url: self.BASE + 'access/users/select2-employees',
                    dataType: 'json',
                    delay: 250,
                    data: p => ({
                        search: p.term || '',
                        exclude_user_id: self.editId || 0
                    }),
                    processResults: d => ({
                        results: (d.data ?? []).map(r => ({
                            id: r.id,
                            text: r.name,
                            nik: r.nik
                        }))
                    }),
                    cache: true,
                },
                templateResult: r => {
                    if (r.loading) return r.text;
                    const nik = r.nik ? `<small class="text-muted ms-1">· ${r.nik}</small>` : '';
                    return $(`<span>${r.text}${nik}</span>`);
                },
                templateSelection: r => r.text || r.id,
            });

            $('#filter-group').select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $('#filter-offcanvas'),
                placeholder: 'Semua Group',
                allowClear: true,
            });
        },

        /* ── DataTable ────────────────────────────────────────────── */
        initDatatable() {
            const self = this;
            this.dt = $('#user-table').DataTable({
                scrollX: true,
                responsive: false,
                autoWidth: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [
                    [-1, 10, 25, 50, 100],
                    ['Semua', 10, 25, 50, 100]
                ],
                order: [
                    [1, 'asc']
                ],
                dom: '<"top"f>rt<"bottom"lpi>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari username / email...',
                    lengthMenu: '_MENU_ / hal',
                    info: 'Tampil _START_–_END_ dari _TOTAL_',
                    infoEmpty: 'Tidak ada data',
                    zeroRecords: 'Data tidak ditemukan',
                    paginate: {
                        previous: '‹',
                        next: '›'
                    },
                    processing: '<span class="spinner-border spinner-border-sm text-primary"></span>',
                },
                ajax: {
                    url: this.BASE + 'access/users/datatables',
                    type: 'GET',
                    data: d => {
                        d.filter_name = self.filters.name;
                        d.filter_group = self.filters.group;
                        d.filter_status = self.filters.status;
                    },
                    error: () => self.toast('error', 'Gagal memuat data'),
                },
                columnDefs: [{
                        targets: 0,
                        width: '45px'
                    },
                    {
                        targets: 1,
                        width: '200px'
                    },
                    {
                        targets: 2,
                        width: '180px'
                    },
                    {
                        targets: 3,
                        width: '180px'
                    },
                    {
                        targets: 4,
                        width: '200px'
                    },
                    {
                        targets: 5,
                        width: '110px'
                    },
                    {
                        targets: 6,
                        width: '130px'
                    },
                    {
                        targets: 7,
                        width: '130px'
                    },
                    {
                        targets: 8,
                        width: '150px'
                    },
                ],
                columns: [{
                        data: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: null,
                        render: (d, t, r) => {
                            const initial = (r.username || '?')[0].toUpperCase();
                            const youBadge = r.id == CURRENT_USER_ID ? '<span class="badge bg-primary bg-opacity-10 text-primary ms-1 fs-10">Anda</span>' : '';
                            return `<div class="d-flex align-items-center gap-2">
                        <span class="user-avatar">${self.e(initial)}</span>
                        <div>
                            <div class="fw-semibold">${self.e(r.username)}${youBadge}</div>
                            <div class="text-muted small">ID: ${r.id}</div>
                        </div>
                    </div>`;
                        }
                    },
                    {
                        data: 'email',
                        render: d => d ? self.e(d) : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'employee_name',
                        render: (d, t, r) => {
                            if (!d) return '<span class="text-muted fst-italic">—</span>';
                            return `<span class="badge-employee" title="${self.e(r.employee_nik||'')}">
                        <span class="fas fa-id-badge"></span>${self.e(d)}
                    </span>`;
                        }
                    },
                    {
                        data: 'groups',
                        orderable: false,
                        render: groups => {
                            if (!groups || !groups.length) return '<span class="text-muted fst-italic">Belum ada group</span>';
                            return groups.map(g => {
                                const cls = ['superadmin', 'admin'].includes(g) ? g : '';
                                return `<span class="badge-group ${cls} me-1">${self.e(g)}</span>`;
                            }).join('');
                        }
                    },
                    {
                        data: 'active',
                        render: d => {
                            const ok = !!Number(d);
                            return `<span class="badge-status ${ok?'active':'inactive'}">
                        <span class="fas ${ok?'fa-check-circle':'fa-times-circle'}"></span>${ok?'Aktif':'Nonaktif'}
                    </span>`;
                        }
                    },
                    {
                        data: 'last_active',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: 'created_at',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end',
                        render: (d, t, r) => {
                            const isMe = r.id == CURRENT_USER_ID;
                            let btns = `<button class="btn btn-subtle-secondary btn-sm btn-activity" data-id="${r.id}" data-username="${self.e(r.username)}" title="Riwayat Aktivitas">
                                    <span class="fas fa-history"></span>
                                </button>`;

                            if (CAN_EDIT) {
                                btns += `<button class="btn btn-subtle-info btn-sm btn-groups" data-id="${r.id}" data-username="${self.e(r.username)}" data-groups='${JSON.stringify(r.groups||[])}' title="Assign Group">
                                    <span class="fas fa-users-cog"></span>
                                 </button>`;
                                btns += `<button class="btn btn-subtle-primary btn-sm btn-edit" data-id="${r.id}" title="Edit">
                                    <span class="fas fa-pencil-alt"></span>
                                 </button>`;
                                if (!isMe) {
                                    const active = !!Number(r.active);
                                    btns += `<button class="btn btn-subtle-${active?'warning':'success'} btn-sm btn-toggle" data-id="${r.id}" data-active="${active?1:0}" title="${active?'Nonaktifkan':'Aktifkan'}">
                                        <span class="fas ${active?'fa-user-slash':'fa-user-check'}"></span>
                                     </button>`;
                                }
                            }
                            if (CAN_DELETE && !isMe) {
                                btns += `<button class="btn btn-subtle-danger btn-sm btn-delete" data-id="${r.id}" data-name="${self.e(r.username)}" title="Hapus">
                                    <span class="fas fa-trash"></span>
                                 </button>`;
                            }
                            return `<div class="btn-group btn-group-sm">${btns}</div>`;
                        }
                    },
                ],
            });
        },

        /* ── Modal: Create / Edit User ───────────────────────────────── */
        openCreate() {
            this.editId = null;
            this._resetUserModal();
            document.getElementById('modal-title').textContent = 'Tambah User';
            document.getElementById('modal-subtitle').textContent = 'Buat akun pengguna baru';
            document.getElementById('save-text').textContent = 'Simpan';
            document.getElementById('pass-required').classList.remove('d-none');
            document.getElementById('pass-confirm-required').classList.remove('d-none');
            document.getElementById('pass-hint').textContent = 'Minimal 8 karakter';
            new bootstrap.Modal(document.getElementById('userModal')).show();
        },

        async openEdit(id) {
            this.editId = id;
            this._resetUserModal();
            document.getElementById('modal-title').textContent = 'Edit User';
            document.getElementById('modal-subtitle').textContent = 'Perbarui informasi user';
            document.getElementById('save-text').textContent = 'Update';
            document.getElementById('pass-required').classList.add('d-none');
            document.getElementById('pass-confirm-required').classList.add('d-none');
            document.getElementById('pass-hint').textContent = 'Kosongkan jika tidak ingin mengubah password';

            this._setUserLoading(true);
            new bootstrap.Modal(document.getElementById('userModal')).show();

            try {
                const d = await this.get(this.BASE + `access/users/get/${id}`);
                if (d.status === 'success' && d.data) {
                    document.getElementById('f-username').value = d.data.username ?? '';
                    document.getElementById('f-email').value = d.data.email ?? '';
                    if (d.data.employee_id) {
                        const opt = new Option(d.data.employee_name, d.data.employee_id, true, true);
                        $('#f-employee').append(opt).trigger('change');
                    }
                } else {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    bootstrap.Modal.getInstance(document.getElementById('userModal'))?.hide();
                }
            } catch {
                this.toast('error', 'Gagal memuat data');
            } finally {
                this._setUserLoading(false);
            }
        },

        async saveUser() {
            if (!this._validateUserForm()) return;

            const fd = new FormData();
            fd.set('username', document.getElementById('f-username').value.trim());
            fd.set('email', document.getElementById('f-email').value.trim());
            fd.set('employee_id', $('#f-employee').val() || '');
            const pass = document.getElementById('f-password').value;
            if (pass) {
                fd.set('password', pass);
                fd.set('password_confirm', document.getElementById('f-password-confirm').value);
            }
            if (this.editId) fd.set('id', this.editId);

            this._setUserLoading(true);
            try {
                const res = await this.post(this.BASE + 'access/users/store', fd);
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('userModal'))?.hide();
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else if (res.errors) {
                    this._showUserErrors(res.errors);
                } else {
                    document.getElementById('modal-alert').classList.remove('d-none');
                    document.getElementById('modal-alert-text').textContent = res.message ?? 'Terjadi kesalahan';
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                this._setUserLoading(false);
            }
        },

        /* ── Client-side validation: is-valid / is-invalid ───────────── */
        _validateUserForm() {
            this._clearUserErrors();
            let valid = true;

            const username = document.getElementById('f-username');
            if (username.value.trim().length < 3) {
                this._markInvalid('f-username', 'err-username', 'Username minimal 3 karakter');
                valid = false;
            } else {
                this._markValid('f-username');
            }

            const email = document.getElementById('f-email');
            const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRe.test(email.value.trim())) {
                this._markInvalid('f-email', 'err-email', 'Format email tidak valid');
                valid = false;
            } else {
                this._markValid('f-email');
            }

            const pass = document.getElementById('f-password').value;
            const passConfirm = document.getElementById('f-password-confirm').value;
            const isCreate = !this.editId;

            if (isCreate || pass) {
                if (pass.length < 8) {
                    this._markInvalid('f-password', 'err-password', 'Password minimal 8 karakter');
                    valid = false;
                } else {
                    this._markValid('f-password');
                }

                if (pass !== passConfirm) {
                    this._markInvalid('f-password-confirm', 'err-password-confirm', 'Konfirmasi password tidak cocok');
                    valid = false;
                } else if (passConfirm) {
                    this._markValid('f-password-confirm');
                }
            }

            return valid;
        },

        _markInvalid(fieldId, errId, msg) {
            const el = document.getElementById(fieldId);
            el.classList.add('is-invalid');
            el.classList.remove('is-valid');
            const errEl = document.getElementById(errId);
            if (errEl) errEl.textContent = msg;
        },
        _markValid(fieldId) {
            const el = document.getElementById(fieldId);
            el.classList.add('is-valid');
            el.classList.remove('is-invalid');
        },

        _resetUserModal() {
            ['f-username', 'f-email', 'f-password', 'f-password-confirm'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.classList.remove('is-invalid', 'is-valid');
                }
            });
            $('#f-employee').val(null).trigger('change');
            document.getElementById('modal-alert').classList.add('d-none');
            this._clearUserErrors();
        },
        _clearUserErrors() {
            document.querySelectorAll('#userModal .is-invalid, #userModal .is-valid').forEach(el => el.classList.remove('is-invalid', 'is-valid'));
            document.querySelectorAll('#userModal .invalid-feedback').forEach(el => el.textContent = '');
        },
        _showUserErrors(errors) {
            const map = {
                username: ['f-username', 'err-username'],
                email: ['f-email', 'err-email'],
                password: ['f-password', 'err-password'],
                password_confirm: ['f-password-confirm', 'err-password-confirm'],
                employee_id: ['f-employee', null],
            };
            Object.entries(errors).forEach(([f, msg]) => {
                const [inp, err] = map[f] ?? [];
                const errMsg = Array.isArray(msg) ? msg[0] : msg;
                if (inp) document.getElementById(inp)?.classList.add('is-invalid');
                if (err) document.getElementById(err).textContent = errMsg;
                if (!err && f === 'employee_id') this.toast('error', errMsg);
            });
        },
        _setUserLoading(on) {
            const btn = document.getElementById('btn-save');
            const ico = document.getElementById('save-icon');
            btn.disabled = on;
            ico.className = on ? 'spinner-border spinner-border-sm me-1' : 'fas fa-save me-1';
        },

        /* ── Modal: Assign Groups ─────────────────────────────────────── */
        openGroupModal(id, username, currentGroups) {
            this.groupEditId = id;
            document.getElementById('group-modal-username').textContent = username;
            document.getElementById('group-modal-alert').classList.add('d-none');

            document.querySelectorAll('#group-checklist .group-check-item').forEach(item => {
                const cb = item.querySelector('input[type=checkbox]');
                const checked = currentGroups.includes(cb.value);
                cb.checked = checked;
                cb.disabled = false;
                item.classList.remove('checked', 'group-check-disabled');
                if (checked) item.classList.add('checked');
            });

            if (id == CURRENT_USER_ID) {
                const supItem = document.querySelector('#group-checklist .group-check-item[data-group="superadmin"]');
                if (supItem) {
                    const cb = supItem.querySelector('input');
                    if (cb.checked) {
                        cb.disabled = true;
                        supItem.classList.add('group-check-disabled');
                    }
                }
            }

            new bootstrap.Modal(document.getElementById('groupModal')).show();
        },

        async saveGroups() {
            const checked = Array.from(document.querySelectorAll('#group-checklist input:checked')).map(cb => cb.value);

            if (checked.length === 0) {
                document.getElementById('group-modal-alert').classList.remove('d-none');
                document.getElementById('group-modal-alert-text').textContent = 'Minimal satu group harus dipilih';
                return;
            }

            const fd = new FormData();
            checked.forEach(g => fd.append('groups[]', g));

            const btn = document.getElementById('btn-save-groups');
            const ico = document.getElementById('group-save-icon');
            btn.disabled = true;
            ico.className = 'spinner-border spinner-border-sm me-1';

            try {
                const res = await this.post(this.BASE + `access/users/assign-groups/${this.groupEditId}`, fd);
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('groupModal'))?.hide();
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else {
                    document.getElementById('group-modal-alert').classList.remove('d-none');
                    document.getElementById('group-modal-alert-text').textContent = res.message ?? 'Terjadi kesalahan';
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                btn.disabled = false;
                ico.className = 'fas fa-save me-1';
            }
        },

        /* ── Activity Log ─────────────────────────────────────────────── */
        async openActivity(id, username) {
            document.getElementById('activity-username').textContent = username;
            document.getElementById('activity-loading').classList.remove('d-none');
            document.getElementById('activity-empty').classList.add('d-none');
            document.getElementById('activity-list').innerHTML = '';

            new bootstrap.Modal(document.getElementById('activityModal')).show();

            try {
                const d = await this.get(this.BASE + `access/users/activity/${id}`);
                document.getElementById('activity-loading').classList.add('d-none');

                if (d.status === 'success' && d.data.length) {
                    document.getElementById('activity-list').innerHTML = d.data.map(log => {
                        const ok = !!Number(log.success);
                        const dt = new Date(log.date);
                        return `<div class="activity-item">
                        <div class="activity-icon ${ok?'success':'failed'}">
                            <span class="fas ${ok?'fa-sign-in-alt':'fa-exclamation-triangle'}"></span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold fs-9">${ok?'Login berhasil':'Login gagal'}</div>
                            <div class="text-muted fs-10">${this.e(log.ip_address)} · ${dt.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})} ${dt.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</div>
                        </div>
                    </div>`;
                    }).join('');
                } else {
                    document.getElementById('activity-empty').classList.remove('d-none');
                }
            } catch {
                document.getElementById('activity-loading').classList.add('d-none');
                document.getElementById('activity-empty').classList.remove('d-none');
            }
        },

        /* ── Toggle Active ────────────────────────────────────────────── */
        async toggle(id, currentlyActive) {
            const action = currentlyActive ? 'menonaktifkan' : 'mengaktifkan';
            const result = await Swal.fire({
                title: `${currentlyActive ? 'Nonaktifkan' : 'Aktifkan'} User?`,
                html: `User ini akan ${action}.`,
                icon: 'question',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: currentlyActive ? '#f5803e' : '#25b003',
                cancelButtonColor: '#748194',
                confirmButtonText: currentlyActive ? 'Nonaktifkan' : 'Aktifkan',
                cancelButtonText: 'Batal',
            });
            if (!result.isConfirmed) return;
            try {
                const res = await this.post(this.BASE + `access/users/toggle/${id}`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        /* ── Delete ───────────────────────────────────────────────────── */
        async deleteItem(id, name) {
            const result = await Swal.fire({
                title: 'Hapus User?',
                html: `<strong>${name}</strong> akan dipindahkan ke sampah.<br><small class="text-muted">Dapat dipulihkan dari menu Sampah.</small>`,
                icon: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#e63757',
                cancelButtonColor: '#748194',
                confirmButtonText: '<span class="fas fa-trash me-1"></span>Hapus',
                cancelButtonText: 'Batal',
            });
            if (!result.isConfirmed) return;
            try {
                const res = await this.post(this.BASE + `access/users/delete/${id}`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        /* ── Filter ───────────────────────────────────────────────────── */
        applyFilter() {
            this.filters.name = document.getElementById('filter-name').value.trim();
            this.filters.group = $('#filter-group').val() || '';
            this.filters.status = document.getElementById('filter-status').value;
            this.dt.ajax.reload();
            this.updateFilterUI();
            bootstrap.Offcanvas.getInstance(document.getElementById('filter-offcanvas'))?.hide();
        },
        resetFilter() {
            this.filters = {
                name: '',
                group: '',
                status: ''
            };
            document.getElementById('filter-name').value = '';
            document.getElementById('filter-status').value = '';
            $('#filter-group').val(null).trigger('change');
            this.dt.ajax.reload();
            this.updateFilterUI();
        },
        updateFilterUI() {
            const labels = [];
            if (this.filters.name) labels.push(`Cari: "${this.filters.name}"`);
            if (this.filters.group) labels.push('Group terpilih');
            if (this.filters.status) labels.push(`Status: ${this.filters.status}`);
            document.getElementById('filter-toggle').classList.toggle('has-filter', labels.length > 0);
            document.getElementById('filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('filter-summary').classList.toggle('d-none', labels.length === 0);
        },

        /* ── Events ───────────────────────────────────────────────────── */
        initEvents() {
            document.getElementById('btn-refresh')?.addEventListener('click', () => {
                this.dt.ajax.reload(() => this.loadStats(), false);
            });
            document.getElementById('btn-create')?.addEventListener('click', () => this.openCreate());
            document.getElementById('btn-save')?.addEventListener('click', () => this.saveUser());
            document.getElementById('btn-save-groups')?.addEventListener('click', () => this.saveGroups());
            document.getElementById('btn-apply-filter')?.addEventListener('click', () => this.applyFilter());
            document.getElementById('btn-reset-filter')?.addEventListener('click', () => this.resetFilter());

            ['toggle-password', 'toggle-password-confirm'].forEach(id => {
                document.getElementById(id)?.addEventListener('click', () => {
                    const inputId = id === 'toggle-password' ? 'f-password' : 'f-password-confirm';
                    const input = document.getElementById(inputId);
                    const icon = document.querySelector(`#${id} .fas`);
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    icon.classList.toggle('fa-eye', !isHidden);
                    icon.classList.toggle('fa-eye-slash', isHidden);
                });
            });

            $(document).on('click', '.btn-edit', e => this.openEdit($(e.currentTarget).data('id')));
            $(document).on('click', '.btn-groups', e => {
                const btn = $(e.currentTarget);
                this.openGroupModal(btn.data('id'), btn.data('username'), btn.data('groups') || []);
            });
            $(document).on('click', '.btn-activity', e => {
                const btn = $(e.currentTarget);
                this.openActivity(btn.data('id'), btn.data('username'));
            });
            $(document).on('click', '.btn-toggle', e => {
                const btn = $(e.currentTarget);
                this.toggle(btn.data('id'), btn.data('active') == 1);
            });
            $(document).on('click', '.btn-delete', e => {
                const btn = $(e.currentTarget);
                this.deleteItem(btn.data('id'), btn.data('name'));
            });
            $(document).on('change', '#group-checklist input[type=checkbox]', e => {
                $(e.currentTarget).closest('.group-check-item').toggleClass('checked', e.currentTarget.checked);
            });
        },

        /* ── Helpers ──────────────────────────────────────────────────── */
        e(s) {
            if (!s) return '';
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        },
        fmtDate(d) {
            if (!d) return '<span class="text-muted">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">${dt.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})}</span>
                <small class="text-muted">${dt.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</small>`;
        },
        toast(type, msg) {
            Swal.fire({
                toast: true,
                position: 'top-right',
                icon: type,
                title: msg,
                showConfirmButton: false,
                timer: type === 'success' ? 2000 : 3500,
                timerProgressBar: true
            });
        },
    };

    $(document).ready(() => UserMgmt.init());
</script>
<?= $this->endSection() ?>