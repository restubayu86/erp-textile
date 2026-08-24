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

    #card-stock-table_wrapper {
        max-width: 100%;
    }

    #card-stock-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #card-stock-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #card-stock-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #card-stock-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #card-stock-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #card-stock-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #card-stock-table_wrapper .dataTables_filter label,
    #card-stock-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #card-stock-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #card-stock-table_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #card-stock-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #card-stock-table {
        width: 100% !important;
    }

    .dt-buttons {
        margin-bottom: .75rem;
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

    .select2-container .select2-selection--single {
        height: calc(1.5em + 1.1rem + 2px) !important;
    }

    .period-range-text {
        font-size: .68rem;
        color: var(--phoenix-secondary-color);
    }

    .text-balance-positive {
        color: var(--phoenix-success);
    }

    .text-balance-zero {
        color: var(--phoenix-secondary-color);
    }

    .text-balance-negative {
        color: var(--phoenix-danger);
    }

    tr.row-opening td {
        background-color: rgba(var(--phoenix-primary-rgb), .05);
        font-weight: 600;
    }

    .movement-badge-Receipt {
        background-color: rgba(var(--phoenix-success-rgb), .12);
        color: var(--phoenix-success);
    }

    .movement-badge-Issue {
        background-color: rgba(var(--phoenix-danger-rgb), .12);
        color: var(--phoenix-danger);
    }

    .movement-badge-TransferIn {
        background-color: rgba(var(--phoenix-info-rgb), .12);
        color: var(--phoenix-info);
    }

    .movement-badge-TransferOut {
        background-color: rgba(var(--phoenix-warning-rgb), .12);
        color: var(--phoenix-warning);
    }

    .movement-badge-AdjustmentIn,
    .movement-badge-AdjustmentOut {
        background-color: rgba(var(--phoenix-secondary-rgb), .15);
        color: var(--phoenix-secondary-color);
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
        <div class="no-print">
            <a href="<?= site_url('warehouse/stocks/position') ?>" class="btn btn-subtle-primary btn-sm">
                <span class="fas fa-list me-1"></span>Posisi Stok
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4 no-print">
        <div class="col-md-3 col-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Stok Awal</div>
                            <div class="info-value text-primary" id="card-stat-opening">—</div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <span class="fas fa-flag"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Total Masuk</div>
                            <div class="info-value text-success" id="card-stat-in">—</div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <span class="fas fa-arrow-down"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Total Keluar</div>
                            <div class="info-value text-danger" id="card-stat-out">—</div>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <span class="fas fa-arrow-up"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Stok Akhir</div>
                            <div class="info-value" id="card-stat-closing">—</div>
                            <div class="period-range-text" id="card-stat-period-range"></div>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                            <span class="fas fa-flag-checkered"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap no-print">
        <div class="d-flex gap-2 align-items-center">
            <span class="badge badge-phoenix badge-phoenix-secondary fs-9 p-2 px-3" id="card-context-badge">
                <span class="fas fa-info-circle me-1"></span>Pilih periode, gudang &amp; bahan kimia
            </span>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <div class="d-flex align-items-center gap-1" title="Pengaturan orientasi kertas untuk Cetak Kartu Stok">
                <label for="card-print-orientation" class="fs-10 text-muted mb-0 text-nowrap">
                    <span class="fas fa-cog me-1"></span>Orientasi
                </label>
                <select class="form-select form-select-sm" id="card-print-orientation" style="width:auto">
                    <option value="portrait" selected>Portrait</option>
                    <option value="landscape">Landscape</option>
                </select>
            </div>
            <button class="btn btn-subtle-primary btn-sm" id="card-btn-print" type="button">
                <span class="fas fa-print me-1"></span>Cetak Kartu Stok
            </button>
            <button class="btn btn-subtle-secondary btn-sm" id="card-btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
        </div>
    </div>

    <!-- Print header -->
    <div class="print-header mb-3">
        <h5 class="fw-bold mb-1">Kartu Stok Bahan Kimia</h5>
        <div class="text-muted small" id="card-print-context"></div>
        <div class="text-muted small" id="card-print-period-range"></div>
        <div class="text-muted small">Dicetak: <span id="card-print-date"></span></div>
        <hr class="my-2">
    </div>

    <!-- Empty state -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y" id="card-empty-wrapper">
        <div class="text-center py-5 text-body-tertiary">
            <span class="fas fa-book fa-2x mb-2 d-block opacity-50"></span>
            Pilih periode, gudang, dan bahan kimia terlebih dahulu untuk menampilkan kartu stok.
        </div>
    </div>

    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y d-none" id="card-table-wrapper">
        <table class="table table-hover fs-9 nowrap align-middle" id="card-stock-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis Transaksi</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Saldo Berjalan</th>
                    <th class="no-print">Catatan</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Filter toggle -->
<a class="card filter-toggle no-print" href="#card-filter-offcanvas" data-bs-toggle="offcanvas" id="card-filter-toggle">
    <div class="card-body">
        <span class="fas fa-filter text-primary"></span>
        <span class="filter-label">Filter</span>
        <span class="filter-dot"></span>
    </div>
</a>

<!-- Filter Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="card-filter-offcanvas" style="width:320px">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title"><span class="fas fa-filter me-2 text-primary"></span>Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="flex-grow-1">
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="card-filter-period">
                    Periode <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="card-filter-period" style="width:100%">
                    <option value=""></option>
                </select>
                <div class="form-text fs-10" id="card-period-status-hint"></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="card-filter-warehouse">
                    Gudang <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="card-filter-warehouse" style="width:100%">
                    <option value=""></option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="card-filter-chemical">
                    Bahan Kimia <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="card-filter-chemical" style="width:100%">
                    <option value=""></option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">
                    Rentang Tanggal <span class="text-muted normal-case fw-normal">(opsional)</span>
                </label>
                <div class="d-flex gap-2 align-items-center">
                    <input type="date" class="form-control form-control-sm" id="card-filter-date-from" placeholder="Dari">
                    <span class="text-muted">—</span>
                    <input type="date" class="form-control form-control-sm" id="card-filter-date-to" placeholder="Sampai">
                </div>
                <div class="form-text fs-10">Kosongkan untuk melihat seluruh transaksi 1 periode penuh</div>
            </div>
        </div>
        <div id="card-filter-summary" class="mb-3 d-none">
            <div class="alert alert-subtle-info py-2 px-3 mb-0 fs-10">
                <span class="fas fa-info-circle me-1"></span>
                <span id="card-filter-summary-text"></span>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary btn-sm" id="card-btn-apply-filter">
                <span class="fas fa-search me-1"></span>Terapkan
            </button>
            <button class="btn btn-subtle-secondary btn-sm" id="card-btn-reset-filter">
                <span class="fas fa-times me-1"></span>Reset
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?php
// Nama yang tampil di "Dicetak Oleh" — prioritas: nama lengkap employee, fallback username/email.
$printedByName = '-';
if (!empty($user_employee['fullname'])) {
    $printedByName = ucwords(strtolower($user_employee['fullname']));
} elseif (auth()->user()) {
    $printedByName = auth()->user()->username ?? auth()->user()->email ?? '-';
}
?>
<?= $this->section('scripts') ?>

<script>
    const StockCard = {
        BASE: '<?= base_url() ?>',
        printedBy: '<?= esc($printedByName) ?>',
        printOrientation: 'portrait',
        currentPeriodId: null,
        currentPeriodRange: null,
        currentPeriodStatus: null,
        currentWarehouseId: null,
        currentWarehouseText: null,
        currentChemicalId: null,
        currentChemicalCode: null,
        currentChemicalText: null,
        currentFromDate: null,
        currentToDate: null,
        dt: null,

        movementLabels: {
            Receipt: 'Penerimaan',
            Issue: 'Pemakaian',
            TransferIn: 'Transfer Masuk',
            TransferOut: 'Transfer Keluar',
            AdjustmentIn: 'Penyesuaian (+)',
            AdjustmentOut: 'Penyesuaian (-)',
        },

        init() {
            this.initSelect2();
            this.initEvents();
            document.getElementById('card-print-date').textContent = new Date().toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            this.initFromQueryParams();
        },

        /**
         * Dipanggil dari halaman lain (mis. Posisi Stok, Penerimaan) lewat link berisi query
         * string, supaya filter langsung terisi & data langsung tampil tanpa user pilih manual.
         * chemical_id bersifat opsional — kalau hanya period_id/warehouse_id yang dikirim
         * (mis. dari tombol "Lihat Kartu Stok" di halaman Penerimaan), dropdown tetap
         * ter-pre-fill tapi user masih perlu pilih bahan kimia sendiri.
         * Contoh: ?period_id=1&period_text=...&warehouse_id=2&warehouse_text=...&chemical_id=3&chemical_text=...&search_regex=Penerimaan
         */
        initFromQueryParams() {
            const p = new URLSearchParams(window.location.search);
            const periodId = p.get('period_id');
            const warehouseId = p.get('warehouse_id');
            const chemicalId = p.get('chemical_id');
            if (!periodId && !warehouseId) return;

            const periodText = p.get('period_text') || '';
            const warehouseText = p.get('warehouse_text') || '';
            const chemicalText = p.get('chemical_text') || '';
            const chemicalCode = p.get('chemical_code') || '';

            // Isi select2 secara visual (kosmetik) tanpa perlu fetch ulang ke server
            if (periodId && periodText) {
                const opt = new Option(periodText, periodId, true, true);
                $('#card-filter-period').append(opt).trigger('change');
                this.currentPeriodId = periodId;
                this.currentPeriodRange = p.get('period_range') || null;
                this.currentPeriodStatus = p.get('period_status') || null;
            }
            if (warehouseId && warehouseText) {
                const opt = new Option(warehouseText, warehouseId, true, true);
                $('#card-filter-warehouse').append(opt).trigger('change');
                this.currentWarehouseId = warehouseId;
                this.currentWarehouseText = warehouseText || null;
            }
            if (chemicalId && chemicalText) {
                const opt = new Option(chemicalText, chemicalId, true, true);
                $('#card-filter-chemical').append(opt).trigger('change');
                this.currentChemicalId = chemicalId;
                // chemical_text dari link Posisi Stok berformat "Nama (KODE)" — dipakai langsung utk tampilan.
                // chemical_code dikirim terpisah supaya barcode di halaman cetak tidak perlu parsing string.
                this.currentChemicalText = chemicalText || null;
                this.currentChemicalCode = chemicalCode || null;
                this._pendingSearchRegex = p.get('search_regex') || null;
                this._pendingSearch = p.get('search') || null;
            }

            if (this.currentPeriodRange) {
                document.getElementById('card-period-status-hint').textContent =
                    `${periodText}${this.currentPeriodStatus === 'Closed' ? ' — Ditutup' : ''} · ${this.currentPeriodRange}`;
            }

            this.updateFilterUI();
            this.reload(); // aman dipanggil meski chemical_id belum ada — reload() akan tampilkan empty state
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
            $('#card-filter-period').select2({
                dropdownParent: $('#card-filter-offcanvas'),
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

            $('#card-filter-warehouse').select2({
                dropdownParent: $('#card-filter-offcanvas'),
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

            $('#card-filter-chemical').select2({
                dropdownParent: $('#card-filter-offcanvas'),
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
                            code: c.code
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
        },

        initEvents() {
            document.getElementById('card-btn-refresh')?.addEventListener('click', () => this.reload());
            document.getElementById('card-btn-print')?.addEventListener('click', () => this.printStockCard());
            document.getElementById('card-print-orientation')?.addEventListener('change', e => {
                this.printOrientation = e.target.value === 'landscape' ? 'landscape' : 'portrait';
            });
            document.getElementById('card-btn-apply-filter')?.addEventListener('click', () => this.applyFilter());
            document.getElementById('card-btn-reset-filter')?.addEventListener('click', () => this.resetFilter());

            $('#card-filter-period').on('select2:select', e => {
                const p = e.params.data;
                const fromInput = document.getElementById('card-filter-date-from');
                const toInput = document.getElementById('card-filter-date-to');
                if (p.start_date) {
                    fromInput.min = p.start_date;
                    toInput.min = p.start_date;
                }
                if (p.end_date) {
                    fromInput.max = p.end_date;
                    toInput.max = p.end_date;
                }
            });
        },

        fmtDateOnly(d) {
            if (!d) return '—';
            // created_at dari backend berformat "Y-m-d H:i:s" — ganti spasi jadi 'T' supaya konsisten diparse semua browser.
            const normalized = typeof d === 'string' ? d.replace(' ', 'T') : d;
            return new Date(normalized).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        },

        applyFilter() {
            const periodData = $('#card-filter-period').select2('data')[0];
            const warehouseData = $('#card-filter-warehouse').select2('data')[0];
            const chemicalData = $('#card-filter-chemical').select2('data')[0];
            const fromDate = document.getElementById('card-filter-date-from').value || null;
            const toDate = document.getElementById('card-filter-date-to').value || null;

            if (!periodData?.id || !warehouseData?.id || !chemicalData?.id) {
                this.toast('error', 'Periode, gudang, dan bahan kimia wajib dipilih');
                return;
            }
            if (fromDate && toDate && fromDate > toDate) {
                this.toast('error', 'Tanggal dari tidak boleh lebih besar dari tanggal sampai');
                return;
            }

            document.getElementById('card-period-status-hint').classList.remove('text-danger');
            const rangeText = `${this.fmtDateOnly(periodData.start_date)} — ${this.fmtDateOnly(periodData.end_date)}`;
            document.getElementById('card-period-status-hint').textContent =
                `${periodData.name} (${periodData.code})${periodData.status === 'Closed' ? ' — Ditutup' : ''} · ${rangeText}`;

            this.currentPeriodId = periodData.id;
            this.currentPeriodRange = rangeText;
            this.currentPeriodStatus = periodData.status;
            this.currentWarehouseId = warehouseData.id;
            this.currentWarehouseText = warehouseData.text;
            this.currentChemicalId = chemicalData.id;
            this.currentChemicalCode = chemicalData.code ?? null;
            this.currentChemicalText = chemicalData.code ? `${chemicalData.text} (${chemicalData.code})` : chemicalData.text;
            this.currentFromDate = fromDate;
            this.currentToDate = toDate;

            this.updateFilterUI();
            bootstrap.Offcanvas.getInstance(document.getElementById('card-filter-offcanvas'))?.hide();
            this.reload();
        },

        resetFilter() {
            $('#card-filter-period').val(null).trigger('change');
            $('#card-filter-warehouse').val(null).trigger('change');
            $('#card-filter-chemical').val(null).trigger('change');
            document.getElementById('card-filter-date-from').value = '';
            document.getElementById('card-filter-date-to').value = '';
            document.getElementById('card-period-status-hint').textContent = '';
            this.currentPeriodId = null;
            this.currentPeriodRange = null;
            this.currentPeriodStatus = null;
            this.currentWarehouseId = null;
            this.currentWarehouseText = null;
            this.currentChemicalId = null;
            this.currentChemicalCode = null;
            this.currentChemicalText = null;
            this.currentFromDate = null;
            this.currentToDate = null;
            this.renderEmpty();
            this.updateFilterUI();
        },

        updateFilterUI() {
            const labels = [];
            if (this.currentPeriodId) labels.push(`Periode: ${$('#card-filter-period').select2('data')[0]?.text ?? ''}`);
            if (this.currentWarehouseText) labels.push(`Gudang: ${this.currentWarehouseText}`);
            if (this.currentChemicalText) labels.push(`Bahan: ${this.currentChemicalText}`);
            if (this.currentFromDate || this.currentToDate) {
                const from = this.currentFromDate ? this.fmtDateOnly(this.currentFromDate) : 'Awal periode';
                const to = this.currentToDate ? this.fmtDateOnly(this.currentToDate) : 'Akhir periode';
                labels.push(`Tanggal: ${from} — ${to}`);
            }

            document.getElementById('card-filter-toggle').classList.toggle('has-filter', labels.length > 0);
            document.getElementById('card-filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('card-filter-summary').classList.toggle('d-none', labels.length === 0);

            const badge = document.getElementById('card-context-badge');
            if (labels.length) {
                badge.innerHTML = `<span class="fas fa-check-circle me-1"></span>${labels.join(' · ')}`;
            } else {
                badge.innerHTML = `<span class="fas fa-info-circle me-1"></span>Pilih periode, gudang &amp; bahan kimia`;
            }
            document.getElementById('card-print-context').textContent = labels.join(' · ');
            document.getElementById('card-print-period-range').textContent = this.currentPeriodRange ? `Rentang: ${this.currentPeriodRange}` : '';
            document.getElementById('card-stat-period-range').textContent =
                (this.currentFromDate || this.currentToDate) ?
                `Difilter: ${this.currentFromDate ? this.fmtDateOnly(this.currentFromDate) : 'awal'} — ${this.currentToDate ? this.fmtDateOnly(this.currentToDate) : 'akhir'}` :
                (this.currentPeriodRange ?? '');
        },

        async reload() {
            if (!this.currentPeriodId || !this.currentWarehouseId || !this.currentChemicalId) {
                this.renderEmpty();
                return;
            }
            this.showLoading();
            try {
                const params = new URLSearchParams({
                    period_id: this.currentPeriodId,
                    warehouse_id: this.currentWarehouseId,
                    chemical_id: this.currentChemicalId,
                });
                if (this.currentFromDate) params.set('from_date', this.currentFromDate);
                if (this.currentToDate) params.set('to_date', this.currentToDate);

                const d = await this.get(this.BASE + `warehouse/stocks/stock-card/data?${params.toString()}`);
                if (d.status !== 'success') {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    this.renderEmpty();
                    return;
                }
                this.showWrapper();
                this.buildTable(d.data);

                // Terapkan pencarian otomatis kalau datang dari link Posisi Stok
                if (this._pendingSearchRegex && this.dt) {
                    this.dt.search(this._pendingSearchRegex, true, false).draw();
                    $('#card-stock-table_filter input').val(this._pendingSearchRegex);
                    this._pendingSearchRegex = null;
                } else if (this._pendingSearch && this.dt) {
                    this.dt.search(this._pendingSearch).draw();
                    $('#card-stock-table_filter input').val(this._pendingSearch);
                    this._pendingSearch = null;
                }
            } catch (e) {
                this.toast('error', 'Gagal memuat data');
                this.renderEmpty();
            }
        },

        showLoading() {
            document.getElementById('card-empty-wrapper').classList.add('d-none');
            document.getElementById('card-table-wrapper').classList.add('d-none');
        },

        renderEmpty() {
            document.getElementById('card-empty-wrapper').classList.remove('d-none');
            document.getElementById('card-table-wrapper').classList.add('d-none');
            this.setStats(null);
        },

        showWrapper() {
            document.getElementById('card-empty-wrapper').classList.add('d-none');
            document.getElementById('card-table-wrapper').classList.remove('d-none');
        },

        balanceClass(v) {
            const n = Number(v ?? 0);
            if (n > 0) return 'text-balance-positive';
            if (n < 0) return 'text-balance-negative';
            return 'text-balance-zero';
        },

        setStats(data) {
            if (!data) {
                document.getElementById('card-stat-opening').textContent = '—';
                document.getElementById('card-stat-in').textContent = '—';
                document.getElementById('card-stat-out').textContent = '—';
                document.getElementById('card-stat-closing').textContent = '—';
                document.getElementById('card-stat-closing').className = 'info-value';
                return;
            }
            document.getElementById('card-stat-opening').textContent = this.fmtNumber(data.opening_qty);
            document.getElementById('card-stat-in').textContent = '+' + this.fmtNumber(data.total_in);
            document.getElementById('card-stat-out').textContent = '-' + this.fmtNumber(data.total_out);
            const closingEl = document.getElementById('card-stat-closing');
            closingEl.textContent = this.fmtNumber(data.closing_qty);
            closingEl.className = 'info-value ' + this.balanceClass(data.closing_qty);
        },

        buildTable(data) {
            this.setStats(data);

            if ($.fn.DataTable.isDataTable('#card-stock-table')) {
                this.dt.destroy();
                $('#card-stock-table tbody').remove();
            }

            const self = this;

            // Baris "Stok Awal" ditambahkan manual sebagai baris pertama (bukan dari ledger).
            // Tanggalnya memakai tanggal input opening stok (created_at chemical_stock_openings), bukan filter tanggal.
            const openingRow = {
                is_opening: true,
                movement_date: data.opening_date ?? null,
                movement_label: data.is_filtered ? 'Saldo Awal (sebelum rentang filter)' : 'Stok Awal Periode',
                quantity_in: null,
                quantity_out: null,
                running_balance: data.opening_qty,
                notes: null,
            };
            const rows = [openingRow, ...data.ledger];

            this.dt = $('#card-stock-table').DataTable({
                data: rows,
                responsive: true,
                scrollX: true,
                paging: false,
                searching: rows.length > 15,
                info: false,
                ordering: false,
                dom: '<"top"f>rt',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari transaksi...',
                    zeroRecords: 'Belum ada transaksi pada periode ini',
                },
                columnDefs: [{
                    targets: [2, 3, 4],
                    className: 'text-end'
                }, ],
                columns: [{
                        data: 'movement_date',
                        render: d => d ? self.fmtDateOnly(d) : '<span class="text-muted">—</span>',
                    },
                    {
                        data: 'movement_label',
                        render: (d, t, row) => {
                            if (row.is_opening) return `<span class="badge badge-phoenix badge-phoenix-primary p-2 fs-10">${self.e(d)}</span>`;
                            return `<span class="badge p-2 fs-10 movement-badge-${self.e(row.movement_type)}">${self.e(d)}</span>`;
                        },
                    },
                    {
                        data: 'quantity_in',
                        render: d => (d === null || d === undefined) ? '<span class="text-muted">—</span>' : `<span class="text-success">+${self.fmtNumber(d)}</span>`,
                    },
                    {
                        data: 'quantity_out',
                        render: d => (d === null || d === undefined) ? '<span class="text-muted">—</span>' : `<span class="text-danger">-${self.fmtNumber(d)}</span>`,
                    },
                    {
                        data: 'running_balance',
                        render: (d, t, row) => `<span class="fw-bold ${self.balanceClass(d)}">${self.fmtNumber(d)}</span>`,
                    },
                    {
                        data: 'notes',
                        className: 'no-print',
                        render: d => d ? self.e(d) : '<span class="text-muted">—</span>',
                    },
                ],
                createdRow: (tr, rowData) => {
                    if (rowData.is_opening) tr.classList.add('row-opening');
                },
            });
        },

        // ============================================================
        // CETAK KARTU STOK — halaman print dengan kop surat resmi
        // ============================================================
        printStockCard() {
            if (!this.currentPeriodId || !this.currentWarehouseId || !this.currentChemicalId || !this.dt) {
                this.toast('error', 'Pilih periode, gudang, dan bahan kimia terlebih dahulu');
                return;
            }

            const rows = this.dt.rows({
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
            const periodText = $('#card-filter-period').select2('data')[0]?.text ?? '';
            const searchTerm = $('#card-stock-table_filter input').val() || '';
            const orientation = this.printOrientation === 'landscape' ? 'landscape' : 'portrait';

            // Kode kimia untuk barcode — utamakan state currentChemicalCode; fallback parsing dari
            // "Nama (KODE)" untuk kompatibilitas link lama yang belum membawa parameter chemical_code.
            const chemicalCode = this.currentChemicalCode || (this.currentChemicalText?.match(/\(([^)]+)\)\s*$/)?.[1] ?? '');

            const rowsHtml = rows.map(r => `
                <tr class="${r.is_opening ? 'opening' : ''}">
                    <td>${r.movement_date ? this.fmtDateOnly(r.movement_date) : '-'}</td>
                    <td>${this.e(r.movement_label)}</td>
                    <td class="num in">${(r.quantity_in === null || r.quantity_in === undefined) ? '-' : '+' + this.fmtNumber(r.quantity_in)}</td>
                    <td class="num out">${(r.quantity_out === null || r.quantity_out === undefined) ? '-' : '-' + this.fmtNumber(r.quantity_out)}</td>
                    <td class="num bold ${this.balancePrintClass(r.running_balance)}">${this.fmtNumber(r.running_balance)}</td>
                    <td>${r.notes ? this.e(r.notes) : '-'}</td>
                </tr>
            `).join('');

            const printWindow = window.open('', '_blank', 'width=1100,height=850');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Kartu Stok - ${this.e(this.currentChemicalText ?? '')}</title>
                    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
                    <style>
                        @page { size: A4 ${orientation}; margin: 3mm; }
                        * { box-sizing: border-box; }
                        body { font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #111; margin: 0; padding: 4mm; }
                        .letterhead { display: flex; align-items: center; justify-content: space-between; gap: 12px; border-bottom: 2px solid #111; padding-bottom: 8px; margin-bottom: 10px; }
                        .letterhead .brand { display: flex; align-items: center; gap: 12px; }
                        .letterhead img { height: 48px; }
                        .letterhead .company { font-size: 15pt; font-weight: bold; letter-spacing: .3px; }
                        .letterhead .division { font-size: 10pt; color: #444; }
                        .barcode-box { text-align: center; }
                        .barcode-box svg { display: block; }
                        .barcode-box .code-text { font-size: 7pt; color: #555; font-family: 'Courier New', monospace; margin-top: -2px; }
                        .doc-title { text-align: center; font-size: 11pt; font-weight: bold; margin: 6px 0 10px; text-transform: uppercase; }
                        .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px 16px; font-size: 8.5pt; margin-bottom: 10px; border: 1px solid #ccc; padding: 8px 10px; background: #f8f8f8; }
                        .info-grid div span.label { color: #666; display: block; font-size: 7.5pt; text-transform: uppercase; letter-spacing: .3px; }
                        .info-grid div span.value { font-weight: 600; }
                        table { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
                        th, td { border: 1px solid #999; padding: 3px 6px; text-align: left; }
                        th { background: #eee; font-weight: 700; }
                        td.num, th.num { text-align: right; }
                        td.bold { font-weight: 700; }
                        td.in { color: #1e7e34; }
                        td.out { color: #c0392b; }
                        td.pos { color: #1e7e34; }
                        td.neg { color: #c0392b; }
                        td.zero { color: #6c757d; }
                        tr.opening { background: #eef4ff; font-weight: 600; }
                        .footer-note { margin-top: 14px; font-size: 7.5pt; color: #777; display: flex; justify-content: space-between; border-top: 1px solid #ccc; padding-top: 6px; }
                        @media print { .no-print { display: none !important; } }
                    </style>
                </head>
                <body>
                    <div class="letterhead">
                        <div class="brand">
                            <img src="${logoPath}" alt="Logo" onerror="this.style.display='none'">
                            <div>
                                <div class="company">PT. SINAR CONTINENTAL</div>
                                <div class="division">DIVISI 3A</div>
                            </div>
                        </div>
                        <div class="barcode-box">
                            <svg id="chemical-barcode"></svg>
                        </div>
                    </div>
                    <div class="doc-title">Kartu Stok Bahan Kimia</div>
                    <div class="info-grid">
                        <div><span class="label">Bahan Kimia</span><span class="value">${this.e(this.currentChemicalText ?? '-')}</span></div>
                        <div><span class="label">Kode Kimia</span><span class="value">${this.e(chemicalCode || '-')}</span></div>
                        <div><span class="label">Gudang</span><span class="value">${this.e(this.currentWarehouseText ?? '-')}</span></div>
                        <div><span class="label">Periode</span><span class="value">${this.e(periodText)}${this.currentPeriodStatus === 'Closed' ? ' (Close)' : ' (Opening)'}</span></div>
                        <div><span class="label">Rentang Tanggal</span><span class="value">${this.e((this.currentFromDate || this.currentToDate) ? `${this.currentFromDate ? this.fmtDateOnly(this.currentFromDate) : 'Awal periode'} — ${this.currentToDate ? this.fmtDateOnly(this.currentToDate) : 'Akhir periode'}` : (this.currentPeriodRange ?? '-'))}</span></div>
                        <div><span class="label">Filter Jenis Transaksi</span><span class="value">${searchTerm ? this.e(searchTerm) : 'Semua'}</span></div>
                        <div><span class="label">Tanggal Cetak</span><span class="value">${now}</span></div>
                    </div>
                    <table>
                        <thead>
                            <tr><th>Tanggal</th><th>Jenis Transaksi</th><th class="num">Masuk</th><th class="num">Keluar</th><th class="num">Saldo Berjalan</th><th>Catatan</th></tr>
                        </thead>
                        <tbody>${rowsHtml}</tbody>
                    </table>
                    <div class="footer-note">
                        <span>Dokumen ini dihasilkan otomatis dari sistem ERP — PT. Sinar Continental · Dicetak oleh ${this.e(this.printedBy)}</span>
                        <span>Dicetak: ${now}</span>
                    </div>
                    <script>
                        window.onload = () => {
                            try {
                                const code = ${JSON.stringify(chemicalCode || '')};
                                if (window.JsBarcode && code.trim()) {
                                    JsBarcode('#chemical-barcode', code, {
                                        format: 'CODE128',
                                        displayValue: true,
                                        fontSize: 11,
                                        height: 34,
                                        width: 1.4,
                                        margin: 0,
                                    });
                                } else {
                                    document.querySelector('.barcode-box').style.display = 'none';
                                }
                            } catch (e) {
                                document.querySelector('.barcode-box').style.display = 'none';
                            }
                            window.print();
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        },

        // Kelas warna saldo berjalan di halaman cetak — meniru text-balance-positive/negative/zero di tabel layar.
        balancePrintClass(v) {
            const n = Number(v ?? 0);
            if (n > 0) return 'pos';
            if (n < 0) return 'neg';
            return 'zero';
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

        fmtNumber(n) {
            return Number(n ?? 0).toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 3
            });
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

    $(document).ready(() => StockCard.init());
</script>
<?= $this->endSection() ?>