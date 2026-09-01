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

    #so-stock-table_wrapper,
    #so-stock-table-combined_wrapper {
        max-width: 100%;
    }

    #so-stock-table_wrapper .top,
    #so-stock-table-combined_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #so-stock-table_wrapper .top input,
    #so-stock-table-combined_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #so-stock-table_wrapper .bottom,
    #so-stock-table-combined_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #so-stock-table_wrapper .bottom .dataTables_length,
    #so-stock-table-combined_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #so-stock-table_wrapper .bottom .dataTables_paginate,
    #so-stock-table-combined_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #so-stock-table_wrapper .bottom .dataTables_info,
    #so-stock-table-combined_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #so-stock-table_wrapper .dataTables_filter label,
    #so-stock-table_wrapper .dataTables_length label,
    #so-stock-table-combined_wrapper .dataTables_filter label,
    #so-stock-table-combined_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #so-stock-table_wrapper .dataTables_length select,
    #so-stock-table-combined_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #so-stock-table_wrapper .dataTables_paginate .paginate_button,
    #so-stock-table-combined_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #so-stock-table_wrapper .dataTables_paginate .paginate_button.current,
    #so-stock-table-combined_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #so-stock-table,
    #so-stock-table-combined {
        width: 100% !important;
    }

    #so-stock-table input.qty-input {
        text-align: right;
        max-width: 140px;
    }

    #so-stock-table tr.row-touched {
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
                            <div class="info-value text-primary" id="so-stat-total">—</div>
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
                            <div class="info-value text-success" id="so-stat-filled">—</div>
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
                            <div class="info-value text-warning" id="so-stat-empty">—</div>
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
                            <div class="info-value" id="so-stat-period">—</div>
                            <div class="period-range-text" id="so-stat-period-range"></div>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary" id="so-stat-period-icon">
                            <span class="fas fa-calendar-alt"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status banner -->
    <div id="so-status-banner" class="d-none mb-3 no-print"></div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap no-print">
        <div class="d-flex gap-2 align-items-center">
            <span class="badge badge-phoenix badge-phoenix-secondary fs-9 p-2 px-3" id="so-context-badge">
                <span class="fas fa-info-circle me-1"></span>Pilih periode &amp; gudang
            </span>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-subtle-success btn-sm" id="so-btn-export-excel" type="button">
                <span class="fas fa-file-excel me-1"></span>Export Excel
            </button>
            <button class="btn btn-subtle-primary btn-sm" id="so-btn-print" type="button">
                <span class="fas fa-print me-1"></span>Cetak
            </button>
            <button class="btn btn-subtle-secondary btn-sm" id="so-btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <button class="btn btn-subtle-info btn-sm d-none" id="so-btn-pull-previous" type="button">
                <span class="fas fa-cloud-download-alt me-1"></span>Tarik dari Periode Sebelumnya
            </button>
            <button class="btn btn-subtle-secondary btn-sm d-none" id="so-btn-reset-grid" type="button">
                <span class="fas fa-undo me-1"></span>Batalkan Perubahan
            </button>
            <button class="btn btn-primary btn-sm d-none" id="so-btn-save-grid" type="button">
                <span class="fas fa-save me-1" id="so-save-icon"></span>
                <span id="so-save-text">Simpan Stok Awal</span>
            </button>
        </div>
    </div>

    <!-- Empty state -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y" id="so-empty-wrapper">
        <div class="text-center py-5 text-body-tertiary">
            <span class="fas fa-boxes fa-2x mb-2 d-block opacity-50"></span>
            Pilih periode dan gudang terlebih dahulu untuk menampilkan data stok awal.
        </div>
    </div>

    <!-- Table (editable per gudang) -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y d-none" id="so-editable-wrapper">
        <table class="table table-hover fs-9 nowrap align-middle" id="so-stock-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bahan Kimia</th>
                    <th>Kategori</th>
                    <th>Stok Awal (kg)</th>
                    <th class="no-print">Catatan</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Table (combined, semua gudang) -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y d-none" id="so-combined-wrapper">
        <table class="table table-hover fs-9 nowrap align-middle" id="so-stock-table-combined">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bahan Kimia</th>
                    <th>Kategori</th>
                    <th>Total Stok Awal (kg)</th>
                    <th>Tersebar di</th>
                    <th class="no-print"></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Filter toggle -->
<a class="card filter-toggle no-print" href="#so-filter-offcanvas" data-bs-toggle="offcanvas" id="so-filter-toggle">
    <div class="card-body">
        <span class="fas fa-filter text-primary"></span>
        <span class="filter-label">Filter</span>
        <span class="filter-dot"></span>
    </div>
</a>

<!-- Filter Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="so-filter-offcanvas" style="width:320px">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title"><span class="fas fa-filter me-2 text-primary"></span>Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="flex-grow-1">
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="so-filter-period">
                    Periode <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="so-filter-period" style="width:100%">
                    <option value=""></option>
                </select>
                <div class="form-text fs-10" id="so-period-status-hint"></div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="so-filter-warehouse">
                    Gudang <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="so-filter-warehouse" style="width:100%">
                    <option value="__combined__">— Gabungan (Semua Gudang) —</option>
                </select>
                <div class="form-text fs-10" id="so-warehouse-scope-hint"></div>
            </div>
        </div>
        <div id="so-filter-summary" class="mb-3 d-none">
            <div class="alert alert-subtle-info py-2 px-3 mb-0 fs-10">
                <span class="fas fa-info-circle me-1"></span>
                <span id="so-filter-summary-text"></span>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary btn-sm" id="so-btn-apply-filter">
                <span class="fas fa-search me-1"></span>Terapkan
            </button>
            <button class="btn btn-subtle-secondary btn-sm" id="so-btn-reset-filter">
                <span class="fas fa-times me-1"></span>Reset
            </button>
        </div>
    </div>
</div>

<!-- Modal Rincian per Gudang (mode Gabungan) -->
<div class="modal fade" id="so-breakdownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="so-breakdown-title">Rincian per Gudang</h5>
                    <p class="text-muted fs-10 mb-0" id="so-breakdown-subtitle"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div id="so-breakdown-body"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    const CAN_CREATE_OPENING = <?= json_encode(canDo('warehouse.stock_opening.create')) ?>;

    const StockOpening = {
        BASE: '<?= base_url() ?>',
        printedBy: '<?= esc($printedByName) ?>',
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
            this.autoSelectCurrentPeriod();
            this.autoSelectUserWarehouse();
        },

        /**
         * Warehouse Operator dibatasi hanya ke gudang departemennya sendiri.
         * Penjelasan lengkap pola ini ada di stocks/receipt.php.
         */
        async autoSelectUserWarehouse() {
            const scope = window.APP_WAREHOUSE_SCOPE;
            const hint = document.getElementById('so-warehouse-scope-hint');
            if (!scope || !scope.restricted) return;

            if (!scope.warehouseIds.length) {
                if (hint) {
                    hint.innerHTML = `<span class="text-danger"><span class="fas fa-triangle-exclamation me-1"></span>` +
                        `Akun Anda (departemen: ${scope.departmentName ?? '—'}) belum terhubung ke gudang manapun. Hubungi admin.</span>`;
                }
                $('#so-filter-warehouse').prop('disabled', true);
                return;
            }

            try {
                const res = await this.get(this.BASE + 'warehouse/master/warehouses/select2');
                const list = res.data ?? [];
                if (!list.length) return;

                const w = list[0];
                const warehouseOptionData = {
                    id: w.id,
                    text: `${w.name} (${w.code})`,
                    name: w.name,
                    code: w.code
                };
                const opt = new Option(warehouseOptionData.text, warehouseOptionData.id, true, true);
                $(opt).data('data', warehouseOptionData);
                $('#so-filter-warehouse').append(opt).trigger('change');

                if (list.length === 1) {
                    $('#so-filter-warehouse').prop('disabled', true);
                    if (hint) hint.textContent = `Gudang departemen Anda (${scope.departmentName ?? '—'})`;
                } else if (hint) {
                    hint.textContent = `${list.length} gudang tersedia untuk departemen Anda (${scope.departmentName ?? '—'})`;
                }
            } catch (e) {
                // Kalau gagal, biarkan user pilih manual dari opsi yang sudah ter-scope backend.
            }
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
                $('#so-filter-period').append(opt).trigger('change');
            } catch (e) {
                // Kalau gagal, biarkan user pilih periode manual seperti biasa.
            }
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
            $('#so-filter-period').select2({
                dropdownParent: $('#so-filter-offcanvas'),
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

            $('#so-filter-warehouse').select2({
                dropdownParent: $('#so-filter-offcanvas'),
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
                        results: [
                            // "Gabungan (Semua Gudang)" cuma masuk akal kalau user tidak dibatasi
                            // ke gudang tertentu (lihat combinedGrid() di controller — diblok utk operator).
                            ...(window.APP_WAREHOUSE_SCOPE?.restricted ? [] : [{
                                id: '__combined__',
                                text: '— Gabungan (Semua Gudang) —'
                            }]),
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
            document.getElementById('so-btn-refresh')?.addEventListener('click', () => this.reload());
            document.getElementById('so-btn-export-excel')?.addEventListener('click', () => this.exportExcel());
            document.getElementById('so-btn-print')?.addEventListener('click', () => this.printStockAwal());
            document.getElementById('so-btn-apply-filter')?.addEventListener('click', () => this.applyFilter());
            document.getElementById('so-btn-reset-filter')?.addEventListener('click', () => this.resetFilter());
            document.getElementById('so-btn-save-grid')?.addEventListener('click', () => this.save());
            document.getElementById('so-btn-reset-grid')?.addEventListener('click', () => this.reload());
            document.getElementById('so-btn-pull-previous')?.addEventListener('click', () => this.pullPrevious());
        },

        // ============================================================
        // EXPORT EXCEL — trigger tombol excelHtml5 yang sudah terpasang di tabel aktif
        // ============================================================
        excelLetterhead(isCombined) {
            const periodText = $('#so-filter-period').select2('data')[0]?.text ?? '-';
            const now = new Date().toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            return `PT. SINAR CONTINENTAL - DIVISI 3A | Gudang: ${isCombined ? 'Gabungan (Semua Gudang)' : (this.currentWarehouseText ?? '-')} | Periode: ${periodText}${this.currentPeriodStatus === 'Closed' ? ' (Close)' : ' (Open)'} | Dicetak: ${now} oleh ${this.printedBy}`;
        },

        exportExcel() {
            if (!this.currentPeriodId || !this.currentWarehouseId) {
                this.toast('error', 'Pilih periode dan gudang terlebih dahulu');
                return;
            }
            const isCombined = this.currentWarehouseId === '__combined__';
            const dt = isCombined ? this.dtCombined : this.dtEditable;
            if (!dt) {
                this.toast('error', 'Belum ada data untuk diexport');
                return;
            }
            dt.button(0).trigger();
        },

        // ============================================================
        // CETAK — popup letterhead, konsisten dengan halaman Penerimaan/Posisi Stok
        // ============================================================
        printStockAwal() {
            if (!this.currentPeriodId || !this.currentWarehouseId) {
                this.toast('error', 'Pilih periode dan gudang terlebih dahulu');
                return;
            }
            const isCombined = this.currentWarehouseId === '__combined__';
            const dt = isCombined ? this.dtCombined : this.dtEditable;
            if (!dt) {
                this.toast('error', 'Belum ada data untuk dicetak');
                return;
            }
            const rows = dt.rows({
                search: 'applied'
            }).data().toArray();
            const periodText = $('#so-filter-period').select2('data')[0]?.text ?? '-';
            const now = new Date().toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            const logoPath = this.BASE + 'assets/img/app/logo-regency-footer.png';
            const filled = rows.filter(r => Number(r.quantity) > 0).length;

            const rowsHtml = isCombined ? rows.map((r, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${this.e(r.chemical_name)}<div class="mono">${this.e(r.chemical_code)}</div></td>
                    <td>${this.e(r.category_name ?? '-')}</td>
                    <td class="num bold">${this.fmtNumber(r.quantity)}</td>
                    <td>${r.warehouse_count} gudang</td>
                </tr>
            `).join('') : rows.map((r, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${this.e(r.chemical_name)}<div class="mono">${this.e(r.chemical_code)}</div></td>
                    <td>${this.e(r.category_name ?? '-')}</td>
                    <td class="num bold">${this.fmtNumber(r.quantity)}</td>
                    <td>${r.notes ? this.e(r.notes) : '-'}</td>
                </tr>
            `).join('');

            const theadHtml = isCombined ?
                `<tr><th>#</th><th>Bahan Kimia</th><th>Kategori</th><th class="num">Total Stok Awal</th><th>Tersebar</th></tr>` :
                `<tr><th>#</th><th>Bahan Kimia</th><th>Kategori</th><th class="num">Stok Awal (kg)</th><th>Catatan</th></tr>`;

            const printWindow = window.open('', '_blank', 'width=1200,height=900');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Stok Awal Bahan Kimia</title>
                    <style>
                        @page { margin: 3mm; }
                        * { box-sizing: border-box; }
                        body { font-family: Arial, Helvetica, sans-serif; font-size: 8pt; color: #111; margin: 0; padding: 4mm; }
                        .letterhead { display: flex; align-items: center; gap: 12px; border-bottom: 2px solid #111; padding-bottom: 8px; margin-bottom: 10px; }
                        .letterhead img { height: 48px; }
                        .letterhead .company { font-size: 15pt; font-weight: bold; letter-spacing: .3px; }
                        .letterhead .division { font-size: 10pt; color: #444; }
                        .doc-title { text-align: center; font-size: 11pt; font-weight: bold; margin: 6px 0 10px; text-transform: uppercase; }
                        .info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px 16px; font-size: 8pt; margin-bottom: 10px; border: 1px solid #ccc; padding: 8px 10px; background: #f8f8f8; }
                        .info-grid div span.label { color: #666; display: block; font-size: 7pt; text-transform: uppercase; letter-spacing: .3px; }
                        .info-grid div span.value { font-weight: 600; }
                        table { width: 100%; border-collapse: collapse; font-size: 7pt; }
                        th, td { border: 1px solid #999; padding: 2px 4px; text-align: left; }
                        th { background: #eee; font-weight: 700; text-align: center; }
                        td.num, th.num { text-align: right; }
                        td.bold { font-weight: 700; }
                        .mono { font-family: 'Courier New', monospace; font-size: 6.5pt; color: #555; }
                        .footer-note { margin-top: 14px; font-size: 7pt; color: #777; display: flex; justify-content: space-between; border-top: 1px solid #ccc; padding-top: 6px; }
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
                    <div class="doc-title">Stok Awal Bahan Kimia${isCombined ? ' (Gabungan Semua Gudang)' : ''}</div>
                    <div class="info-grid">
                        <div><span class="label">Gudang</span><span class="value">${isCombined ? 'Gabungan (Semua Gudang)' : this.e(this.currentWarehouseText ?? '-')}</span></div>
                        <div><span class="label">Periode</span><span class="value">${this.e(periodText)}${this.currentPeriodStatus === 'Closed' ? ' (Close)' : ' (Open)'}</span></div>
                        <div><span class="label">Rentang Periode</span><span class="value">${this.e(this.currentPeriodRange ?? '-')}</span></div>
                        <div><span class="label">Tanggal Cetak</span><span class="value">${now}</span></div>
                        <div><span class="label">Total Item</span><span class="value">${rows.length} bahan kimia</span></div>
                        <div><span class="label">Sudah Diinput</span><span class="value">${filled} item</span></div>
                        <div><span class="label">Dicetak Oleh</span><span class="value">${this.e(this.printedBy)}</span></div>
                    </div>
                    <table>
                        <thead>${theadHtml}</thead>
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

        async pullPrevious() {
            if (!this.currentPeriodId || !this.currentWarehouseId || this.currentWarehouseId === '__combined__') return;

            const confirm = await Swal.fire({
                title: 'Tarik dari Periode Sebelumnya?',
                text: 'Kolom Nilai akan diisi otomatis dari saldo akhir periode sebelumnya. Data yang sudah kamu ketik di sini akan tertimpa. Kamu tetap harus klik Simpan setelah ini.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tarik',
                cancelButtonText: 'Batal',
            });
            if (!confirm.isConfirmed) return;

            try {
                const d = await this.get(this.BASE +
                    `warehouse/stocks/opening/pull-previous?period_id=${this.currentPeriodId}&warehouse_id=${this.currentWarehouseId}`);
                if (d.status !== 'success') {
                    this.toast('error', d.message ?? 'Gagal menarik data periode sebelumnya');
                    return;
                }

                let count = 0;
                Object.entries(d.data).forEach(([chemicalId, qty]) => {
                    const rowApi = this.dtEditable.row('#' + chemicalId);
                    if (!rowApi.length) return;
                    const rowData = rowApi.data();
                    rowData.quantity = qty;
                    rowData._touched = true;
                    rowApi.invalidate();
                    count++;
                });

                $('#so-stock-table tbody tr').addClass('row-touched');
                document.getElementById('so-btn-save-grid').classList.remove('d-none');
                document.getElementById('so-btn-reset-grid').classList.remove('d-none');

                this.toast('success', `${count} item ditarik dari periode "${d.period.name}". Jangan lupa klik Simpan.`);
            } catch (e) {
                this.toast('error', 'Gagal menarik data periode sebelumnya');
            }
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
            const periodData = $('#so-filter-period').select2('data')[0];
            const warehouseId = $('#so-filter-warehouse').val();
            const warehouseText = $('#so-filter-warehouse option:selected').text() || $('#so-filter-warehouse').find(':selected').text();

            if (!periodData?.id || !warehouseId) {
                this.toast('error', 'Periode dan gudang wajib dipilih');
                return;
            }

            document.getElementById('so-period-status-hint').classList.remove('text-danger');
            const rangeText = `${this.fmtDateOnly(periodData.start_date)} — ${this.fmtDateOnly(periodData.end_date)}`;
            document.getElementById('so-period-status-hint').textContent =
                `${periodData.name} (${periodData.code})${periodData.status === 'Closed' ? ' — Ditutup' : ''} · ${rangeText}`;

            this.currentPeriodId = periodData.id;
            this.currentPeriodRange = rangeText;
            this.currentPeriodStatus = periodData.status;
            this.currentWarehouseId = warehouseId;
            this.currentWarehouseText = warehouseId === '__combined__' ?
                'Gabungan (Semua Gudang)' :
                (this.dtFromSelect2Text(warehouseId) ?? warehouseText);

            this.updateFilterUI(periodData.text, this.currentWarehouseText);
            bootstrap.Offcanvas.getInstance(document.getElementById('so-filter-offcanvas'))?.hide();
            this.reload();
        },

        dtFromSelect2Text(id) {
            const data = $('#so-filter-warehouse').select2('data');
            const found = (data ?? []).find(x => String(x.id) === String(id));
            return found?.text ?? null;
        },

        resetFilter() {
            $('#so-filter-period').val(null).trigger('change');
            // Operator tidak boleh direset ke "Gabungan" (opsi itu memang tidak
            // ditawarkan untuknya) — arahkan ulang ke gudang departemennya sendiri.
            if (window.APP_WAREHOUSE_SCOPE?.restricted) {
                $('#so-filter-warehouse').val(null).trigger('change');
                this.autoSelectUserWarehouse();
            } else {
                $('#so-filter-warehouse').val('__combined__').trigger('change');
            }
            document.getElementById('so-period-status-hint').textContent = '';
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

            document.getElementById('so-filter-toggle').classList.toggle('has-filter', labels.length > 0);
            document.getElementById('so-filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('so-filter-summary').classList.toggle('d-none', labels.length === 0);

            const badge = document.getElementById('so-context-badge');
            if (labels.length) {
                badge.innerHTML = `<span class="fas fa-check-circle me-1"></span>${labels.join(' · ')}`;
            } else {
                badge.innerHTML = `<span class="fas fa-info-circle me-1"></span>Pilih periode &amp; gudang`;
            }
            document.getElementById('so-stat-period-range').textContent = this.currentPeriodRange ?? '';
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
                    `warehouse/stocks/opening/grid?period_id=${this.currentPeriodId}&warehouse_id=${this.currentWarehouseId}`);
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
                const d = await this.get(this.BASE + `warehouse/stocks/opening/combined?period_id=${this.currentPeriodId}`);
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
            document.getElementById('so-empty-wrapper').classList.add('d-none');
            document.getElementById('so-editable-wrapper').classList.toggle('d-none', mode !== 'editable');
            document.getElementById('so-combined-wrapper').classList.toggle('d-none', mode !== 'combined');
        },

        showLoading() {
            document.getElementById('so-empty-wrapper').classList.remove('d-none');
            document.getElementById('so-empty-wrapper').innerHTML = `
                <div class="text-center py-5 text-body-tertiary">
                    <span class="spinner-border spinner-border-sm text-primary me-2"></span>Memuat data...
                </div>`;
            document.getElementById('so-editable-wrapper').classList.add('d-none');
            document.getElementById('so-combined-wrapper').classList.add('d-none');
        },

        renderEmpty() {
            document.getElementById('so-status-banner').classList.add('d-none');
            document.getElementById('so-btn-save-grid').classList.add('d-none');
            document.getElementById('so-btn-reset-grid').classList.add('d-none');
            document.getElementById('so-btn-pull-previous').classList.add('d-none');
            document.getElementById('so-editable-wrapper').classList.add('d-none');
            document.getElementById('so-combined-wrapper').classList.add('d-none');
            const empty = document.getElementById('so-empty-wrapper');
            empty.classList.remove('d-none');
            empty.innerHTML = `
                <div class="text-center py-5 text-body-tertiary">
                    <span class="fas fa-boxes fa-2x mb-2 d-block opacity-50"></span>
                    Pilih periode dan gudang terlebih dahulu untuk menampilkan data stok awal.
                </div>`;
            this.setStats(null);
            this.setPeriodStatCard(null);
        },

        /**
         * Warna stat card "Status Periode": hijau=Open, abu=Close. Hanya 2 state.
         */
        setPeriodStatCard(status) {
            const valueEl = document.getElementById('so-stat-period');
            const iconEl = document.getElementById('so-stat-period-icon');

            if (!valueEl || !iconEl) {
                console.warn('setPeriodStatCard: elemen so-stat-period / so-stat-period-icon tidak ditemukan di DOM');
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
            const banner = document.getElementById('so-status-banner');
            banner.classList.remove('d-none');

            this.setPeriodStatCard(isCombined ? this.currentPeriodStatus : periodStatus);

            if (isCombined) {
                banner.innerHTML = `
                    <div class="alert alert-subtle-info py-2 px-3 fs-9 mb-0">
                        <span class="fas fa-layer-group me-1"></span>
                        Mode gabungan — total stok awal dari seluruh gudang untuk periode ini. Klik baris untuk lihat rincian per gudang. Mode ini hanya untuk melihat, bukan untuk mengedit.
                    </div>`;
                document.getElementById('so-btn-save-grid').classList.add('d-none');
                document.getElementById('so-btn-reset-grid').classList.add('d-none');
                document.getElementById('so-btn-pull-previous').classList.add('d-none');
                return;
            }

            if (periodStatus === 'Closed') {
                banner.innerHTML = `
                    <div class="alert alert-subtle-secondary py-2 px-3 fs-9 mb-0">
                        <span class="fas fa-lock me-1"></span>
                        Periode ini sudah <strong>ditutup</strong>. Stok awal tidak bisa diubah lagi.
                    </div>`;
                document.getElementById('so-btn-save-grid').classList.add('d-none');
                document.getElementById('so-btn-reset-grid').classList.add('d-none');
                document.getElementById('so-btn-pull-previous').classList.add('d-none');
                return;
            }

            if (!isInitialized) {
                banner.innerHTML = `
                    <div class="alert alert-subtle-warning py-2 px-3 fs-9 mb-0">
                        <span class="fas fa-exclamation-triangle me-1"></span>
                        <strong>Stok awal belum diinput</strong> untuk kombinasi periode &amp; gudang ini. Penerimaan, pengeluaran, dan alokasi stok tidak bisa dilakukan sebelum stok awal disimpan.
                    </div>`;
            } else {
                banner.innerHTML = `
                    <div class="alert alert-subtle-success py-2 px-3 fs-9 mb-0">
                        <span class="fas fa-check-circle me-1"></span>
                        Stok awal sudah diinput untuk kombinasi ini. Kamu masih bisa mengubahnya selama periode masih <strong>Open</strong>.
                    </div>`;
            }
            if (CAN_CREATE_OPENING) {
                document.getElementById('so-btn-save-grid').classList.remove('d-none');
                document.getElementById('so-btn-reset-grid').classList.remove('d-none');
                document.getElementById('so-btn-pull-previous').classList.remove('d-none');
            }
        },

        setStats(rows) {
            if (!rows) {
                document.getElementById('so-stat-total').textContent = '—';
                document.getElementById('so-stat-filled').textContent = '—';
                document.getElementById('so-stat-empty').textContent = '—';
                return;
            }
            const total = rows.length;
            const filled = rows.filter(r => Number(r.quantity) > 0).length;
            document.getElementById('so-stat-total').textContent = total;
            document.getElementById('so-stat-filled').textContent = filled;
            document.getElementById('so-stat-empty').textContent = total - filled;
        },

        // ============================================================
        // EDITABLE TABLE (per gudang)
        // ============================================================
        buildEditableTable(rows) {
            this.setStats(rows);

            if ($.fn.DataTable.isDataTable('#so-stock-table')) {
                this.dtEditable.destroy();
                $('#so-stock-table tbody').remove();
            }

            const readOnly = this.periodStatus === 'Closed' || !CAN_CREATE_OPENING;
            const self = this;

            this.dtEditable = $('#so-stock-table').DataTable({
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
                dom: '<"top"f>rt<"bottom"lpi>', // 'B' sengaja dihilangkan — tombol export dipicu dari toolbar halaman (lihat so-btn-export-excel)
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Stok Awal Bahan Kimia',
                    messageTop: () => this.excelLetterhead(false),
                    exportOptions: {
                        // Export SEMUA kolom termasuk yang visible:false (Kode Kimia) —
                        // FIX bug lama: pola lama pakai format.body yang manggil .replace()
                        // langsung di atas number (No urut / Qty), jadi export selalu gagal
                        // waktu mode per-Gudang dipakai. Sekarang pakai orthogonal:'export'
                        // supaya tiap kolom kirim teks polos sendiri, tanpa perlu strip HTML manual.
                        columns: (idx, data, node) => true,
                        orthogonal: 'export',
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
                        visible: false,
                        searchable: false,
                    },
                    {
                        targets: 3,
                        width: '150px'
                    },
                    {
                        targets: 4,
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
                            // Excel/sort/filter pakai nama polos saja — kode kimia jadi kolom tersendiri.
                            if (type !== 'display') return data;
                            return `<span class="fw-semibold">${self.e(data)}</span>
                                    <div class="text-muted small font-monospace">${self.e(row.chemical_code)}</div>`;
                        },
                    },
                    {
                        // Kolom khusus export Excel: kode kimia terpisah dari nama (disembunyikan di layar).
                        data: 'chemical_code',
                        title: 'Kode Kimia',
                    },
                    {
                        data: 'category_name',
                        render: d => d ?
                            d.split(', ').map(c => `<span class="badge badge-phoenix badge-phoenix-secondary p-2 fs-10 me-1 mb-1">${self.e(c)}</span>`).join('') : '<span class="text-muted fst-italic">—</span>',
                    },
                    {
                        data: 'quantity',
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
                $('#so-stock-table tbody').off('input').on('input', '.qty-input, .notes-input', function() {
                    const tr = $(this).closest('tr');
                    const rowApi = self.dtEditable.row(tr);
                    const rowData = rowApi.data();
                    if (!rowData) return;

                    if (this.classList.contains('qty-input')) rowData.quantity = this.value;
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

            if ($.fn.DataTable.isDataTable('#so-stock-table-combined')) {
                this.dtCombined.destroy();
                $('#so-stock-table-combined tbody').remove();
            }

            const self = this;

            this.dtCombined = $('#so-stock-table-combined').DataTable({
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
                dom: '<"top"f>rt<"bottom"lpi>', // 'B' sengaja dihilangkan — tombol export dipicu dari toolbar halaman
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Stok Awal Bahan Kimia (Gabungan)',
                    messageTop: () => this.excelLetterhead(true),
                    exportOptions: {
                        columns: (idx, data, node) => true,
                        orthogonal: 'export',
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
                        visible: false,
                        searchable: false,
                    },
                    {
                        targets: 3,
                        width: '150px'
                    },
                    {
                        targets: 4,
                        width: '160px'
                    },
                    {
                        targets: 5,
                        width: '110px'
                    },
                    {
                        targets: 6,
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
                        // Kolom khusus export Excel: kode kimia terpisah dari nama (disembunyikan di layar).
                        data: 'chemical_code',
                        title: 'Kode Kimia',
                    },
                    {
                        data: 'category_name',
                        render: d => d ?
                            d.split(', ').map(c => `<span class="badge badge-phoenix badge-phoenix-secondary p-2 fs-10 me-1 mb-1">${self.e(c)}</span>`).join('') : '<span class="text-muted fst-italic">—</span>',
                    },
                    {
                        data: 'quantity',
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

            $('#so-stock-table-combined tbody').off('click').on('click', 'tr', function() {
                const rowData = self.dtCombined.row(this).data();
                if (rowData) self.openBreakdown(rowData.chemical_id, rowData.chemical_name);
            });
        },

        // ============================================================
        // BREAKDOWN MODAL
        // ============================================================
        async openBreakdown(chemicalId, chemicalName) {
            document.getElementById('so-breakdown-title').textContent = chemicalName;
            document.getElementById('so-breakdown-subtitle').textContent = 'Rincian stok awal per gudang';
            document.getElementById('so-breakdown-body').innerHTML = `<div class="text-center py-3"><span class="spinner-border spinner-border-sm text-primary"></span></div>`;
            new bootstrap.Modal(document.getElementById('so-breakdownModal')).show();

            try {
                const d = await this.get(this.BASE +
                    `warehouse/stocks/opening/breakdown?period_id=${this.currentPeriodId}&chemical_id=${chemicalId}`);
                if (d.status !== 'success' || !d.data.length) {
                    document.getElementById('so-breakdown-body').innerHTML = `<p class="text-muted text-center mb-0">Belum ada data di gudang manapun.</p>`;
                    return;
                }
                const rows = d.data.map(r => `
                    <tr>
                        <td>${this.e(r.warehouse_name)} <span class="text-muted small">(${this.e(r.warehouse_code)})</span></td>
                        <td class="text-end fw-semibold">${this.fmtNumber(r.quantity)} kg</td>
                    </tr>
                `).join('');
                document.getElementById('so-breakdown-body').innerHTML = `
                    <table class="table table-sm fs-9 mb-0">
                        <thead><tr><th>Gudang</th><th class="text-end">Stok Awal</th></tr></thead>
                        <tbody>${rows}</tbody>
                    </table>`;
            } catch {
                document.getElementById('so-breakdown-body').innerHTML = `<p class="text-danger text-center mb-0">Gagal memuat rincian.</p>`;
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
                quantity: r.quantity || 0,
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

                const res = await this.post(this.BASE + 'warehouse/stocks/opening/store', fd);
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
            const btn = document.getElementById('so-btn-save-grid');
            const ico = document.getElementById('so-save-icon');
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

    $(document).ready(() => StockOpening.init());
</script>
<?= $this->endSection() ?>