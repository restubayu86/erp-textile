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

    #ifs-stock-table_wrapper,
    #ifs-stock-table-combined_wrapper {
        max-width: 100%;
    }

    #ifs-stock-table_wrapper .top,
    #ifs-stock-table-combined_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #ifs-stock-table_wrapper .top input,
    #ifs-stock-table-combined_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #ifs-stock-table_wrapper .bottom,
    #ifs-stock-table-combined_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #ifs-stock-table_wrapper .bottom .dataTables_length,
    #ifs-stock-table-combined_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #ifs-stock-table_wrapper .bottom .dataTables_paginate,
    #ifs-stock-table-combined_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #ifs-stock-table_wrapper .bottom .dataTables_info,
    #ifs-stock-table-combined_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #ifs-stock-table_wrapper .dataTables_filter label,
    #ifs-stock-table_wrapper .dataTables_length label,
    #ifs-stock-table-combined_wrapper .dataTables_filter label,
    #ifs-stock-table-combined_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #ifs-stock-table_wrapper .dataTables_length select,
    #ifs-stock-table-combined_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #ifs-stock-table_wrapper .dataTables_paginate .paginate_button,
    #ifs-stock-table-combined_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #ifs-stock-table_wrapper .dataTables_paginate .paginate_button.current,
    #ifs-stock-table-combined_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #ifs-stock-table,
    #ifs-stock-table-combined {
        width: 100% !important;
    }

    #ifs-stock-table input.qty-input {
        text-align: right;
        max-width: 140px;
    }

    #ifs-stock-table tr.row-touched {
        background-color: rgba(var(--phoenix-warning-rgb), .06);
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
        <div class="col-md-3 col-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Total Bahan Kimia</div>
                            <div class="info-value text-primary" id="ifs-stat-total">—</div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <span class="fas fa-flask"></span>
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
                            <div class="info-label">Sudah Diinput</div>
                            <div class="info-value text-success" id="ifs-stat-filled">—</div>
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
                            <div class="info-label">Belum Diinput</div>
                            <div class="info-value text-warning" id="ifs-stat-empty">—</div>
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
                            <div class="info-value" id="ifs-stat-period">—</div>
                            <div class="period-range-text" id="ifs-stat-period-range"></div>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary" id="ifs-stat-period-icon">
                            <span class="fas fa-calendar-alt"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status banner -->
    <div id="ifs-status-banner" class="d-none mb-3 no-print"></div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap no-print">
        <div class="d-flex gap-2 align-items-center">
            <span class="badge badge-phoenix badge-phoenix-secondary fs-9 p-2 px-3" id="ifs-context-badge">
                <span class="fas fa-info-circle me-1"></span>Pilih periode &amp; gudang
            </span>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="ifs-btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <button class="btn btn-subtle-secondary btn-sm d-none" id="ifs-btn-reset-grid" type="button">
                <span class="fas fa-undo me-1"></span>Batalkan Perubahan
            </button>
            <button class="btn btn-primary btn-sm d-none" id="ifs-btn-save-grid" type="button">
                <span class="fas fa-save me-1" id="ifs-save-icon"></span>
                <span id="ifs-save-text">Simpan Stok Akhir IFS</span>
            </button>
        </div>
    </div>

    <!-- Print header -->
    <div class="print-header mb-3">
        <h5 class="fw-bold mb-1">Stok Akhir IFS</h5>
        <div class="text-muted small" id="ifs-print-context"></div>
        <div class="text-muted small" id="ifs-print-period-range"></div>
        <div class="text-muted small">Dicetak: <span id="ifs-print-date"></span></div>
        <hr class="my-2">
    </div>

    <!-- Empty state -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y" id="ifs-empty-wrapper">
        <div class="text-center py-5 text-body-tertiary">
            <span class="fas fa-boxes fa-2x mb-2 d-block opacity-50"></span>
            Pilih periode dan gudang terlebih dahulu untuk menampilkan data stok akhir IFS.
        </div>
    </div>

    <!-- Table (editable per gudang) -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y d-none" id="ifs-editable-wrapper">
        <table class="table table-hover fs-9 nowrap align-middle" id="ifs-stock-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bahan Kimia</th>
                    <th>Kategori</th>
                    <th>Stok Akhir IFS (kg)</th>
                    <th class="no-print">Catatan</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Table (combined, semua gudang) -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y d-none" id="ifs-combined-wrapper">
        <table class="table table-hover fs-9 nowrap align-middle" id="ifs-stock-table-combined">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bahan Kimia</th>
                    <th>Kategori</th>
                    <th>Total Stok Akhir IFS (kg)</th>
                    <th>Tersebar di</th>
                    <th class="no-print"></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Filter toggle -->
<a class="card filter-toggle no-print" href="#ifs-filter-offcanvas" data-bs-toggle="offcanvas" id="ifs-filter-toggle">
    <div class="card-body">
        <span class="fas fa-filter text-primary"></span>
        <span class="filter-label">Filter</span>
        <span class="filter-dot"></span>
    </div>
</a>

<!-- Filter Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="ifs-filter-offcanvas" style="width:320px">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title"><span class="fas fa-filter me-2 text-primary"></span>Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="flex-grow-1">
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="ifs-filter-period">
                    Periode <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="ifs-filter-period" style="width:100%">
                    <option value=""></option>
                </select>
                <div class="form-text fs-10" id="ifs-period-status-hint"></div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="ifs-filter-warehouse">
                    Gudang <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="ifs-filter-warehouse" style="width:100%">
                    <option value="__combined__">— Gabungan (Semua Gudang) —</option>
                </select>
            </div>
        </div>
        <div id="ifs-filter-summary" class="mb-3 d-none">
            <div class="alert alert-subtle-info py-2 px-3 mb-0 fs-10">
                <span class="fas fa-info-circle me-1"></span>
                <span id="ifs-filter-summary-text"></span>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary btn-sm" id="ifs-btn-apply-filter">
                <span class="fas fa-search me-1"></span>Terapkan
            </button>
            <button class="btn btn-subtle-secondary btn-sm" id="ifs-btn-reset-filter">
                <span class="fas fa-times me-1"></span>Reset
            </button>
        </div>
    </div>
</div>

<!-- Modal Rincian per Gudang (mode Gabungan) -->
<div class="modal fade" id="ifs-breakdownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="ifs-breakdown-title">Rincian per Gudang</h5>
                    <p class="text-muted fs-10 mb-0" id="ifs-breakdown-subtitle"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div id="ifs-breakdown-body"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    const CAN_CREATE_IFS = <?= json_encode(canDo('warehouse.stock_ifs.create')) ?>;

    const StockIfs = {
        BASE: '<?= base_url() ?>',
        currentPeriodId: null,
        currentPeriodRange: null,
        currentWarehouseId: null,
        currentWarehouseText: null,
        currentPeriodStatus: null,
        periodStatus: null,
        dtEditable: null,
        dtCombined: null,

        init() {
            this.initSelect2();
            this.initEvents();
            document.getElementById('ifs-print-date').textContent = new Date().toLocaleDateString('id-ID', {
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
            $('#ifs-filter-period').select2({
                dropdownParent: $('#ifs-filter-offcanvas'),
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

            $('#ifs-filter-warehouse').select2({
                dropdownParent: $('#ifs-filter-offcanvas'),
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
            document.getElementById('ifs-btn-refresh')?.addEventListener('click', () => this.reload());
            document.getElementById('ifs-btn-apply-filter')?.addEventListener('click', () => this.applyFilter());
            document.getElementById('ifs-btn-reset-filter')?.addEventListener('click', () => this.resetFilter());
            document.getElementById('ifs-btn-save-grid')?.addEventListener('click', () => this.save());
            document.getElementById('ifs-btn-reset-grid')?.addEventListener('click', () => this.reload());
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
            const periodData = $('#ifs-filter-period').select2('data')[0];
            const warehouseId = $('#ifs-filter-warehouse').val();
            const warehouseText = $('#ifs-filter-warehouse option:selected').text() || $('#ifs-filter-warehouse').find(':selected').text();

            if (!periodData?.id || !warehouseId) {
                this.toast('error', 'Periode dan gudang wajib dipilih');
                return;
            }

            document.getElementById('ifs-period-status-hint').classList.remove('text-danger');
            const rangeText = `${this.fmtDateOnly(periodData.start_date)} — ${this.fmtDateOnly(periodData.end_date)}`;
            document.getElementById('ifs-period-status-hint').textContent =
                `${periodData.name} (${periodData.code})${periodData.status === 'Closed' ? ' — Ditutup' : ''} · ${rangeText}`;

            this.currentPeriodId = periodData.id;
            this.currentPeriodRange = rangeText;
            this.currentPeriodStatus = periodData.status;
            this.currentWarehouseId = warehouseId;
            this.currentWarehouseText = warehouseId === '__combined__' ?
                'Gabungan (Semua Gudang)' :
                (this.dtFromSelect2Text(warehouseId) ?? warehouseText);

            this.updateFilterUI(periodData.text, this.currentWarehouseText);
            bootstrap.Offcanvas.getInstance(document.getElementById('ifs-filter-offcanvas'))?.hide();
            this.reload();
        },

        dtFromSelect2Text(id) {
            const data = $('#ifs-filter-warehouse').select2('data');
            const found = (data ?? []).find(x => String(x.id) === String(id));
            return found?.text ?? null;
        },

        resetFilter() {
            $('#ifs-filter-period').val(null).trigger('change');
            $('#ifs-filter-warehouse').val('__combined__').trigger('change');
            document.getElementById('ifs-period-status-hint').textContent = '';
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

            document.getElementById('ifs-filter-toggle').classList.toggle('has-filter', labels.length > 0);
            document.getElementById('ifs-filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('ifs-filter-summary').classList.toggle('d-none', labels.length === 0);

            const badge = document.getElementById('ifs-context-badge');
            if (labels.length) {
                badge.innerHTML = `<span class="fas fa-check-circle me-1"></span>${labels.join(' · ')}`;
            } else {
                badge.innerHTML = `<span class="fas fa-info-circle me-1"></span>Pilih periode &amp; gudang`;
            }
            document.getElementById('ifs-print-context').textContent = labels.join(' · ');
            document.getElementById('ifs-print-period-range').textContent = this.currentPeriodRange ? `Rentang: ${this.currentPeriodRange}` : '';
            document.getElementById('ifs-stat-period-range').textContent = this.currentPeriodRange ?? '';
        },

        async reload() {
            if (!this.currentPeriodId || !this.currentWarehouseId) {
                this.renderEmpty();
                return;
            }
            if (this.currentWarehouseId === '__combined__') {
                await this.loadCombined();
            } else {
                await this.loadGrid();
            }
        },

        async loadGrid() {
            this.showLoading();
            try {
                const d = await this.get(this.BASE +
                    `warehouse/stocks/ifs/grid?period_id=${this.currentPeriodId}&warehouse_id=${this.currentWarehouseId}`);
                if (d.status !== 'success') {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    this.renderEmpty();
                    return;
                }
                this.periodStatus = d.period_status;
                this.renderBanner(d.is_initialized, d.period_status);
                this.showWrapper('editable');
                this.buildEditableTable(d.data);
            } catch (e) {
                this.toast('error', 'Gagal memuat data');
                this.renderEmpty();
            }
        },

        async loadCombined() {
            this.showLoading();
            try {
                const d = await this.get(this.BASE + `warehouse/stocks/ifs/combined?period_id=${this.currentPeriodId}`);
                if (d.status !== 'success') {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    this.renderEmpty();
                    return;
                }
                this.renderBanner(null, null, true);
                this.showWrapper('combined');
                this.buildCombinedTable(d.data);
            } catch (e) {
                this.toast('error', 'Gagal memuat data');
                this.renderEmpty();
            }
        },

        showWrapper(mode) {
            document.getElementById('ifs-empty-wrapper').classList.add('d-none');
            document.getElementById('ifs-editable-wrapper').classList.toggle('d-none', mode !== 'editable');
            document.getElementById('ifs-combined-wrapper').classList.toggle('d-none', mode !== 'combined');
        },

        showLoading() {
            document.getElementById('ifs-empty-wrapper').classList.remove('d-none');
            document.getElementById('ifs-empty-wrapper').innerHTML = `
                <div class="text-center py-5 text-body-tertiary">
                    <span class="spinner-border spinner-border-sm text-primary me-2"></span>Memuat data...
                </div>`;
            document.getElementById('ifs-editable-wrapper').classList.add('d-none');
            document.getElementById('ifs-combined-wrapper').classList.add('d-none');
        },

        renderEmpty() {
            document.getElementById('ifs-status-banner').classList.add('d-none');
            document.getElementById('ifs-btn-save-grid').classList.add('d-none');
            document.getElementById('ifs-btn-reset-grid').classList.add('d-none');
            document.getElementById('ifs-editable-wrapper').classList.add('d-none');
            document.getElementById('ifs-combined-wrapper').classList.add('d-none');
            const empty = document.getElementById('ifs-empty-wrapper');
            empty.classList.remove('d-none');
            empty.innerHTML = `
                <div class="text-center py-5 text-body-tertiary">
                    <span class="fas fa-boxes fa-2x mb-2 d-block opacity-50"></span>
                    Pilih periode dan gudang terlebih dahulu untuk menampilkan data stok akhir IFS.
                </div>`;
            this.setStats(null);
            this.setPeriodStatCard(null);
        },

        /**
         * Warna stat card "Status Periode": hijau=Open, abu=Close. Hanya 2 state.
         */
        setPeriodStatCard(status) {
            const valueEl = document.getElementById('ifs-stat-period');
            const iconEl = document.getElementById('ifs-stat-period-icon');

            if (!valueEl || !iconEl) {
                console.warn('setPeriodStatCard: elemen ifs-stat-period / ifs-stat-period-icon tidak ditemukan di DOM');
                return;
            }

            valueEl.className = 'info-value';
            iconEl.className = 'stat-icon';

            const states = {
                Open: {
                    text: 'Open',
                    color: 'success',
                    icon: 'fa-lock-open'
                },
                Closed: {
                    text: 'Close',
                    color: 'secondary',
                    icon: 'fa-lock'
                },
            };

            // Default ke Close kalau status belum diketahui/belum ada periode dipilih.
            const s = states[status] ?? states.Closed;

            valueEl.textContent = s.text;
            valueEl.classList.add(`text-${s.color}`);
            iconEl.classList.add(`bg-${s.color}`, 'bg-opacity-10', `text-${s.color}`);

            // Rebuild isi icon dari nol setiap kali dipanggil, supaya selalu
            // ada tepat satu <span> dan tidak pernah menumpuk.
            iconEl.innerHTML = `<span class="fas ${s.icon}"></span>`;
        },

        renderBanner(isInitialized, periodStatus, isCombined = false) {
            const banner = document.getElementById('ifs-status-banner');
            banner.classList.remove('d-none');

            this.setPeriodStatCard(isCombined ? this.currentPeriodStatus : periodStatus);

            if (isCombined) {
                banner.innerHTML = `
                    <div class="alert alert-subtle-info py-2 px-3 fs-9 mb-0">
                        <span class="fas fa-layer-group me-1"></span>
                        Mode gabungan — total stok akhir IFS dari seluruh gudang untuk periode ini. Klik baris untuk lihat rincian per gudang. Mode ini hanya untuk melihat, bukan untuk mengedit.
                    </div>`;
                document.getElementById('ifs-btn-save-grid').classList.add('d-none');
                document.getElementById('ifs-btn-reset-grid').classList.add('d-none');
                return;
            }

            if (periodStatus === 'Closed') {
                banner.innerHTML = `
                    <div class="alert alert-subtle-secondary py-2 px-3 fs-9 mb-0">
                        <span class="fas fa-lock me-1"></span>
                        Periode ini sudah <strong>ditutup</strong>. Stok akhir IFS tidak bisa diubah lagi.
                    </div>`;
                document.getElementById('ifs-btn-save-grid').classList.add('d-none');
                document.getElementById('ifs-btn-reset-grid').classList.add('d-none');
                return;
            }

            if (!isInitialized) {
                banner.innerHTML = `
                    <div class="alert alert-subtle-warning py-2 px-3 fs-9 mb-0">
                        <span class="fas fa-exclamation-triangle me-1"></span>
                        <strong>Stok akhir IFS belum diinput</strong> untuk kombinasi periode &amp; gudang ini.
                    </div>`;
            } else {
                banner.innerHTML = `
                    <div class="alert alert-subtle-success py-2 px-3 fs-9 mb-0">
                        <span class="fas fa-check-circle me-1"></span>
                        Stok akhir IFS sudah diinput untuk kombinasi ini. Kamu masih bisa mengubahnya selama periode masih <strong>Open</strong>.
                    </div>`;
            }
            if (CAN_CREATE_IFS) {
                document.getElementById('ifs-btn-save-grid').classList.remove('d-none');
                document.getElementById('ifs-btn-reset-grid').classList.remove('d-none');
            }
        },

        setStats(rows) {
            if (!rows) {
                document.getElementById('ifs-stat-total').textContent = '—';
                document.getElementById('ifs-stat-filled').textContent = '—';
                document.getElementById('ifs-stat-empty').textContent = '—';
                return;
            }
            const total = rows.length;
            const filled = rows.filter(r => Number(r.ifs_qty) > 0).length;
            document.getElementById('ifs-stat-total').textContent = total;
            document.getElementById('ifs-stat-filled').textContent = filled;
            document.getElementById('ifs-stat-empty').textContent = total - filled;
        },

        // ============================================================
        // EDITABLE TABLE (per gudang)
        // ============================================================
        buildEditableTable(rows) {
            this.setStats(rows);

            if ($.fn.DataTable.isDataTable('#ifs-stock-table')) {
                this.dtEditable.destroy();
                $('#ifs-stock-table tbody').remove();
            }

            const readOnly = this.periodStatus === 'Closed' || !CAN_CREATE_IFS;
            const self = this;

            this.dtEditable = $('#ifs-stock-table').DataTable({
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
                    title: 'Stok Akhir IFS',
                    exportOptions: {
                        columns: [0, 1, 2, 3],
                        format: {
                            body: (data) => data.replace(/<[^>]*>/g, '').trim()
                        }
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
                        targets: 3,
                        width: '150px'
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
                        render: d => d ?
                            d.split(', ').map(c => `<span class="badge badge-phoenix badge-phoenix-secondary p-2 fs-10 me-1 mb-1">${self.e(c)}</span>`).join('') :
                            '<span class="text-muted fst-italic">—</span>',
                    },
                    {
                        data: 'ifs_qty',
                        render: (data, type) => {
                            if (type !== 'display') return data ?? 0;
                            return `<input type="number" step="0.001" min="0" class="form-control form-control-sm qty-input"
                                        value="${data ?? ''}" placeholder="0" ${readOnly ? 'disabled' : ''}>`;
                        },
                    },
                    {
                        data: 'notes',
                        className: 'no-print',
                        render: (data, type) => {
                            if (type !== 'display') return data ?? '';
                            return `<input type="text" class="form-control form-control-sm notes-input"
                                        value="${self.e(data ?? '')}" placeholder="opsional" ${readOnly ? 'disabled' : ''}>`;
                        },
                    },
                ],
                createdRow: (tr, rowData) => {
                    if (rowData._touched) tr.classList.add('row-touched');
                },
            });

            if (!readOnly) {
                $('#ifs-stock-table tbody').off('input').on('input', '.qty-input, .notes-input', function() {
                    const tr = $(this).closest('tr');
                    const rowApi = self.dtEditable.row(tr);
                    const rowData = rowApi.data();
                    if (!rowData) return;

                    if (this.classList.contains('qty-input')) rowData.ifs_qty = this.value;
                    if (this.classList.contains('notes-input')) rowData.notes = this.value;
                    rowData._touched = true;
                    tr.addClass('row-touched');
                });
            }
        },

        // ============================================================
        // COMBINED TABLE (gabungan semua gudang, read-only)
        // ============================================================
        buildCombinedTable(rows) {
            this.setStats(rows);

            if ($.fn.DataTable.isDataTable('#ifs-stock-table-combined')) {
                this.dtCombined.destroy();
                $('#ifs-stock-table-combined tbody').remove();
            }

            const self = this;

            this.dtCombined = $('#ifs-stock-table-combined').DataTable({
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
                    title: 'Stok Akhir IFS (Gabungan)',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
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
                        targets: 3,
                        width: '160px'
                    },
                    {
                        targets: 4,
                        width: '110px'
                    },
                    {
                        targets: 5,
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
                        render: d => d ?
                            d.split(', ').map(c => `<span class="badge badge-phoenix badge-phoenix-secondary p-2 fs-10 me-1 mb-1">${self.e(c)}</span>`).join('') :
                            '<span class="text-muted fst-italic">—</span>',
                    },
                    {
                        data: 'ifs_qty',
                        render: {
                            display: (data) => `<span class="fw-semibold">${self.fmtNumber(data)}</span> <span class="text-muted">kg</span>`,
                            sort: data => Number(data ?? 0),
                            type: data => Number(data ?? 0),
                            _: data => data,
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

            $('#ifs-stock-table-combined tbody').off('click').on('click', 'tr', function() {
                const rowData = self.dtCombined.row(this).data();
                if (rowData) self.openBreakdown(rowData.chemical_id, rowData.chemical_name);
            });
        },

        // ============================================================
        // BREAKDOWN MODAL
        // ============================================================
        async openBreakdown(chemicalId, chemicalName) {
            document.getElementById('ifs-breakdown-title').textContent = chemicalName;
            document.getElementById('ifs-breakdown-subtitle').textContent = 'Rincian stok akhir IFS per gudang';
            document.getElementById('ifs-breakdown-body').innerHTML = `<div class="text-center py-3"><span class="spinner-border spinner-border-sm text-primary"></span></div>`;
            new bootstrap.Modal(document.getElementById('ifs-breakdownModal')).show();

            try {
                const d = await this.get(this.BASE +
                    `warehouse/stocks/ifs/breakdown?period_id=${this.currentPeriodId}&chemical_id=${chemicalId}`);
                if (d.status !== 'success' || !d.data.length) {
                    document.getElementById('ifs-breakdown-body').innerHTML = `<p class="text-muted text-center mb-0">Belum ada data di gudang manapun.</p>`;
                    return;
                }
                const rows = d.data.map(r => `
                    <tr>
                        <td>${this.e(r.warehouse_name)} <span class="text-muted small">(${this.e(r.warehouse_code)})</span></td>
                        <td class="text-end fw-semibold">${this.fmtNumber(r.ifs_qty)} kg</td>
                    </tr>
                `).join('');
                document.getElementById('ifs-breakdown-body').innerHTML = `
                    <table class="table table-sm fs-9 mb-0">
                        <thead><tr><th>Gudang</th><th class="text-end">Stok Akhir IFS</th></tr></thead>
                        <tbody>${rows}</tbody>
                    </table>`;
            } catch {
                document.getElementById('ifs-breakdown-body').innerHTML = `<p class="text-danger text-center mb-0">Gagal memuat rincian.</p>`;
            }
        },

        // ============================================================
        // SAVE
        // ============================================================
        async save() {
            if (!this.dtEditable) {
                this.toast('error', 'Tidak ada data untuk disimpan');
                return;
            }

            const rows = this.dtEditable.rows().data().toArray().map(r => ({
                chemical_id: r.chemical_id,
                ifs_qty: r.ifs_qty || 0,
                notes: r.notes || '',
            }));

            if (!rows.length) {
                this.toast('error', 'Tidak ada data untuk disimpan');
                return;
            }

            this.setLoading(true);
            try {
                const fd = new FormData();
                fd.set('period_id', this.currentPeriodId);
                fd.set('warehouse_id', this.currentWarehouseId);
                fd.set('rows', JSON.stringify(rows));

                const res = await this.post(this.BASE + 'warehouse/stocks/ifs/store', fd);
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    this.reload();
                } else {
                    this.toast('error', res.message ?? 'Gagal menyimpan data');
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                this.setLoading(false);
            }
        },

        setLoading(on) {
            const btn = document.getElementById('ifs-btn-save-grid');
            const ico = document.getElementById('ifs-save-icon');
            if (!btn) return;
            btn.disabled = on;
            ico.className = on ? 'spinner-border spinner-border-sm me-1' : 'fas fa-save me-1';
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

    $(document).ready(() => StockIfs.init());
</script>
<?= $this->endSection() ?>