<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    body {
        overflow-x: hidden;
    }

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

    #chemical-table_wrapper {
        max-width: 100%;
    }

    #chemical-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #chemical-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #chemical-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #chemical-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #chemical-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #chemical-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #chemical-table_wrapper .dataTables_filter label,
    #chemical-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #chemical-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #chemical-table_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #chemical-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #chemical-table {
        width: 100% !important;
    }

    .print-header {
        display: none;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        .print-header {
            display: block !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
    }

    .btn-group-sm .btn {
        padding: .5rem .75rem;
        font-size: .7rem;
    }

    .select2-container .select2-selection--single {
        height: calc(1.5em + 1.1rem + 2px) !important;
    }

    /* ── Variant Modal ───────────────────────────────────────────── */
    #variantModal .modal-dialog {
        max-width: 780px;
    }

    #variantModal .modal-header {
        background: linear-gradient(135deg, rgba(var(--phoenix-info-rgb), .06), transparent 60%);
    }

    .variant-count-badge {
        font-size: .68rem;
        font-weight: 700;
        padding: .3rem .65rem;
        border-radius: 20px;
        background-color: rgba(var(--phoenix-info-rgb), .12);
        color: var(--phoenix-info);
        border: 1px solid rgba(var(--phoenix-info-rgb), .25);
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        white-space: nowrap;
    }

    .variant-list-card {
        border: 1px solid var(--phoenix-border-color);
        border-radius: .85rem;
        overflow: hidden;
    }

    .variant-list-card .card-header {
        background-color: var(--phoenix-body-tertiary-bg, rgba(0, 0, 0, .015));
        border-bottom: 1px solid var(--phoenix-border-color);
        padding: .65rem 1rem;
    }

    /* Area data varian — scroll vertikal, header tabel tetap terlihat (sticky) */
    .variant-scroll-area {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .variant-scroll-area::-webkit-scrollbar {
        width: 7px;
    }

    .variant-scroll-area::-webkit-scrollbar-track {
        background: transparent;
    }

    .variant-scroll-area::-webkit-scrollbar-thumb {
        background-color: var(--phoenix-border-color);
        border-radius: 10px;
    }

    .variant-scroll-area::-webkit-scrollbar-thumb:hover {
        background-color: var(--phoenix-secondary-color);
    }

    #variant-list-table {
        margin-bottom: 0 !important;
    }

    #variant-list-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        font-size: .66rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--phoenix-secondary-color);
        font-weight: 700;
        border-bottom-width: 1px;
        white-space: nowrap;
        background-color: var(--phoenix-card-bg, var(--phoenix-body-bg, #fff));
        padding-top: .6rem;
        padding-bottom: .6rem;
    }

    #variant-list-table tbody tr {
        transition: background-color .12s;
    }

    #variant-list-table tbody tr:hover {
        background-color: rgba(var(--phoenix-info-rgb), .04);
    }

    #variant-list-body td {
        vertical-align: middle;
        padding-top: .55rem;
        padding-bottom: .55rem;
    }

    #variant-list-table .btn-group-sm .btn {
        padding: .3rem .5rem;
        font-size: .68rem;
    }

    .variant-row-default {
        background-color: rgba(var(--phoenix-success-rgb), .045);
    }

    .variant-state-msg {
        text-align: center;
        padding: 2.75rem 1rem;
        color: var(--phoenix-secondary-color);
    }

    .variant-state-msg .fas {
        font-size: 1.9rem;
        opacity: .35;
        display: block;
        margin-bottom: .6rem;
    }

    .variant-state-msg.text-danger .fas {
        opacity: .6;
    }

    .variant-form-card {
        border: 1px solid var(--phoenix-border-color);
        border-radius: .85rem;
        background-color: var(--phoenix-body-tertiary-bg, transparent);
    }

    .variant-section-label {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .variant-divider {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 1.1rem 0;
        color: var(--phoenix-secondary-color);
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .variant-divider::before,
    .variant-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background-color: var(--phoenix-border-color);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-100">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <?php foreach ($breadcrumbs as $crumb): ?>
                        <?php if (!empty($crumb['active'])): ?>
                            <li class="breadcrumb-item active"><?= esc((string)$crumb['name']) ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= esc((string)$crumb['name']) ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <h1 class="h3 mb-1 fw-bold"><?= esc((string)$page_title) ?></h1>
            <p class="text-body-tertiary mb-0"><?= esc((string)$page_description) ?></p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4 no-print">
        <?php
        $stats = [
            ['id' => 'stat-total',    'label' => 'Total',    'icon' => 'fa-flask',        'color' => 'primary'],
            ['id' => 'stat-active',   'label' => 'Active',   'icon' => 'fa-check-circle', 'color' => 'success'],
            ['id' => 'stat-draft',    'label' => 'Draft',    'icon' => 'fa-pencil-alt',   'color' => 'warning'],
            ['id' => 'stat-archived', 'label' => 'Archived', 'icon' => 'fa-archive',      'color' => 'secondary'],
        ];
        foreach ($stats as $s): ?>
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
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap no-print">
        <div class="d-flex gap-2">
            <?php if (canDo('warehouse.chemicals.delete')): ?>
                <a href="<?= site_url('warehouse/master/chemicals/trash') ?>" class="btn btn-subtle-danger btn-sm">
                    <span class="fas fa-trash-alt me-1"></span>Sampah
                    <span class="badge bg-danger ms-1 d-none" id="trash-badge">0</span>
                </a>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <?php if (canDo('warehouse.chemicals.create')): ?>
                <button class="btn btn-primary btn-sm" id="btn-create" type="button">
                    <span class="fas fa-plus me-1"></span>Tambah Bahan Kimia
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Print header -->
    <div class="print-header mb-3">
        <h5 class="fw-bold mb-1">Daftar Bahan Kimia</h5>
        <div class="text-muted small">Dicetak: <span id="print-date"></span></div>
        <hr class="my-2">
    </div>

    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="chemical-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bahan Kimia</th>
                    <th>Kategori</th>
                    <th>Jml Varian</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Oleh</th>
                    <th>Diupdate</th>
                    <th>Oleh</th>
                    <th class="text-end no-print">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Filter toggle -->
<a class="card filter-toggle no-print" href="#filter-offcanvas" data-bs-toggle="offcanvas" id="filter-toggle">
    <div class="card-body">
        <span class="fas fa-filter text-primary"></span>
        <span class="filter-label">Filter</span>
        <span class="filter-dot"></span>
    </div>
</a>

<!-- Filter Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="filter-offcanvas" style="width:300px">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title"><span class="fas fa-filter me-2 text-primary"></span>Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="flex-grow-1">
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Nama / Kode</label>
                <input type="text" class="form-control form-control-sm" id="filter-name" placeholder="Cari...">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Kategori</label>
                <select class="form-select form-select-sm" id="filter-category" style="width:100%">
                    <option value=""></option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Status</label>
                <select class="form-select form-select-sm" id="filter-status">
                    <option value="">Semua</option>
                    <option value="Active">Active</option>
                    <option value="Draft">Draft</option>
                    <option value="Archived">Archived</option>
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
            <button class="btn btn-primary btn-sm" id="btn-apply-filter">
                <span class="fas fa-search me-1"></span>Terapkan
            </button>
            <button class="btn btn-subtle-secondary btn-sm" id="btn-reset-filter">
                <span class="fas fa-times me-1"></span>Reset
            </button>
        </div>
    </div>
</div>

<!-- Modal Tambah / Edit -->
<div class="modal fade" id="chemicalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4" id="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary" id="modal-icon">
                        <span class="fas fa-flask"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modal-title">Tambah Bahan Kimia</h5>
                        <p class="text-muted fs-10 mb-0" id="modal-subtitle">Buat data bahan kimia baru</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-subtle-danger py-2 px-3 fs-10 d-none" id="modal-alert">
                    <span class="fas fa-exclamation-triangle me-1"></span>
                    <span id="modal-alert-text"></span>
                </div>

                <div class="card border mb-0" style="border-radius:.75rem">
                    <div class="card-body p-3">
                        <p class="fs-10 fw-bold text-uppercase text-primary mb-3" style="letter-spacing:.08em">
                            <span class="fas fa-clipboard-list me-1"></span>Informasi Bahan Kimia
                        </p>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-name">
                                    Nama Bahan Kimia <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="f-name"
                                    placeholder="Nama bahan kimia" maxlength="150" autocomplete="off">
                                <div class="invalid-feedback" id="err-chemical_name"></div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-code">
                                    Kode <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm font-monospace fw-bold bg-body-tertiary"
                                    id="f-code" placeholder="Otomatis" maxlength="50" autocomplete="off" readonly
                                    style="text-transform:uppercase;letter-spacing:.06em;cursor:not-allowed;">
                                <div class="form-text fs-10">Kode dibuat otomatis oleh sistem</div>
                                <div class="invalid-feedback" id="err-chemical_code"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-category">
                                    Kategori
                                </label>
                                <select class="form-select form-select-sm" id="f-category" multiple style="width:100%">
                                </select>
                                <div class="invalid-feedback" id="err-category_id"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-status">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-sm" id="f-status">
                                    <option value="Active">Active</option>
                                    <option value="Draft" selected>Draft</option>
                                    <option value="Archived">Archived</option>
                                </select>
                                <div class="invalid-feedback" id="err-status"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-desc">
                                    Deskripsi
                                </label>
                                <textarea class="form-control form-control-sm" id="f-desc"
                                    rows="3" maxlength="500" placeholder="Deskripsi singkat..." style="resize:vertical"></textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <div class="invalid-feedback d-block" id="err-description" style="visibility:hidden">‎</div>
                                    <small class="text-muted fs-10 ms-auto"><span id="char-count">0</span>/500</small>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-subtle-info py-2 px-3 fs-10 mt-3 mb-0" id="variant-note">
                            <span class="fas fa-info-circle me-1"></span>
                            Pengaturan varian (kemasan, satuan, harga) dikelola setelah data bahan kimia disimpan.
                        </div>
                    </div>
                </div>
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
<!-- Modal Kelola Varian -->
<div class="modal fade" id="variantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <span class="fas fa-boxes"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Kelola Varian</h5>
                        <p class="text-muted fs-10 mb-0" id="variant-modal-subtitle">—</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <!-- Daftar varian -->
                <div class="card variant-list-card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <p class="variant-section-label text-body mb-0">
                            <span class="fas fa-list-ul me-1 text-info"></span>Daftar Varian
                        </p>
                        <span class="variant-count-badge" id="variant-count-badge">
                            <span class="fas fa-boxes"></span>
                            <span id="variant-count-text">0 varian</span>
                        </span>
                    </div>
                    <div class="variant-scroll-area">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0" id="variant-list-table">
                                <thead>
                                    <tr>
                                        <th>Nama Varian</th>
                                        <th>Kemasan</th>
                                        <th>Satuan</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-center">Default</th>
                                        <th>Status</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="variant-list-body">
                                    <tr>
                                        <td colspan="7" class="p-0">
                                            <div class="variant-state-msg">
                                                <span class="fas fa-spinner fa-spin"></span>Memuat varian...
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="variant-divider">
                    <span><span class="fas fa-plus me-1"></span>Tambah / Edit Varian</span>
                </div>

                <div class="card variant-form-card">
                    <div class="card-body p-3">
                        <div class="alert alert-subtle-danger py-2 px-3 fs-10 d-none" id="variant-form-alert">
                            <span class="fas fa-exclamation-triangle me-1"></span>
                            <span id="variant-form-alert-text"></span>
                        </div>
                        <p class="variant-section-label text-primary mb-3" id="variant-form-title">
                            <span class="fas fa-plus me-1"></span>Tambah Varian Baru
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Nama Varian <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="v-name" placeholder="cth: Drum 200kg" maxlength="100">
                                <div class="invalid-feedback" id="err-variant_name"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Kemasan</label>
                                <input type="text" class="form-control form-control-sm" id="v-packaging" placeholder="cth: Drum" maxlength="50">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Ukuran Kemasan</label>
                                <input type="number" step="0.001" class="form-control form-control-sm" id="v-packaging-size" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Satuan</label>
                                <input type="text" class="form-control form-control-sm" id="v-unit" placeholder="cth: kg" maxlength="20">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Harga</label>
                                <input type="number" step="0.01" min="0" class="form-control form-control-sm" id="v-price" placeholder="0">
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" role="switch" id="v-is-default">
                                    <label class="form-check-label fs-9 fw-semibold" for="v-is-default">Jadikan varian default</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Status</label>
                                <select class="form-select form-select-sm" id="v-status">
                                    <option value="Active">Active</option>
                                    <option value="Archived">Archived</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm d-none" id="btn-cancel-variant-edit">
                    <span class="fas fa-times me-1"></span>Batal Edit
                </button>
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-check me-1"></span>Selesai
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save-variant">
                    <span class="fas fa-save me-1" id="variant-save-icon"></span>
                    <span id="variant-save-text">Tambah Varian</span>
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CAN_EDIT_CHEMICAL = <?= json_encode(canDo('warehouse.chemicals.edit')) ?>;
    const CAN_DELETE_CHEMICAL = <?= json_encode(canDo('warehouse.chemicals.delete')) ?>;

    const Chemical = {
        BASE: '<?= base_url() ?>',
        dt: null,
        editId: null,
        variantChemicalId: null,
        variantEditId: null,
        filters: {
            name: '',
            category: '',
            status: ''
        },

        init() {
            this.initSelect2();
            this.initDatatable();
            this.initEvents();
            this.initFieldEvents();
            this.loadStats();
            document.getElementById('print-date').textContent = new Date().toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

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

        initSelect2() {
            const categorySource = {
                ajax: {
                    url: this.BASE + 'warehouse/master/chemical-categories/select2',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: (data.data ?? []).map(c => ({
                            id: c.id,
                            text: `${c.name} (${c.code})`
                        }))
                    }),
                },
            };

            $('#f-category').select2({
                dropdownParent: $('#chemicalModal'),
                theme: 'bootstrap-5',
                placeholder: '— Pilih Kategori —',
                allowClear: true,
                width: '100%',
                multiple: true,
                ...categorySource,
            });

            $('#filter-category').select2({
                dropdownParent: $('#filter-offcanvas'),
                theme: 'bootstrap-5',
                placeholder: '— Semua Kategori —',
                allowClear: true,
                width: '100%',
                ...categorySource,
            });
        },

        async loadStats() {
            try {
                const d = await this.get(this.BASE + 'warehouse/master/chemicals/stats');
                if (d.status !== 'success') return;
                document.getElementById('stat-total').textContent = d.data.total ?? 0;
                document.getElementById('stat-active').textContent = d.data.active ?? 0;
                document.getElementById('stat-draft').textContent = d.data.draft ?? 0;
                document.getElementById('stat-archived').textContent = d.data.archived ?? 0;
                const badge = document.getElementById('trash-badge');
                if (badge) {
                    badge.textContent = d.data.trash ?? 0;
                    badge.classList.toggle('d-none', !d.data.trash);
                }
            } catch {}
        },

        initDatatable() {
            const self = this;
            this.dt = $('#chemical-table').DataTable({
                responsive: true,
                scrollX: true,
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
                    searchPlaceholder: 'Cari bahan kimia...',
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
                    url: this.BASE + 'warehouse/master/chemicals/datatables',
                    type: 'GET',
                    data: d => {
                        d.filter_name = self.filters.name;
                        d.filter_category = self.filters.category;
                        d.filter_status = self.filters.status;
                    },
                    error: () => self.toast('error', 'Gagal memuat data'),
                },
                columnDefs: [{
                        targets: 0,
                        width: '30px'
                    },
                    {
                        targets: 1,
                        width: '200px'
                    },
                    {
                        targets: 2,
                        width: '150px'
                    },
                    {
                        targets: 3,
                        width: '100px'
                    },
                    {
                        targets: 4,
                        width: '100px'
                    },
                    {
                        targets: 5,
                        width: '120px'
                    },
                    {
                        targets: 6,
                        width: '130px'
                    },
                    {
                        targets: 7,
                        width: '120px'
                    },
                    {
                        targets: 8,
                        width: '130px'
                    },
                    {
                        targets: 9,
                        width: '80px'
                    }
                ],
                columns: [{
                        data: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: null,
                        render: (d, t, r) =>
                            `<span class="fw-semibold">${self.e(r.chemical_name)}</span>
                             <div class="text-muted small font-monospace">${self.e(r.chemical_code)}</div>`
                    },
                    {
                        data: 'category_names',
                        render: d => {
                            if (!d) return '<span class="text-muted fst-italic">—</span>';
                            return d.split(', ').map(c =>
                                `<span class="badge badge-phoenix badge-phoenix-secondary p-2 fs-10 me-1 mb-1">${self.e(c)}</span>`
                            ).join('');
                        }
                    },
                    {
                        data: 'variant_count',
                        render: d => Number(d) > 0 ?
                            `<span class="badge badge-phoenix badge-phoenix-success p-2 fs-10">${self.e(d)} varian</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'status',
                        render: d => self.fmtStatus(d)
                    },
                    {
                        data: 'created_at',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: 'created_by_name',
                        render: (d, t, r) => self.fmtUser(d, r.created_by_employee)
                    },
                    {
                        data: 'updated_at',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: 'updated_by_name',
                        render: (d, t, r) => self.fmtUser(d, r.updated_by_employee)
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render: (d, t, r) => {
                            const variants = CAN_EDIT_CHEMICAL ?
                                `<button class="btn btn-subtle-info btn-sm btn-variants" data-id="${r.id}" data-name="${self.e(r.chemical_name)}" title="Kelola Varian">
                                    <span class="fas fa-boxes"></span>
                                </button>` : '';
                            const edit = CAN_EDIT_CHEMICAL ?
                                `<button class="btn btn-subtle-primary btn-sm btn-edit" data-id="${r.id}"><span class="fas fa-pencil-alt"></span></button>` :
                                '';
                            const del = CAN_DELETE_CHEMICAL ?
                                `<button class="btn btn-subtle-danger btn-sm btn-delete" data-id="${r.id}" data-name="${self.e(r.chemical_name)}"><span class="fas fa-trash"></span></button>` :
                                '';
                            return `<div class="btn-group btn-group-sm">${variants}${edit}${del}</div>`;
                        }
                    },
                ],
            });
        },

        async openCreate() {
            this.editId = null;
            this.resetModal();
            document.getElementById('modal-title').textContent = 'Tambah Bahan Kimia';
            document.getElementById('modal-subtitle').textContent = 'Buat data bahan kimia baru';
            document.getElementById('save-text').textContent = 'Simpan';
            document.getElementById('variant-note').classList.add('d-none');
            new bootstrap.Modal(document.getElementById('chemicalModal')).show();

            // Ambil kode berikutnya secara otomatis (readonly, bukan input manual)
            try {
                const codeEl = document.getElementById('f-code');
                codeEl.value = '...';
                const d = await this.get(this.BASE + 'warehouse/master/chemicals/next-code');
                console.log('next-code response:', d); // ← sementara
                if (d.status === 'success' && d.code) {
                    codeEl.value = d.code;
                    this.markValid('f-code');
                } else {
                    codeEl.value = '';
                    console.warn('next-code gagal:', d.message);
                }
            } catch (err) {
                console.error('next-code fetch error:', err); // ← sementara
                document.getElementById('f-code').value = '';
            }
        },

        async openEdit(id) {
            this.editId = id;
            this.resetModal();
            document.getElementById('modal-title').textContent = 'Edit Bahan Kimia';
            document.getElementById('modal-subtitle').textContent = 'Perbarui data bahan kimia';
            document.getElementById('save-text').textContent = 'Update';
            document.getElementById('variant-note').classList.remove('d-none');
            this.setLoading(true);
            new bootstrap.Modal(document.getElementById('chemicalModal')).show();
            try {
                const d = await this.get(this.BASE + `warehouse/master/chemicals/${id}`);
                if (d.status === 'success' && d.data) {
                    document.getElementById('f-name').value = d.data.chemical_name ?? '';
                    document.getElementById('f-code').value = d.data.chemical_code ?? '';
                    document.getElementById('f-desc').value = d.data.description ?? '';
                    document.getElementById('f-status').value = d.data.status ?? 'Draft';
                    document.getElementById('char-count').textContent = (d.data.description ?? '').length;

                    if (d.data.chemical_name) this.markValid('f-name');
                    if (d.data.chemical_code) this.markValid('f-code');
                    if (d.data.status) this.markValid('f-status');

                    if (Array.isArray(d.data.categories) && d.data.categories.length > 0) {
                        d.data.categories.forEach(c => {
                            const opt = new Option(`${c.category_name} (${c.category_code ?? ''})`, c.id, true, true);
                            $('#f-category').append(opt);
                        });
                        $('#f-category').trigger('change');
                    } else {
                        $('#f-category').val(null).trigger('change');
                    }
                } else {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    bootstrap.Modal.getInstance(document.getElementById('chemicalModal'))?.hide();
                }
            } catch {
                this.toast('error', 'Gagal memuat data');
            } finally {
                this.setLoading(false);
            }
        },

        async save() {
            this.clearErrors();
            const fd = new FormData();
            fd.set('chemical_name', document.getElementById('f-name').value.trim());
            fd.set('chemical_code', document.getElementById('f-code').value.trim().toUpperCase());
            const categoryIds = $('#f-category').val() || [];
            categoryIds.forEach(id => fd.append('category_ids[]', id));
            fd.set('description', document.getElementById('f-desc').value.trim());
            fd.set('status', document.getElementById('f-status').value);
            if (this.editId) fd.set('id', this.editId);

            this.setLoading(true);
            try {
                const res = await this.post(this.BASE + 'warehouse/master/chemicals/store', fd);
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('chemicalModal'))?.hide();
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else if (res.errors?.chemical_code && !this.editId) {
                    // Kode bentrok (race condition) — ambil kode baru & retry otomatis sekali
                    const d = await this.get(this.BASE + 'warehouse/master/chemicals/next-code');
                    if (d.status === 'success' && d.code) {
                        document.getElementById('f-code').value = d.code;
                        fd.set('chemical_code', d.code);
                        const retryRes = await this.post(this.BASE + 'warehouse/master/chemicals/store', fd);
                        if (retryRes.status === 'success') {
                            bootstrap.Modal.getInstance(document.getElementById('chemicalModal'))?.hide();
                            this.dt.ajax.reload(null, false);
                            this.loadStats();
                            this.toast('success', retryRes.message);
                            return;
                        }
                        this.showErrors(retryRes.errors ?? {});
                        return;
                    }
                    this.showErrors(res.errors);
                } else if (res.errors) {
                    this.showErrors(res.errors);
                } else {
                    document.getElementById('modal-alert').classList.remove('d-none');
                    document.getElementById('modal-alert-text').textContent = res.message ?? 'Terjadi kesalahan';
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                this.setLoading(false);
            }
        },

        resetModal() {
            ['f-name', 'f-code', 'f-desc'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.classList.remove('is-invalid', 'is-valid');
                }
            });
            document.getElementById('f-status').value = 'Draft';
            document.getElementById('char-count').textContent = '0';
            document.getElementById('modal-alert').classList.add('d-none');
            $('#f-category').empty().val(null).trigger('change');
            this.clearErrors();
        },

        clearErrors() {
            document.querySelectorAll('#chemicalModal .is-invalid, #chemicalModal .is-valid').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });
            document.querySelectorAll('#chemicalModal .invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.visibility = '';
            });
            document.getElementById('modal-alert').classList.add('d-none');
        },

        initFieldEvents() {
            [{
                    input: 'f-name',
                    required: true
                },
                {
                    input: 'f-code',
                    required: true
                },
                {
                    input: 'f-status',
                    required: true
                },
                {
                    input: 'f-desc',
                    required: false
                },
            ].forEach(({
                input,
                required
            }) => {
                const el = document.getElementById(input);
                if (!el) return;

                const revalidate = () => {
                    const val = el.value.trim();
                    if (val) {
                        el.classList.remove('is-invalid');
                        el.classList.add('is-valid');
                    } else if (required) {
                        el.classList.remove('is-valid');
                        el.classList.add('is-invalid');
                    } else {
                        el.classList.remove('is-valid', 'is-invalid');
                    }
                };

                el.addEventListener('input', revalidate);
                el.addEventListener('change', revalidate);
            });

            $('#f-category').on('select2:select select2:clear', () => {
                document.getElementById('f-category').classList.remove('is-invalid', 'is-valid');
            });
        },

        markValid(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('is-invalid');
            el.classList.add('is-valid');
        },

        markInvalid(id, errId, msg) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('is-invalid');
                el.classList.remove('is-valid');
            }
            const errEl = document.getElementById(errId);
            if (errEl) {
                errEl.textContent = msg;
                errEl.style.visibility = 'visible';
            }
        },

        showErrors(errors) {
            const map = {
                chemical_name: ['f-name', 'err-chemical_name'],
                chemical_code: ['f-code', 'err-chemical_code'],
                category_id: ['f-category', 'err-category_id'],
                description: ['f-desc', 'err-description'],
                status: ['f-status', 'err-status'],
            };
            Object.entries(errors).forEach(([f, msg]) => {
                const [inp, err] = map[f] ?? [];
                if (inp && err) this.markInvalid(inp, err, Array.isArray(msg) ? msg[0] : msg);
            });
        },

        setLoading(on) {
            const btn = document.getElementById('btn-save');
            const ico = document.getElementById('save-icon');
            btn.disabled = on;
            ico.className = on ? 'spinner-border spinner-border-sm me-1' : 'fas fa-save me-1';
        },

        async deleteItem(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Bahan Kimia?',
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
                const res = await this.post(this.BASE + `warehouse/master/chemicals/${id}/delete`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else {
                    this.toast('error', res.message);
                }
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        applyFilter() {
            this.filters.name = document.getElementById('filter-name').value.trim();
            this.filters.category = $('#filter-category').val() ?? '';
            this.filters.status = document.getElementById('filter-status').value;
            this.dt.ajax.reload();
            this.updateFilterUI();
            bootstrap.Offcanvas.getInstance(document.getElementById('filter-offcanvas'))?.hide();
        },

        resetFilter() {
            this.filters = {
                name: '',
                category: '',
                status: ''
            };
            document.getElementById('filter-name').value = '';
            $('#filter-category').val(null).trigger('change');
            document.getElementById('filter-status').value = '';
            this.dt.ajax.reload();
            this.updateFilterUI();
        },

        updateFilterUI() {
            const labels = [];
            if (this.filters.name) labels.push(`Nama: "${this.filters.name}"`);
            if (this.filters.category) {
                const txt = $('#filter-category option:selected').text();
                if (txt) labels.push(`Kategori: ${txt}`);
            }
            if (this.filters.status) labels.push(`Status: ${this.filters.status}`);
            document.getElementById('filter-toggle').classList.toggle('has-filter', labels.length > 0);
            document.getElementById('filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('filter-summary').classList.toggle('d-none', labels.length === 0);
        },

        initEvents() {
            document.getElementById('btn-refresh')?.addEventListener('click', () => {
                this.dt.ajax.reload(() => this.loadStats(), false);
            });
            document.getElementById('btn-create')?.addEventListener('click', () => this.openCreate());
            document.getElementById('btn-save')?.addEventListener('click', () => this.save());
            document.getElementById('btn-apply-filter')?.addEventListener('click', () => this.applyFilter());
            document.getElementById('btn-reset-filter')?.addEventListener('click', () => this.resetFilter());

            document.getElementById('f-desc')?.addEventListener('input', e => {
                document.getElementById('char-count').textContent = e.target.value.length;
            });
            document.getElementById('f-code')?.addEventListener('input', e => {
                e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_\-]/g, '');
            });

            document.getElementById('chemicalModal')
                ?.addEventListener('hide.bs.modal', () => {
                    if (document.activeElement instanceof HTMLElement) {
                        document.activeElement.blur();
                    }
                });

            $(document).on('click', '.btn-edit', e => this.openEdit($(e.currentTarget).data('id')));
            $(document).on('click', '.btn-delete', e => {
                const btn = $(e.currentTarget);
                this.deleteItem(btn.data('id'), btn.data('name'));
            });

            $(document).on('click', '.btn-variants', e => {
                const btn = $(e.currentTarget);
                this.openVariants(btn.data('id'), btn.data('name'));
            });

            document.getElementById('btn-save-variant')?.addEventListener('click', () => this.saveVariant());
            document.getElementById('btn-cancel-variant-edit')?.addEventListener('click', () => this.resetVariantForm());

            $(document).on('click', '.btn-edit-variant', e => {
                this.editVariantRow($(e.currentTarget).data('id'));
            });
            $(document).on('click', '.btn-delete-variant', e => {
                const btn = $(e.currentTarget);
                this.deleteVariantRow(btn.data('id'), btn.data('name'));
            });
            $(document).on('click', '.btn-set-default-variant', e => {
                this.setDefaultVariantRow($(e.currentTarget).data('id'));
            });

            document.getElementById('variantModal')
                ?.addEventListener('hide.bs.modal', () => {
                    if (document.activeElement instanceof HTMLElement) {
                        document.activeElement.blur();
                    }
                });
        },

        e(s) {
            if (s === null || s === undefined) return '';
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        },

        fmtDate(d) {
            if (!d) return '<span class="text-muted">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">${dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                    <small class="text-muted">${dt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</small>`;
        },

        fmtStatus(status) {
            if (!status) return '<span class="text-muted fst-italic">—</span>';

            if (status.toLowerCase() === 'active') {
                return `<span class="badge badge-phoenix badge-phoenix-success rounded-pill fs-10 p-2 px-2" title="Status: ${this.e(status)}">
                    <span class="fas fa-check-circle me-1"></span>${this.e(status)}
                </span>`;
            }

            if (status.toLowerCase() === 'draft') {
                return `<span class="badge badge-phoenix badge-phoenix-warning rounded-pill fs-10 p-2 px-2" title="Status: ${this.e(status)}">
                    <span class="fas fa-pencil-alt me-1"></span>${this.e(status)}
                </span>`;
            }

            if (status.toLowerCase() === 'archived') {
                return `<span class="badge badge-phoenix badge-phoenix-secondary rounded-pill fs-10 p-2 px-2" title="Status: ${this.e(status)}">
                    <span class="fas fa-archive me-1"></span>${this.e(status)}
                </span>`;
            }

            return `<span class="badge badge-phoenix badge-phoenix-secondary rounded-pill fs-10 p-2 px-2" title="Status: ${this.e(status)}">
                <span class="fas fa-question-circle me-1"></span>${this.e(status)}
            </span>`;
        },


        fmtUser(name, employeeName = null) {
            if (!name && !employeeName) return '<span class="text-muted fst-italic">—</span>';

            if (!employeeName) {
                return `<span class="badge badge-phoenix badge-phoenix-info rounded-pill fs-10 p-2 px-2" title="Username: ${this.e(name)}">
                    <span class="fas fa-user-circle me-1"></span>${this.e(name)}
                </span>`;
            }

            return `<span class="badge badge-phoenix badge-phoenix-primary rounded-pill fs-10 p-2 px-3"
                 title="Karyawan: ${this.e(employeeName)}&#013;Username: ${this.e(name)}"
                 style="cursor:help;border-radius:50px;display:inline-flex;align-items:center;gap:0.3rem;">
                <span class="fas fa-user me-1"></span>
                ${this.e(employeeName)}
            </span>`;
        },

        async openVariants(chemicalId, chemicalName) {
            this.variantChemicalId = chemicalId;
            this.variantEditId = null;
            this.resetVariantForm();
            document.getElementById('variant-modal-subtitle').textContent = chemicalName;
            new bootstrap.Modal(document.getElementById('variantModal')).show();
            await this.loadVariantList();
        },

        updateVariantCount(n) {
            const el = document.getElementById('variant-count-text');
            if (el) el.textContent = `${n} varian`;
        },

        async loadVariantList() {
            const tbody = document.getElementById('variant-list-body');
            tbody.innerHTML = `<tr><td colspan="7" class="p-0"><div class="variant-state-msg"><span class="fas fa-spinner fa-spin"></span>Memuat varian...</div></td></tr>`;
            try {
                const d = await this.get(this.BASE + `warehouse/master/chemicals/${this.variantChemicalId}/variants`);
                if (d.status !== 'success') {
                    tbody.innerHTML = `<tr><td colspan="7" class="p-0"><div class="variant-state-msg text-danger"><span class="fas fa-exclamation-triangle"></span>${this.e(d.message ?? 'Gagal memuat varian')}</div></td></tr>`;
                    this.updateVariantCount(0);
                    return;
                }
                const rows = d.data ?? [];
                this.updateVariantCount(rows.length);
                if (rows.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="7" class="p-0"><div class="variant-state-msg"><span class="fas fa-box-open"></span>Belum ada varian</div></td></tr>`;
                    return;
                }
                tbody.innerHTML = rows.map(v => `
            <tr class="${Number(v.is_default) === 1 ? 'variant-row-default' : ''}">
                <td class="fw-semibold">${this.e(v.variant_name)}</td>
                <td>${v.packaging ? this.e(v.packaging) + (v.packaging_size ? ` (${this.e(v.packaging_size)})` : '') : '<span class="text-muted fst-italic">—</span>'}</td>
                <td>${v.unit ? this.e(v.unit) : '<span class="text-muted fst-italic">—</span>'}</td>
                <td class="text-end">${v.price ? Number(v.price).toLocaleString('id-ID') : '<span class="text-muted fst-italic">—</span>'}</td>
                <td class="text-center">
                    ${Number(v.is_default) === 1
                        ? '<span class="badge badge-phoenix badge-phoenix-primary fs-10"><span class="fas fa-star me-1"></span>Default</span>'
                        : `<button class="btn btn-subtle-secondary btn-sm py-0 px-2 btn-set-default-variant" data-id="${v.id}" title="Jadikan Default"><span class="fas fa-star"></span></button>`
                    }
                </td>
                <td>${v.status === 'Active'
                    ? '<span class="badge badge-phoenix badge-phoenix-success fs-10">Active</span>'
                    : '<span class="badge badge-phoenix badge-phoenix-secondary fs-10">Archived</span>'
                }</td>
                <td class="text-end">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-subtle-primary btn-sm btn-edit-variant" data-id="${v.id}" title="Edit"><span class="fas fa-pencil-alt"></span></button>
                        <button class="btn btn-subtle-danger btn-sm btn-delete-variant" data-id="${v.id}" data-name="${this.e(v.variant_name)}" title="Hapus"><span class="fas fa-trash"></span></button>
                    </div>
                </td>
            </tr>
        `).join('');

                this._variantCache = rows; // simpan sementara untuk isi form saat edit
            } catch (e) {
                tbody.innerHTML = `<tr><td colspan="7" class="p-0"><div class="variant-state-msg text-danger"><span class="fas fa-exclamation-triangle"></span>Gagal memuat varian</div></td></tr>`;
                this.updateVariantCount(0);
            }
        },

        resetVariantForm() {
            this.variantEditId = null;
            document.getElementById('v-name').value = '';
            document.getElementById('v-packaging').value = '';
            document.getElementById('v-packaging-size').value = '';
            document.getElementById('v-unit').value = '';
            document.getElementById('v-price').value = '';
            document.getElementById('v-is-default').checked = false;
            document.getElementById('v-status').value = 'Active';
            document.getElementById('variant-form-alert').classList.add('d-none');
            document.getElementById('variant-form-title').innerHTML = '<span class="fas fa-plus me-1"></span>Tambah Varian Baru';
            document.getElementById('variant-save-text').textContent = 'Tambah Varian';
            document.getElementById('btn-cancel-variant-edit').classList.add('d-none');
            ['v-name'].forEach(id => document.getElementById(id).classList.remove('is-invalid'));
        },

        editVariantRow(variantId) {
            const v = (this._variantCache ?? []).find(x => String(x.id) === String(variantId));
            if (!v) return;
            this.variantEditId = variantId;
            document.getElementById('v-name').value = v.variant_name ?? '';
            document.getElementById('v-packaging').value = v.packaging ?? '';
            document.getElementById('v-packaging-size').value = v.packaging_size ?? '';
            document.getElementById('v-unit').value = v.unit ?? '';
            document.getElementById('v-price').value = v.price ?? '';
            document.getElementById('v-is-default').checked = Number(v.is_default) === 1;
            document.getElementById('v-status').value = v.status ?? 'Active';
            document.getElementById('variant-form-alert').classList.add('d-none');
            document.getElementById('variant-form-title').innerHTML = '<span class="fas fa-pencil-alt me-1"></span>Edit Varian';
            document.getElementById('variant-save-text').textContent = 'Update Varian';
            document.getElementById('btn-cancel-variant-edit').classList.remove('d-none');
            document.getElementById('v-name').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        },

        async saveVariant() {
            const nameEl = document.getElementById('v-name');
            const name = nameEl.value.trim();
            if (!name) {
                nameEl.classList.add('is-invalid');
                return;
            }
            nameEl.classList.remove('is-invalid');

            const fd = new FormData();
            fd.set('variant_name', name);
            fd.set('packaging', document.getElementById('v-packaging').value.trim());
            fd.set('packaging_size', document.getElementById('v-packaging-size').value);
            fd.set('unit', document.getElementById('v-unit').value.trim());
            fd.set('price', document.getElementById('v-price').value);
            fd.set('is_default', document.getElementById('v-is-default').checked ? '1' : '0');
            fd.set('status', document.getElementById('v-status').value);

            const btn = document.getElementById('btn-save-variant');
            const icon = document.getElementById('variant-save-icon');
            btn.disabled = true;
            icon.className = 'spinner-border spinner-border-sm me-1';

            try {
                const url = this.variantEditId ?
                    this.BASE + `warehouse/master/chemicals/${this.variantChemicalId}/variants/${this.variantEditId}/update` :
                    this.BASE + `warehouse/master/chemicals/${this.variantChemicalId}/variants/store`;

                const res = await this.post(url, fd);
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    this.resetVariantForm();
                    await this.loadVariantList();
                    this.dt.ajax.reload(null, false); // refresh jumlah varian di tabel utama
                } else if (res.errors) {
                    const msg = Object.values(res.errors)[0];
                    document.getElementById('variant-form-alert').classList.remove('d-none');
                    document.getElementById('variant-form-alert-text').textContent = Array.isArray(msg) ? msg[0] : msg;
                } else {
                    document.getElementById('variant-form-alert').classList.remove('d-none');
                    document.getElementById('variant-form-alert-text').textContent = res.message ?? 'Terjadi kesalahan';
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                btn.disabled = false;
                icon.className = 'fas fa-save me-1';
            }
        },

        async deleteVariantRow(variantId, name) {
            const result = await Swal.fire({
                title: 'Hapus Varian?',
                html: `Varian <strong>${name}</strong> akan dihapus permanen.`,
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
                const res = await this.post(this.BASE + `warehouse/master/chemicals/variants/${variantId}/delete`, new FormData());
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    await this.loadVariantList();
                    this.dt.ajax.reload(null, false);
                } else {
                    this.toast('error', res.message);
                }
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        async setDefaultVariantRow(variantId) {
            try {
                const res = await this.post(this.BASE + `warehouse/master/chemicals/${this.variantChemicalId}/variants/${variantId}/default`, new FormData());
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    await this.loadVariantList();
                } else {
                    this.toast('error', res.message);
                }
            } catch (e) {
                this.toast('error', e.message);
            }
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

    $(document).ready(() => Chemical.init());
</script>
<?= $this->endSection() ?>