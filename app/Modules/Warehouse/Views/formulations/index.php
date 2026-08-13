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

    #formulation-table_wrapper {
        max-width: 100%;
    }

    #formulation-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #formulation-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #formulation-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #formulation-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #formulation-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #formulation-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #formulation-table_wrapper .dataTables_filter label,
    #formulation-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #formulation-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #formulation-table_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #formulation-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #formulation-table {
        width: 100% !important;
    }

    .print-header {
        display: none;
    }

    /* Compare highlight */
    .compare-highlight {
        background: rgba(var(--phoenix-warning-rgb), 0.08) !important;
        border-left: 3px solid var(--phoenix-warning) !important;
        border-right: 3px solid var(--phoenix-warning) !important;
    }

    .compare-highlight strong {
        color: var(--phoenix-warning) !important;
    }

    /* Badge di card */
    .compare-card .badge-phoenix {
        font-size: 0.6rem;
        padding: 0.2rem 0.6rem;
    }

    /* ── Compare Styles ────────────────────────────────────────────────── */
    .compare-card {
        border-radius: 0.75rem;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid var(--phoenix-border-color);
    }

    .compare-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .compare-card .card-header {
        border-bottom: 1px solid var(--phoenix-border-color);
        padding: 0.75rem 1rem;
        border-radius: 0.75rem 0.75rem 0 0 !important;
    }

    .compare-card .card-body {
        padding: 0.75rem 1rem;
    }

    .compare-card .card-body .text-muted {
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .compare-table th,
    .compare-table td {
        padding: 0.5rem 0.75rem;
        vertical-align: middle;
    }

    .compare-table thead th {
        background: #1a1a2e;
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .compare-table tbody tr:hover {
        background-color: rgba(var(--phoenix-primary-rgb), 0.03);
    }

    .compare-table .table-light td {
        background-color: rgba(var(--phoenix-secondary-rgb), 0.04);
    }

    .compare-table .badge-phoenix {
        font-size: 0.65rem;
        padding: 0.2rem 0.6rem;
    }

    /* Status badge di card */
    .compare-card .badge-phoenix {
        font-size: 0.6rem;
        padding: 0.2rem 0.6rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .compare-card .col-6 {
            width: 100% !important;
        }
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

    .formulation-code {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        font-size: .8rem;
        background: rgba(var(--phoenix-primary-rgb), .06);
        padding: .15rem .5rem;
        border-radius: 4px;
        border: 1px solid rgba(var(--phoenix-primary-rgb), .1);
        display: inline-block;
    }

    .version-badge {
        font-size: .6rem;
        padding: .15rem .4rem;
        border-radius: 3px;
        background: rgba(var(--phoenix-secondary-rgb), .08);
        color: var(--phoenix-secondary-color);
        border: 1px solid rgba(var(--phoenix-secondary-rgb), .15);
        white-space: nowrap;
    }

    /* Modal styles */
    .modal-content {
        border-radius: 0.75rem;
    }

    .modal-header {
        border-bottom: 1px solid var(--phoenix-border-color);
        padding: 1rem 1.25rem;
    }

    .modal-body {
        padding: 1.25rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-footer {
        border-top: 1px solid var(--phoenix-border-color);
        padding: 0.75rem 1.25rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .version-row:hover {
        background-color: rgba(var(--phoenix-primary-rgb), 0.02);
    }

    .compare-checkbox {
        cursor: pointer;
    }

    .compare-checkbox:checked+label {
        font-weight: 600;
        color: var(--phoenix-primary);
    }

    /* Barcode */
    .barcode-row {
        text-align: center;
        margin-top: 5px;
        padding: 5px 0;
        border-top: 1px dashed #ddd;
    }

    .barcode-row img {
        max-width: 100%;
        max-height: 40px;
    }

    .barcode-row .barcode-text {
        font-size: 7pt;
        font-family: 'Courier New', monospace;
        letter-spacing: 0.1em;
        margin-top: 2px;
        color: #333;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Sama seperti sebelumnya hingga table -->
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
            <button class="btn btn-subtle-secondary btn-sm" id="btn-compare-formulations">
                <span class="fas fa-code-compare me-1"></span>Komparasi
            </button>
            <?php if (canDo('warehouse.formulations.manage')): ?>
                <a href="<?= site_url('warehouse/formulations/trash') ?>" class="btn btn-subtle-danger btn-sm">
                    <span class="fas fa-trash-alt me-1"></span>Sampah
                    <span class="badge bg-danger ms-1 d-none" id="trash-badge">0</span>
                </a>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <?php if (canDo('warehouse.formulations.manage')): ?>
                <a href="<?= site_url('warehouse/formulations/create') ?>" class="btn btn-primary btn-sm">
                    <span class="fas fa-plus me-1"></span>Tambah Formulasi
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Print header -->
    <div class="print-header mb-3">
        <h5 class="fw-bold mb-1">Daftar Formulasi</h5>
        <div class="text-muted small">Dicetak: <span id="print-date"></span></div>
        <hr class="my-2">
    </div>

    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="formulation-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Formulasi</th>
                    <th>Proses</th>
                    <th>Sub Proses</th>
                    <th> Total Nilai</th>
                    <th>Jumlah <br> Kimia</th>
                    <th>Status</th>
                    <th>Versi</th>
                    <th>Last Used</th>
                    <th>Dibuat Oleh</th>
                    <th class="text-end no-print">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Filter toggle dan offcanvas (sama) -->
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
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Proses</label>
                <select class="form-select form-select-sm" id="filter-process-type">
                    <option value="">Semua</option>
                    <option value="Dyeing">Dyeing</option>
                    <option value="Finishing">Finishing</option>
                    <option value="Other">Lainnya</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Sub Proses</label>
                <select class="form-select form-select-sm" id="filter-sub-process-type">
                    <option value="">Semua</option>
                    <option value="Dyeing">Dyeing</option>
                    <option value="Dipping">Dipping</option>
                    <option value="Dipping 1">Dipping 1</option>
                    <option value="Dipping 2">Dipping 2</option>
                    <option value="Dip+Coat">Dip+Coat</option>
                    <option value="Coating">Coating</option>
                    <option value="Spray">Spray</option>
                    <option value="Coating_Foam">Coating Foam</option>
                    <option value="Finishing">Finishing</option>
                    <option value="Other">Lainnya</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Group</label>
                <select class="form-select form-select-sm" id="filter-group" style="width:100%">
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

<!-- Modals (Versi, Preview, Komparasi) -->
<!-- ─── Modal Riwayat Versi ────────────────────────────────────────── -->
<div class="modal fade" id="versionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <span class="fas fa-clock-rotate-left"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Riwayat Versi</h5>
                        <p class="text-muted fs-10 mb-0" id="versions-modal-subtitle">—</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0" id="versions-list-table">
                        <thead>
                            <tr>
                                <th>Versi</th>
                                <th class="text-center">Aktif</th>
                                <th>Status</th>
                                <th>Total Nilai</th>
                                <th>Satuan</th>
                                <th>Catatan</th>
                                <th>Last Used</th>
                                <th>Tanggal</th>
                                <th>Dibuat Oleh</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="versions-list-body">
                            <tr>
                                <td colspan="10" class="text-center text-muted py-3">Memuat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top bg-body-tertiary">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ─── Modal Preview Versi ────────────────────────────────────────── -->
<div class="modal fade" id="versionPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <span class="fas fa-eye"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="preview-title">Preview Versi</h5>
                        <p class="text-muted fs-10 mb-0" id="preview-subtitle">—</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-subtle-secondary btn-sm" id="btn-print-preview">
                        <span class="fas fa-print me-1"></span>Print
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body px-4 py-3" id="preview-body">
                <div class="text-center text-muted py-4">Memuat...</div>
            </div>
            <div class="modal-footer border-top bg-body-tertiary">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ─── Modal Komparasi Formulasi ────────────────────────────────────── -->
<div class="modal fade" id="compareModalFormulation" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <span class="fas fa-code-compare"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Komparasi Formulasi</h5>
                        <p class="text-muted fs-10 mb-0">Bandingkan 2 atau lebih formulasi/versi</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-subtle-secondary btn-sm" id="btn-print-compare" style="display:none;">
                        <span class="fas fa-print me-1"></span>Print
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body px-4 py-3">
                <!-- Repeater Container -->
                <div id="compare-repeater">
                    <div class="compare-item row g-3 mb-3" data-index="0">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Formulasi</label>
                            <select class="form-select form-select-sm compare-formulation-select" style="width:100%">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Versi</label>
                            <select class="form-select form-select-sm compare-version-select" style="width:100%">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-subtle-danger btn-sm btn-remove-compare" style="display:none;">
                                <span class="fas fa-times"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="button" class="btn btn-subtle-primary btn-sm" id="btn-add-compare">
                        <span class="fas fa-plus me-1"></span>Tambah Formulasi
                    </button>
                </div>
                <hr>
                <div class="mt-3">
                    <button type="button" class="btn btn-primary btn-sm" id="btn-do-compare">
                        <span class="fas fa-code-compare me-1"></span>Komparasi
                    </button>
                </div>
                <div id="compare-result" class="mt-3"></div>
            </div>
            <div class="modal-footer border-top bg-body-tertiary">
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
    const CAN_MANAGE_FORMULATION = <?= json_encode(canDo('warehouse.formulations.manage')) ?>;

    const Formulation = {
        BASE: '<?= base_url() ?>',
        dt: null,
        filters: {
            name: '',
            process_type: '',
            sub_process_type: '',
            group: '',
            status: ''
        },
        compareCounter: 0,
        compareData: [],
        isComparing: false,
        lastCompareResults: null,

        // ============================================================
        // FORMATTER - Desimal (tanpa %)
        // ============================================================

        formatNumber(value) {
            if (value === null || value === undefined || value === '') return '—';
            const num = parseFloat(value);
            if (isNaN(num)) return '—';
            if (Number.isInteger(num)) {
                return num.toString();
            }
            return num.toFixed(4).replace(/\.?0+$/, '');
        },

        formatValue(value) {
            if (value === null || value === undefined || value === '') return '—';
            const num = parseFloat(value);
            if (isNaN(num)) return '—';
            return this.formatNumber(num);
        },

        unitLabel(unit) {
            const labels = {
                owf: 'owf %',
                percent: '%',
                gpl: 'gpl'
            };
            return labels[unit] ?? unit ?? '';
        },

        // ============================================================
        // INIT
        // ============================================================

        init() {
            const statusEl = document.getElementById('filter-status');
            if (statusEl) statusEl.value = this.filters.status;
            this.initSelect2();
            this.initDatatable();
            this.initEvents();
            this.updateFilterUI();
            this.loadStats();
            document.getElementById('print-date').textContent = new Date().toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        // ============================================================
        // CSRF & AJAX HELPERS
        // ============================================================

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

        // ============================================================
        // SELECT2
        // ============================================================

        initSelect2() {
            $('#filter-group').select2({
                dropdownParent: $('#filter-offcanvas'),
                theme: 'bootstrap-5',
                placeholder: '— Semua Group —',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: this.BASE + 'warehouse/formulations/groups/select2',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: (data.data ?? []).map(g => ({
                            id: g.id,
                            text: g.name
                        }))
                    }),
                },
            });
        },

        // ============================================================
        // STATS
        // ============================================================

        async loadStats() {
            try {
                const d = await this.get(this.BASE + 'warehouse/formulations/stats');
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

        // ============================================================
        // DATATABLE
        // ============================================================

        initDatatable() {
            const self = this;
            this.dt = $('#formulation-table').DataTable({
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
                    searchPlaceholder: 'Cari formulasi...',
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
                    url: this.BASE + 'warehouse/formulations/datatables',
                    type: 'GET',
                    data: d => {
                        d.filter_name = self.filters.name;
                        d.filter_process_type = self.filters.process_type;
                        d.filter_sub_process_type = self.filters.sub_process_type;
                        d.filter_group_id = self.filters.group;
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
                        width: '100px'
                    },
                    {
                        targets: 3,
                        width: '120px'
                    },
                    {
                        targets: 4,
                        width: '100px'
                    },
                    {
                        targets: 5,
                        width: '100px'
                    },
                    {
                        targets: 6,
                        width: '100px'
                    },
                    {
                        targets: 7,
                        width: '80px'
                    },
                    {
                        targets: 8,
                        width: '130px'
                    },
                    {
                        targets: 9,
                        width: '130px'
                    },
                    {
                        targets: 10,
                        width: '130px'
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
                            `<span class="fw-semibold">${self.e(r.formulation_name)}</span>
                             <div class="text-muted small"><span class="formulation-code">${self.e(r.formulation_code)}</span></div>
                             ${r.group_name ? `<div class="text-muted small"><span class="fas fa-tag me-1" style="font-size:.6rem;"></span>${self.e(r.group_name)}</div>` : ''}`
                    },
                    {
                        data: 'process_type',
                        render: d => {
                            const colors = {
                                'Dyeing': 'primary',
                                'Finishing': 'info',
                                'Other': 'secondary'
                            };
                            return `<span class="badge badge-phoenix badge-phoenix-${colors[d] || 'secondary'} fs-10 p-2 px-2">${self.e(d)}</span>`;
                        }
                    },
                    {
                        data: 'process_sub_type',
                        render: d => {
                            const labels = {
                                'Dyeing': 'Dyeing',
                                'Dipping': 'Dipping',
                                'Dipping 1': 'Dipping 1',
                                'Dipping 2': 'Dipping 2',
                                'Dip+Coat': 'Dip+Coat',
                                'Coating': 'Coating',
                                'Spray': 'Spray',
                                'Coating_Foam': 'Coating Foam',
                                'Finishing': 'Finishing',
                                'Other': 'Lainnya'
                            };
                            return `<span class="badge badge-phoenix badge-phoenix-secondary fs-10 p-2 px-2">${self.e(labels[d] || d)}</span>`;
                        }
                    },
                    {
                        data: null,
                        render: (d, t, r) => {
                            const val = r.output_percentage;
                            if (val === null || val === undefined || val === '') {
                                return '<span class="text-muted fst-italic">—</span>';
                            }
                            return `<span class="fw-semibold">${self.formatValue(val)}</span>`;
                        }
                    },
                    {
                        data: 'item_count',
                        render: d => Number(d) > 0 ?
                            `<span class="badge badge-phoenix badge-phoenix-success fs-10 p-2 px-2">${self.e(d)} item</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'status',
                        render: d => self.fmtStatus(d)
                    },
                    {
                        data: 'version_no',
                        render: d => d ? `<span class="version-badge">v${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'last_used_at',
                        render: d => d ? self.fmtDate(d) : '<span class="text-muted fst-italic">Belum pernah</span>'
                    },
                    {
                        data: 'created_by_name',
                        render: (d, t, r) => self.fmtUser(d, r.created_by_employee)
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render: (d, t, r) => {
                            const versions = `<button class="btn btn-subtle-info btn-sm btn-versions" data-id="${r.id}" data-name="${self.e(r.formulation_name)}" title="Riwayat Versi"><span class="fas fa-clock-rotate-left"></span></button>`;
                            const edit = CAN_MANAGE_FORMULATION ?
                                `<a href="${self.BASE}warehouse/formulations/${r.id}/edit" class="btn btn-subtle-primary btn-sm" title="Edit"><span class="fas fa-pencil-alt"></span></a>` :
                                '';
                            const copy = CAN_MANAGE_FORMULATION ? // ⬅️ baru
                                `<a href="${self.BASE}warehouse/formulations/${r.id}/copy" class="btn btn-subtle-secondary btn-sm" title="Salin sebagai Baru"><span class="fas fa-copy"></span></a>` : // ⬅️ baru
                                ''; // ⬅️ baru
                            const del = CAN_MANAGE_FORMULATION ?
                                `<button class="btn btn-subtle-danger btn-sm btn-delete" data-id="${r.id}" data-name="${self.e(r.formulation_name)}" title="Hapus"><span class="fas fa-trash"></span></button>` :
                                '';
                            return `<div class="btn-group btn-group-sm">${versions}${edit}${copy}${del}</div>`; // ⬅️ tambahkan ${copy}
                        }
                    },
                ],
            });
        },

        // ============================================================
        // FILTER
        // ============================================================

        applyFilter() {
            this.filters.name = document.getElementById('filter-name').value.trim();
            this.filters.process_type = document.getElementById('filter-process-type').value;
            this.filters.sub_process_type = document.getElementById('filter-sub-process-type').value;
            this.filters.group = $('#filter-group').val() ?? '';
            this.filters.status = document.getElementById('filter-status').value;
            this.dt.ajax.reload();
            this.updateFilterUI();
            bootstrap.Offcanvas.getInstance(document.getElementById('filter-offcanvas'))?.hide();
        },

        resetFilter() {
            this.filters = {
                name: '',
                process_type: '',
                sub_process_type: '',
                group: '',
                status: ''
            };
            document.getElementById('filter-name').value = '';
            document.getElementById('filter-process-type').value = '';
            document.getElementById('filter-sub-process-type').value = '';
            $('#filter-group').val(null).trigger('change');
            document.getElementById('filter-status').value = '';
            this.dt.ajax.reload();
            this.updateFilterUI();
        },

        updateFilterUI() {
            const labels = [];
            if (this.filters.name) labels.push(`Nama: "${this.filters.name}"`);
            if (this.filters.process_type) labels.push(`Proses: ${this.filters.process_type}`);
            if (this.filters.sub_process_type) {
                const labelsMap = {
                    'Dyeing': 'Dyeing',
                    'Dipping': 'Dipping',
                    'Dipping 1': 'Dipping 1',
                    'Dipping 2': 'Dipping 2',
                    'Dip+Coat': 'Dip+Coat',
                    'Coating': 'Coating',
                    'Spray': 'Spray',
                    'Coating_Foam': 'Coating Foam',
                    'Finishing': 'Finishing',
                    'Other': 'Lainnya'
                };
                labels.push(`Sub Proses: ${labelsMap[this.filters.sub_process_type] || this.filters.sub_process_type}`);
            }
            if (this.filters.group) {
                const txt = $('#filter-group option:selected').text();
                if (txt) labels.push(`Group: ${txt}`);
            }
            if (this.filters.status) labels.push(`Status: ${this.filters.status}`);
            document.getElementById('filter-toggle').classList.toggle('has-filter', labels.length > 0);
            document.getElementById('filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('filter-summary').classList.toggle('d-none', labels.length === 0);
        },

        // ============================================================
        // VERSIONS
        // ============================================================

        async openVersions(id, name) {
            document.getElementById('versions-modal-subtitle').textContent = name;
            const tbody = document.getElementById('versions-list-body');
            tbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted py-3"><span class="spinner-border spinner-border-sm me-2"></span>Memuat...</td></tr>';

            try {
                const d = await this.get(this.BASE + `warehouse/formulations/${id}/versions`);
                if (d.status !== 'success') {
                    tbody.innerHTML = `<tr><td colspan="10" class="text-center text-danger py-3">${this.e(d.message || 'Gagal memuat data')}</td></tr>`;
                    return;
                }

                if (!d.data || !d.data.length) {
                    tbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted py-3">Belum ada versi</td></tr>';
                    return;
                }

                tbody.innerHTML = d.data.map(v => {
                    const statusClass = v.status === 'Active' ? 'success' : v.status === 'Draft' ? 'warning' : 'secondary';
                    const outputDisplay = v.output_percentage !== null && v.output_percentage !== '-' ?
                        this.formatValue(v.output_percentage) :
                        '—';
                    const isActive = v.status === 'Active';
                    return `
                        <tr class="version-row">
                            <td><strong>#${v.version_no}</strong></td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center mb-0">
                                    <input class="form-check-input toggle-version-active" type="checkbox"
                                        role="switch"
                                        data-formulation="${id}"
                                        data-version="${v.id}"
                                        ${isActive ? 'checked' : ''}
                                        title="${isActive ? 'Klik untuk nonaktifkan versi ini' : 'Klik untuk aktifkan versi ini'}">
                                </div>
                            </td>
                            <td><span class="badge badge-phoenix badge-phoenix-${statusClass} fs-10">${v.status}</span></td>
                            <td>${outputDisplay}</td>
                            <td>${v.unit ? this.e(v.unit) : '<span class="text-muted fst-italic">—</span>'}</td>
                            <td>${v.notes ? this.e(v.notes) : '<span class="text-muted fst-italic">—</span>'}</td>
                            <td>${v.last_used_at ? this.fmtDate(v.last_used_at) : '<span class="text-muted fst-italic">Belum pernah</span>'}</td>
                            <td style="font-size:0.8rem;">${v.created_at ? new Date(v.created_at).toLocaleString('id-ID') : '-'}</td>
                            <td>${v.created_by_name ? this.e(v.created_by_name) : '<span class="text-muted fst-italic">—</span>'}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-subtle-primary btn-sm btn-preview-version" data-formulation="${id}" data-version="${v.id}" title="Preview"><span class="fas fa-eye"></span></button>
                                    ${isActive ?
                                        `<button class="btn btn-subtle-warning btn-sm btn-deactivate-version" data-formulation="${id}" data-version="${v.id}" title="Nonaktifkan"><span class="fas fa-ban"></span></button>` :
                                        `<button class="btn btn-subtle-success btn-sm btn-activate-version" data-formulation="${id}" data-version="${v.id}" title="Aktifkan"><span class="fas fa-check"></span></button>`
                                    }
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');

                tbody.querySelectorAll('.btn-preview-version').forEach(btn => {
                    btn.addEventListener('click', () => {
                        this.previewVersion(btn.dataset.formulation, btn.dataset.version);
                    });
                });
                tbody.querySelectorAll('.btn-activate-version').forEach(btn => {
                    btn.addEventListener('click', () => {
                        this.setVersionActive(btn.dataset.formulation, btn.dataset.version, name, true);
                    });
                });
                tbody.querySelectorAll('.btn-deactivate-version').forEach(btn => {
                    btn.addEventListener('click', () => {
                        this.setVersionActive(btn.dataset.formulation, btn.dataset.version, name, false);
                    });
                });
                tbody.querySelectorAll('.toggle-version-active').forEach(chk => {
                    chk.addEventListener('change', () => {
                        this.setVersionActive(chk.dataset.formulation, chk.dataset.version, name, chk.checked, chk);
                    });
                });

                bootstrap.Modal.getOrCreateInstance(document.getElementById('versionsModal')).show();
            } catch (e) {
                tbody.innerHTML = `<tr><td colspan="10" class="text-center text-danger py-3">Gagal memuat data</td></tr>`;
            }
        },

        async setVersionActive(formulationId, versionId, formulationName, activate, checkboxEl = null) {
            if (checkboxEl) checkboxEl.disabled = true;
            try {
                const endpoint = activate ? 'activate' : 'deactivate';
                const res = await this.post(this.BASE + `warehouse/formulations/${formulationId}/versions/${versionId}/${endpoint}`, new FormData());
                if (res.status === 'success') {
                    this.toast('success', res.message || (activate ? 'Versi berhasil diaktifkan' : 'Versi berhasil dinonaktifkan'));
                    await this.openVersions(formulationId, formulationName);
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                } else {
                    if (checkboxEl) {
                        checkboxEl.checked = !activate;
                        checkboxEl.disabled = false;
                    }
                    this.toast('error', res.message || 'Gagal mengubah status versi');
                }
            } catch (e) {
                if (checkboxEl) {
                    checkboxEl.checked = !activate;
                    checkboxEl.disabled = false;
                }
                this.toast('error', e.message);
            }
        },

        // ============================================================
        // GENERATE BARCODE
        // ============================================================

        generateBarcode(text) {
            if (!text) return '';
            return `https://barcode.tec-it.com/barcode.ashx?data=${encodeURIComponent(text)}&code=Code128&dpi=96&width=300&height=60`;
        },

        // ============================================================
        // PREVIEW VERSION
        // ============================================================

        async previewVersion(formulationId, versionId) {
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('versionPreviewModal'));
            const body = document.getElementById('preview-body');
            body.innerHTML = '<div class="text-center text-muted py-4"><span class="spinner-border spinner-border-sm me-2"></span>Memuat...</div>';
            modal.show();

            try {
                const d = await this.get(this.BASE + `warehouse/formulations/${formulationId}/versions/${versionId}/detail`);
                if (d.status !== 'success') {
                    body.innerHTML = `<div class="text-center text-danger py-4">${this.e(d.message || 'Gagal memuat data')}</div>`;
                    return;
                }

                const data = d.data;
                const barcode = this.generateBarcode(data.formulation.formulation_code);
                document.getElementById('preview-title').textContent = `${this.e(data.formulation.formulation_name)} - Versi #${data.version.version_no}`;
                document.getElementById('preview-subtitle').textContent = `Status: ${data.version.status} · Nilai: ${this.formatValue(data.version.output_percentage)}`;

                let itemsHtml = '';
                if (data.items && data.items.length) {
                    itemsHtml = `
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis</th>
                                        <th>Bahan / Label</th>
                                        <th>Nilai</th>
                                        <th>Satuan</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.items.map((item, i) => `
                                        <tr>
                                            <td>${i + 1}</td>
                                            <td><span class="badge badge-phoenix badge-phoenix-${item.composition_type === 'chemical' ? 'primary' : 'info'} fs-10">${item.composition_type === 'chemical' ? 'Chemical' : 'Softener Water'}</span></td>
                                            <td>${item.composition_type === 'chemical' ? this.e(item.chemical_name) + (item.chemical_code ? ` <small class="text-muted">(${this.e(item.chemical_code)})</small>` : '') : this.e(item.custom_label)}</td>
                                            <td style="text-align: right;"><strong>${this.formatValue(item.percentage)}</strong></td>
                                            <td>${item.unit ? this.e(this.unitLabel(item.unit)) : '<span class="text-muted fst-italic">—</span>'}</td>
                                            <td>${item.notes ? this.e(item.notes) : '<span class="text-muted fst-italic">—</span>'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total</td>
                                        <td><strong>${this.formatValue(data.items.reduce((sum, item) => sum + parseFloat(item.percentage || 0), 0))}</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    `;
                } else {
                    itemsHtml = '<div class="text-center text-muted py-3">Tidak ada komposisi</div>';
                }

                body.innerHTML = `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Kode</small>
                                    <strong>${this.e(data.formulation.formulation_code)}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Proses</small>
                                    <span class="badge badge-phoenix badge-phoenix-secondary fs-10">${this.e(data.formulation.process_type)}</span>
                                    ${data.formulation.process_sub_type ? `<span class="badge badge-phoenix badge-phoenix-secondary fs-10 ms-1">${this.e(data.formulation.process_sub_type)}</span>` : ''}
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Group</small>
                                    ${data.formulation.group_name ? this.e(data.formulation.group_name) : '<span class="text-muted fst-italic">—</span>'}
                                </div>
                                ${data.formulation.description ? `<div class="col-12"><small class="text-muted d-block">Deskripsi</small><p class="mb-0">${this.e(data.formulation.description)}</p></div>` : ''}
                            </div>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-3">Komposisi</h6>
                    ${itemsHtml}
                    <div class="barcode-row">
                        <img src="${barcode}" alt="Barcode" onerror="this.style.display='none'">
                        <div class="barcode-text">${this.e(data.formulation.formulation_code)}</div>
                    </div>
                    <div class="text-muted small mt-3">Dibuat: ${data.version.created_at ? new Date(data.version.created_at).toLocaleString('id-ID') : '-'} · ${data.version.created_by_name ? `Oleh: ${this.e(data.version.created_by_name)}` : ''}</div>
                `;

                // Print handler - F4 Landscape, 1 kartu + garis panduan potong
                const logoPath = '<?= base_url('assets/img/app/logo-regency-footer.png') ?>';
                document.getElementById('btn-print-preview').onclick = () => {
                    const printWindow = window.open('', '_blank', 'width=1100,height=800');
                    const printItems = data.items || [];
                    const totalValue = printItems.reduce((sum, item) => sum + parseFloat(item.percentage || 0), 0);

                    const renderItems = (items) => {
                        return items.map((item, idx) => {
                            const label = item.composition_type === 'chemical' ? (item.chemical_name || '-') : (item.custom_label || '-');
                            const unit = item.unit ? this.e(this.unitLabel(item.unit)) : '';
                            const num = idx + 1;
                            return `
                                <tr>
                                    <td class="col-no">${num}</td>
                                    <td class="col-name">${this.e(label)}</td>
                                    <td class="col-pct">${this.formatValue(item.percentage)}</td>
                                    <td class="col-unit">${unit ? `<span class="unit"> ${unit}</span>` : ''}</td>
                                </tr>
                            `;
                        }).join('');
                    };

                    const renderTotal = (items) => {
                        const total = items.reduce((sum, item) => sum + parseFloat(item.percentage || 0), 0);
                        return `
                            <tr class="total-row">
                                <td colspan="2" style="text-align:right;font-weight:bold;">TOTAL</td>
                                <td style="text-align:right;font-weight:bold;">${this.formatValue(total)}</td>
                            </tr>
                        `;
                    };

                    printWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Dokumen Formulasi</title>
                            <style>
                                @page {
                                    size: F4 landscape;
                                    margin: 0.5cm;
                                }
                                body {
                                    margin: 0;
                                    padding: 0;
                                    background: white;
                                    font-family: 'Courier New', Courier, monospace;
                                    font-size: 12pt;
                                    width: 100%;
                                    height: 100%;
                                }
                                .container {
                                    display: flex;
                                    align-items: stretch;
                                    width: 100%;
                                    min-height: 100vh;
                                }
                                .card {
                                    border: 1px solid #000;
                                    width: 95mm;
                                    max-width: 95mm;
                                    flex: 0 0 auto;
                                    padding: 2mm;
                                    box-sizing: border-box;
                                    display: flex;
                                    flex-direction: column;
                                    page-break-inside: avoid;
                                }
                                .cut-guide {
                                    flex: 0 0 auto;
                                    width: 6mm;
                                    margin: 0 4mm;
                                    border-left: 1px dashed #000;
                                }
                                .dashed {
                                    border-top: 1px dashed #000;
                                    margin: 3px 0;
                                }
                                .center {
                                    text-align: center;
                                }
                                .header-logo {
                                    display: block;
                                    margin: 0 auto 2px;
                                    max-height: 20px;
                                    max-width: 80%;
                                }
                                .company-name {
                                    font-weight: bold;
                                    font-size: 12pt;
                                    letter-spacing: 0.03em;
                                    text-transform: uppercase;
                                }
                                .company-sub {
                                    font-size: 10pt;
                                    font-weight: bold;
                                    letter-spacing: 0.08em;
                                    text-transform: uppercase;
                                }
                                .formulation-name {
                                    font-weight: bold;
                                    font-size: 10pt;
                                    text-transform: uppercase;
                                    margin-top: 3px;
                                    word-break: break-word;
                                }
                                .meta-row {
                                    display: flex;
                                    justify-content: space-between;
                                    font-size: 10pt;
                                }
                                .meta-row span:last-child {
                                    text-align: right;
                                }
                                table.items {
                                    width: 100%;
                                    border-collapse: collapse;
                                    margin-top: 2px;
                                    font-size: 9pt;
                                }
                                table.items th {
                                    text-align: left;
                                    font-size: 9pt;
                                    text-transform: uppercase;
                                    border-bottom: 1px dashed #000;
                                    padding: 1px 0;
                                }
                                table.items td {
                                    padding: 1px 0;
                                    vertical-align: top;
                                    font-size: 9pt;
                                }
                                .col-no {
                                    width: 12%;
                                }
                                .col-name {
                                    width: 55%;
                                    word-break: break-word;
                                }
                                .col-pct {
                                    width: 15%;
                                    text-align: center;
                                }
                                .col-unit {
                                    width: 10%;
                                    text-align: center;
                                }
                                .unit {
                                    font-size: 9pt;
                                }
                                .total-row {
                                    font-weight: bold;
                                    font-size: 9pt;
                                    border-top: 1px dashed #000;
                                }
                                .footer {
                                    margin-top: 4px;
                                    font-size: 8pt;
                                    text-align: center;
                                }
                                .status-tag {
                                    display: inline-block;
                                    border: 1px solid #000;
                                    padding: 0 3px;
                                    font-size: 5.2pt;
                                    font-weight: bold;
                                }
                                .barcode-row {
                                    text-align: center;
                                    margin-top: 3px;
                                    padding-top: 3px;
                                    border-top: 1px dashed #000;
                                }
                                .barcode-row img {
                                    max-width: 100%;
                                    max-height: 25px;
                                }
                                .barcode-text {
                                    font-size: 8pt;
                                    font-family: 'Courier New', monospace;
                                    letter-spacing: 0.1em;
                                    margin-top: 2px;
                                }

                                @media print {
                                    body { margin: 0; padding: 0; }
                                    .card { border: 1px solid #000; page-break-inside: avoid; }
                                    .cut-guide { border-left: 1px dashed #000; }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="container">
                                <div class="card">
                                    <div class="center">
                                        <img class="header-logo" src="${logoPath}" alt="Logo" onerror="this.style.display='none'">
                                        <div class="company-name">PT. SINAR CONTINENTAL</div>
                                        <div class="company-sub">DOKUMEN FORMULASI</div>
                                    </div>
                                    <div class="dashed"></div>
                                    <div class="formulation-name">${this.e(data.formulation.formulation_name)}</div>
                                    <div class="meta-row"><span>Kode: ${this.e(data.formulation.formulation_code)}</span><span>Versi #${data.version.version_no}</span></div>
                                    <div class="meta-row">
                                        <span>${this.e(data.formulation.process_type)}${data.formulation.process_sub_type ? '/' + this.e(data.formulation.process_sub_type) : ''}</span>
                                        <span class="status-tag">${this.e(data.version.status)}</span>
                                    </div>
                                    ${data.formulation.group_name ? `<div class="meta-row"><span>Group: ${this.e(data.formulation.group_name)}</span><span></span></div>` : ''}
                                    <div class="dashed"></div>
                                    ${printItems.length > 0 ? `
                                    <table class="items">
                                        <thead><tr><th class="col-no">#</th><th class="col-name">Bahan</th><th class="col-pct">Nilai</th><th class="col-unit">Unit</th></tr></thead>
                                        <tbody>
                                            ${renderItems(printItems)}
                                            ${renderTotal(printItems)}
                                        </tbody>
                                    </table>
                                    ` : '<div class="center" style="padding:8px 0;">Tidak ada komposisi</div>'}
                                    <div class="barcode-row">
                                        <img src="${this.generateBarcode(data.formulation.formulation_code)}" alt="Barcode" onerror="this.style.display='none'">
                                        <div class="barcode-text">${this.e(data.formulation.formulation_code)}</div>
                                    </div>
                                    <div class="dashed"></div>
                                    <div class="footer">
                                        Dicetak: ${new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })} ${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}<br>
                                        DIVISI 3A
                                    </div>
                                </div>
                                <div class="cut-guide"></div>
                            </div>
                        </body>
                        </html>
                    `);
                    printWindow.document.close();
                    printWindow.onload = () => {
                        printWindow.focus();
                        printWindow.print();
                    };
                };
            } catch (e) {
                body.innerHTML = `<div class="text-center text-danger py-4">Gagal memuat data</div>`;
            }
        },

        // ============================================================
        // COMPARE WITH REPEATER
        // ============================================================

        async openCompare() {
            this.compareCounter = 0;
            this.compareData = [];
            this.isComparing = false;
            this.lastCompareResults = null;
            document.getElementById('compare-result').innerHTML = '';
            document.getElementById('btn-print-compare').style.display = 'none';

            const container = document.getElementById('compare-repeater');
            container.innerHTML = '';
            this.addCompareItem();

            bootstrap.Modal.getOrCreateInstance(document.getElementById('compareModalFormulation')).show();

            setTimeout(() => {
                this.initCompareSelects();
            }, 500);
        },

        addCompareItem(data = null) {
            const container = document.getElementById('compare-repeater');
            const index = this.compareCounter++;

            const div = document.createElement('div');
            div.className = 'compare-item row g-3 mb-3';
            div.dataset.index = index;

            div.innerHTML = `
                <div class="col-md-5">
                    <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Formulasi</label>
                    <select class="form-select form-select-sm compare-formulation-select" style="width:100%" data-index="${index}">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Versi</label>
                    <select class="form-select form-select-sm compare-version-select" style="width:100%" data-index="${index}" disabled>
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-subtle-danger btn-sm btn-remove-compare" data-index="${index}" ${container.children.length <= 1 ? 'style="display:none;"' : ''}>
                        <span class="fas fa-times"></span>
                    </button>
                </div>
            `;

            container.appendChild(div);

            const $formSelect = $(div).find('.compare-formulation-select');
            $formSelect.select2({
                theme: 'bootstrap-5',
                placeholder: '— Pilih Formulasi —',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#compareModalFormulation'),
                ajax: {
                    url: this.BASE + 'warehouse/formulations/select2',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => {
                        return {
                            results: (data.data ?? []).map(f => ({
                                id: f.id,
                                text: `${f.name} (${f.code})`
                            }))
                        };
                    }
                },
            });

            $formSelect.on('change', function() {
                const formulationId = $(this).val();
                const $versionSelect = $(div).find('.compare-version-select');

                if (formulationId) {
                    $versionSelect.prop('disabled', false);
                    $versionSelect.empty().append('<option value="">Memuat versi...</option>');
                    Formulation.getVersionsForCompare(formulationId, $versionSelect);
                } else {
                    $versionSelect.prop('disabled', true);
                    $versionSelect.empty().append('<option value=""></option>');
                }
            });

            div.querySelector('.btn-remove-compare').addEventListener('click', () => {
                const idx = parseInt(div.dataset.index);
                this.compareData = this.compareData.filter(d => d.index !== idx);
                $(div).find('.compare-formulation-select').select2('destroy');
                $(div).find('.compare-version-select').select2('destroy');
                div.remove();
                this.updateRemoveButtons();
            });

            if (data) {
                setTimeout(() => {
                    const option = new Option(data.formulation_name, data.formulation_id, true, true);
                    $formSelect.append(option).trigger('change');
                    setTimeout(() => {
                        const $versionSelect = $(div).find('.compare-version-select');
                        const versionOption = new Option(`Versi #${data.version_no} (${data.version_status})`, data.version_id, true, true);
                        $versionSelect.append(versionOption).trigger('change');
                        this.compareData.push({
                            index: parseInt(div.dataset.index),
                            formulation_id: data.formulation_id,
                            version_id: data.version_id
                        });
                    }, 500);
                }, 300);
            }

            this.updateRemoveButtons();
        },

        updateRemoveButtons() {
            const items = document.querySelectorAll('.compare-item');
            items.forEach((item) => {
                const btn = item.querySelector('.btn-remove-compare');
                if (btn) {
                    btn.style.display = items.length <= 1 ? 'none' : 'block';
                }
            });
        },

        async getVersionsForCompare(formulationId, $select) {
            try {
                const url = this.BASE + `warehouse/formulations/${formulationId}/versions`;
                const d = await this.get(url);

                if (d.status === 'success' && d.data && d.data.length) {
                    $select.empty().append('<option value=""></option>');
                    d.data.forEach(v => {
                        $select.append(`<option value="${v.id}">Versi #${v.version_no} (${v.status})</option>`);
                    });
                } else {
                    $select.empty().append('<option value="">Tidak ada versi</option>');
                }
            } catch (e) {
                console.error('Error loading versions:', e);
                $select.empty().append('<option value="">Gagal memuat versi</option>');
            }
        },

        initCompareSelects() {
            document.querySelectorAll('.compare-item').forEach(item => {
                const $formSelect = $(item).find('.compare-formulation-select');
                if (!$formSelect.data('select2')) {
                    $formSelect.select2({
                        theme: 'bootstrap-5',
                        placeholder: '— Pilih Formulasi —',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#compareModalFormulation'),
                        ajax: {
                            url: this.BASE + 'warehouse/formulations/select2',
                            dataType: 'json',
                            delay: 250,
                            data: params => ({
                                search: params.term
                            }),
                            processResults: data => {
                                return {
                                    results: (data.data ?? []).map(f => ({
                                        id: f.id,
                                        text: `${f.name} (${f.code})`
                                    }))
                                };
                            }
                        },
                    });

                    $formSelect.on('change', function() {
                        const formulationId = $(this).val();
                        const $versionSelect = $(item).find('.compare-version-select');

                        if (formulationId) {
                            $versionSelect.prop('disabled', false);
                            $versionSelect.empty().append('<option value="">Memuat versi...</option>');
                            Formulation.getVersionsForCompare(formulationId, $versionSelect);
                        } else {
                            $versionSelect.prop('disabled', true);
                            $versionSelect.empty().append('<option value=""></option>');
                        }
                    });
                }
            });
        },

        async doCompare() {
            if (this.isComparing) return;
            this.isComparing = true;

            const resultDiv = document.getElementById('compare-result');
            resultDiv.innerHTML = '<div class="text-center text-muted py-3"><span class="spinner-border spinner-border-sm me-2"></span>Memuat komparasi...</div>';

            const items = document.querySelectorAll('.compare-item');
            const selections = [];
            let hasError = false;

            items.forEach(item => {
                const formulationId = $(item).find('.compare-formulation-select').val();
                const versionId = $(item).find('.compare-version-select').val();

                if (!formulationId || !versionId) {
                    hasError = true;
                    return;
                }

                selections.push({
                    formulation_id: parseInt(formulationId),
                    version_id: parseInt(versionId)
                });
            });

            if (hasError || selections.length < 2) {
                resultDiv.innerHTML = `<div class="alert alert-warning">Pilih minimal 2 formulasi dengan versi yang valid</div>`;
                this.isComparing = false;
                return;
            }

            try {
                const results = [];
                for (const sel of selections) {
                    const url = this.BASE + `warehouse/formulations/${sel.formulation_id}/versions/${sel.version_id}/detail`;
                    const d = await this.get(url);

                    if (d.status === 'success' && d.data) {
                        results.push(d.data);
                    } else {
                        throw new Error(d.message || 'Gagal memuat data');
                    }
                }

                if (results.length < 2) {
                    resultDiv.innerHTML = `<div class="alert alert-danger">Gagal memuat data komparasi</div>`;
                    this.isComparing = false;
                    return;
                }

                this.lastCompareResults = results;
                this.renderCompareResults(results, resultDiv);
            } catch (e) {
                console.error('Compare error:', e);
                resultDiv.innerHTML = `<div class="alert alert-danger">Gagal memuat komparasi: ${e.message}</div>`;
            }

            this.isComparing = false;
        },

        renderCompareResults(results, resultDiv) {
            // Build cards for each formulation
            let cardsHtml = '';
            results.forEach((r, idx) => {
                const name = r.formulation?.formulation_name || 'Formulasi ' + (idx + 1);
                const code = r.formulation?.formulation_code || '-';
                const version = r.version?.version_no || '?';
                const status = r.version?.status || 'Draft';
                const statusClass = status === 'Active' ? 'success' : status === 'Draft' ? 'warning' : 'secondary';
                const totalValue = this.formatValue(r.version?.output_percentage);
                const itemCount = (r.items || []).length;

                const units = (r.items || []).map(i => this.unitLabel(i.unit)).filter(Boolean); // sebelumnya: i.unit
                const unitList = [...new Set(units)].join(', ') || '—';

                cardsHtml += `
                    <div class="col-md-${Math.floor(12 / results.length)} mb-3">
                        <div class="card compare-card h-100">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-bold">${this.e(name)}</h6>
                                        <small class="text-muted">${this.e(code)}</small>
                                    </div>
                                    <span class="badge badge-phoenix badge-phoenix-${statusClass} fs-10">${status}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-6"><small class="text-muted d-block">Versi</small><span class="fw-semibold">#${version}</span></div>
                                    <div class="col-6"><small class="text-muted d-block">Nilai</small><span class="fw-semibold">${totalValue}</span></div>
                                    <div class="col-6"><small class="text-muted d-block">Proses</small><span class="badge badge-phoenix badge-phoenix-secondary fs-10">${this.e(r.formulation?.process_type || '-')}</span></div>
                                    <div class="col-6"><small class="text-muted d-block">Sub Proses</small><span class="badge badge-phoenix badge-phoenix-info fs-10">${this.e(r.formulation?.process_sub_type || '-')}</span></div>
                                    <div class="col-12"><small class="text-muted d-block">Group</small><span>${r.formulation?.group_name ? this.e(r.formulation.group_name) : '<span class="text-muted fst-italic">—</span>'}</span></div>
                                    <div class="col-12"><small class="text-muted d-block">Jumlah Item</small><span class="fw-semibold">${itemCount} item</span></div>
                                    <div class="col-12"><small class="text-muted d-block">Satuan</small><span class="fw-semibold">${unitList}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            // Build comparison table
            const allItems = new Map();
            results.forEach((r, idx) => {
                (r.items || []).forEach(item => {
                    const key = item.composition_type === 'chemical' ? `chem_${item.chemical_id}` : `soft_${item.custom_label}`;
                    if (!allItems.has(key)) {
                        allItems.set(key, {
                            type: item.composition_type,
                            label: item.composition_type === 'chemical' ? (item.chemical_name || 'Unknown') : (item.custom_label || 'Unknown'),
                            code: item.chemical_code || null,
                            unit: item.unit || null,
                            values: {}
                        });
                    }
                    const data = allItems.get(key);
                    data.values[idx] = item.percentage || 0;
                });
            });

            let tableHeader = '<tr><th style="width:30%;">Bahan Kimia</th><th style="width:8%;">Satuan</th>';
            results.forEach((r, idx) => {
                const name = r.formulation?.formulation_name || 'Formulasi ' + (idx + 1);
                const version = r.version?.version_no || '?';
                tableHeader += `<th class="text-center" style="width:${62 / results.length}%;">
                    <div class="d-flex flex-column"><span class="fw-bold">${this.e(name)}</span><small class="text-muted">Versi #${version}</small></div>
                </th>`;
            });
            tableHeader += '</tr>';

            let tableRows = '';

            const infoRows = [{
                    label: 'Proses',
                    value: r => `${r.formulation?.process_type || '-'}${r.formulation?.process_sub_type ? ` (${r.formulation.process_sub_type})` : ''}`
                },
                {
                    label: 'Group',
                    value: r => r.formulation?.group_name || '—'
                },
                {
                    label: 'Nilai',
                    value: r => this.formatValue(r.version?.output_percentage),
                    bold: true
                },
                {
                    label: 'Satuan',
                    value: r => {
                        const units = (r.items || []).map(i => this.unitLabel(i.unit)).filter(Boolean); // sebelumnya: i.unit
                        return [...new Set(units)].join(', ') || '—';
                    }
                },
                {
                    label: 'Status',
                    value: r => {
                        const status = r.version?.status || 'Draft';
                        const cls = status === 'Active' ? 'success' : status === 'Draft' ? 'warning' : 'secondary';
                        return `<span class="badge badge-phoenix badge-phoenix-${cls} fs-10">${status}</span>`;
                    }
                },
                {
                    label: 'Jumlah Item',
                    value: r => (r.items || []).length + ' item'
                }
            ];

            infoRows.forEach(info => {
                tableRows += `<tr class="table-light"><td colspan="2"><strong>${info.label}</strong></td>`;
                results.forEach(r => {
                    const val = info.value(r);
                    tableRows += `<td class="text-center">${val}</td>`;
                });
                tableRows += '</tr>';
            });

            tableRows += `<tr><td colspan="${results.length + 2}" class="bg-light fw-bold">Komposisi Bahan</td></tr>`;

            allItems.forEach((chem, key) => {
                const badgeClass = chem.type === 'chemical' ? 'primary' : 'info';
                const badgeText = chem.type === 'chemical' ? 'Chemical' : 'Softener';
                const allValues = results.map((_, i) => chem.values[i] || 0);
                const avg = allValues.reduce((a, b) => a + b, 0) / allValues.length;

                tableRows += `<tr>
                                <td><span class="badge badge-phoenix badge-phoenix-${badgeClass} fs-10 me-1">${badgeText}</span> ${chem.label}${chem.code ? `<small class="text-muted">(${chem.code})</small>` : ''}</td>
                                <td class="text-center">${chem.unit ? this.e(this.unitLabel(chem.unit)) : '—'}</td>`;
                results.forEach((r, idx) => {
                    const val = chem.values[idx];
                    const diff = Math.abs((val || 0) - avg);
                    const isHighlight = diff > 5 && val !== undefined;
                    tableRows += `<td class="text-center ${isHighlight ? 'compare-highlight' : ''}">
                        ${val !== undefined ? `<strong>${this.formatValue(val)}</strong>` : '<span class="text-muted">—</span>'}
                        ${isHighlight ? '<br><small class="text-warning">⬆ berbeda</small>' : ''}
                    </td>`;
                });
                tableRows += '</tr>';
            });

            tableRows += `<tr class="table-light fw-bold"><td colspan="2">TOTAL</td>`;
            results.forEach(r => {
                const total = (r.items || []).reduce((sum, item) => sum + parseFloat(item.percentage || 0), 0);
                tableRows += `<td class="text-center">${this.formatValue(total)}</td>`;
            });
            tableRows += '</tr>';

            resultDiv.innerHTML = `
                <div class="alert alert-subtle-info mb-3">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span class="fas fa-code-compare fa-2x text-primary"></span>
                        <div><h6 class="mb-0 fw-bold">Komparasi ${results.length} Formulasi</h6>
                        <small class="text-muted">${results.map((r, i) => `${i+1}. ${r.formulation?.formulation_name || 'Formulasi'} (v${r.version?.version_no || '?'})`).join(' | ')}</small></div>
                        <span class="badge bg-primary ms-auto">${results.length} item</span>
                    </div>
                </div>
                <div class="row mb-4">${cardsHtml}</div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle compare-table">
                        <thead class="table-dark">${tableHeader}</thead>
                        <tbody>${tableRows}</tbody>
                    </table>
                </div>
                <div class="mt-3 text-muted small">
                    <span class="fas fa-info-circle me-1"></span>
                    <span class="badge bg-warning bg-opacity-10 text-warning me-1">⬆</span> Menandakan perbedaan signifikan (>5%) antar formulasi
                </div>
            `;

            document.getElementById('btn-print-compare').style.display = 'inline-block';
        },

        // ============================================================
        // PRINT COMPARE REPORT
        // ============================================================

        printCompareReport() {
            const results = this.lastCompareResults;
            if (!results || results.length < 2) {
                this.toast('error', 'Belum ada hasil komparasi untuk dicetak');
                return;
            }

            const allItems = new Map();
            results.forEach((r, idx) => {
                (r.items || []).forEach(item => {
                    const key = item.composition_type === 'chemical' ? `chem_${item.chemical_id}` : `soft_${item.custom_label}`;
                    if (!allItems.has(key)) {
                        allItems.set(key, {
                            type: item.composition_type,
                            label: item.composition_type === 'chemical' ? (item.chemical_name || 'Unknown') : (item.custom_label || 'Unknown'),
                            code: item.chemical_code || null,
                            unit: item.unit || null,
                            values: {}
                        });
                    }
                    allItems.get(key).values[idx] = item.percentage || 0;
                });
            });

            const logoPath = '<?= base_url('assets/img/app/logo-regency-footer.png') ?>';

            // HEADER untuk Informasi Formulasi - TANPA kolom Satuan
            let infoTheadCols = '<th>Informasi</th>';
            results.forEach((r, idx) => {
                const name = r.formulation?.formulation_name || 'Formulasi ' + (idx + 1);
                const version = r.version?.version_no || '?';
                infoTheadCols += `<th class="text-center">${this.e(name)}<br><small>Versi #${version}</small></th>`;
            });

            // HEADER untuk Komposisi Bahan - DENGAN kolom Satuan
            let compTheadCols = '<th style="width:30%;">Bahan Kimia</th><th style="width:8%;">Satuan</th>';
            results.forEach((r, idx) => {
                const name = r.formulation?.formulation_name || 'Formulasi ' + (idx + 1);
                const version = r.version?.version_no || '?';
                compTheadCols += `<th class="text-center">${this.e(name)}<br><small>Versi #${version}</small></th>`;
            });

            // Rows Informasi - TANPA Satuan
            let infoRows = '';
            const infoDefs = [{
                    label: 'Kode',
                    value: r => r.formulation?.formulation_code || '-'
                },
                {
                    label: 'Proses',
                    value: r => `${r.formulation?.process_type || '-'}${r.formulation?.process_sub_type ? ` (${r.formulation.process_sub_type})` : ''}`
                },
                {
                    label: 'Group',
                    value: r => r.formulation?.group_name || '—'
                },
                {
                    label: 'Status',
                    value: r => r.version?.status || 'Draft'
                },
                {
                    label: 'Nilai',
                    value: r => this.formatValue(r.version?.output_percentage)
                },
            ];
            infoDefs.forEach(def => {
                infoRows += `<tr class="info-row"><td><strong>${def.label}</strong></td>`;
                results.forEach(r => {
                    infoRows += `<td class="text-center">${this.e(String(def.value(r)))}</td>`;
                });
                infoRows += '</tr>';
            });

            // Material rows - DENGAN Satuan
            let materialRows = '';
            allItems.forEach(chem => {
                const allValues = results.map((_, i) => chem.values[i] || 0);
                const avg = allValues.reduce((a, b) => a + b, 0) / allValues.length;
                materialRows += `<tr>
                            <td>${this.e(chem.label)}${chem.code ? ` <span class="muted">(${this.e(chem.code)})</span>` : ''}</td>
                            <td class="text-center">${chem.unit ? this.e(this.unitLabel(chem.unit)) : '—'}</td>`; // sebelumnya: this.e(chem.unit)
                results.forEach((r, idx) => {
                    const val = chem.values[idx];
                    const diff = Math.abs((val || 0) - avg);
                    const isHighlight = diff > 5 && val !== undefined;
                    materialRows += `<td class="text-center ${isHighlight ? 'highlight' : ''}">${val !== undefined ? this.formatValue(val) : '—'}</td>`;
                });
                materialRows += '</tr>';
            });

            // Total row
            let totalRow = '<tr class="total-row"><td colspan="2">TOTAL</td>';
            results.forEach(r => {
                const total = (r.items || []).reduce((sum, item) => sum + parseFloat(item.percentage || 0), 0);
                totalRow += `<td class="text-center">${this.formatValue(total)}</td>`;
            });
            totalRow += '</tr>';

            const printWindow = window.open('', '_blank', 'width=1100,height=750');
            printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Laporan Komparasi Formulasi</title>
            <style>
                @page { size: A4 landscape; margin: 1.2cm; }
                * { box-sizing: border-box; }
                body { font-family: Arial, sans-serif; margin: 0; padding: 0; font-size: 9.5pt; color: #1a1a2e; }
                .header { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #1a1a2e; padding-bottom: 10px; margin-bottom: 14px; }
                .header-left { display: flex; align-items: center; gap: 12px; }
                .header-left img { height: 40px; max-height: 40px; }
                .header-left .company-name { font-weight: bold; font-size: 15pt; }
                .header-left .company-sub { font-size: 10pt; color: #555; }
                .header-right { text-align: right; }
                .header-right .title { font-weight: bold; font-size: 12pt; }
                .header-right .date { font-size: 8pt; color: #666; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
                th, td { border: 1px solid #ccc; padding: 5px 8px; }
                thead th { background: #1a1a2e; color: #fff; font-size: 8.5pt; text-transform: uppercase; letter-spacing: 0.03em; }
                .info-row td { background: #f5f6fa; font-size: 8.5pt; }
                .muted { color: #888; font-size: 8pt; }
                .highlight { background: #fff3cd; font-weight: bold; color: #856404; }
                .total-row td { background: #eceef5; font-weight: bold; }
                .section-title { font-weight: bold; font-size: 10pt; margin: 10px 0 4px; }
                .footer { margin-top: 14px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 7.5pt; color: #999; text-align: center; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="header-left">
                    <img src="${logoPath}" alt="Logo" onerror="this.style.display='none'">
                    <div><div class="company-name">PT. SINAR CONTINENTAL</div><div class="company-sub">DOKUMEN FORMULASI</div></div>
                </div>
                <div class="header-right">
                    <div class="title">Laporan Komparasi Formulasi</div>
                    <div class="date">${new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</div>
                </div>
            </div>

            <!-- Informasi Formulasi - TANPA kolom Satuan -->
            <div class="section-title">Informasi Formulasi</div>
            <table>
                <thead><tr>${infoTheadCols}</tr></thead>
                <tbody>${infoRows}</tbody>
            </table>

            <!-- Komposisi Bahan - DENGAN kolom Satuan -->
            <div class="section-title">Komposisi Bahan</div>
            <table>
                <thead><tr>${compTheadCols}</tr></thead>
                <tbody>${materialRows}${totalRow}</tbody>
            </table>

            <div class="footer">PT. Sinar Continental &middot; Sistem Manajemen Formulasi &middot; Dicetak otomatis dari aplikasi</div>
        </body>
        </html>
    `);
            printWindow.document.close();
            printWindow.onload = () => {
                printWindow.focus();
                printWindow.print();
            };
        },

        // ============================================================
        // DELETE
        // ============================================================

        async deleteItem(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Formulasi?',
                html: `Formulasi <strong>${this.e(name)}</strong> akan dipindahkan ke sampah.<br><small class="text-muted">Dapat dipulihkan dari menu Sampah.</small>`,
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
                const res = await this.post(this.BASE + `warehouse/formulations/${id}/delete`, new FormData());
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

        // ============================================================
        // HELPERS
        // ============================================================

        e(s) {
            if (s === null || s === undefined) return '';
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        },

        fmtDate(d) {
            if (!d) return '<span class="text-muted fst-italic">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">${dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                    <small class="text-muted">${dt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</small>`;
        },

        fmtStatus(status) {
            if (!status) return '<span class="text-muted fst-italic">—</span>';
            let cls = 'draft',
                icon = 'fa-pencil-alt';
            const s = status.toLowerCase();
            if (s === 'active') {
                cls = 'active';
                icon = 'fa-check-circle';
            } else if (s === 'archived') {
                cls = 'archived';
                icon = 'fa-archive';
            }
            return `<span class="badge-status ${cls}"><span class="fas ${icon}"></span>${this.e(status)}</span>`;
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
                <span class="fas fa-user me-1"></span>${this.e(employeeName)}
            </span>`;
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

        // ============================================================
        // INIT EVENTS
        // ============================================================

        initEvents() {
            document.getElementById('btn-refresh')?.addEventListener('click', () => {
                this.dt.ajax.reload(() => this.loadStats(), false);
            });

            document.getElementById('btn-apply-filter')?.addEventListener('click', () => this.applyFilter());
            document.getElementById('btn-reset-filter')?.addEventListener('click', () => this.resetFilter());
            document.getElementById('filter-name')?.addEventListener('keyup', e => {
                if (e.key === 'Enter') this.applyFilter();
            });

            document.getElementById('btn-compare-formulations')?.addEventListener('click', () => {
                this.openCompare();
            });

            document.getElementById('btn-add-compare')?.addEventListener('click', () => {
                this.addCompareItem();
            });

            document.getElementById('btn-do-compare')?.addEventListener('click', () => {
                this.doCompare();
            });

            document.getElementById('btn-print-compare')?.addEventListener('click', () => {
                this.printCompareReport();
            });

            $(document).on('click', '.btn-delete', e => {
                const btn = $(e.currentTarget);
                this.deleteItem(btn.data('id'), btn.data('name'));
            });

            $(document).on('click', '.btn-versions', e => {
                const btn = $(e.currentTarget);
                this.openVersions(btn.data('id'), btn.data('name'));
            });
        },
    };

    $(document).ready(() => Formulation.init());
</script>
<?= $this->endSection() ?>