<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    /* ── Form styling ─────────────────────────────────────────────── */
    .form-section {
        border-radius: 0.75rem;
        border: 1px solid var(--phoenix-border-color);
        background: var(--phoenix-card-bg);
        transition: box-shadow 0.2s;
    }

    .form-section:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .form-section-header {
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid var(--phoenix-border-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-section-header .icon-wrapper {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: rgba(var(--phoenix-primary-rgb), 0.08);
        color: var(--phoenix-primary);
    }

    .form-section-header h6 {
        font-weight: 600;
        margin: 0;
        font-size: 0.9rem;
    }

    .form-section-header .badge-required {
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        background: rgba(var(--phoenix-danger-rgb), 0.08);
        color: var(--phoenix-danger);
        border: 1px solid rgba(var(--phoenix-danger-rgb), 0.15);
    }

    .form-section-body {
        padding: 1.25rem;
    }

    /* ── Form controls ────────────────────────────────────────────── */
    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--phoenix-secondary-color);
        margin-bottom: 0.3rem;
    }

    .form-label .text-danger {
        font-weight: 700;
    }

    .form-control-sm,
    .form-select-sm {
        font-size: 0.85rem;
        padding: 0.4rem 0.75rem;
        border-radius: 0.5rem;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .form-control-sm:focus,
    .form-select-sm:focus {
        border-color: var(--phoenix-primary);
        box-shadow: 0 0 0 0.2rem rgba(var(--phoenix-primary-rgb), 0.15);
    }

    .form-control-sm.is-invalid,
    .form-select-sm.is-invalid {
        border-color: var(--phoenix-danger);
    }

    .form-control-sm.is-invalid:focus,
    .form-select-sm.is-invalid:focus {
        border-color: var(--phoenix-danger);
        box-shadow: 0 0 0 0.2rem rgba(var(--phoenix-danger-rgb), 0.15);
    }

    .invalid-feedback {
        font-size: 0.75rem;
        margin-top: 0.2rem;
        display: none;
    }

    .invalid-feedback.show {
        display: block;
    }

    .code-input {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.06em;
        background: rgba(var(--phoenix-primary-rgb), 0.04);
        border-color: rgba(var(--phoenix-primary-rgb), 0.2);
        cursor: default;
        color: var(--phoenix-primary);
    }

    .code-input:focus {
        border-color: var(--phoenix-primary);
        box-shadow: 0 0 0 0.2rem rgba(var(--phoenix-primary-rgb), 0.15);
    }

    .input-group-sm .btn {
        padding: 0.4rem 0.75rem;
        font-size: 0.85rem;
        border-radius: 0 0.5rem 0.5rem 0;
    }

    /* ── Select2 Custom ───────────────────────────────────────────── */
    /* Chemical Select */
    .chemical-select-result {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .chemical-select-result .chemical-name {
        font-weight: 500;
        font-size: 0.85rem;
    }

    .chemical-select-result .chemical-code {
        font-size: 0.65rem;
        color: var(--phoenix-secondary-color);
        font-family: 'Courier New', monospace;
        background: rgba(var(--phoenix-secondary-rgb), 0.08);
        padding: 0.1rem 0.5rem;
        border-radius: 3px;
        flex-shrink: 0;
        margin-left: 0.5rem;
    }

    .select2-results__option--highlighted .chemical-select-result .chemical-code {
        color: rgba(255, 255, 255, 0.8);
        background: rgba(255, 255, 255, 0.15);
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-right: 1.5rem;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered .chemical-code {
        font-size: 0.6rem;
        color: var(--phoenix-secondary-color);
        font-family: 'Courier New', monospace;
        background: rgba(var(--phoenix-secondary-rgb), 0.08);
        padding: 0.05rem 0.4rem;
        border-radius: 3px;
        margin-left: 0.5rem;
        flex-shrink: 0;
    }

    /* Process & Sub Process Select2 with Tags - Single Select */
    .process-select-container .select2-selection {
        min-height: 38px !important;
        border-radius: 0.5rem !important;
    }

    .process-select-container .select2-selection .select2-selection__rendered {
        padding: 0.2rem 0.75rem;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .process-select-container .select2-selection .select2-selection__choice {
        background: rgba(var(--phoenix-primary-rgb), 0.08);
        border: 1px solid rgba(var(--phoenix-primary-rgb), 0.2);
        border-radius: 4px;
        padding: 0.05rem 0.5rem;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .process-select-container .select2-selection .select2-selection__choice .select2-selection__choice__remove {
        color: var(--phoenix-secondary-color);
        font-weight: 700;
        margin-right: 0.15rem;
        border: none;
        background: transparent;
        padding: 0 0.2rem;
        font-size: 0.8rem;
    }

    .process-select-container .select2-selection .select2-selection__choice .select2-selection__choice__remove:hover {
        color: var(--phoenix-danger);
        background: transparent;
    }

    .process-select-container .select2-selection .select2-search--inline .select2-search__field {
        font-size: 0.85rem;
        min-width: 100px;
        padding: 0.15rem 0;
        margin: 0;
    }

    .process-select-container .select2-selection .select2-selection__clear {
        margin-right: 0.3rem;
        font-size: 1rem;
    }

    /* Select2 error state */
    .select2-container--bootstrap-5.is-invalid .select2-selection {
        border-color: var(--phoenix-danger) !important;
        box-shadow: 0 0 0 0.2rem rgba(var(--phoenix-danger-rgb), 0.15) !important;
    }

    /* ── Item rows ────────────────────────────────────────────────── */
    .item-row {
        transition: background-color 0.15s;
        border-radius: 0.5rem;
    }

    .item-row:hover {
        background-color: rgba(var(--phoenix-primary-rgb), 0.02);
    }

    .item-row .form-control-sm,
    .item-row .form-select-sm {
        border-radius: 0.4rem;
    }

    .item-row.type-softener_water .item-chemical-cell {
        display: none;
    }

    .item-row.type-chemical .item-label-cell {
        display: none;
    }

    .item-remove-btn {
        opacity: 0.4;
        transition: opacity 0.2s;
    }

    .item-row:hover .item-remove-btn {
        opacity: 1;
    }

    .item-label[readonly] {
        background-color: var(--phoenix-body-tertiary-bg);
        cursor: not-allowed;
    }

    .item-unit-select {
        min-width: 70px;
    }

    /* ── Composition Summary ──────────────────────────────────────── */
    .composition-summary {
        background: rgba(var(--phoenix-primary-rgb), 0.03);
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        border: 1px solid rgba(var(--phoenix-primary-rgb), 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .composition-summary .total-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--phoenix-secondary-color);
    }

    .composition-summary .total-value {
        font-size: 1.1rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        padding: 0.15rem 0.75rem;
        border-radius: 20px;
        background: rgba(var(--phoenix-primary-rgb), 0.08);
        color: var(--phoenix-primary);
    }

    .composition-summary .total-value.total-ok {
        background: rgba(var(--phoenix-success-rgb), 0.1);
        color: var(--phoenix-success);
    }

    .composition-summary .total-value.total-warning {
        background: rgba(var(--phoenix-warning-rgb), 0.1);
        color: var(--phoenix-warning);
    }

    .composition-summary .total-value.total-danger {
        background: rgba(var(--phoenix-danger-rgb), 0.1);
        color: var(--phoenix-danger);
    }

    .composition-summary .count-badge {
        font-size: 0.7rem;
        color: var(--phoenix-secondary-color);
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .composition-summary .count-badge .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
    }

    /* ── Empty state ───────────────────────────────────────────────── */
    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--phoenix-secondary-color);
    }

    .empty-state .fas {
        font-size: 2.2rem;
        opacity: 0.3;
        margin-bottom: 0.75rem;
        display: block;
    }

    .empty-state p {
        font-size: 0.85rem;
        margin: 0;
    }

    /* ── Sidebar ───────────────────────────────────────────────────── */
    .sidebar-card {
        border-radius: 0.75rem;
        border: 1px solid var(--phoenix-border-color);
        background: var(--phoenix-card-bg);
        position: sticky;
        top: 1.5rem;
    }

    .sidebar-card .card-body {
        padding: 1.25rem;
    }

    .sidebar-divider {
        border: 0;
        border-top: 1px solid var(--phoenix-border-color);
        margin: 1rem 0;
    }

    /* ── Version alert ────────────────────────────────────────────── */
    .version-alert {
        border-radius: 0.75rem;
        border-left: 4px solid var(--phoenix-info);
        background: rgba(var(--phoenix-info-rgb), 0.04);
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .version-alert .version-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        font-size: 0.85rem;
    }

    .version-alert .version-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.2rem 0.7rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(var(--phoenix-info-rgb), 0.1);
        color: var(--phoenix-info);
        border: 1px solid rgba(var(--phoenix-info-rgb), 0.2);
    }

    /* Toggle styling */
    .form-switch .form-check-input {
        width: 2.5rem;
        height: 1.25rem;
        cursor: pointer;
    }

    .form-switch .form-check-input:checked {
        background-color: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
    }

    .form-switch .form-check-input:not(:checked) {
        background-color: var(--phoenix-secondary-color);
        border-color: var(--phoenix-secondary-color);
    }

    .text-warning .fas {
        color: var(--phoenix-warning);
    }

    /* ── Responsive tweaks ────────────────────────────────────────── */
    @media (max-width: 768px) {
        .form-section-body {
            padding: 1rem;
        }

        .sidebar-card {
            position: static;
        }

        .version-alert {
            flex-direction: column;
            align-items: stretch;
        }

        .version-alert .version-info {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }
    }

    /* Unique name validation */
    .input-group .is-valid~.btn {
        border-color: var(--phoenix-success);
    }

    .input-group .is-invalid~.btn {
        border-color: var(--phoenix-danger);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-100">
    <!-- Breadcrumb & Header -->
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

    <div class="d-flex align-items-start justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold"><?= esc((string)$page_title) ?></h1>
            <p class="text-body-tertiary mb-0"><?= esc((string)$page_description) ?></p>
        </div>
        <a href="<?= site_url('warehouse/formulations') ?>" class="btn btn-subtle-secondary btn-sm no-print">
            <span class="fas fa-arrow-left me-1"></span>Kembali
        </a>
    </div>

    <!-- Version Alert (Edit Mode) -->
    <?php if (!empty($formulation['id'])): ?>
        <div class="version-alert mb-4 no-print">
            <div class="version-info">
                <span class="version-badge">
                    <span class="fas fa-code-branch"></span>
                    v<?= esc((string)($formulation['version_no'] ?? '1')) ?>
                </span>
                <span>
                    Status: <strong><?= esc((string)($formulation['status'] ?? 'Draft')) ?></strong>
                </span>
                <span class="text-muted">
                    <span class="fas fa-clock me-1"></span>
                    <?= date('d M Y H:i', strtotime($formulation['updated_at'] ?? 'now')) ?>
                </span>
            </div>
            <button type="button" class="btn btn-subtle-info btn-sm" id="btn-show-history">
                <span class="fas fa-clock-rotate-left me-1"></span>Riwayat Versi
            </button>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form id="formulation-form">
        <input type="hidden" id="f-id" value="<?= esc((string)($formulation['id'] ?? '')) ?>">

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Section: Informasi Formulasi -->
                <div class="form-section mb-4">
                    <div class="form-section-header">
                        <div class="icon-wrapper">
                            <span class="fas fa-clipboard-list"></span>
                        </div>
                        <h6>Informasi Formulasi</h6>
                        <span class="badge-required ms-auto">Wajib diisi</span>
                    </div>
                    <div class="form-section-body">
                        <div class="row g-3">
                            <!-- Kode -->
                            <div class="col-md-4">
                                <label class="form-label" for="f-code">
                                    Kode Formulasi
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control code-input" id="f-code"
                                        value="<?= esc((string)($formulation['formulation_code'] ?? $suggested_code ?? '')) ?>"
                                        readonly placeholder="Otomatis" maxlength="50">
                                    <button class="btn btn-outline-primary" type="button" id="btn-generate-code" title="Generate ulang kode">
                                        <span class="fas fa-sync-alt"></span>
                                    </button>
                                </div>
                                <div class="form-text mt-1" style="font-size:0.7rem;">
                                    Format: <strong>F[MM][YY]XXXX</strong>
                                    <span class="text-muted">· Contoh: F08260001</span>
                                </div>
                                <div class="invalid-feedback" id="err-formulation_code"></div>
                            </div>

                            <!-- Nama -->
                            <div class="col-md-8">
                                <label class="form-label" for="f-name">
                                    Nama Formulasi <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" id="f-name"
                                        value="<?= esc((string)($formulation['formulation_name'] ?? '')) ?>"
                                        placeholder="Masukkan nama formulasi..." maxlength="150" required>
                                    <span class="input-group-text" id="name-status" style="display:none;">
                                        <span class="fas fa-check text-success" id="name-valid"></span>
                                        <span class="fas fa-times text-danger" id="name-invalid" style="display:none;"></span>
                                    </span>
                                </div>
                                <div class="invalid-feedback" id="err-formulation_name"></div>
                                <div class="form-text mt-1" style="font-size:0.7rem;">
                                    <span class="fas fa-info-circle me-1"></span>
                                    Nama formulasi harus unik
                                </div>
                            </div>

                            <!-- Proses & Sub Proses - Menggunakan Select2 dengan Tags (Single Select) -->
                            <div class="col-md-6">
                                <label class="form-label" for="f-process-type">
                                    Jenis Proses <span class="text-danger">*</span>
                                </label>
                                <div class="process-select-container">
                                    <select class="form-select form-select-sm" id="f-process-type" style="width:100%">
                                        <?php
                                        $processOptions = ['Dyeing', 'Finishing', 'Other'];
                                        $selectedProcess = $formulation['process_type'] ?? 'Dyeing';
                                        ?>
                                        <option value=""></option>
                                        <?php foreach ($processOptions as $opt): ?>
                                            <option value="<?= $opt ?>" <?= ($opt === $selectedProcess) ? 'selected' : '' ?>>
                                                <?= $opt ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-text mt-1" style="font-size:0.7rem;">
                                    <span class="fas fa-info-circle me-1"></span>
                                    Pilih dari daftar atau ketik untuk menambah proses baru
                                </div>
                                <div class="invalid-feedback" id="err-process_type"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="f-process-sub-type">
                                    Sub Proses <span class="text-danger">*</span>
                                </label>
                                <div class="process-select-container">
                                    <select class="form-select form-select-sm" id="f-process-sub-type" style="width:100%">
                                        <?php
                                        $subOptions = ['Dyeing', 'Dipping', 'Dipping 1', 'Dipping 2', 'Dip+Coat', 'Coating', 'Spray', 'Coating Foam', 'Finishing', 'Other'];
                                        $selectedSub = $formulation['process_sub_type'] ?? 'Dyeing';
                                        ?>
                                        <option value=""></option>
                                        <?php foreach ($subOptions as $opt): ?>
                                            <option value="<?= $opt ?>" <?= ($opt === $selectedSub) ? 'selected' : '' ?>>
                                                <?= $opt ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-text mt-1" style="font-size:0.7rem;">
                                    <span class="fas fa-info-circle me-1"></span>
                                    Pilih dari daftar atau ketik untuk menambah sub proses baru
                                </div>
                                <div class="invalid-feedback" id="err-process_sub_type"></div>
                            </div>

                            <!-- Group & Hasil -->
                            <div class="col-md-6">
                                <label class="form-label" for="f-group">
                                    Group
                                </label>
                                <select class="form-select form-select-sm" id="f-group" style="width:100%">
                                    <?php if (!empty($formulation['group_id'])): ?>
                                        <option value="<?= esc((string)$formulation['group_id']) ?>" selected>
                                            <?= esc((string)($formulation['group_name'] ?? '')) ?>
                                        </option>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text mt-1" style="font-size:0.7rem;">
                                    Ketik nama baru untuk membuat group
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="f-output-percentage">
                                    Total Persentase
                                    <span class="text-muted" style="font-weight:400;text-transform:none;font-size:0.7rem;">(opsional)</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.001" min="0" class="form-control form-control-sm" id="f-output-percentage"
                                        value="<?= esc((string)($formulation['output_percentage'] ?? '')) ?>"
                                        placeholder="Kosongkan jika tidak digunakan">
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text mt-1" style="font-size:0.7rem;">
                                    <span class="fas fa-info-circle me-1"></span>
                                    Total persentase komposisi (opsional, dapat dikosongkan)
                                </div>
                                <div class="invalid-feedback" id="err-output_percentage"></div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label class="form-label" for="f-description">
                                    Deskripsi
                                </label>
                                <textarea class="form-control form-control-sm" id="f-description" rows="2"
                                    maxlength="500" placeholder="Deskripsi singkat formulasi..."><?= esc((string)($formulation['description'] ?? '')) ?></textarea>
                                <div class="text-end mt-1">
                                    <small class="text-muted" style="font-size:0.7rem;">
                                        <span id="char-count">0</span>/500
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Komposisi Resep -->
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="icon-wrapper" style="background:rgba(var(--phoenix-success-rgb),0.08);color:var(--phoenix-success);">
                            <span class="fas fa-flask"></span>
                        </div>
                        <h6>Komposisi Resep</h6>
                        <button type="button" class="btn btn-sm btn-primary ms-auto" id="btn-add-item">
                            <span class="fas fa-plus me-1"></span>Tambah Baris
                        </button>
                    </div>
                    <div class="form-section-body">
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0" style="font-size:0.85rem;">
                                <thead>
                                    <tr>
                                        <th style="width:15%">Jenis</th>
                                        <th style="width:25%">Bahan / Label</th>
                                        <th style="width:10%">Satuan</th>
                                        <th style="width:19%">Nilai</th>
                                        <th style="width:22%">Catatan</th>
                                        <th style="width:6%" class="text-end"></th>
                                    </tr>
                                </thead>
                                <tbody id="item-rows"></tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div id="item-empty-msg" class="empty-state">
                            <span class="fas fa-box-open"></span>
                            <p>Belum ada komposisi</p>
                            <small class="text-muted">Klik "Tambah Baris" untuk menambahkan bahan</small>
                        </div>

                        <!-- Composition Summary -->
                        <div id="composition-summary" class="composition-summary mt-3" style="display:none;">
                            <div>
                                <span class="total-label">Total Komposisi</span>
                                <span class="count-badge">
                                    <span class="badge bg-primary bg-opacity-10 text-primary" id="item-count">0 item</span>
                                </span>
                            </div>
                            <div>
                                <span class="total-value" id="total-percentage">0.00%</span>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="mt-3 p-2 bg-body-tertiary rounded-3" style="font-size:0.75rem;">
                            <div class="d-flex flex-wrap gap-3">
                                <span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary me-1">
                                        <span class="fas fa-flask"></span>
                                    </span>
                                    <strong>Chemical</strong> — mempengaruhi stok
                                </span>
                                <span>
                                    <span class="badge bg-info bg-opacity-10 text-info me-1">
                                        <span class="fas fa-water"></span>
                                    </span>
                                    <strong>Softener Water</strong> — tidak mempengaruhi stok
                                </span>
                                <span class="text-muted">
                                    <span class="fas fa-info-circle me-1"></span>
                                    Total persentase boleh lebih dari 100%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-card">
                    <div class="card-body">
                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label" for="f-status">
                                Status Versi
                            </label>
                            <select class="form-select form-select-sm" id="f-status">
                                <?php $st = $formulation['status'] ?? 'Draft'; ?>
                                <option value="Draft" <?= $st === 'Draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="Active" <?= $st === 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Archived" <?= $st === 'Archived' ? 'selected' : '' ?>>Archived</option>
                            </select>
                            <?php if (!empty($formulation['id'])): ?>
                                <div class="form-text mt-1" style="font-size:0.7rem;">
                                    <span class="fas fa-info-circle me-1"></span>
                                    Menyimpan akan membuat <strong>versi baru</strong>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label class="form-label" for="f-version-notes">
                                Catatan Versi
                            </label>
                            <textarea class="form-control form-control-sm" id="f-version-notes" rows="2"
                                maxlength="500" placeholder="Catatan perubahan versi..."></textarea>
                        </div>

                        <hr class="sidebar-divider">

                        <!-- Toggle Buat Versi Baru (hanya untuk edit mode) -->
                        <?php if (!empty($formulation['id'])): ?>
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0" for="create-new-version">
                                        Buat Versi Baru
                                    </label>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="create-new-version">
                                    </div>
                                </div>
                                <div class="form-text mt-1 text-warning" style="font-size:0.7rem;" id="version-toggle-hint">
                                    <span class="fas fa-info-circle me-1"></span>
                                    <span id="version-toggle-text">Nonaktif: update data tanpa membuat versi baru (overwrite versi saat ini)</span>
                                </div>
                            </div>
                            <hr class="sidebar-divider">
                        <?php endif; ?>
                        <!-- Actions -->
                        <div class="d-grid gap-2">
                            <button type="submit" id="btn-submit-formulation" class="btn btn-primary">
                                <span class="fas fa-save me-1"></span>
                                <span id="btn-submit-text"><?= !empty($formulation['id']) ? 'Simpan Perubahan' : 'Simpan Formulasi' ?></span>
                            </button>
                            <a href="<?= site_url('warehouse/formulations') ?>" class="btn btn-subtle-secondary">
                                <span class="fas fa-times me-1"></span>Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ─── Item Row Template ────────────────────────────────────────── -->
<template id="item-row-template">
    <tr class="item-row type-chemical">
        <td>
            <select class="form-select form-select-sm item-type">
                <option value="chemical">Chemical</option>
                <option value="softener_water">Softener Water</option>
            </select>
        </td>
        <td class="item-chemical-cell">
            <select class="form-select form-select-sm item-chemical" required></select>
        </td>
        <td class="item-label-cell">
            <input type="text" class="form-control form-control-sm item-label" maxlength="150" placeholder="Nama softener...">
        </td>
        <td>
            <select class="form-select form-select-sm item-unit item-unit-select">
                <option value="percent">%</option>
                <option value="owf">owf</option>
                <option value="gpl">gpl</option>
            </select>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="number" step="0.001" min="0" class="form-control item-percentage" placeholder="0" required>
                <span class="input-group-text" style="font-size:0.7rem;" id="unit-label">%</span>
            </div>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm item-notes" maxlength="255" placeholder="Catatan...">
        </td>
        <td class="text-end">
            <button type="button" class="btn btn-subtle-danger btn-sm item-remove-btn" style="padding:0.2rem 0.4rem;font-size:0.7rem;">
                <span class="fas fa-trash"></span>
            </button>
        </td>
    </tr>
</template>

<!-- ─── History Modal ────────────────────────────────────────────── -->
<div class="modal fade" id="modal-history" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <span class="fas fa-clock-rotate-left text-info"></span>
                    <h5 class="modal-title fw-bold">Riwayat Versi</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Versi</th>
                                <th>Aktif</th>
                                <th>Total %</th>
                                <th>Tanggal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="history-rows">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Memuat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-text mt-2" style="font-size:0.75rem;">
                    <span class="fas fa-circle-info me-1"></span>
                    Versi dengan nomor terbesar yang berstatus <strong>Aktif</strong> akan menjadi formula utama dan tampil di halaman daftar.
                </div>
            </div>
            <div class="modal-footer border-top bg-body-tertiary">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-check me-1"></span>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const FormulationForm = {
        BASE: '<?= base_url() ?>',
        formulationId: '<?= esc((string)($formulation['id'] ?? '')) ?>',
        existingItems: <?= json_encode($formulation['items'] ?? []) ?>,

        init() {
            this.initSelect2();
            this.initGroupSelect();
            this.initEvents();
            this.initCharCounter();
            this.initNameValidation();

            const outputEl = document.getElementById('f-output-percentage');
            if (outputEl && outputEl.value !== '') {
                outputEl.value = this.formatDecimal(outputEl.value);
            }

            if (this.existingItems.length) {
                this.existingItems.forEach(item => this.addRow(item));
            } else {
                this.addRow();
            }
            this.toggleEmptyMsg();
            this.updateCompositionSummary();

            if (!document.getElementById('f-code').value) {
                this.generateCode();
            }
        },

        // ─── FORMATTER ──────────────────────────────────────────────
        // Rapikan angka desimal: buang trailing zero (3.000 -> "3", 3.010 -> "3.01").
        // Dipakai untuk mengisi <input type="number">, jadi separator tetap titik (bukan koma).
        formatDecimal(value, decimals = 3) {
            if (value === null || value === undefined || value === '') return '';
            const num = parseFloat(value);
            if (isNaN(num)) return '';
            let str = num.toFixed(decimals);
            if (str.includes('.')) {
                str = str.replace(/0+$/, '').replace(/\.$/, '');
            }
            return str;
        },

        // Versi tampilan (teks, bukan input) dengan koma sebagai pemisah desimal ala Indonesia.
        formatDecimalDisplay(value, decimals = 3) {
            const formatted = this.formatDecimal(value, decimals);
            return formatted === '' ? '' : formatted.replace('.', ',');
        },

        // ─── CSRF & AJAX ────────────────────────────────────────────
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
            const d = await r.json();
            if (d?.csrfHash) this.updateCsrf(d.csrfHash);
            return d;
        },

        // ─── SELECT2 ────────────────────────────────────────────────
        initSelect2() {
            $('#f-process-type').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih atau ketik proses...',
                width: '100%',
                tags: true,
                dropdownAutoWidth: true,
                containerCssClass: 'process-select-container',
                allowClear: true,
                language: {
                    noResults: function() {
                        return 'Ketik untuk menambah proses baru';
                    }
                }
            });

            $('#f-process-sub-type').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih atau ketik sub proses...',
                width: '100%',
                tags: true,
                dropdownAutoWidth: true,
                containerCssClass: 'process-select-container',
                allowClear: true,
                language: {
                    noResults: function() {
                        return 'Ketik untuk menambah sub proses baru';
                    }
                }
            });

            $('#f-process-type, #f-process-sub-type').on('change', () => {
                if (!document.getElementById('f-id').value) {
                    this.generateCode();
                }
            });
        },

        initGroupSelect() {
            $('#f-group').select2({
                theme: 'bootstrap-5',
                placeholder: '— Pilih atau ketik nama baru —',
                allowClear: true,
                width: '100%',
                tags: true,
                dropdownAutoWidth: true,
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

        initChemicalSelect($select) {
            if (!$select || !$select.length) return;

            $select.select2({
                theme: 'bootstrap-5',
                placeholder: '— Pilih Bahan Kimia —',
                width: '100%',
                dropdownAutoWidth: true,
                templateResult: this.formatChemicalResult,
                templateSelection: this.formatChemicalSelection,
                ajax: {
                    url: this.BASE + 'warehouse/master/chemicals/select2',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: (data.data ?? []).map(c => ({
                            id: c.id,
                            text: `${c.name} (${c.code})`,
                            code: c.code,
                            name: c.name
                        }))
                    }),
                },
            });
        },

        formatChemicalResult(data) {
            if (data.loading) return data.text;
            if (!data.code) return data.text;
            return $(`
                <div class="chemical-select-result">
                    <span class="chemical-name">${data.name}</span>
                    <span class="chemical-code">${data.code}</span>
                </div>
            `);
        },

        formatChemicalSelection(data) {
            if (!data.code) return data.text;
            return $(`
                <span>${data.name}</span>
                <span class="chemical-code">${data.code}</span>
            `);
        },

        // ─── HELPERS ────────────────────────────────────────────────
        initCharCounter() {
            const desc = document.getElementById('f-description');
            const counter = document.getElementById('char-count');
            if (desc && counter) {
                desc.addEventListener('input', () => {
                    counter.textContent = desc.value.length;
                });
            }
        },

        initNameValidation() {
            const nameInput = document.getElementById('f-name');
            const statusEl = document.getElementById('name-status');
            const validIcon = document.getElementById('name-valid');
            const invalidIcon = document.getElementById('name-invalid');
            const errorEl = document.getElementById('err-formulation_name');
            if (!nameInput) return;

            let timeoutId = null;

            nameInput.addEventListener('input', () => {
                clearTimeout(timeoutId);
                const name = nameInput.value.trim();
                if (!name) {
                    if (statusEl) statusEl.style.display = 'none';
                    nameInput.classList.remove('is-valid', 'is-invalid');
                    if (errorEl) errorEl.classList.remove('show');
                    return;
                }
                timeoutId = setTimeout(async () => {
                    try {
                        const fd = new FormData();
                        fd.set('name', name);
                        fd.set('exclude_id', document.getElementById('f-id').value || '0');
                        const res = await this.post(this.BASE + 'warehouse/formulations/check-name', fd);
                        if (res.status === 'success' && res.available) {
                            nameInput.classList.remove('is-invalid');
                            nameInput.classList.add('is-valid');
                            if (validIcon) validIcon.style.display = 'inline';
                            if (invalidIcon) invalidIcon.style.display = 'none';
                            if (statusEl) statusEl.style.display = 'flex';
                            if (errorEl) errorEl.classList.remove('show');
                        } else {
                            nameInput.classList.remove('is-valid');
                            nameInput.classList.add('is-invalid');
                            if (validIcon) validIcon.style.display = 'none';
                            if (invalidIcon) invalidIcon.style.display = 'inline';
                            if (statusEl) statusEl.style.display = 'flex';
                            if (errorEl) {
                                errorEl.textContent = res.message || 'Nama formulasi sudah digunakan';
                                errorEl.classList.add('show');
                            }
                        }
                    } catch (e) {
                        console.warn('Name validation error:', e);
                    }
                }, 500);
            });
        },

        async generateCode() {
            try {
                const res = await this.post(this.BASE + 'warehouse/formulations/generate-code-suggestion', new FormData());
                const codeEl = document.getElementById('f-code');
                if (res.status === 'success' && res.code && codeEl) {
                    codeEl.value = res.code;
                }
            } catch (e) {
                console.warn('Generate code error:', e);
            }
        },

        // ─── ITEM ROWS ──────────────────────────────────────────────
        addRow(item = null) {
            const tpl = document.getElementById('item-row-template');
            if (!tpl) return;

            const row = tpl.content.cloneNode(true).querySelector('tr');
            if (!row) return;

            const container = document.getElementById('item-rows');
            if (!container) return;
            container.appendChild(row);

            // Cari semua elemen dengan aman
            const $select = $(row).find('.item-chemical');
            if ($select.length) {
                this.initChemicalSelect($select);
            }

            const $typeSelect = $(row).find('.item-type');
            const $labelInput = $(row).find('.item-label');
            const $chemicalSelect = $(row).find('.item-chemical');
            const $unitSelect = $(row).find('.item-unit');
            const $unitLabel = $(row).find('#unit-label');

            // Unit change
            if ($unitSelect.length && $unitLabel.length) {
                $unitSelect.off('change').on('change', function() {
                    const unit = $(this).val();
                    if (unit) {
                        const labels = {
                            owf: '%',
                            percent: '%',
                            gpl: 'gpl'
                        };
                        $unitLabel.text(labels[unit] ?? unit);
                    }
                });
            }

            // Type change
            if ($typeSelect.length) {
                const self = this; // keep component ref without rebinding `this` (which jQuery needs to stay the <select>)
                $typeSelect.off('change').on('change', function() {
                    const type = $(this).val();
                    if (!type) return;

                    row.classList.toggle('type-chemical', type === 'chemical');
                    row.classList.toggle('type-softener_water', type === 'softener_water');

                    if (type === 'chemical') {
                        if ($chemicalSelect.length) {
                            $chemicalSelect.prop('required', true);
                        }
                        if ($labelInput.length) {
                            $labelInput.prop('required', false);
                            $labelInput.val('');
                            $labelInput.prop('readonly', false);
                            $labelInput.attr('placeholder', 'Nama softener...');
                        }
                        if ($unitSelect.length) {
                            $unitSelect.val('owf').trigger('change');
                        }
                    } else {
                        if ($chemicalSelect.length) {
                            $chemicalSelect.prop('required', false);
                            $chemicalSelect.val(null).trigger('change');
                        }
                        if ($labelInput.length) {
                            $labelInput.prop('required', true);
                            $labelInput.val('Softener Water');
                            $labelInput.prop('readonly', true);
                            $labelInput.attr('placeholder', 'Softener Water (readonly)');
                        }
                        if ($unitSelect.length) {
                            $unitSelect.val('gpl').trigger('change');
                        }
                    }
                    self.updateCompositionSummary();
                });
            }

            // Percentage change
            const $percentage = $(row).find('.item-percentage');
            if ($percentage.length) {
                $percentage.off('input').on('input', function() {
                    this.updateCompositionSummary();
                }.bind(this));
            }

            // Jika ada data item (edit)
            if (item) {
                const type = item.composition_type || 'chemical';
                if ($typeSelect.length) {
                    $typeSelect.val(type).trigger('change');
                }

                if (type === 'chemical' && item.chemical_id) {
                    if ($select.length) {
                        const text = `${item.chemical_name} (${item.chemical_code})`;
                        const opt = new Option(text, item.chemical_id, true, true);

                        $(opt).data('data', {
                            id: item.chemical_id,
                            text: text,
                            code: item.chemical_code,
                            name: item.chemical_name
                        });
                        $select.append(opt).trigger('change');
                    }
                } else {
                    if ($labelInput.length) {
                        $labelInput.val(item.custom_label || 'Softener Water');
                        $labelInput.prop('readonly', true);
                    }
                }

                if ($percentage.length) {
                    $percentage.val(this.formatDecimal(item.percentage ?? ''));
                }

                const $notes = $(row).find('.item-notes');
                if ($notes.length) {
                    $notes.val(item.notes ?? '');
                }

                if ($unitSelect.length) {
                    if (item.unit) {
                        $unitSelect.val(item.unit).trigger('change');
                    } else {
                        // Data lama sebelum fitur satuan ada — default ke % (satuan dasar dosis kimia)
                        $unitSelect.val('percent').trigger('change');
                    }
                }
            } else {
                // default untuk baris baru — % (satuan dasar dosis kimia)
                if ($unitSelect.length) {
                    $unitSelect.val('percent').trigger('change');
                }
            }

            // Remove button
            const removeBtn = row.querySelector('.item-remove-btn');
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    row.remove();
                    this.toggleEmptyMsg();
                    this.updateCompositionSummary();
                }.bind(this));
            }

            this.toggleEmptyMsg();
            this.updateCompositionSummary();
        },

        toggleEmptyMsg() {
            const container = document.getElementById('item-rows');
            const emptyMsg = document.getElementById('item-empty-msg');
            if (container && emptyMsg) {
                const has = container.children.length > 0;
                emptyMsg.classList.toggle('d-none', has);
            }
        },

        updateCompositionSummary() {
            const rows = document.querySelectorAll('#item-rows .item-row');
            const total = rows.length;
            let sum = 0;

            rows.forEach(row => {
                const input = row.querySelector('.item-percentage');
                if (input) {
                    const val = parseFloat(input.value);
                    if (!isNaN(val)) sum += val;
                }
            });

            const summary = document.getElementById('composition-summary');
            const totalEl = document.getElementById('total-percentage');
            const countEl = document.getElementById('item-count');

            if (!summary || !totalEl || !countEl) return;

            if (total === 0) {
                summary.style.display = 'none';
                return;
            }

            summary.style.display = 'flex';
            countEl.textContent = `${total} item`;

            totalEl.textContent = `${this.formatDecimal(sum, 2)}%`;

            totalEl.className = 'total-value';
            if (sum === 0) {
                totalEl.classList.add('total-warning');
            } else if (sum < 100) {
                totalEl.classList.add('total-warning');
            } else if (sum === 100) {
                totalEl.classList.add('total-ok');
            } else {
                totalEl.classList.add('total-danger');
            }
        },

        // ─── COLLECT ITEMS ──────────────────────────────────────────
        collectItems() {
            const rows = document.querySelectorAll('#item-rows .item-row');
            const items = [];

            rows.forEach(row => {
                const typeSelect = row.querySelector('.item-type');
                const percentageInput = row.querySelector('.item-percentage');

                if (!typeSelect || !percentageInput) return;

                const type = typeSelect.value;
                const percentage = percentageInput.value;
                if (!percentage) return;

                const unitSelect = row.querySelector('.item-unit');
                const unit = unitSelect ? unitSelect.value : null;

                if (type === 'chemical') {
                    const chemicalSelect = $(row).find('.item-chemical');
                    if (!chemicalSelect.length) return;

                    const chemicalId = chemicalSelect.val();
                    if (!chemicalId) return;

                    const notesInput = row.querySelector('.item-notes');
                    items.push({
                        composition_type: 'chemical',
                        chemical_id: chemicalId,
                        percentage: percentage,
                        unit: unit,
                        notes: notesInput ? notesInput.value : '',
                    });
                } else {
                    const labelInput = row.querySelector('.item-label');
                    if (!labelInput) return;

                    const label = labelInput.value.trim();
                    if (!label) return;

                    const notesInput = row.querySelector('.item-notes');
                    items.push({
                        composition_type: 'softener_water',
                        custom_label: label,
                        percentage: percentage,
                        unit: unit,
                        notes: notesInput ? notesInput.value : '',
                    });
                }
            });

            return items;
        },

        // ─── SUBMIT ──────────────────────────────────────────────────
        async submit(e) {
            e.preventDefault();
            this.clearErrors();

            const items = this.collectItems();
            if (!items.length) {
                this.toast('error', 'Minimal 1 baris komposisi harus diisi');
                return;
            }

            const processVal = $('#f-process-type').val();
            const subProcessVal = $('#f-process-sub-type').val();

            if (!processVal || processVal === '') {
                $('#f-process-type').next('.select2-container').addClass('is-invalid');
                const errEl = document.getElementById('err-process_type');
                if (errEl) {
                    errEl.textContent = 'Jenis Proses harus dipilih';
                    errEl.classList.add('show');
                }
                this.toast('error', 'Jenis Proses harus dipilih');
                return;
            }

            if (!subProcessVal || subProcessVal === '') {
                $('#f-process-sub-type').next('.select2-container').addClass('is-invalid');
                const errEl = document.getElementById('err-process_sub_type');
                if (errEl) {
                    errEl.textContent = 'Sub Proses harus dipilih';
                    errEl.classList.add('show');
                }
                this.toast('error', 'Sub Proses harus dipilih');
                return;
            }

            const nameInput = document.getElementById('f-name');
            if (!nameInput || !nameInput.value.trim()) {
                if (nameInput) nameInput.classList.add('is-invalid');
                const errEl = document.getElementById('err-formulation_name');
                if (errEl) {
                    errEl.textContent = 'Nama formulasi wajib diisi';
                    errEl.classList.add('show');
                }
                this.toast('error', 'Nama formulasi wajib diisi');
                return;
            }

            if (nameInput.classList.contains('is-invalid')) {
                this.toast('error', 'Nama formulasi sudah digunakan');
                return;
            }

            const groupVal = $('#f-group').val();
            const fd = new FormData();
            const id = document.getElementById('f-id')?.value || '';

            if (id) fd.set('id', id);

            const codeEl = document.getElementById('f-code');
            fd.set('formulation_code', codeEl ? codeEl.value.trim() : '');
            fd.set('formulation_name', nameInput.value.trim());
            fd.set('process_type', processVal);
            fd.set('process_sub_type', subProcessVal);

            const outputEl = document.getElementById('f-output-percentage');
            const outputVal = outputEl ? outputEl.value.trim() : '';
            if (outputVal !== '' && outputVal !== null) {
                fd.set('output_percentage', outputVal);
            }

            const descEl = document.getElementById('f-description');
            fd.set('description', descEl ? descEl.value.trim() : '');

            const statusEl = document.getElementById('f-status');
            fd.set('version_status', statusEl ? statusEl.value : 'Draft');

            const notesEl = document.getElementById('f-version-notes');
            fd.set('version_notes', notesEl ? notesEl.value.trim() : '');

            fd.set('items', JSON.stringify(items));

            const toggle = document.getElementById('create-new-version');
            if (toggle) {
                fd.set('create_new_version', toggle.checked ? '1' : '0');
            }

            if (groupVal) {
                if (/^\d+$/.test(String(groupVal))) {
                    fd.set('group_id', groupVal);
                } else {
                    fd.set('group_name', groupVal);
                }
            }

            try {
                const res = await this.post(this.BASE + 'warehouse/formulations/store', fd);
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    setTimeout(() => window.location.href = this.BASE + 'warehouse/formulations', 900);
                } else if (res.errors) {
                    this.showErrors(res.errors);
                } else {
                    this.toast('error', res.message ?? 'Gagal menyimpan');
                }
            } catch (err) {
                this.toast('error', err.message);
            }
        },

        // ─── ERROR HANDLING ─────────────────────────────────────────
        clearErrors() {
            document.querySelectorAll('.invalid-feedback').forEach(e => {
                e.textContent = '';
                e.classList.remove('show');
            });
            document.querySelectorAll('.is-invalid').forEach(e => e.classList.remove('is-invalid'));
            document.querySelectorAll('.is-valid').forEach(e => e.classList.remove('is-valid'));
            $('.select2-container--bootstrap-5').removeClass('is-invalid');
            const statusEl = document.getElementById('name-status');
            if (statusEl) statusEl.style.display = 'none';
        },

        showErrors(errors) {
            const map = {
                formulation_code: 'f-code',
                formulation_name: 'f-name',
                process_type: 'f-process-type',
                process_sub_type: 'f-process-sub-type',
                output_percentage: 'f-output-percentage',
            };
            Object.entries(errors ?? {}).forEach(([key, msg]) => {
                const fieldId = map[key];
                if (fieldId) {
                    const el = document.getElementById(fieldId);
                    if (el) {
                        if (el.tagName === 'SELECT' && $(el).hasClass('select2-hidden-accessible')) {
                            $(el).next('.select2-container').addClass('is-invalid');
                        } else {
                            el.classList.add('is-invalid');
                        }
                    }
                    const errEl = document.getElementById('err-' + key);
                    if (errEl) {
                        errEl.textContent = Array.isArray(msg) ? msg[0] : msg;
                        errEl.classList.add('show');
                    }
                } else {
                    this.toast('error', msg);
                }
            });
        },

        // ─── HISTORY ─────────────────────────────────────────────────
        async showHistory() {
            if (!this.formulationId) return;
            try {
                const r = await fetch(this.BASE + `warehouse/formulations/${this.formulationId}/versions`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const d = await r.json();
                const tbody = document.getElementById('history-rows');
                if (!tbody) return;

                tbody.innerHTML = '';
                if (d.data && d.data.length) {
                    d.data.forEach(v => {
                        const tr = document.createElement('tr');
                        const isActive = v.status === 'Active';
                        const outputDisplay = v.output_percentage !== null && v.output_percentage !== '-' ? `${this.formatDecimalDisplay(v.output_percentage)}%` : '<span class="text-muted">—</span>';
                        tr.innerHTML = `
                            <td><strong>#${v.version_no}</strong></td>
                            <td>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input version-active-toggle" type="checkbox"
                                        role="switch" data-id="${v.id}" ${isActive ? 'checked' : ''}>
                                </div>
                            </td>
                            <td>${outputDisplay}</td>
                            <td style="font-size:0.8rem;">${v.created_at ? new Date(v.created_at).toLocaleString('id-ID') : '-'}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-subtle-secondary btn-load-version" data-id="${v.id}">
                                    <span class="fas fa-arrow-up-right-from-square me-1"></span>Muat ke Form
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Belum ada versi</td></tr>';
                }

                tbody.querySelectorAll('.version-active-toggle').forEach(el => {
                    el.addEventListener('change', () => this.toggleVersionActive(el.dataset.id, el.checked, el));
                });
                tbody.querySelectorAll('.btn-load-version').forEach(btn => {
                    btn.addEventListener('click', () => this.loadVersion(btn.dataset.id));
                });
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-history')).show();
            } catch (e) {
                const tbody = document.getElementById('history-rows');
                if (tbody) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger py-3">Gagal memuat riwayat</td></tr>';
                }
            }
        },

        // Toggle status aktif per versi (independen, bisa lebih dari 1 versi aktif)
        async toggleVersionActive(versionId, active, checkboxEl) {
            try {
                const fd = new FormData();
                fd.set('active', active ? '1' : '0');
                const res = await this.post(
                    this.BASE + `warehouse/formulations/${this.formulationId}/versions/${versionId}/toggle-active`,
                    fd
                );
                if (res.status === 'success') {
                    this.toast('success', res.message ?? (active ? 'Versi diaktifkan' : 'Versi dinonaktifkan'));
                } else {
                    if (checkboxEl) checkboxEl.checked = !active; // revert on failure
                    this.toast('error', res.message ?? 'Gagal mengubah status versi');
                }
            } catch (e) {
                if (checkboxEl) checkboxEl.checked = !active;
                this.toast('error', e.message);
            }
        },

        // Muat data versi tertentu ke dalam form tanpa mengubah data tersimpan
        async loadVersion(versionId) {
            try {
                const r = await fetch(
                    this.BASE + `warehouse/formulations/${this.formulationId}/versions/${versionId}/detail`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );
                const d = await r.json();
                if (!d.data) {
                    this.toast('error', d.message ?? 'Gagal memuat versi');
                    return;
                }
                const {
                    formulation,
                    version,
                    items
                } = d.data;

                if (formulation?.process_type) $('#f-process-type').val(formulation.process_type).trigger('change');
                if (formulation?.process_sub_type) $('#f-process-sub-type').val(formulation.process_sub_type).trigger('change');

                const outputEl = document.getElementById('f-output-percentage');
                if (outputEl) outputEl.value = this.formatDecimal(version.output_percentage ?? '');

                const notesEl = document.getElementById('f-version-notes');
                if (notesEl) notesEl.value = version.notes ?? '';

                const container = document.getElementById('item-rows');
                if (container) container.innerHTML = '';
                (items ?? []).forEach(item => this.addRow(item));
                this.toggleEmptyMsg();
                this.updateCompositionSummary();

                bootstrap.Modal.getInstance(document.getElementById('modal-history'))?.hide();
                this.toast('success', `Versi #${version.version_no} dimuat ke form. Data belum tersimpan.`);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        // ─── TOAST ───────────────────────────────────────────────────
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

        // ─── INIT EVENTS ─────────────────────────────────────────────
        initEvents() {
            const addBtn = document.getElementById('btn-add-item');
            if (addBtn) addBtn.addEventListener('click', () => this.addRow());

            const genBtn = document.getElementById('btn-generate-code');
            if (genBtn) genBtn.addEventListener('click', () => this.generateCode());

            const form = document.getElementById('formulation-form');
            if (form) form.addEventListener('submit', e => this.submit(e));

            const historyBtn = document.getElementById('btn-show-history');
            if (historyBtn) historyBtn.addEventListener('click', () => this.showHistory());

            const toggle = document.getElementById('create-new-version');
            if (toggle) {
                toggle.addEventListener('change', () => {
                    const text = document.getElementById('version-toggle-text');
                    const hint = document.getElementById('version-toggle-hint');
                    const btnText = document.getElementById('btn-submit-text');
                    if (text) {
                        if (toggle.checked) {
                            text.textContent = 'Aktif: menyimpan akan membuat versi baru';
                            if (hint) hint.className = 'form-text mt-1';
                        } else {
                            text.textContent = 'Nonaktif: update data tanpa membuat versi baru (overwrite versi saat ini)';
                            if (hint) hint.className = 'form-text mt-1 text-warning';
                        }
                    }
                    if (btnText) {
                        btnText.textContent = toggle.checked ? 'Simpan sebagai Versi Baru' : 'Simpan Perubahan';
                    }
                });
            }
        },
    };

    $(document).ready(() => FormulationForm.init());
</script>
<?= $this->endSection() ?>