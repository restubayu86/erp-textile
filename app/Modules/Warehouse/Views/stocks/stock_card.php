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
        <div class="d-flex gap-2">
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

<?= $this->section('scripts') ?>

<script>
    const StockCard = {
        BASE: '<?= base_url() ?>',
        currentPeriodId: null,
        currentPeriodRange: null,
        currentPeriodStatus: null,
        currentWarehouseId: null,
        currentWarehouseText: null,
        currentChemicalId: null,
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
            return new Date(d).toLocaleDateString('id-ID', {
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
            this.currentChemicalId = null;
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

            // Baris "Stok Awal" ditambahkan manual sebagai baris pertama (bukan dari ledger)
            const openingRow = {
                is_opening: true,
                movement_date: this.currentFromDate ?? null,
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
                dom: '<"top"Bf>rt',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<span class="fas fa-file-excel me-1"></span>Export Excel',
                        className: 'btn btn-subtle-success btn-sm',
                        title: `Kartu Stok - ${self.currentChemicalText ?? ''}`,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                    },
                    {
                        extend: 'print',
                        text: '<span class="fas fa-print me-1"></span>Cetak',
                        className: 'btn btn-subtle-secondary btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                    },
                ],
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