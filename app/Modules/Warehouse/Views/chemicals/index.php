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
        background-color: rgba(var(--phoenix-success-rgb), .12);
        color: var(--phoenix-success);
        border: 1px solid rgba(var(--phoenix-success-rgb), .25);
    }

    .badge-status.draft {
        background-color: rgba(var(--phoenix-warning-rgb), .12);
        color: var(--phoenix-warning);
        border: 1px solid rgba(var(--phoenix-warning-rgb), .25);
    }

    .badge-status.archived {
        background-color: rgba(var(--phoenix-secondary-rgb), .12);
        color: var(--phoenix-secondary);
        border: 1px solid rgba(var(--phoenix-secondary-rgb), .25);
    }

    .badge-status .fas {
        font-size: .7rem;
        flex-shrink: 0;
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

    .chemical-code-badge {
        font-family: var(--bs-font-monospace, monospace);
        font-weight: 700;
        letter-spacing: .04em;
        background-color: rgba(var(--phoenix-primary-rgb), .1);
        color: var(--phoenix-primary);
        padding: .2rem .5rem;
        border-radius: .35rem;
        font-size: .78rem;
        display: inline-block;
    }

    /* Variant modal */
    .variant-row {
        border: 1px solid var(--phoenix-border-color);
        border-radius: .6rem;
        padding: .75rem .9rem;
        margin-bottom: .6rem;
        transition: all .15s;
    }

    .variant-row.is-default {
        border-color: rgba(var(--phoenix-success-rgb), .4);
        background-color: rgba(var(--phoenix-success-rgb), .04);
    }

    .variant-row .variant-name {
        font-weight: 600;
    }

    .variant-empty {
        text-align: center;
        padding: 2rem 1rem;
        color: var(--phoenix-secondary-color);
    }

    #variantFormCard {
        border: 1px dashed var(--phoenix-border-color);
        border-radius: .75rem;
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

<!-- Modal Tambah / Edit Bahan Kimia -->
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
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted">
                                    Kode
                                </label>
                                <div>
                                    <span class="chemical-code-badge" id="f-code-display">Otomatis</span>
                                </div>
                                <div class="form-text fs-10">Dibuat otomatis: CH-00001, CH-00002, dst.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-category">
                                    Kategori
                                </label>
                                <select class="form-select form-select-sm" id="f-category" multiple="multiple" style="width:100%">
                                </select>
                                <div class="form-text fs-10">Bisa pilih lebih dari satu kategori</div>
                                <div class="invalid-feedback" id="err-category_ids"></div>
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
                            Pengaturan varian (kemasan, satuan, harga) dikelola lewat tombol <strong>Varian</strong> di tabel setelah data disimpan.
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

<!-- Modal Varian (CRUD per bahan kimia) -->
<div class="modal fade" id="variantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <span class="fas fa-boxes-stacked"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Varian Bahan Kimia</h5>
                        <p class="text-muted fs-10 mb-0">
                            <span class="chemical-code-badge" id="vm-code">—</span>
                            <span id="vm-name" class="ms-1 fw-semibold"></span>
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <!-- List varian -->
                <div id="variant-list-wrap">
                    <div id="variant-list"></div>
                    <div class="variant-empty d-none" id="variant-empty">
                        <span class="fas fa-box-open fs-2 d-block mb-2 opacity-50"></span>
                        Belum ada varian untuk bahan kimia ini
                    </div>
                    <div class="text-center py-3 d-none" id="variant-loading">
                        <span class="spinner-border spinner-border-sm text-primary"></span>
                    </div>
                </div>

                <!-- Form tambah/edit varian -->
                <div id="variantFormCard" class="p-3 mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="fs-10 fw-bold text-uppercase text-info mb-0" style="letter-spacing:.08em" id="vf-title">
                            <span class="fas fa-plus-circle me-1"></span>Tambah Varian
                        </p>
                        <button type="button" class="btn btn-subtle-secondary btn-sm d-none" id="btn-cancel-edit-variant">
                            <span class="fas fa-times me-1"></span>Batal Edit
                        </button>
                    </div>
                    <div class="alert alert-subtle-danger py-2 px-3 fs-10 d-none" id="vf-alert"></div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Nama Varian <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="vf-name" maxlength="100" placeholder="Contoh: Botol 500ml">
                            <div class="invalid-feedback" id="err-variant_name"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Kemasan</label>
                            <input type="text" class="form-control form-control-sm" id="vf-packaging" maxlength="50" placeholder="Botol/Drum/dll">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Ukuran</label>
                            <input type="number" step="any" class="form-control form-control-sm" id="vf-size" placeholder="500">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Satuan</label>
                            <input type="text" class="form-control form-control-sm" id="vf-unit" maxlength="20" placeholder="ml/kg/L">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Harga</label>
                            <input type="number" step="any" class="form-control form-control-sm" id="vf-price" placeholder="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Status</label>
                            <select class="form-select form-select-sm" id="vf-status">
                                <option value="Active">Active</option>
                                <option value="Archived">Archived</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="checkbox" id="vf-default">
                                <label class="form-check-label fs-9" for="vf-default">Default</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-info btn-sm text-white" id="btn-save-variant">
                            <span class="fas fa-save me-1" id="vf-save-icon"></span>
                            <span id="vf-save-text">Simpan Varian</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Tutup
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
        filters: {
            name: '',
            category: '',
            status: ''
        },

        // State next-code
        _nextCodeLoading: false,
        _previewCode: null,

        // State variant
        variantChemicalId: null,
        editVariantId: null,

        // ── Init ─────────────────────────────────────────────────────
        init() {
            this.initSelect2();
            this.initDatatable();
            this.initEvents();
            this.initFieldEvents();
            this.initVariantEvents();
            this.loadStats();
            document.getElementById('print-date').textContent = new Date().toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        // ── CSRF ─────────────────────────────────────────────────────
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
            if (r.status === 403) throw new Error('Sesi habis, muat ulang halaman.');
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

        // ── Select2 ──────────────────────────────────────────────────
        initSelect2() {
            const categoryAjax = {
                ajax: {
                    url: this.BASE + 'warehouse/master/chemical-categories/select2',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term ?? ''
                    }),
                    processResults: data => ({
                        results: (data.data ?? []).map(c => ({
                            id: c.id,
                            text: `${c.name} (${c.code})`
                        }))
                    }),
                },
            };

            // Kategori di form modal (multi-select)
            $('#f-category').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#chemicalModal'),
                placeholder: '— Pilih Kategori —',
                allowClear: true,
                width: '100%',
                closeOnSelect: false,
                ...categoryAjax,
            });

            // Kategori di filter offcanvas (single-select)
            $('#filter-category').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#filter-offcanvas'),
                placeholder: '— Semua Kategori —',
                allowClear: true,
                width: '100%',
                ...categoryAjax,
            });
        },

        // ── Stats ─────────────────────────────────────────────────────
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

        // ── DataTable ─────────────────────────────────────────────────
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
                        width: '210px'
                    },
                    {
                        targets: 2,
                        width: '160px'
                    },
                    {
                        targets: 3,
                        width: '110px'
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
                        width: '110px'
                    },
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
                                `<span class="badge badge-phoenix badge-phoenix-secondary fs-10 me-1 mb-1">${self.e(c)}</span>`
                            ).join('');
                        }
                    },
                    {
                        data: 'variant_count',
                        render: d => Number(d) > 0 ?
                            `<span class="badge badge-phoenix badge-phoenix-success fs-10">${self.e(d)} varian</span>` :
                            '<span class="text-muted fst-italic">—</span>'
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
                        render: (d, t, r) => self.fmtUser(d, r.created_by_employee ?? null)
                    },
                    {
                        data: 'updated_at',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: 'updated_by_name',
                        render: (d, t, r) => self.fmtUser(d, r.updated_by_employee ?? null)
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render: (d, t, r) => {
                            const variant = `<button class="btn btn-subtle-info btn-sm btn-variant"
                            data-id="${r.id}" data-code="${self.e(r.chemical_code)}"
                            data-name="${self.e(r.chemical_name)}" title="Kelola Varian">
                            <span class="fas fa-boxes-stacked"></span></button>`;
                            const edit = CAN_EDIT_CHEMICAL ?
                                `<button class="btn btn-subtle-primary btn-sm btn-edit"
                                data-id="${r.id}" title="Edit">
                                <span class="fas fa-pencil-alt"></span></button>` :
                                '';
                            const del = CAN_DELETE_CHEMICAL ?
                                `<button class="btn btn-subtle-danger btn-sm btn-delete"
                                data-id="${r.id}" data-name="${self.e(r.chemical_name)}" title="Hapus">
                                <span class="fas fa-trash"></span></button>` :
                                '';
                            return `<div class="btn-group btn-group-sm">${variant}${edit}${del}</div>`;
                        }
                    },
                ],
            });
        },

        // ── Modal Chemical — Create ───────────────────────────────────
        async openCreate() {
            if (this._nextCodeLoading) return;
            this._nextCodeLoading = true;

            this.editId = null;
            this.resetModal();
            document.getElementById('modal-title').textContent = 'Tambah Bahan Kimia';
            document.getElementById('modal-subtitle').textContent = 'Buat data bahan kimia baru';
            document.getElementById('save-text').textContent = 'Simpan';

            // Loading state di badge kode
            const codeDisplay = document.getElementById('f-code-display');
            codeDisplay.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:.7rem;height:.7rem;"></span>';
            codeDisplay.classList.add('opacity-50');

            // Buka modal langsung — fetch kode berjalan di background
            new bootstrap.Modal(document.getElementById('chemicalModal')).show();

            try {
                const d = await this.get(this.BASE + 'warehouse/master/chemicals/next-code');
                if (d.status === 'success') {
                    codeDisplay.textContent = d.code;
                    this._previewCode = d.code;
                } else {
                    codeDisplay.textContent = 'Otomatis';
                }
            } catch {
                codeDisplay.textContent = 'Otomatis';
            } finally {
                codeDisplay.classList.remove('opacity-50');
                this._nextCodeLoading = false;
            }
        },

        // ── Modal Chemical — Edit ─────────────────────────────────────
        async openEdit(id) {
            this.editId = id;
            this.resetModal();
            document.getElementById('modal-title').textContent = 'Edit Bahan Kimia';
            document.getElementById('modal-subtitle').textContent = 'Perbarui data bahan kimia';
            document.getElementById('save-text').textContent = 'Update';

            this.setLoading(true);
            new bootstrap.Modal(document.getElementById('chemicalModal')).show();

            try {
                const d = await this.get(this.BASE + `warehouse/master/chemicals/${id}`);
                if (d.status !== 'success') {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    bootstrap.Modal.getInstance(document.getElementById('chemicalModal'))?.hide();
                    return;
                }

                const row = d.data;
                document.getElementById('f-name').value = row.chemical_name ?? '';
                document.getElementById('f-code-display').textContent = row.chemical_code ?? '—';
                document.getElementById('f-desc').value = row.description ?? '';
                document.getElementById('f-status').value = row.status ?? 'Draft';
                document.getElementById('char-count').textContent = (row.description ?? '').length;

                // Set is-valid jika ada nilai
                if (row.chemical_name) this.markValid('f-name');
                if (row.status) this.markValid('f-status');

                // Populate Select2 kategori
                $('#f-category').empty().val(null).trigger('change');
                if (Array.isArray(row.categories) && row.categories.length) {
                    row.categories.forEach(c => {
                        const opt = new Option(
                            `${c.category_name} (${c.category_code ?? ''})`,
                            c.id, true, true
                        );
                        $('#f-category').append(opt);
                    });
                    $('#f-category').trigger('change');
                }

            } catch {
                this.toast('error', 'Gagal memuat data');
            } finally {
                this.setLoading(false);
            }
        },

        // ── Modal Chemical — Save ─────────────────────────────────────
        async save() {
            this.clearErrors();

            const fd = new FormData();
            fd.set('chemical_name', document.getElementById('f-name').value.trim());
            fd.set('description', document.getElementById('f-desc').value.trim());
            fd.set('status', document.getElementById('f-status').value);
            if (this.editId) fd.set('id', this.editId);

            const categoryIds = $('#f-category').val() ?? [];
            categoryIds.forEach(catId => fd.append('category_ids[]', catId));

            this.setLoading(true);
            try {
                const res = await this.post(this.BASE + 'warehouse/master/chemicals/store', fd);

                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('chemicalModal'))?.hide();
                    this.dt.ajax.reload(null, false);
                    this.loadStats();

                    // Tampilkan kode hanya saat CREATE (editId kosong)
                    const savedCode = !this.editId ? (res.code ?? this._previewCode ?? null) : null;
                    const msgHtml = savedCode ?
                        `${this.e(res.message)}<br><small class="text-muted">Kode: <strong>${this.e(savedCode)}</strong></small>` :
                        this.e(res.message);

                    // Satu toast saja — gunakan html supaya bisa multiline
                    Swal.fire({
                        toast: true,
                        position: 'top-right',
                        icon: 'success',
                        html: msgHtml,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                } else if (res.errors) {
                    this.showErrors(res.errors);
                } else {
                    document.getElementById('modal-alert').classList.remove('d-none');
                    document.getElementById('modal-alert-text').textContent = res.message ?? 'Terjadi kesalahan.';
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                this.setLoading(false);
                this._previewCode = null;
            }
        },

        // ── Modal helpers ─────────────────────────────────────────────
        resetModal() {
            ['f-name', 'f-desc'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.classList.remove('is-invalid', 'is-valid');
                }
            });
            document.getElementById('f-status').value = 'Draft';
            document.getElementById('f-code-display').textContent = 'Otomatis';
            document.getElementById('char-count').textContent = '0';
            document.getElementById('modal-alert').classList.add('d-none');
            $('#f-category').empty().val(null).trigger('change');
            this.clearErrors();
        },

        clearErrors() {
            document.querySelectorAll('#chemicalModal .is-invalid, #chemicalModal .is-valid')
                .forEach(el => el.classList.remove('is-invalid', 'is-valid'));
            document.querySelectorAll('#chemicalModal .invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.visibility = '';
            });
            document.getElementById('modal-alert').classList.add('d-none');
        },

        showErrors(errors) {
            const map = {
                chemical_name: ['f-name', 'err-chemical_name'],
                category_ids: ['f-category', 'err-category_ids'],
                description: ['f-desc', 'err-description'],
                status: ['f-status', 'err-status'],
            };
            Object.entries(errors).forEach(([f, msg]) => {
                const [inp, err] = map[f] ?? [];
                if (inp) this.markInvalid(inp, err, Array.isArray(msg) ? msg[0] : msg);
            });
        },

        markValid(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('is-invalid');
                el.classList.add('is-valid');
            }
        },

        markInvalid(inputId, errId, msg) {
            const el = document.getElementById(inputId);
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

        setLoading(on) {
            const btn = document.getElementById('btn-save');
            const ico = document.getElementById('save-icon');
            btn.disabled = on;
            ico.className = on ? 'spinner-border spinner-border-sm me-1' : 'fas fa-save me-1';
        },

        // ── Live field validation ─────────────────────────────────────
        initFieldEvents() {
            [{
                    input: 'f-name',
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
                const check = () => {
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
                el.addEventListener('input', check);
                el.addEventListener('change', check);
            });

            document.getElementById('f-desc')?.addEventListener('input', e => {
                document.getElementById('char-count').textContent = e.target.value.length;
            });
        },

        // ── Delete Chemical ───────────────────────────────────────────
        async deleteItem(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Bahan Kimia?',
                html: `<strong>${this.e(name)}</strong> akan dipindahkan ke sampah.<br>
                   <small class="text-muted">Dapat dipulihkan dari menu Sampah.</small>`,
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
                const res = await this.post(
                    this.BASE + `warehouse/master/chemicals/${id}/delete`,
                    new FormData()
                );
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

        // ── Filter ────────────────────────────────────────────────────
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
            document.getElementById('filter-status').value = '';
            $('#filter-category').val(null).trigger('change');
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
            document.getElementById('filter-toggle').classList.toggle('has-filter', !!labels.length);
            document.getElementById('filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('filter-summary').classList.toggle('d-none', !labels.length);
        },

        // ── Variant Modal ─────────────────────────────────────────────
        async openVariantModal(chemicalId, code, name) {
            this.variantChemicalId = chemicalId;
            this.editVariantId = null;
            document.getElementById('vm-code').textContent = code;
            document.getElementById('vm-name').textContent = name;
            this.resetVariantForm();
            new bootstrap.Modal(document.getElementById('variantModal')).show();
            await this.loadVariants();
        },

        async loadVariants() {
            const listEl = document.getElementById('variant-list');
            const emptyEl = document.getElementById('variant-empty');
            const loadingEl = document.getElementById('variant-loading');

            loadingEl.classList.remove('d-none');
            listEl.innerHTML = '';
            emptyEl.classList.add('d-none');

            try {
                const d = await this.get(
                    this.BASE + `warehouse/master/chemicals/${this.variantChemicalId}/variants`
                );
                if (d.status !== 'success') {
                    this.toast('error', d.message ?? 'Gagal memuat varian');
                    return;
                }
                if (!d.data.length) {
                    emptyEl.classList.remove('d-none');
                    return;
                }
                listEl.innerHTML = d.data.map(v => this.renderVariantRow(v)).join('');
            } catch {
                this.toast('error', 'Gagal memuat varian');
            } finally {
                loadingEl.classList.add('d-none');
            }
        },

        renderVariantRow(v) {
            const isDefault = Number(v.is_default) === 1;
            const price = v.price ?
                'Rp ' + Number(v.price).toLocaleString('id-ID') :
                '—';
            const pack = [v.packaging, v.packaging_size, v.unit].filter(Boolean).join(' ');
            const statusBadge = v.status === 'Active' ?
                '<span class="badge badge-phoenix badge-phoenix-success fs-10">Active</span>' :
                '<span class="badge badge-phoenix badge-phoenix-secondary fs-10">Archived</span>';
            const defaultBadge = isDefault ?
                '<span class="badge badge-phoenix badge-phoenix-success fs-10 ms-1"><span class="fas fa-star me-1"></span>Default</span>' :
                '';
            const defaultBtn = !isDefault ?
                `<button class="btn btn-subtle-success btn-sm btn-variant-default" data-id="${v.id}" title="Jadikan Default">
                   <span class="fas fa-star"></span>
               </button>` :
                '';

            return `
        <div class="variant-row ${isDefault ? 'is-default' : ''}" data-id="${v.id}">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <div class="variant-name">
                        ${this.e(v.variant_name)}${defaultBadge}
                    </div>
                    <div class="text-muted fs-10 mt-1">
                        ${pack ? `<span class="me-2"><span class="fas fa-box me-1"></span>${this.e(pack)}</span>` : ''}
                        <span class="me-2"><span class="fas fa-tag me-1"></span>${this.e(price)}</span>
                        ${statusBadge}
                    </div>
                </div>
                <div class="btn-group btn-group-sm flex-shrink-0">
                    ${defaultBtn}
                    <button class="btn btn-subtle-primary btn-sm btn-variant-edit"
                            data-id="${v.id}" title="Edit">
                        <span class="fas fa-pencil-alt"></span>
                    </button>
                    <button class="btn btn-subtle-danger btn-sm btn-variant-delete"
                            data-id="${v.id}" data-name="${this.e(v.variant_name)}" title="Hapus">
                        <span class="fas fa-trash"></span>
                    </button>
                </div>
            </div>
        </div>`;
        },

        resetVariantForm() {
            this.editVariantId = null;
            ['vf-name', 'vf-packaging', 'vf-size', 'vf-unit', 'vf-price'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.classList.remove('is-invalid');
                }
            });
            document.getElementById('vf-status').value = 'Active';
            document.getElementById('vf-default').checked = false;
            document.getElementById('vf-title').innerHTML =
                '<span class="fas fa-plus-circle me-1"></span>Tambah Varian';
            document.getElementById('vf-save-text').textContent = 'Simpan Varian';
            document.getElementById('btn-cancel-edit-variant').classList.add('d-none');
            document.getElementById('vf-alert').classList.add('d-none');
            document.getElementById('err-variant_name').textContent = '';
        },

        async editVariantRow(id) {
            try {
                const d = await this.get(
                    this.BASE + `warehouse/master/chemicals/${this.variantChemicalId}/variants`
                );
                if (d.status !== 'success') return;

                const v = (d.data ?? []).find(x => String(x.id) === String(id));
                if (!v) return;

                this.editVariantId = id;
                document.getElementById('vf-name').value = v.variant_name ?? '';
                document.getElementById('vf-packaging').value = v.packaging ?? '';
                document.getElementById('vf-size').value = v.packaging_size ?? '';
                document.getElementById('vf-unit').value = v.unit ?? '';
                document.getElementById('vf-price').value = v.price ?? '';
                document.getElementById('vf-status').value = v.status ?? 'Active';
                document.getElementById('vf-default').checked = Number(v.is_default) === 1;

                document.getElementById('vf-title').innerHTML =
                    '<span class="fas fa-pencil-alt me-1"></span>Edit Varian';
                document.getElementById('vf-save-text').textContent = 'Update Varian';
                document.getElementById('btn-cancel-edit-variant').classList.remove('d-none');
                document.getElementById('vf-alert').classList.add('d-none');
                document.getElementById('vf-name').classList.remove('is-invalid');
                document.getElementById('err-variant_name').textContent = '';

                // Scroll ke form
                document.getElementById('vf-name').scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            } catch {
                this.toast('error', 'Gagal memuat data varian');
            }
        },

        async saveVariant() {
            const nameEl = document.getElementById('vf-name');
            const name = nameEl.value.trim();

            // Reset error
            nameEl.classList.remove('is-invalid');
            document.getElementById('err-variant_name').textContent = '';
            document.getElementById('vf-alert').classList.add('d-none');

            if (!name) {
                nameEl.classList.add('is-invalid');
                document.getElementById('err-variant_name').textContent = 'Nama varian wajib diisi.';
                return;
            }

            const fd = new FormData();
            fd.set('variant_name', name);
            fd.set('packaging', document.getElementById('vf-packaging').value.trim());
            fd.set('packaging_size', document.getElementById('vf-size').value);
            fd.set('unit', document.getElementById('vf-unit').value.trim());
            fd.set('price', document.getElementById('vf-price').value);
            fd.set('status', document.getElementById('vf-status').value);
            fd.set('is_default', document.getElementById('vf-default').checked ? '1' : '0');

            const btn = document.getElementById('btn-save-variant');
            const ico = document.getElementById('vf-save-icon');
            btn.disabled = true;
            ico.className = 'spinner-border spinner-border-sm me-1';

            try {
                const url = this.editVariantId ?
                    this.BASE + `warehouse/master/chemicals/${this.variantChemicalId}/variants/${this.editVariantId}/update` :
                    this.BASE + `warehouse/master/chemicals/${this.variantChemicalId}/variants/store`;

                const res = await this.post(url, fd);

                if (res.status === 'success') {
                    this.toast('success', res.message);
                    this.resetVariantForm();
                    await this.loadVariants();
                    this.dt.ajax.reload(null, false);
                } else if (res.errors) {
                    Object.entries(res.errors).forEach(([f, msg]) => {
                        if (f === 'variant_name') {
                            nameEl.classList.add('is-invalid');
                            document.getElementById('err-variant_name').textContent =
                                Array.isArray(msg) ? msg[0] : msg;
                        }
                    });
                } else {
                    document.getElementById('vf-alert').textContent = res.message ?? 'Terjadi kesalahan.';
                    document.getElementById('vf-alert').classList.remove('d-none');
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                btn.disabled = false;
                ico.className = 'fas fa-save me-1';
            }
        },

        async deleteVariant(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Varian?',
                html: `<strong>${this.e(name)}</strong> akan dihapus permanen.`,
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
                const res = await this.post(
                    this.BASE + `warehouse/master/chemicals/variants/${id}/delete`,
                    new FormData()
                );
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    if (String(this.editVariantId) === String(id)) this.resetVariantForm();
                    await this.loadVariants();
                    this.dt.ajax.reload(null, false);
                } else {
                    this.toast('error', res.message);
                }
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        async setDefaultVariant(id) {
            try {
                const res = await this.post(
                    this.BASE + `warehouse/master/chemicals/${this.variantChemicalId}/variants/${id}/default`,
                    new FormData()
                );
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    await this.loadVariants();
                } else {
                    this.toast('error', res.message);
                }
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        // ── Variant Events ────────────────────────────────────────────
        initVariantEvents() {
            document.getElementById('btn-save-variant')
                ?.addEventListener('click', () => this.saveVariant());
            document.getElementById('btn-cancel-edit-variant')
                ?.addEventListener('click', () => this.resetVariantForm());

            document.getElementById('variantModal')
                ?.addEventListener('hidden.bs.modal', () => {
                    this.variantChemicalId = null;
                    this.editVariantId = null;
                    this.resetVariantForm();
                });

            $(document).on('click', '.btn-variant', e => {
                const b = $(e.currentTarget);
                this.openVariantModal(b.data('id'), b.data('code'), b.data('name'));
            });
            $(document).on('click', '.btn-variant-edit', e => this.editVariantRow($(e.currentTarget).data('id')));
            $(document).on('click', '.btn-variant-delete', e => {
                const b = $(e.currentTarget);
                this.deleteVariant(b.data('id'), b.data('name'));
            });
            $(document).on('click', '.btn-variant-default', e => this.setDefaultVariant($(e.currentTarget).data('id')));
        },

        // ── Main Events ───────────────────────────────────────────────
        initEvents() {
            document.getElementById('btn-refresh')
                ?.addEventListener('click', () => this.dt.ajax.reload(() => this.loadStats(), false));
            document.getElementById('btn-create')
                ?.addEventListener('click', () => this.openCreate());
            document.getElementById('btn-save')
                ?.addEventListener('click', () => this.save());
            document.getElementById('btn-apply-filter')
                ?.addEventListener('click', () => this.applyFilter());
            document.getElementById('btn-reset-filter')
                ?.addEventListener('click', () => this.resetFilter());

            // Blur supaya keyboard mobile dismiss saat modal ditutup
            document.getElementById('chemicalModal')
                ?.addEventListener('hide.bs.modal', () => {
                    if (document.activeElement instanceof HTMLElement) document.activeElement.blur();
                });

            $(document).on('click', '.btn-edit', e => this.openEdit($(e.currentTarget).data('id')));
            $(document).on('click', '.btn-delete', e => {
                const b = $(e.currentTarget);
                this.deleteItem(b.data('id'), b.data('name'));
            });
        },

        // ── Formatters ────────────────────────────────────────────────
        fmtDate(d) {
            if (!d) return '<span class="text-muted">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">${dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                <small class="text-muted">${dt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</small>`;
        },

        fmtStatus(status) {
            if (!status) return '<span class="text-muted fst-italic">—</span>';
            const map = {
                active: ['active', 'fa-check-circle'],
                draft: ['draft', 'fa-pencil-alt'],
                archived: ['archived', 'fa-archive'],
            };
            const [cls, ico] = map[status.toLowerCase()] ?? ['draft', 'fa-pencil-alt'];
            return `<span class="badge-status ${cls}">
                    <span class="fas ${ico}"></span>${this.e(status)}
                </span>`;
        },

        fmtUser(username, employeeName = null) {
            if (!username && !employeeName) return '<span class="text-muted fst-italic">—</span>';

            // Jika tidak ada employee — tampilkan username saja
            if (!employeeName) {
                return `<span class="badge badge-phoenix badge-phoenix-info rounded-pill fs-10 p-1 px-2"
                         title="Username: ${this.e(username)}">
                        <span class="fas fa-user-circle me-1"></span>${this.e(username)}
                    </span>`;
            }

            // Ada employee — tampilkan nama karyawan, username di tooltip
            return `<span class="badge badge-phoenix badge-phoenix-primary rounded-pill fs-10 p-1 px-3"
                     title="Karyawan: ${this.e(employeeName)}&#013;Username: ${this.e(username)}"
                     style="cursor:help">
                    <span class="fas fa-user me-1"></span>${this.e(employeeName)}
                </span>`;
        },

        // ── Utilities ─────────────────────────────────────────────────
        e(s) {
            if (s === null || s === undefined) return '';
            return String(s)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        },

        toast(type, msg) {
            Swal.fire({
                toast: true,
                position: 'top-right',
                icon: type,
                title: this.e(msg),
                showConfirmButton: false,
                timer: type === 'success' ? 2000 : 3500,
                timerProgressBar: true,
            });
        },
    };

    $(document).ready(() => Chemical.init());
</script>
<?= $this->endSection() ?>