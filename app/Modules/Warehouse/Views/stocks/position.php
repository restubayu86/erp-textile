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

    #pos-stock-table_wrapper,
    #pos-stock-table-combined_wrapper {
        max-width: 100%;
    }

    #pos-stock-table_wrapper .top,
    #pos-stock-table-combined_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #pos-stock-table_wrapper .top input,
    #pos-stock-table-combined_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #pos-stock-table_wrapper .bottom,
    #pos-stock-table-combined_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #pos-stock-table_wrapper .bottom .dataTables_length,
    #pos-stock-table-combined_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #pos-stock-table_wrapper .bottom .dataTables_paginate,
    #pos-stock-table-combined_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #pos-stock-table_wrapper .bottom .dataTables_info,
    #pos-stock-table-combined_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #pos-stock-table_wrapper .dataTables_filter label,
    #pos-stock-table_wrapper .dataTables_length label,
    #pos-stock-table-combined_wrapper .dataTables_filter label,
    #pos-stock-table-combined_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #pos-stock-table_wrapper .dataTables_length select,
    #pos-stock-table-combined_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #pos-stock-table_wrapper .dataTables_paginate .paginate_button,
    #pos-stock-table-combined_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #pos-stock-table_wrapper .dataTables_paginate .paginate_button.current,
    #pos-stock-table-combined_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #pos-stock-table,
    #pos-stock-table-combined {
        width: 100% !important;
    }

    .badge-wh-count {
        font-size: .68rem;
        font-weight: 600;
    }

    tr.combined-row {
        cursor: pointer;
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
            <a href="<?= site_url('warehouse/stocks/stock-card') ?>" class="btn btn-subtle-primary btn-sm">
                <span class="fas fa-book me-1"></span>Kartu Stok
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
                            <div class="info-label">Total Bahan Kimia</div>
                            <div class="info-value text-primary" id="pos-stat-total">—</div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <span class="fas fa-vial"></span>
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
                            <div class="info-label">Ada Stok (On Hand)</div>
                            <div class="info-value text-success" id="pos-stat-available">—</div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <span class="fas fa-check-circle"></span>
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
                            <div class="info-label">Ada Selisih Opname</div>
                            <div class="info-value text-warning" id="pos-stat-empty">—</div>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <span class="fas fa-exclamation-triangle"></span>
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
                            <div class="info-label">Status Periode</div>
                            <div class="info-value" id="pos-stat-period">—</div>
                            <div class="period-range-text" id="pos-stat-period-range"></div>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary" id="pos-stat-period-icon">
                            <span class="fas fa-calendar-alt"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap no-print">
        <div class="d-flex gap-2 align-items-center">
            <span class="badge badge-phoenix badge-phoenix-secondary fs-9 p-2 px-3" id="pos-context-badge">
                <span class="fas fa-info-circle me-1"></span>Pilih periode &amp; gudang
            </span>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="pos-btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
        </div>
    </div>

    <!-- Print header -->
    <div class="print-header mb-3">
        <h5 class="fw-bold mb-1">Posisi Stok Bahan Kimia</h5>
        <div class="text-muted small" id="pos-print-context"></div>
        <div class="text-muted small" id="pos-print-period-range"></div>
        <div class="text-muted small">Dicetak: <span id="pos-print-date"></span></div>
        <hr class="my-2">
    </div>

    <!-- Empty state -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y" id="pos-empty-wrapper">
        <div class="text-center py-5 text-body-tertiary">
            <span class="fas fa-boxes fa-2x mb-2 d-block opacity-50"></span>
            Pilih periode dan gudang terlebih dahulu untuk menampilkan posisi stok.
        </div>
    </div>

    <!-- Table (per gudang, read-only) -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y d-none" id="pos-perwarehouse-wrapper">
        <table class="table table-hover fs-9 nowrap align-middle" id="pos-stock-table">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Bahan Kimia</th>
                    <th rowspan="2">Kategori</th>
                    <th colspan="2" class="text-center">Stok Awal</th>
                    <th colspan="3" class="text-center">Pergerakan</th>
                    <th colspan="3" class="text-center">Saldo &amp; Alokasi</th>
                    <th colspan="2" class="text-center">Stock Opname</th>
                    <th rowspan="2">IFS</th>
                    <th colspan="2" class="text-center">Selisih</th>
                </tr>
                <tr>
                    <th>Available</th>
                    <th>On Hand</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Adjustment</th>
                    <th>Allocated</th>
                    <th>On Hand</th>
                    <th>Available</th>
                    <th>Available</th>
                    <th>On Hand</th>
                    <th>vs Opname</th>
                    <th>IFS vs Opname</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Table (combined, semua gudang) -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y d-none" id="pos-combined-wrapper">
        <table class="table table-hover fs-9 nowrap align-middle" id="pos-stock-table-combined">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bahan Kimia</th>
                    <th>Kategori</th>
                    <th>On Hand</th>
                    <th>Allocated</th>
                    <th>Available</th>
                    <th>Tersebar di</th>
                    <th class="no-print"></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Filter toggle -->
<a class="card filter-toggle no-print" href="#pos-filter-offcanvas" data-bs-toggle="offcanvas" id="pos-filter-toggle">
    <div class="card-body">
        <span class="fas fa-filter text-primary"></span>
        <span class="filter-label">Filter</span>
        <span class="filter-dot"></span>
    </div>
</a>

<!-- Filter Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="pos-filter-offcanvas" style="width:320px">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title"><span class="fas fa-filter me-2 text-primary"></span>Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="flex-grow-1">
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="pos-filter-period">
                    Periode <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="pos-filter-period" style="width:100%">
                    <option value=""></option>
                </select>
                <div class="form-text fs-10" id="pos-period-status-hint"></div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="pos-filter-warehouse">
                    Gudang <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="pos-filter-warehouse" style="width:100%">
                    <option value="__combined__">— Gabungan (Semua Gudang) —</option>
                </select>
            </div>
        </div>
        <div id="pos-filter-summary" class="mb-3 d-none">
            <div class="alert alert-subtle-info py-2 px-3 mb-0 fs-10">
                <span class="fas fa-info-circle me-1"></span>
                <span id="pos-filter-summary-text"></span>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary btn-sm" id="pos-btn-apply-filter">
                <span class="fas fa-search me-1"></span>Terapkan
            </button>
            <button class="btn btn-subtle-secondary btn-sm" id="pos-btn-reset-filter">
                <span class="fas fa-times me-1"></span>Reset
            </button>
        </div>
    </div>
</div>

<!-- Modal Rincian per Gudang (mode Gabungan) -->
<div class="modal fade" id="pos-breakdownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="pos-breakdown-title">Rincian per Gudang</h5>
                    <p class="text-muted fs-10 mb-0" id="pos-breakdown-subtitle"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div id="pos-breakdown-body"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    const PositionStock = {
        BASE: '<?= base_url() ?>',
        currentPeriodId: null,
        currentPeriodRange: null,
        currentWarehouseId: null,
        currentWarehouseText: null,
        currentPeriodStatus: null,
        dtPerWarehouse: null,
        dtCombined: null,

        init() {
            this.initSelect2();
            this.initEvents();
            document.getElementById('pos-print-date').textContent = new Date().toLocaleDateString('id-ID', {
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
            $('#pos-filter-period').select2({
                dropdownParent: $('#pos-filter-offcanvas'),
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

            $('#pos-filter-warehouse').select2({
                dropdownParent: $('#pos-filter-offcanvas'),
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
                        results: [{
                                id: '__combined__',
                                text: '— Gabungan (Semua Gudang) —'
                            },
                            ...(data.data ?? []).map(w => ({
                                id: w.id,
                                text: `${w.name} (${w.code})`
                            }))
                        ]
                    }),
                },
            });
        },

        initEvents() {
            document.getElementById('pos-btn-refresh')?.addEventListener('click', () => this.reload());
            document.getElementById('pos-btn-apply-filter')?.addEventListener('click', () => this.applyFilter());
            document.getElementById('pos-btn-reset-filter')?.addEventListener('click', () => this.resetFilter());
        },

        fmtDateOnly(d) {
            if (!d) return '—';
            return new Date(d).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        },

        async applyFilter() {
            const periodData = $('#pos-filter-period').select2('data')[0];
            const warehouseId = $('#pos-filter-warehouse').val();
            const warehouseText = $('#pos-filter-warehouse option:selected').text() || $('#pos-filter-warehouse').find(':selected').text();

            if (!periodData?.id || !warehouseId) {
                this.toast('error', 'Periode dan gudang wajib dipilih');
                return;
            }

            document.getElementById('pos-period-status-hint').classList.remove('text-danger');
            const rangeText = `${this.fmtDateOnly(periodData.start_date)} — ${this.fmtDateOnly(periodData.end_date)}`;
            document.getElementById('pos-period-status-hint').textContent =
                `${periodData.name} (${periodData.code})${periodData.status === 'Closed' ? ' — Ditutup' : ''} · ${rangeText}`;

            this.currentPeriodId = periodData.id;
            this.currentPeriodRange = rangeText;
            this.currentPeriodStatus = periodData.status;
            this.currentWarehouseId = warehouseId;
            this.currentWarehouseText = warehouseId === '__combined__' ?
                'Gabungan (Semua Gudang)' :
                (this.dtFromSelect2Text(warehouseId) ?? warehouseText);

            this.updateFilterUI(periodData.text, this.currentWarehouseText);
            bootstrap.Offcanvas.getInstance(document.getElementById('pos-filter-offcanvas'))?.hide();
            this.reload();
        },

        dtFromSelect2Text(id) {
            const data = $('#pos-filter-warehouse').select2('data');
            const found = (data ?? []).find(x => String(x.id) === String(id));
            return found?.text ?? null;
        },

        resetFilter() {
            $('#pos-filter-period').val(null).trigger('change');
            $('#pos-filter-warehouse').val('__combined__').trigger('change');
            document.getElementById('pos-period-status-hint').textContent = '';
            this.currentPeriodId = null;
            this.currentPeriodRange = null;
            this.currentPeriodStatus = null;
            this.currentWarehouseId = null;
            this.renderEmpty();
            this.updateFilterUI(null, null);
        },

        updateFilterUI(periodLabel, warehouseText) {
            const labels = [];
            if (periodLabel) labels.push(`Periode: ${periodLabel}`);
            if (warehouseText) labels.push(`Gudang: ${warehouseText}`);

            document.getElementById('pos-filter-toggle').classList.toggle('has-filter', labels.length > 0);
            document.getElementById('pos-filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('pos-filter-summary').classList.toggle('d-none', labels.length === 0);

            const badge = document.getElementById('pos-context-badge');
            if (labels.length) {
                badge.innerHTML = `<span class="fas fa-check-circle me-1"></span>${labels.join(' · ')}`;
            } else {
                badge.innerHTML = `<span class="fas fa-info-circle me-1"></span>Pilih periode &amp; gudang`;
            }
            document.getElementById('pos-print-context').textContent = labels.join(' · ');
            document.getElementById('pos-print-period-range').textContent = this.currentPeriodRange ? `Rentang: ${this.currentPeriodRange}` : '';
            document.getElementById('pos-stat-period-range').textContent = this.currentPeriodRange ?? '';

            const periodEl = document.getElementById('pos-stat-period');
            const iconEl = document.getElementById('pos-stat-period-icon');
            if (this.currentPeriodStatus === 'Closed') {
                periodEl.textContent = 'Ditutup';
                periodEl.className = 'info-value text-secondary';
                iconEl.className = 'stat-icon bg-secondary bg-opacity-10 text-secondary';
            } else if (this.currentPeriodStatus) {
                periodEl.textContent = 'Terbuka';
                periodEl.className = 'info-value text-success';
                iconEl.className = 'stat-icon bg-success bg-opacity-10 text-success';
            } else {
                periodEl.textContent = '—';
                periodEl.className = 'info-value';
                iconEl.className = 'stat-icon bg-secondary bg-opacity-10 text-secondary';
            }
        },

        async reload() {
            if (!this.currentPeriodId || !this.currentWarehouseId) {
                this.renderEmpty();
                return;
            }
            if (this.currentWarehouseId === '__combined__') {
                await this.loadCombined();
            } else {
                await this.loadPerWarehouse();
            }
        },

        async loadPerWarehouse() {
            this.showLoading();
            try {
                const d = await this.get(this.BASE +
                    `warehouse/stocks/position/grid?period_id=${this.currentPeriodId}&warehouse_id=${this.currentWarehouseId}`);
                if (d.status !== 'success') {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    this.renderEmpty();
                    return;
                }
                this.showWrapper('perwarehouse');
                this.buildPerWarehouseTable(d.data);
            } catch (e) {
                this.toast('error', 'Gagal memuat data');
                this.renderEmpty();
            }
        },

        async loadCombined() {
            this.showLoading();
            try {
                const d = await this.get(this.BASE + `warehouse/stocks/position/combined?period_id=${this.currentPeriodId}`);
                if (d.status !== 'success') {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    this.renderEmpty();
                    return;
                }
                this.showWrapper('combined');
                this.buildCombinedTable(d.data);
            } catch (e) {
                this.toast('error', 'Gagal memuat data');
                this.renderEmpty();
            }
        },

        showLoading() {
            document.getElementById('pos-empty-wrapper').classList.add('d-none');
            document.getElementById('pos-perwarehouse-wrapper').classList.add('d-none');
            document.getElementById('pos-combined-wrapper').classList.add('d-none');
        },

        renderEmpty() {
            document.getElementById('pos-empty-wrapper').classList.remove('d-none');
            document.getElementById('pos-perwarehouse-wrapper').classList.add('d-none');
            document.getElementById('pos-combined-wrapper').classList.add('d-none');
            this.setStats([]);
        },

        showWrapper(kind) {
            document.getElementById('pos-empty-wrapper').classList.add('d-none');
            document.getElementById('pos-perwarehouse-wrapper').classList.toggle('d-none', kind !== 'perwarehouse');
            document.getElementById('pos-combined-wrapper').classList.toggle('d-none', kind !== 'combined');
        },

        setStats(rows) {
            const total = rows.length;
            const available = rows.filter(r => Number(r.on_hand) > 0).length;
            const withVariance = rows.filter(r => r.variance_on_hand !== null && Math.abs(Number(r.variance_on_hand)) > 0.001).length;
            document.getElementById('pos-stat-total').textContent = total;
            document.getElementById('pos-stat-available').textContent = available;
            document.getElementById('pos-stat-empty').textContent = withVariance;
        },

        balanceClass(v) {
            const n = Number(v ?? 0);
            if (n > 0) return 'text-balance-positive';
            if (n < 0) return 'text-balance-negative';
            return 'text-balance-zero';
        },

        categoryBadges(catText) {
            return catText ?
                catText.split(', ').map(c => `<span class="badge badge-phoenix badge-phoenix-secondary p-2 fs-10 me-1 mb-1">${this.e(c)}</span>`).join('') :
                '<span class="text-muted fst-italic">—</span>';
        },

        fmtOrDash(v) {
            return v === null || v === undefined ? '<span class="text-muted fst-italic">—</span>' : this.fmtNumber(v);
        },

        varianceClass(v) {
            if (v === null || v === undefined) return 'text-muted';
            const n = Number(v);
            if (Math.abs(n) < 0.001) return 'text-balance-zero';
            return n > 0 ? 'text-balance-positive' : 'text-balance-negative';
        },

        signedFmt(v) {
            if (v === null || v === undefined) return '<span class="text-muted fst-italic">—</span>';
            const n = Number(v);
            const sign = n > 0 ? '+' : '';
            return `${sign}${this.fmtNumber(n)}`;
        },

        // ============================================================
        // TABLE — per gudang (read-only, 13 metrik + no + nama + kategori)
        // ============================================================
        buildPerWarehouseTable(rows) {
            this.setStats(rows);

            if ($.fn.DataTable.isDataTable('#pos-stock-table')) {
                this.dtPerWarehouse.destroy();
                $('#pos-stock-table tbody').remove();
            }

            const self = this;
            const numCol = (field, opts = {}) => ({
                data: field,
                render: {
                    display: (d, t, row) => opts.signed ? self.signedFmt(d) : self.fmtOrDash(d),
                    sort: d => Number(d ?? -Infinity),
                    type: d => Number(d ?? -Infinity),
                    _: d => d,
                },
            });

            this.dtPerWarehouse = $('#pos-stock-table').DataTable({
                data: rows,
                rowId: 'chemical_id',
                responsive: false,
                scrollX: true,
                pageLength: 25,
                lengthMenu: [
                    [-1, 10, 25, 50, 100],
                    ['Semua', 10, 25, 50, 100]
                ],
                order: [
                    [1, 'asc']
                ],
                dom: '<"top"Bf>rt<"bottom"lpi>',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<span class="fas fa-file-excel me-1"></span>Export Excel',
                        className: 'btn btn-subtle-success btn-sm',
                        title: 'Posisi Stok Bahan Kimia',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },
                    {
                        extend: 'print',
                        text: '<span class="fas fa-print me-1"></span>Cetak',
                        className: 'btn btn-subtle-secondary btn-sm',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },
                ],
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
                        width: '130px'
                    },
                    {
                        targets: '_all',
                        className: 'text-end'
                    },
                    {
                        targets: [0, 1, 2],
                        className: ''
                    },
                ],
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: (d, t, r, meta) => meta.row + 1,
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
                        data: 'category_name',
                        render: d => self.categoryBadges(d)
                    },
                    numCol('available_opening'),
                    numCol('on_hand_opening'),
                    {
                        data: 'stock_in',
                        render: {
                            display: d => `<span class="text-success">+${self.fmtNumber(d)}</span>`,
                            sort: d => Number(d ?? 0),
                            type: d => Number(d ?? 0),
                            _: d => d
                        }
                    },
                    {
                        data: 'stock_out',
                        render: {
                            display: d => `<span class="text-danger">-${self.fmtNumber(d)}</span>`,
                            sort: d => Number(d ?? 0),
                            type: d => Number(d ?? 0),
                            _: d => d
                        }
                    },
                    numCol('adjustment', {
                        signed: true
                    }),
                    numCol('allocated'),
                    {
                        data: 'on_hand',
                        render: {
                            display: (d, t, row) => `<span class="fw-bold ${self.balanceClass(d)}">${self.fmtNumber(d)}</span>`,
                            sort: d => Number(d ?? 0),
                            type: d => Number(d ?? 0),
                            _: d => d,
                        },
                    },
                    {
                        data: 'available',
                        render: {
                            display: (d, t, row) => `<span class="fw-bold ${self.balanceClass(d)}">${self.fmtNumber(d)}</span>`,
                            sort: d => Number(d ?? 0),
                            type: d => Number(d ?? 0),
                            _: d => d,
                        },
                    },
                    numCol('opname_available'),
                    numCol('opname_on_hand'),
                    numCol('ifs_qty'),
                    {
                        data: 'variance_on_hand',
                        render: {
                            display: (d, t, row) => `<span class="fw-semibold ${self.varianceClass(d)}">${self.signedFmt(d)}</span>`,
                            sort: d => Number(d ?? -Infinity),
                            type: d => Number(d ?? -Infinity),
                            _: d => d,
                        },
                    },
                    {
                        data: 'variance_ifs',
                        render: {
                            display: (d, t, row) => `<span class="fw-semibold ${self.varianceClass(d)}">${self.signedFmt(d)}</span>`,
                            sort: d => Number(d ?? -Infinity),
                            type: d => Number(d ?? -Infinity),
                            _: d => d,
                        },
                    },
                ],
            });
        },

        // ============================================================
        // TABLE — combined (semua gudang) — ringkasan, detail lengkap ada di modal breakdown
        // ============================================================
        buildCombinedTable(rows) {
            this.setStats(rows);

            if ($.fn.DataTable.isDataTable('#pos-stock-table-combined')) {
                this.dtCombined.destroy();
                $('#pos-stock-table-combined tbody').remove();
            }

            const self = this;

            this.dtCombined = $('#pos-stock-table-combined').DataTable({
                data: rows,
                rowId: 'chemical_id',
                responsive: true,
                scrollX: true,
                pageLength: 25,
                lengthMenu: [
                    [-1, 10, 25, 50, 100],
                    ['Semua', 10, 25, 50, 100]
                ],
                order: [
                    [1, 'asc']
                ],
                dom: '<"top"Bf>rt<"bottom"lpi>',
                buttons: [{
                    extend: 'excelHtml5',
                    text: '<span class="fas fa-file-excel me-1"></span>Export Excel',
                    className: 'btn btn-subtle-success btn-sm',
                    title: 'Posisi Stok Bahan Kimia (Gabungan)',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    },
                }, ],
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
                },
                columnDefs: [{
                        targets: 0,
                        width: '30px'
                    },
                    {
                        targets: 1,
                        width: '220px'
                    },
                    {
                        targets: 2,
                        width: '150px'
                    },
                    {
                        targets: [3, 4, 5],
                        className: 'text-end'
                    },
                    {
                        targets: 6,
                        width: '110px'
                    },
                    {
                        targets: 7,
                        width: '30px'
                    },
                ],
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: (d, t, r, meta) => meta.row + 1,
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
                        data: 'category_name',
                        render: d => self.categoryBadges(d)
                    },
                    {
                        data: 'on_hand',
                        render: {
                            display: d => `<span class="fw-bold ${self.balanceClass(d)}">${self.fmtNumber(d)}</span>`,
                            sort: d => Number(d ?? 0),
                            type: d => Number(d ?? 0),
                            _: d => d,
                        },
                    },
                    {
                        data: 'allocated',
                        render: {
                            display: d => self.fmtOrDash(d),
                            sort: d => Number(d ?? 0),
                            type: d => Number(d ?? 0),
                            _: d => d
                        },
                    },
                    {
                        data: 'available',
                        render: {
                            display: d => `<span class="fw-bold ${self.balanceClass(d)}">${self.fmtNumber(d)}</span>`,
                            sort: d => Number(d ?? 0),
                            type: d => Number(d ?? 0),
                            _: d => d,
                        },
                    },
                    {
                        data: 'warehouse_count',
                        render: d => `<span class="badge badge-phoenix badge-phoenix-info badge-wh-count">${d} gudang</span>`,
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render: () => '<span class="fas fa-chevron-right text-muted"></span>',
                    },
                ],
                createdRow: tr => tr.classList.add('combined-row'),
            });

            $('#pos-stock-table-combined tbody').off('click').on('click', 'tr', function() {
                const rowData = self.dtCombined.row(this).data();
                if (rowData) self.openBreakdown(rowData.chemical_id, rowData.chemical_name);
            });
        },

        // ============================================================
        // BREAKDOWN MODAL
        // ============================================================
        async openBreakdown(chemicalId, chemicalName) {
            document.getElementById('pos-breakdown-title').textContent = chemicalName;
            document.getElementById('pos-breakdown-subtitle').textContent = 'Rincian posisi stok per gudang';
            document.getElementById('pos-breakdown-body').innerHTML = `<div class="text-center py-3"><span class="spinner-border spinner-border-sm text-primary"></span></div>`;
            new bootstrap.Modal(document.getElementById('pos-breakdownModal')).show();

            try {
                const d = await this.get(this.BASE +
                    `warehouse/stocks/position/breakdown?period_id=${this.currentPeriodId}&chemical_id=${chemicalId}`);
                if (d.status !== 'success' || !d.data.length) {
                    document.getElementById('pos-breakdown-body').innerHTML = `<p class="text-muted text-center mb-0">Belum ada data di gudang manapun.</p>`;
                    return;
                }
                const rows = d.data.map(r => `
                    <tr>
                        <td>${this.e(r.warehouse_name)} <span class="text-muted small">(${this.e(r.warehouse_code)})</span></td>
                        <td class="text-end">${this.fmtOrDash(r.available_opening)}</td>
                        <td class="text-end">${this.fmtOrDash(r.on_hand_opening)}</td>
                        <td class="text-end text-success">+${this.fmtNumber(r.stock_in)}</td>
                        <td class="text-end text-danger">-${this.fmtNumber(r.stock_out)}</td>
                        <td class="text-end ${this.varianceClass(r.adjustment)}">${this.signedFmt(r.adjustment)}</td>
                        <td class="text-end">${this.fmtOrDash(r.allocated)}</td>
                        <td class="text-end fw-bold ${this.balanceClass(r.on_hand)}">${this.fmtNumber(r.on_hand)}</td>
                        <td class="text-end fw-bold ${this.balanceClass(r.available)}">${this.fmtNumber(r.available)}</td>
                        <td class="text-end">${this.fmtOrDash(r.opname_on_hand)}</td>
                        <td class="text-end">${this.fmtOrDash(r.ifs_qty)}</td>
                        <td class="text-end fw-semibold ${this.varianceClass(r.variance_on_hand)}">${this.signedFmt(r.variance_on_hand)}</td>
                        <td class="text-end fw-semibold ${this.varianceClass(r.variance_ifs)}">${this.signedFmt(r.variance_ifs)}</td>
                    </tr>
                `).join('');
                document.getElementById('pos-breakdown-body').innerHTML = `
                    <div class="table-responsive">
                    <table class="table table-sm fs-9 mb-0">
                        <thead>
                            <tr>
                                <th>Gudang</th>
                                <th class="text-end">Awal (Avl)</th>
                                <th class="text-end">Awal (OH)</th>
                                <th class="text-end">Masuk</th>
                                <th class="text-end">Keluar</th>
                                <th class="text-end">Adj.</th>
                                <th class="text-end">Alloc.</th>
                                <th class="text-end">On Hand</th>
                                <th class="text-end">Available</th>
                                <th class="text-end">Opname (OH)</th>
                                <th class="text-end">IFS</th>
                                <th class="text-end">Selisih OH</th>
                                <th class="text-end">Selisih IFS</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                    </div>`;
            } catch {
                document.getElementById('pos-breakdown-body').innerHTML = `<p class="text-danger text-center mb-0">Gagal memuat rincian.</p>`;
            }
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

    $(document).ready(() => PositionStock.init());
</script>
<?= $this->endSection() ?>