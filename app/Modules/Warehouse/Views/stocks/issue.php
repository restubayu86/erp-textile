<?php
// Nama yang tampil di "Dicetak Oleh" — prioritas: nama lengkap employee, fallback username/email.
$printedByName = '-';
if (!empty($user_employee['fullname'])) {
    $printedByName = ucwords(strtolower($user_employee['fullname']));
} elseif (auth()->user()) {
    $printedByName = auth()->user()->username ?? auth()->user()->email ?? '-';
}
?>
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

    /* Status periode — Open = hijau, Closed = merah, netral = abu-abu */
    .info-value.period-open {
        color: var(--phoenix-success) !important;
    }

    .info-value.period-closed {
        color: var(--phoenix-danger) !important;
    }

    .info-value.period-neutral {
        color: var(--phoenix-secondary-color) !important;
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

    .select2-container .select2-selection--single {
        height: calc(1.5em + 1.1rem + 2px) !important;
    }

    .period-range-text {
        font-size: .68rem;
        color: var(--phoenix-secondary-color);
    }

    #iss-history-table {
        width: 100% !important;
    }

    /* Margin/padding tabel Daftar Item Pemakaian supaya lebih lega, tidak mepet */
    #iss-pending-table th,
    #iss-pending-table td {
        padding: .65rem .75rem;
    }

    #iss-pending-table .btn-row-remove {
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: .4rem;
    }

    /* Tombol modal (Batal / Tambahkan) proporsional & sama lebar minimum */
    #iss-add-modal .modal-footer .btn {
        min-width: 130px;
    }

    #iss-history-mode-group label {
        font-size: .72rem;
    }

    /* Gaya DataTable disamakan dengan tabel di halaman Posisi Stok — search box bulat
       di tengah, baris bawah (length/pagination/info) sejajar flex, pagination rounded. */
    #iss-history-table_wrapper {
        max-width: 100%;
    }

    #iss-history-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #iss-history-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #iss-history-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #iss-history-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #iss-history-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #iss-history-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #iss-history-table_wrapper .dataTables_filter label,
    #iss-history-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #iss-history-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #iss-history-table_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #iss-history-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
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
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Item Belum Disimpan</div>
                            <div class="info-value text-warning" id="iss-stat-pending">0</div>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <span class="fas fa-clipboard-list"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Pemakaian Hari Ini</div>
                            <div class="info-value text-danger" id="iss-stat-today">—</div>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <span class="fas fa-dolly"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Status Periode</div>
                            <div class="info-value period-neutral" id="iss-stat-period">—</div>
                            <div class="period-range-text" id="iss-stat-period-range"></div>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary" id="iss-stat-period-icon">
                            <span class="fas fa-calendar-alt"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status banner -->
    <div id="iss-status-banner" class="d-none mb-3"></div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <div class="d-flex gap-2 align-items-center">
            <span class="badge badge-phoenix badge-phoenix-secondary fs-9 p-2 px-3" id="iss-context-badge">
                <span class="fas fa-info-circle me-1"></span>Pilih periode &amp; gudang
            </span>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <a href="<?= site_url('warehouse/stocks/receipt') ?>" class="btn btn-outline-secondary btn-sm" title="Buka halaman Penerimaan">
                <span class="fas fa-dolly-flatbed me-1"></span>Penerimaan
            </a>
            <a href="<?= site_url('warehouse/stocks/position') ?>" class="btn btn-outline-secondary btn-sm" title="Buka halaman Posisi Stok">
                <span class="fas fa-layer-group me-1"></span>Posisi Stok
            </a>
            <a href="<?= site_url('warehouse/stocks/stock-card') ?>" class="btn btn-outline-secondary btn-sm" title="Buka halaman Kartu Stok">
                <span class="fas fa-address-card me-1"></span>Kartu Stok
            </a>
            <div class="vr mx-1 d-none d-md-block"></div>
            <button class="btn btn-subtle-primary btn-sm" id="iss-btn-print" type="button">
                <span class="fas fa-print me-1"></span>Cetak
            </button>
            <button class="btn btn-subtle-secondary btn-sm" id="iss-btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
        </div>
    </div>

    <!-- Empty state -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y" id="iss-empty-wrapper">
        <div class="text-center py-5 text-body-tertiary">
            <span class="fas fa-dolly fa-2x mb-2 d-block opacity-50"></span>
            Pilih periode dan gudang terlebih dahulu.
        </div>
    </div>

    <!-- Form area -->
    <div class="d-none" id="iss-form-wrapper">
        <!-- Daftar item pending -->
        <div class="card mb-3">
            <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="mb-0 fw-bold"><span class="fas fa-list-ul me-2 text-primary"></span>Daftar Item Pemakaian</h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" id="iss-btn-open-add-modal" type="button">
                        <span class="fas fa-plus me-1"></span>Tambah Item
                    </button>
                    <button class="btn btn-danger btn-sm" id="iss-btn-save" type="button" disabled>
                        <span class="fas fa-save me-1" id="iss-save-icon"></span>
                        <span id="iss-save-text">Simpan Pemakaian</span>
                    </button>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover fs-9 align-middle mb-0" id="iss-pending-table">
                        <thead class="bg-body-secondary">
                            <tr>
                                <th style="width:30px">No</th>
                                <th style="width:100px">Tanggal</th>
                                <th>Bahan Kimia</th>
                                <th>Varian / Kemasan</th>
                                <th style="width:130px">Tujuan</th>
                                <th class="text-end" style="width:110px">Qty Berat</th>
                                <th style="width:70px">Satuan</th>
                                <th>Catatan</th>
                                <th style="width:50px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="iss-pending-tbody">
                            <tr id="iss-pending-empty-row">
                                <td colspan="9" class="text-center text-muted py-4">Belum ada item ditambahkan. Klik "Tambah Item" untuk mulai.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Riwayat pemakaian -->
        <div class="card mb-3">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><span class="fas fa-history me-2 text-primary"></span>Riwayat Pemakaian</h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-end gap-2 mb-3">
                    <div>
                        <label class="form-label fs-10 text-muted mb-1 d-block">Tampilkan</label>
                        <div class="btn-group btn-group-sm" role="group" id="iss-history-mode-group">
                            <input type="radio" class="btn-check" name="iss-history-mode" id="iss-mode-period" value="period" checked autocomplete="off">
                            <label class="btn btn-outline-secondary" for="iss-mode-period">Seluruh Periode</label>
                            <input type="radio" class="btn-check" name="iss-history-mode" id="iss-mode-range" value="range" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="iss-mode-range">Rentang Tanggal</label>
                            <input type="radio" class="btn-check" name="iss-history-mode" id="iss-mode-date" value="date" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="iss-mode-date">Tanggal Tertentu</label>
                        </div>
                    </div>
                    <div class="d-none align-items-end gap-2" id="iss-history-range-inputs">
                        <div>
                            <label class="form-label fs-10 text-muted mb-1 d-block">Dari</label>
                            <input type="date" class="form-control form-control-sm" id="iss-history-from">
                        </div>
                        <div>
                            <label class="form-label fs-10 text-muted mb-1 d-block">Sampai</label>
                            <input type="date" class="form-control form-control-sm" id="iss-history-to">
                        </div>
                    </div>
                    <div class="d-none align-items-end gap-2" id="iss-history-date-input">
                        <div>
                            <label class="form-label fs-10 text-muted mb-1 d-block">Tanggal</label>
                            <input type="date" class="form-control form-control-sm" id="iss-history-single-date">
                        </div>
                    </div>
                    <button class="btn btn-outline-primary btn-sm" id="iss-btn-history-apply" type="button">
                        <span class="fas fa-filter me-1"></span>Tampilkan
                    </button>
                </div>

                <table class="table table-hover fs-9 nowrap align-middle" id="iss-history-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Bahan Kimia</th>
                            <th>Varian / Kemasan</th>
                            <th>Tujuan</th>
                            <th class="text-end">Qty Keluar</th>
                            <th>Catatan</th>
                            <th>Dicatat Oleh</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Filter toggle -->
<a class="card filter-toggle" href="#iss-filter-offcanvas" data-bs-toggle="offcanvas" id="iss-filter-toggle">
    <div class="card-body">
        <span class="fas fa-filter text-primary"></span>
        <span class="filter-label">Filter</span>
        <span class="filter-dot"></span>
    </div>
</a>

<!-- Filter Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="iss-filter-offcanvas" style="width:320px">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title"><span class="fas fa-filter me-2 text-primary"></span>Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="flex-grow-1">
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="iss-filter-period">
                    Periode <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="iss-filter-period" style="width:100%">
                    <option value=""></option>
                </select>
                <div class="form-text fs-10" id="iss-period-status-hint"></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="iss-filter-warehouse">
                    Gudang <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="iss-filter-warehouse" style="width:100%">
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary btn-sm" id="iss-btn-apply-filter">
                <span class="fas fa-search me-1"></span>Terapkan
            </button>
            <button class="btn btn-subtle-secondary btn-sm" id="iss-btn-reset-filter">
                <span class="fas fa-times me-1"></span>Reset
            </button>
        </div>
    </div>
</div>

<!-- Modal: Tambah Item Pemakaian -->
<div class="modal fade" id="iss-add-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold"><span class="fas fa-plus-circle me-2 text-primary"></span>Tambah Item Pemakaian</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-7">
                        <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Bahan Kimia</label>
                        <select class="form-select form-select-sm" id="iss-modal-chemical" style="width:100%">
                            <option value=""></option>
                        </select>
                        <div class="form-text fs-10" id="iss-modal-balance-hint"></div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Tanggal Pemakaian</label>
                        <input type="date" class="form-control form-control-sm" id="iss-modal-date">
                        <div class="form-text fs-10" id="iss-modal-date-hint"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Tujuan Pemakaian</label>
                    <select class="form-select form-select-sm" id="iss-modal-purpose">
                        <option value="">— Pilih tujuan —</option>
                        <option value="Sample">Sample</option>
                        <option value="Litbang">Litbang (R&amp;D)</option>
                        <option value="SamplingProduksi">Sampling (Produksi)</option>
                        <option value="Perbaikan">Perbaikan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Varian / Kemasan (template)</label>
                    <select class="form-select form-select-sm" id="iss-modal-variant" disabled>
                        <option value="">— Pilih bahan kimia dulu —</option>
                    </select>
                    <div class="form-text fs-10" id="iss-modal-variant-hint"></div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Qty Unit (jumlah kemasan)</label>
                        <input type="number" min="0" step="1" class="form-control form-control-sm text-end" id="iss-modal-qty-unit" placeholder="mis. 5" disabled>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Qty Berat (total)</label>
                        <div class="input-group input-group-sm">
                            <input type="number" min="0.001" step="0.001" class="form-control text-end" id="iss-modal-qty-berat" placeholder="0">
                            <span class="input-group-text" id="iss-modal-unit-suffix">kg</span>
                        </div>
                    </div>
                </div>
                <div class="mb-1">
                    <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Catatan (opsional)</label>
                    <input type="text" class="form-control form-control-sm" id="iss-modal-notes" placeholder="mis. keperluan/keterangan tambahan">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" type="button">
                    <span class="fas fa-times me-1"></span>Batal
                </button>
                <button class="btn btn-danger btn-sm" id="iss-modal-btn-submit" type="button">
                    <span class="fas fa-plus me-1"></span>Tambahkan ke Daftar
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    const StockIssue = {
        BASE: '<?= base_url() ?>',
        printedBy: '<?= esc($printedByName) ?>',
        PURPOSE_LABELS: {
            Sample: 'Sample',
            Litbang: 'Litbang',
            SamplingProduksi: 'Sampling (Produksi)',
            Perbaikan: 'Perbaikan',
        },
        PURPOSE_BADGE_CLASS: {
            Sample: 'badge-phoenix-info',
            Litbang: 'badge-phoenix-primary',
            SamplingProduksi: 'badge-phoenix-warning',
            Perbaikan: 'badge-phoenix-danger',
        },
        currentPeriodId: null,
        currentPeriodRange: null,
        currentPeriodStart: null,
        currentPeriodEnd: null,
        currentPeriodStatus: null,
        currentPeriodText: null,
        currentWarehouseId: null,
        currentWarehouseText: null,
        currentDate: null,
        pendingItems: [], // { chemical_id, chemical_name, chemical_code, variant_id, variant_label, qty_unit, quantity, unit, movement_date, usage_purpose, notes }
        dtHistory: null,
        _modalSelectedVariant: null,
        _modalQtyBeratManual: false,
        _modalAvailableBalance: null,

        init() {
            this.initSelect2();
            this.initEvents();
            this.autoSelectCurrentPeriod();
        },

        /**
         * Default-select periode yang ditandai "Periode Berjalan" (is_current) supaya user
         * tidak perlu pilih manual tiap buka halaman ini. Gudang tetap harus dipilih sendiri.
         */
        async autoSelectCurrentPeriod() {
            try {
                const res = await this.get(this.BASE + 'warehouse/master/periods/select2');
                const list = res.data ?? [];
                const current = list.find(p => Number(p.is_current) === 1) ?? list.find(p => p.status === 'Open') ?? null;
                if (!current) return;

                const periodOptionData = {
                    id: current.id,
                    text: `${current.name} (${current.code})`,
                    status: current.status,
                    start_date: current.start_date,
                    end_date: current.end_date,
                    name: current.name,
                    code: current.code,
                    is_current: current.is_current,
                };
                const opt = new Option(periodOptionData.text, periodOptionData.id, true, true);
                $(opt).data('data', periodOptionData);
                $('#iss-filter-period').append(opt).trigger('change');

                document.getElementById('iss-period-status-hint').textContent =
                    `${current.name} (${current.code})${current.status === 'Closed' ? ' — Ditutup' : ''} · ${this.fmtDateOnly(current.start_date)} — ${this.fmtDateOnly(current.end_date)}`;
            } catch (e) {
                // Kalau gagal, biarkan user pilih periode manual seperti biasa.
            }
        },

        initSelect2() {
            $('#iss-filter-period').select2({
                dropdownParent: $('#iss-filter-offcanvas'),
                width: '100%',
                theme: 'bootstrap-5',
                placeholder: '— Pilih Periode —',
                escapeMarkup: m => m,
                ajax: {
                    url: this.BASE + 'warehouse/master/periods/select2',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: (data.data ?? []).map(p => ({
                            id: p.id,
                            text: `${p.name} (${p.code})`,
                            status: p.status,
                            start_date: p.start_date,
                            end_date: p.end_date,
                            name: p.name,
                            code: p.code,
                            is_current: p.is_current,
                        })),
                    }),
                },
                templateResult: p => {
                    if (!p.id) return p.text;
                    const badge = p.status === 'Closed' ?
                        '<span class="badge badge-phoenix badge-phoenix-secondary fs-10 ms-2">Ditutup</span>' :
                        '<span class="badge badge-phoenix badge-phoenix-success fs-10 ms-2">Open</span>';
                    return $(`<span>${this.e(p.text)}${badge}</span>`);
                },
                templateSelection: p => {
                    if (!p.id) return p.text;
                    const badge = p.status === 'Closed' ?
                        '<span class="badge badge-phoenix badge-phoenix-secondary fs-10 ms-2">Ditutup</span>' :
                        '';
                    return $(`<span>${this.e(p.text)}${badge}</span>`);
                },
            });

            $('#iss-filter-warehouse').select2({
                dropdownParent: $('#iss-filter-offcanvas'),
                width: '100%',
                theme: 'bootstrap-5',
                placeholder: '— Pilih Gudang —',
                ajax: {
                    url: this.BASE + 'warehouse/master/warehouses/select2',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: (data.data ?? []).map(w => ({
                            id: w.id,
                            text: `${w.name} (${w.code})`
                        })),
                    }),
                },
            });

            $('#iss-modal-chemical').select2({
                dropdownParent: $('#iss-add-modal'),
                width: '100%',
                theme: 'bootstrap-5',
                placeholder: '— Pilih Bahan Kimia —',
                escapeMarkup: m => m,
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
                            text: c.name,
                            code: c.code,
                        })),
                    }),
                },
                templateResult: c => {
                    if (!c.id) return c.text;
                    return $(`<div>${this.e(c.text)}<div class="text-muted font-monospace" style="font-size:.75em">${this.e(c.code)}</div></div>`);
                },
                templateSelection: c => {
                    if (!c.id) return c.text;
                    return $(`<span>${this.e(c.text)}${c.code ? ` <span class="text-muted font-monospace" style="font-size:.85em">(${this.e(c.code)})</span>` : ''}</span>`);
                },
            });

            $('#iss-modal-chemical').on('change', () => {
                const data = $('#iss-modal-chemical').select2('data')[0];
                this.onModalChemicalChange(data);
            });
        },

        initEvents() {
            document.getElementById('iss-btn-refresh')?.addEventListener('click', () => this.reload());
            document.getElementById('iss-btn-apply-filter')?.addEventListener('click', () => this.applyFilter());
            document.getElementById('iss-btn-reset-filter')?.addEventListener('click', () => this.resetFilter());
            document.getElementById('iss-btn-save')?.addEventListener('click', () => this.save());
            document.getElementById('iss-btn-print')?.addEventListener('click', () => this.printIssueHistory());

            // Modal tambah item
            document.getElementById('iss-btn-open-add-modal')?.addEventListener('click', () => this.openAddModal());
            document.getElementById('iss-modal-variant')?.addEventListener('change', () => this.onModalVariantChange());
            document.getElementById('iss-modal-qty-unit')?.addEventListener('input', () => this.recalcModalQtyBerat());
            document.getElementById('iss-modal-qty-berat')?.addEventListener('input', () => {
                this._modalQtyBeratManual = true;
            });
            document.getElementById('iss-modal-btn-submit')?.addEventListener('click', () => this.submitModalItem());

            // Riwayat: mode filter tanggal
            document.querySelectorAll('input[name="iss-history-mode"]').forEach(r =>
                r.addEventListener('change', () => this.onHistoryModeChange()));
            document.getElementById('iss-btn-history-apply')?.addEventListener('click', () => this.loadHistory());
        },

        applyFilter() {
            const periodData = $('#iss-filter-period').select2('data')[0];
            const warehouseData = $('#iss-filter-warehouse').select2('data')[0];

            if (!periodData?.id || !warehouseData?.id) {
                this.toast('error', 'Periode dan gudang wajib dipilih');
                return;
            }

            this.currentPeriodId = periodData.id;
            this.currentPeriodStart = periodData.start_date;
            this.currentPeriodEnd = periodData.end_date;
            this.currentPeriodRange = `${this.fmtDateOnly(periodData.start_date)} — ${this.fmtDateOnly(periodData.end_date)}`;
            this.currentPeriodStatus = periodData.status;
            this.currentPeriodText = `${periodData.name} (${periodData.code})`;
            this.currentWarehouseId = warehouseData.id;
            this.currentWarehouseText = warehouseData.text;

            // Tanggal transaksi kini diisi per item lewat modal "Tambah Item" — di sini cukup
            // siapkan tanggal default (hari ini, dijepit ke rentang periode) untuk modal & filter riwayat.
            const today = this.todayLocal();
            this.currentDate = today < periodData.start_date ? periodData.start_date :
                (today > periodData.end_date ? periodData.end_date : today);

            document.getElementById('iss-period-status-hint').textContent =
                `${this.currentPeriodText}${periodData.status === 'Closed' ? ' — Ditutup' : ''} · ${this.currentPeriodRange}`;

            // Default input filter riwayat mengikuti rentang periode terpilih
            document.getElementById('iss-history-single-date').value = this.currentDate;
            document.getElementById('iss-history-from').value = periodData.start_date;
            document.getElementById('iss-history-to').value = periodData.end_date;
            document.getElementById('iss-mode-period').checked = true;
            this.onHistoryModeChange();

            this.updateFilterUI();
            bootstrap.Offcanvas.getInstance(document.getElementById('iss-filter-offcanvas'))?.hide();
            this.reload();
        },

        resetFilter() {
            $('#iss-filter-period').val(null).trigger('change');
            $('#iss-filter-warehouse').val(null).trigger('change');
            document.getElementById('iss-period-status-hint').textContent = '';
            this.currentPeriodId = null;
            this.currentPeriodStart = null;
            this.currentPeriodEnd = null;
            this.currentPeriodRange = null;
            this.currentPeriodStatus = null;
            this.currentPeriodText = null;
            this.currentWarehouseId = null;
            this.currentWarehouseText = null;
            this.currentDate = null;
            this.pendingItems = [];
            this.renderPending();
            this.renderEmpty();
            this.updateFilterUI();
        },

        updateFilterUI() {
            const labels = [];
            if (this.currentPeriodId) labels.push(`Periode: ${this.currentPeriodText}`);
            if (this.currentWarehouseText) labels.push(`Gudang: ${this.currentWarehouseText}`);

            const badge = document.getElementById('iss-context-badge');
            const toggle = document.getElementById('iss-filter-toggle');
            if (labels.length) {
                badge.innerHTML = `<span class="fas fa-check-circle me-1"></span>${labels.join(' · ')}`;
                toggle.classList.add('has-filter');
            } else {
                badge.innerHTML = `<span class="fas fa-info-circle me-1"></span>Pilih periode &amp; gudang`;
                toggle.classList.remove('has-filter');
            }

            const periodEl = document.getElementById('iss-stat-period');
            const icon = document.getElementById('iss-stat-period-icon');
            periodEl.textContent = this.currentPeriodStatus ?? '—';
            document.getElementById('iss-stat-period-range').textContent = this.currentPeriodRange ?? '';

            periodEl.classList.remove('period-open', 'period-closed', 'period-neutral');
            if (this.currentPeriodStatus === 'Closed') {
                periodEl.classList.add('period-closed');
                icon.className = 'stat-icon bg-danger bg-opacity-10 text-danger';
            } else if (this.currentPeriodStatus === 'Open') {
                periodEl.classList.add('period-open');
                icon.className = 'stat-icon bg-success bg-opacity-10 text-success';
            } else {
                periodEl.classList.add('period-neutral');
                icon.className = 'stat-icon bg-secondary bg-opacity-10 text-secondary';
            }
        },

        renderEmpty() {
            document.getElementById('iss-empty-wrapper').classList.remove('d-none');
            document.getElementById('iss-form-wrapper').classList.add('d-none');
        },

        async reload() {
            if (!this.currentPeriodId || !this.currentWarehouseId) {
                this.renderEmpty();
                return;
            }

            document.getElementById('iss-empty-wrapper').classList.add('d-none');
            document.getElementById('iss-form-wrapper').classList.remove('d-none');

            const banner = document.getElementById('iss-status-banner');
            if (this.currentPeriodStatus === 'Closed') {
                banner.className = 'alert alert-subtle-danger d-flex align-items-center gap-2 mb-3';
                banner.innerHTML = `<span class="fas fa-lock"></span> Periode ini sudah <b>Ditutup</b> — transaksi pemakaian tidak bisa dicatat.`;
                banner.classList.remove('d-none');
            } else {
                banner.classList.add('d-none');
            }

            this.pendingItems = [];
            this.renderPending();
            await this.loadHistory();
        },

        // ============================================================
        // MODAL TAMBAH ITEM — pakai varian sebagai template Qty Unit & Qty Berat
        // ============================================================
        openAddModal() {
            if (this.currentPeriodStatus === 'Closed') {
                this.toast('error', 'Periode sudah ditutup, tidak bisa menambah item');
                return;
            }
            if (!this.currentPeriodId || !this.currentWarehouseId) {
                this.toast('error', 'Pilih periode dan gudang terlebih dahulu');
                return;
            }

            $('#iss-modal-chemical').val(null).trigger('change');
            document.getElementById('iss-modal-purpose').value = '';
            document.getElementById('iss-modal-balance-hint').textContent = '';
            document.getElementById('iss-modal-balance-hint').classList.remove('text-danger');
            document.getElementById('iss-modal-variant').innerHTML = '<option value="">— Pilih bahan kimia dulu —</option>';
            document.getElementById('iss-modal-variant').disabled = true;
            document.getElementById('iss-modal-variant-hint').textContent = '';
            document.getElementById('iss-modal-qty-unit').value = '';
            document.getElementById('iss-modal-qty-unit').disabled = true;
            document.getElementById('iss-modal-qty-berat').value = '';
            document.getElementById('iss-modal-unit-suffix').textContent = 'kg';
            document.getElementById('iss-modal-notes').value = '';

            // Tanggal pemakaian default: tanggal terakhir dipakai (atau hari ini), dijepit
            // ke rentang periode yang aktif.
            const dateInput = document.getElementById('iss-modal-date');
            dateInput.min = this.currentPeriodStart ?? '';
            dateInput.max = this.currentPeriodEnd ?? '';
            dateInput.value = this.currentDate ?? this.currentPeriodStart ?? '';
            document.getElementById('iss-modal-date-hint').textContent =
                this.currentPeriodStart && this.currentPeriodEnd ?
                `Rentang periode: ${this.fmtDateOnly(this.currentPeriodStart)} — ${this.fmtDateOnly(this.currentPeriodEnd)}` : '';

            this._modalSelectedVariant = null;
            this._modalQtyBeratManual = false;

            new bootstrap.Modal(document.getElementById('iss-add-modal')).show();
        },

        async onModalChemicalChange(chemicalData) {
            const variantSelect = document.getElementById('iss-modal-variant');
            const balanceHint = document.getElementById('iss-modal-balance-hint');
            this._modalSelectedVariant = null;
            this._modalAvailableBalance = null;

            if (!chemicalData?.id) {
                variantSelect.innerHTML = '<option value="">— Pilih bahan kimia dulu —</option>';
                variantSelect.disabled = true;
                balanceHint.textContent = '';
                balanceHint.classList.remove('text-danger');
                return;
            }

            balanceHint.textContent = 'Mengecek saldo tersedia...';
            balanceHint.classList.remove('text-danger');

            variantSelect.innerHTML = '<option value="">Memuat varian...</option>';
            variantSelect.disabled = true;

            try {
                const [variantRes, balanceRes] = await Promise.all([
                    this.get(this.BASE + `warehouse/master/chemicals/${chemicalData.id}/variants`),
                    this.get(this.BASE + `warehouse/stocks/issue/available-balance?period_id=${this.currentPeriodId}&warehouse_id=${this.currentWarehouseId}&chemical_id=${chemicalData.id}`),
                ]);

                const available = balanceRes?.data?.available;
                this._modalAvailableBalance = available === null || available === undefined ? null : Number(available);
                if (this._modalAvailableBalance === null) {
                    balanceHint.textContent = 'Bahan kimia ini belum aktif/tidak ditemukan di Posisi Stok.';
                    balanceHint.classList.add('text-danger');
                } else if (this._modalAvailableBalance <= 0) {
                    balanceHint.textContent = `Saldo tersedia: ${this.fmtNumber(this._modalAvailableBalance)} — stok habis/tidak cukup.`;
                    balanceHint.classList.add('text-danger');
                } else {
                    balanceHint.textContent = `Saldo tersedia: ${this.fmtNumber(this._modalAvailableBalance)}`;
                    balanceHint.classList.remove('text-danger');
                }

                const res = variantRes;
                const variants = (res.status === 'success' ? (res.data ?? []) : []).filter(v => v.status === 'Active');

                let opts = '<option value="">— Tanpa Varian (input manual) —</option>';
                opts += variants.map(v => {
                    const sizeText = v.packaging_size ? `${this.fmtNumber(v.packaging_size)} ${v.unit ?? ''}`.trim() : (v.unit ?? '');
                    return `<option value="${v.id}"
                                data-packaging-size="${v.packaging_size ?? ''}"
                                data-unit="${this.e(v.unit ?? 'kg')}"
                                data-name="${this.e(v.variant_name)}"
                                ${Number(v.is_default) === 1 ? 'selected' : ''}>
                                ${this.e(v.variant_name)}${sizeText ? ' — ' + sizeText : ''}
                            </option>`;
                }).join('');

                variantSelect.innerHTML = opts;
                variantSelect.disabled = false;
                this.onModalVariantChange();
            } catch (e) {
                variantSelect.innerHTML = '<option value="">Gagal memuat varian — coba lagi</option>';
                variantSelect.disabled = true;
                balanceHint.textContent = 'Gagal mengecek saldo — coba pilih ulang bahan kimia.';
                balanceHint.classList.add('text-danger');
            }
        },

        onModalVariantChange() {
            const sel = document.getElementById('iss-modal-variant');
            const opt = sel.options[sel.selectedIndex];
            const qtyUnitInput = document.getElementById('iss-modal-qty-unit');
            const unitSuffix = document.getElementById('iss-modal-unit-suffix');
            const hint = document.getElementById('iss-modal-variant-hint');

            if (!opt || !opt.value) {
                this._modalSelectedVariant = null;
                qtyUnitInput.value = '';
                qtyUnitInput.disabled = true;
                unitSuffix.textContent = 'kg';
                hint.textContent = 'Tanpa varian — isi Qty Berat &amp; satuan secara manual.';
                this._modalQtyBeratManual = true; // manual sepenuhnya
                return;
            }

            const packagingSize = parseFloat(opt.dataset.packagingSize || '0');
            const unit = opt.dataset.unit || 'kg';
            this._modalSelectedVariant = {
                id: opt.value,
                name: opt.dataset.name,
                packaging_size: packagingSize,
                unit,
            };
            unitSuffix.textContent = unit;

            if (packagingSize > 0) {
                qtyUnitInput.disabled = false;
                hint.textContent = `1 ${opt.dataset.name} = ${this.fmtNumber(packagingSize)} ${unit} — isi Qty Unit, Qty Berat terhitung otomatis (bisa disesuaikan).`;
                this._modalQtyBeratManual = false;
                this.recalcModalQtyBerat();
            } else {
                qtyUnitInput.value = '';
                qtyUnitInput.disabled = true;
                hint.textContent = 'Varian ini tidak punya ukuran kemasan baku — isi Qty Berat manual.';
                this._modalQtyBeratManual = true;
            }
        },

        recalcModalQtyBerat() {
            if (this._modalQtyBeratManual) return;
            const qtyUnit = parseFloat(document.getElementById('iss-modal-qty-unit').value) || 0;
            if (this._modalSelectedVariant?.packaging_size) {
                const total = qtyUnit * this._modalSelectedVariant.packaging_size;
                document.getElementById('iss-modal-qty-berat').value = total > 0 ? total : '';
            }
        },

        async submitModalItem() {
            const chemicalData = $('#iss-modal-chemical').select2('data')[0];
            const qtyBerat = parseFloat(document.getElementById('iss-modal-qty-berat').value);
            const qtyUnit = parseFloat(document.getElementById('iss-modal-qty-unit').value) || null;
            const notes = document.getElementById('iss-modal-notes').value.trim();
            const usagePurpose = document.getElementById('iss-modal-purpose').value;
            const variant = this._modalSelectedVariant;
            const unit = variant?.unit || document.getElementById('iss-modal-unit-suffix').textContent || 'kg';
            const movementDate = document.getElementById('iss-modal-date').value;

            if (!chemicalData?.id) {
                this.toast('error', 'Pilih bahan kimia terlebih dahulu');
                return;
            }
            if (!usagePurpose) {
                this.toast('error', 'Pilih tujuan pemakaian terlebih dahulu');
                return;
            }
            if (!movementDate) {
                this.toast('error', 'Tanggal pemakaian wajib diisi');
                return;
            }
            if (this.currentPeriodStart && this.currentPeriodEnd &&
                (movementDate < this.currentPeriodStart || movementDate > this.currentPeriodEnd)) {
                this.toast('error', 'Tanggal pemakaian harus berada dalam rentang periode');
                return;
            }
            if (!qtyBerat || qtyBerat <= 0) {
                this.toast('error', 'Qty Berat harus lebih dari 0');
                return;
            }
            if (this.pendingItems.some(it => String(it.chemical_id) === String(chemicalData.id) && it.movement_date === movementDate && it.usage_purpose === usagePurpose)) {
                this.toast('error', 'Bahan kimia + tujuan + tanggal yang sama sudah ada di daftar — hapus dulu kalau mau ubah');
                return;
            }

            // Validasi saldo tersedia — perhitungkan juga item yang sudah masuk daftar pending
            // untuk bahan kimia yang sama (belum ke-submit ke server, tapi sudah "dijatah").
            if (this._modalAvailableBalance !== null && this._modalAvailableBalance !== undefined) {
                const alreadyPending = this.pendingItems
                    .filter(it => String(it.chemical_id) === String(chemicalData.id))
                    .reduce((sum, it) => sum + Number(it.quantity), 0);
                const remaining = this._modalAvailableBalance - alreadyPending;
                if (qtyBerat > remaining + 0.0005) {
                    this.toast('error', `Qty melebihi saldo tersedia. Sisa saldo: ${this.fmtNumber(remaining)} ${unit}` +
                        (alreadyPending > 0 ? ` (sudah dipakai ${this.fmtNumber(alreadyPending)} di daftar ini)` : ''));
                    return;
                }
            }

            // Wajib: bahan kimia ini harus sudah punya Stok Awal di gudang & periode ini.
            try {
                const res = await this.get(this.BASE +
                    `warehouse/stocks/opening/status?period_id=${this.currentPeriodId}&warehouse_id=${this.currentWarehouseId}&chemical_id=${chemicalData.id}`);
                if (res.status !== 'success' || !res.data?.has_opening_stock) {
                    this.toast('error', `${chemicalData.text} belum punya Stok Awal di gudang & periode ini. Isi Stok Awal dulu (boleh 0) sebelum mencatat Pemakaian.`);
                    return;
                }
            } catch (e) {
                this.toast('error', 'Gagal memeriksa status Stok Awal, coba lagi');
                return;
            }

            this.pendingItems.push({
                chemical_id: chemicalData.id,
                chemical_name: chemicalData.text,
                chemical_code: chemicalData.code ?? '',
                variant_id: variant?.id ?? null,
                variant_label: variant ? `${qtyUnit ? this.fmtNumber(qtyUnit) + ' x ' : ''}${variant.name}` : null,
                qty_unit: variant ? qtyUnit : null,
                quantity: qtyBerat,
                unit,
                movement_date: movementDate,
                usage_purpose: usagePurpose,
                notes,
            });

            // Ingat tanggal terakhir dipakai supaya modal berikutnya prefill dengan tanggal ini.
            this.currentDate = movementDate;

            bootstrap.Modal.getInstance(document.getElementById('iss-add-modal'))?.hide();
            this.renderPending();
        },

        removeItem(idx) {
            this.pendingItems.splice(idx, 1);
            this.renderPending();
        },

        renderPending() {
            const tbody = document.getElementById('iss-pending-tbody');
            document.getElementById('iss-stat-pending').textContent = this.pendingItems.length;
            document.getElementById('iss-btn-save').disabled = this.pendingItems.length === 0 || this.currentPeriodStatus === 'Closed';

            if (!this.pendingItems.length) {
                tbody.innerHTML = `<tr id="iss-pending-empty-row"><td colspan="9" class="text-center text-muted py-4">Belum ada item ditambahkan. Klik "Tambah Item" untuk mulai.</td></tr>`;
                return;
            }

            tbody.innerHTML = this.pendingItems.map((it, idx) => `
                <tr>
                    <td>${idx + 1}</td>
                    <td>${this.fmtDateOnly(it.movement_date)}</td>
                    <td>
                        <span class="fw-semibold">${this.e(it.chemical_name)}</span>
                        <div class="text-muted small font-monospace">${this.e(it.chemical_code)}</div>
                    </td>
                    <td>${it.variant_label ? this.e(it.variant_label) : '<span class="text-muted fst-italic">—</span>'}</td>
                    <td><span class="badge badge-phoenix ${this.PURPOSE_BADGE_CLASS[it.usage_purpose] ?? 'badge-phoenix-secondary'} fs-10">${this.PURPOSE_LABELS[it.usage_purpose] ?? it.usage_purpose}</span></td>
                    <td class="text-end fw-semibold">${this.fmtNumber(it.quantity)}</td>
                    <td>${this.e(it.unit)}</td>
                    <td class="text-muted">${it.notes ? this.e(it.notes) : '<span class="fst-italic">—</span>'}</td>
                    <td class="text-center">
                        <button class="btn btn-subtle-danger btn-row-remove" onclick="StockIssue.removeItem(${idx})" type="button" title="Hapus item">
                            <span class="fas fa-times"></span>
                        </button>
                    </td>
                </tr>
            `).join('');
        },

        // ============================================================
        // SIMPAN
        // ============================================================
        async save() {
            if (!this.pendingItems.length) {
                this.toast('error', 'Belum ada item untuk disimpan');
                return;
            }
            if (this.currentPeriodStatus === 'Closed') {
                this.toast('error', 'Periode sudah ditutup, tidak bisa menyimpan');
                return;
            }

            this.setLoading(true);
            try {
                const fd = new FormData();
                fd.set('period_id', this.currentPeriodId);
                fd.set('warehouse_id', this.currentWarehouseId);
                // variant_id, qty_unit & movement_date dikirim per baris (tiap item punya
                // tanggalnya sendiri, diisi lewat modal) — catatan tetap murni isian user.
                fd.set('rows', JSON.stringify(this.pendingItems.map(it => ({
                    chemical_id: it.chemical_id,
                    variant_id: it.variant_id,
                    qty_unit: it.qty_unit,
                    quantity: it.quantity,
                    unit: it.unit,
                    movement_date: it.movement_date,
                    usage_purpose: it.usage_purpose,
                    notes: it.notes,
                }))));

                const res = await this.post(this.BASE + 'warehouse/stocks/issue/store', fd);
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    this.pendingItems = [];
                    this.renderPending();
                    await this.loadHistory();
                } else {
                    this.toast('error', res.message ?? 'Gagal menyimpan pemakaian');
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                this.setLoading(false);
            }
        },

        // ============================================================
        // RIWAYAT — bisa per periode, rentang tanggal, atau satu tanggal
        // ============================================================
        onHistoryModeChange() {
            const mode = document.querySelector('input[name="iss-history-mode"]:checked')?.value ?? 'period';
            const rangeEl = document.getElementById('iss-history-range-inputs');
            const dateEl = document.getElementById('iss-history-date-input');

            rangeEl.classList.toggle('d-none', mode !== 'range');
            rangeEl.classList.toggle('d-flex', mode === 'range');
            dateEl.classList.toggle('d-none', mode !== 'date');
            dateEl.classList.toggle('d-flex', mode === 'date');
        },

        async loadHistory() {
            if (!this.currentPeriodId || !this.currentWarehouseId) return;

            const mode = document.querySelector('input[name="iss-history-mode"]:checked')?.value ?? 'period';
            let qs = `period_id=${this.currentPeriodId}&warehouse_id=${this.currentWarehouseId}`;

            if (mode === 'range') {
                const from = document.getElementById('iss-history-from').value;
                const to = document.getElementById('iss-history-to').value;
                if (from) qs += `&from_date=${from}`;
                if (to) qs += `&to_date=${to}`;
            } else if (mode === 'date') {
                const d = document.getElementById('iss-history-single-date').value;
                if (d) qs += `&from_date=${d}&to_date=${d}`;
            }

            try {
                const res = await this.get(this.BASE + `warehouse/stocks/issue/recent?${qs}`);
                const rows = res.status === 'success' ? (res.data ?? []) : [];
                this.buildHistoryTable(rows);

                const todayStr = this.todayLocal();
                const todayCount = rows.filter(r => (r.movement_date ?? '').slice(0, 10) === todayStr).length;
                document.getElementById('iss-stat-today').textContent = todayCount;
            } catch (e) {
                this.buildHistoryTable([]);
            }
        },

        buildHistoryTable(rows) {
            const self = this;
            if ($.fn.DataTable.isDataTable('#iss-history-table')) {
                this.dtHistory.destroy();
                $('#iss-history-table tbody').remove();
            }

            this.dtHistory = $('#iss-history-table').DataTable({
                data: rows,
                responsive: true,
                scrollX: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'Semua']
                ],
                order: [
                    [0, 'desc']
                ],
                dom: '<"top"f>rt<"bottom"lpi>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari transaksi...',
                    lengthMenu: '_MENU_ / hal',
                    info: 'Tampil _START_–_END_ dari _TOTAL_',
                    infoEmpty: 'Belum ada riwayat pemakaian',
                    zeroRecords: 'Data tidak ditemukan',
                    paginate: {
                        previous: '‹',
                        next: '›'
                    },
                },
                columns: [{
                        data: 'movement_date',
                        render: d => self.fmtDateOnly(d),
                    },
                    {
                        data: 'chemical_name',
                        render: (data, type, row) => {
                            if (type !== 'display') return data;
                            return `<span class="fw-semibold">${self.e(data)}</span>
                                    <div class="text-muted small font-monospace">${self.e(row.chemical_code)}</div>`;
                        },
                    },
                    {
                        data: null,
                        render: (d, t, row) => {
                            if (!row.variant_name) return '<span class="text-muted fst-italic">—</span>';
                            const qtyUnitText = row.qty_unit && Number(row.qty_unit) > 0 ? `${self.fmtNumber(row.qty_unit)} x ` : '';
                            return `${qtyUnitText}${self.e(row.variant_name)}` +
                                (row.packaging ? `<div class="text-muted small">${self.e(row.packaging)}</div>` : '');
                        },
                    },
                    {
                        data: 'usage_purpose',
                        render: d => d ?
                            `<span class="badge badge-phoenix ${self.PURPOSE_BADGE_CLASS[d] ?? 'badge-phoenix-secondary'} fs-10">${self.PURPOSE_LABELS[d] ?? d}</span>` : '<span class="text-muted fst-italic">—</span>',
                    },
                    {
                        data: 'quantity_out',
                        className: 'text-end fw-semibold text-danger',
                        render: d => '-' + self.fmtNumber(d),
                    },
                    {
                        data: 'notes',
                        render: d => d ? self.e(d) : '<span class="text-muted fst-italic">—</span>',
                    },
                    {
                        data: null,
                        render: (d, t, row) => self.e(row.employee_fullname || row.username || '-'),
                    },
                    {
                        data: 'id',
                        className: 'text-end',
                        orderable: false,
                        render: id => self.currentPeriodStatus === 'Closed' ? '' : `<button class="btn btn-subtle-danger btn-sm py-0 px-2" onclick="StockIssue.deleteHistory(${id})" type="button">
                                <span class="fas fa-trash-alt"></span>
                             </button>`,
                    },
                ],
            });
        },

        async deleteHistory(id) {
            const confirmed = await Swal.fire({
                title: 'Batalkan transaksi ini?',
                text: 'Baris pemakaian ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d63939',
            });
            if (!confirmed.isConfirmed) return;

            try {
                const fd = new FormData();
                fd.set('id', id);
                const res = await this.post(this.BASE + 'warehouse/stocks/issue/delete', fd);
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    await this.loadHistory();
                } else {
                    this.toast('error', res.message ?? 'Gagal menghapus');
                }
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        setLoading(on) {
            const btn = document.getElementById('iss-btn-save');
            const ico = document.getElementById('iss-save-icon');
            if (!btn) return;
            btn.disabled = on || this.pendingItems.length === 0;
            ico.className = on ? 'spinner-border spinner-border-sm me-1' : 'fas fa-save me-1';
        },

        // ============================================================
        // CETAK — layout sama dengan Posisi Stok / Kartu Stok (letterhead, info-grid, dst)
        // ============================================================
        printIssueHistory() {
            if (!this.currentPeriodId || !this.currentWarehouseId || !this.dtHistory) {
                this.toast('error', 'Pilih periode & gudang, lalu tunggu riwayat termuat dulu');
                return;
            }

            const rows = this.dtHistory.rows({
                search: 'applied'
            }).data().toArray();
            const logoPath = this.BASE + 'assets/img/app/logo-regency-footer.png';
            const now = new Date().toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            const mode = document.querySelector('input[name="iss-history-mode"]:checked')?.value ?? 'period';
            let filterText = this.currentPeriodRange ?? '-';
            if (mode === 'range') {
                const from = document.getElementById('iss-history-from').value;
                const to = document.getElementById('iss-history-to').value;
                filterText = `${from ? this.fmtDateOnly(from) : 'Awal periode'} — ${to ? this.fmtDateOnly(to) : 'Akhir periode'}`;
            } else if (mode === 'date') {
                const d = document.getElementById('iss-history-single-date').value;
                filterText = d ? this.fmtDateOnly(d) : '-';
            }

            const totalQty = rows.reduce((sum, r) => sum + (Number(r.quantity_out) || 0), 0);

            const rowsHtml = rows.map((r, i) => {
                const qtyUnitText = r.qty_unit && Number(r.qty_unit) > 0 ? `${this.fmtNumber(r.qty_unit)} x ` : '';
                const variantText = r.variant_name ? `${qtyUnitText}${this.e(r.variant_name)}` : '-';
                const purposeText = r.usage_purpose ? (this.PURPOSE_LABELS[r.usage_purpose] ?? r.usage_purpose) : '-';
                return `
                <tr>
                    <td>${i + 1}</td>
                    <td>${this.fmtDateOnly(r.movement_date)}</td>
                    <td>${this.e(r.chemical_name)}<div class="mono">${this.e(r.chemical_code)}</div></td>
                    <td>${variantText}</td>
                    <td>${this.e(purposeText)}</td>
                    <td class="num out bold">-${this.fmtNumber(r.quantity_out)}</td>
                    <td>${this.e(r.unit ?? '-')}</td>
                    <td>${r.notes ? this.e(r.notes) : '-'}</td>
                    <td>${this.e(r.employee_fullname || r.username || '-')}</td>
                </tr>
            `;
            }).join('');

            const printWindow = window.open('', '_blank', 'width=1300,height=900');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Bukti Pemakaian Langsung Bahan Kimia</title>
                    <style>
                        @page { margin: 3mm; }
                        * { box-sizing: border-box; }
                        body { font-family: Arial, Helvetica, sans-serif; font-size: 8pt; color: #111; margin: 0; padding: 4mm; }
                        .letterhead { display: flex; align-items: center; gap: 12px; border-bottom: 2px solid #111; padding-bottom: 8px; margin-bottom: 10px; }
                        .letterhead img { height: 48px; }
                        .letterhead .company { font-size: 15pt; font-weight: bold; letter-spacing: .3px; }
                        .letterhead .division { font-size: 10pt; color: #444; }
                        .doc-title { text-align: center; font-size: 11pt; font-weight: bold; margin: 6px 0 10px; text-transform: uppercase; }
                        .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px 16px; font-size: 8pt; margin-bottom: 10px; border: 1px solid #ccc; padding: 8px 10px; background: #f8f8f8; }
                        .info-grid div span.label { color: #666; display: block; font-size: 7pt; text-transform: uppercase; letter-spacing: .3px; }
                        .info-grid div span.value { font-weight: 600; }
                        table { width: 100%; border-collapse: collapse; font-size: 7.5pt; }
                        th, td { border: 1px solid #999; padding: 3px 5px; text-align: left; }
                        th { background: #eee; font-weight: 700; text-align: center; }
                        td.num, th.num { text-align: right; }
                        td.bold { font-weight: 700; }
                        td.out { color: #c0392b; }
                        .mono { font-family: 'Courier New', monospace; font-size: 6.5pt; color: #555; }
                        .footer-note { margin-top: 14px; font-size: 7pt; color: #777; display: flex; justify-content: space-between; border-top: 1px solid #ccc; padding-top: 6px; }
                        @media print { .no-print { display: none !important; } }
                    </style>
                </head>
                <body>
                    <div class="letterhead">
                        <img src="${logoPath}" alt="Logo" onerror="this.style.display='none'">
                        <div>
                            <div class="company">PT. SINAR CONTINENTAL</div>
                            <div class="division">DIVISI 3A</div>
                        </div>
                    </div>
                    <div class="doc-title">Bukti Pemakaian Langsung Bahan Kimia</div>
                    <div class="info-grid">
                        <div><span class="label">Gudang</span><span class="value">${this.e(this.currentWarehouseText ?? '-')}</span></div>
                        <div><span class="label">Periode</span><span class="value">${this.e(this.currentPeriodText ?? '-')}${this.currentPeriodStatus === 'Closed' ? ' (Close)' : ' (Open)'}</span></div>
                        <div><span class="label">Filter Tanggal</span><span class="value">${this.e(filterText)}</span></div>
                        <div><span class="label">Total Transaksi</span><span class="value">${rows.length} baris</span></div>
                        <div><span class="label">Total Qty Dipakai</span><span class="value">${this.fmtNumber(totalQty)}</span></div>
                        <div><span class="label">Dicetak Oleh</span><span class="value">${this.e(this.printedBy)}</span></div>
                    </div>
                    <table>
                        <thead>
                            <tr><th>#</th><th>Tanggal</th><th>Bahan Kimia</th><th>Varian / Kemasan</th><th>Tujuan</th><th class="num">Qty Keluar</th><th>Satuan</th><th>Catatan</th><th>Dicatat Oleh</th></tr>
                        </thead>
                        <tbody>${rowsHtml}</tbody>
                    </table>
                    <div class="footer-note">
                        <span>Dokumen ini dihasilkan otomatis dari sistem ERP — PT. Sinar Continental</span>
                        <span>Dicetak: ${now}</span>
                    </div>
                    <script>window.onload = () => window.print();<\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        },

        // ============================================================
        // HELPERS
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

        /**
         * Tanggal HARI INI menurut waktu lokal browser (bukan UTC!). `toISOString()` selalu
         * memakai UTC — di WIB (UTC+7), antara jam 00:00–07:00 itu akan salah menunjuk ke
         * tanggal KEMARIN. Helper ini menggeser dulu sebelum konversi supaya tetap akurat.
         */
        todayLocal() {
            const d = new Date();
            const offsetMs = d.getTimezoneOffset() * 60000;
            return new Date(d.getTime() - offsetMs).toISOString().slice(0, 10);
        },

        fmtDateOnly(d) {
            if (!d) return '—';
            const normalized = typeof d === 'string' ? d.replace(' ', 'T') : d;
            return new Date(normalized).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        },

        fmtNumber(n) {
            return Number(n ?? 0).toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 3
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

        toast(type, msg) {
            Swal.fire({
                toast: true,
                position: 'top-right',
                icon: type,
                title: msg,
                showConfirmButton: false,
                timer: type === 'success' ? 2500 : 4000,
                timerProgressBar: true
            });
        },
    };

    $(document).ready(() => StockIssue.init());
</script>
<?= $this->endSection() ?>