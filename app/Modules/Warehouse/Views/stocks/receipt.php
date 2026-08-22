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

    .select2-container .select2-selection--single {
        height: calc(1.5em + 1.1rem + 2px) !important;
    }

    .period-range-text {
        font-size: .68rem;
        color: var(--phoenix-secondary-color);
    }

    #rcp-pending-table input.qty-input,
    #rcp-pending-table input.unit-input {
        text-align: right;
        max-width: 110px;
    }

    #rcp-pending-table input.notes-input {
        min-width: 160px;
    }

    #rcp-history-table {
        width: 100% !important;
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
                            <div class="info-value text-warning" id="rcp-stat-pending">0</div>
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
                            <div class="info-label">Penerimaan Hari Ini</div>
                            <div class="info-value text-success" id="rcp-stat-today">—</div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <span class="fas fa-truck-loading"></span>
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
                            <div class="info-value" id="rcp-stat-period">—</div>
                            <div class="period-range-text" id="rcp-stat-period-range"></div>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary" id="rcp-stat-period-icon">
                            <span class="fas fa-calendar-alt"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status banner -->
    <div id="rcp-status-banner" class="d-none mb-3"></div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <div class="d-flex gap-2 align-items-center">
            <span class="badge badge-phoenix badge-phoenix-secondary fs-9 p-2 px-3" id="rcp-context-badge">
                <span class="fas fa-info-circle me-1"></span>Pilih periode, gudang &amp; tanggal penerimaan
            </span>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="rcp-btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
        </div>
    </div>

    <!-- Empty state -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y" id="rcp-empty-wrapper">
        <div class="text-center py-5 text-body-tertiary">
            <span class="fas fa-truck-loading fa-2x mb-2 d-block opacity-50"></span>
            Pilih periode, gudang, dan tanggal penerimaan terlebih dahulu.
        </div>
    </div>

    <!-- Form area -->
    <div class="d-none" id="rcp-form-wrapper">
        <!-- Tambah item -->
        <div class="card mb-3">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><span class="fas fa-plus-circle me-2 text-primary"></span>Tambah Item Penerimaan</h6>
            </div>
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Bahan Kimia</label>
                        <select class="form-select form-select-sm" id="rcp-input-chemical" style="width:100%">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Qty</label>
                        <input type="number" min="0.001" step="0.001" class="form-control form-control-sm text-end" id="rcp-input-qty" placeholder="0">
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Satuan</label>
                        <input type="text" class="form-control form-control-sm" id="rcp-input-unit" value="kg">
                    </div>
                    <div class="col-lg-3 col-md-8">
                        <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Catatan (opsional)</label>
                        <input type="text" class="form-control form-control-sm" id="rcp-input-notes" placeholder="mis. No. Surat Jalan / Supplier">
                    </div>
                    <div class="col-lg-1 col-md-4">
                        <button class="btn btn-primary btn-sm w-100" id="rcp-btn-add-item" type="button">
                            <span class="fas fa-plus"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar item pending -->
        <div class="card mb-3">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><span class="fas fa-list-ul me-2 text-primary"></span>Daftar Item yang Akan Disimpan</h6>
                <button class="btn btn-success btn-sm" id="rcp-btn-save" type="button" disabled>
                    <span class="fas fa-save me-1" id="rcp-save-icon"></span>
                    <span id="rcp-save-text">Simpan Penerimaan</span>
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover fs-9 align-middle mb-0" id="rcp-pending-table">
                        <thead class="bg-body-secondary">
                            <tr>
                                <th style="width:30px">No</th>
                                <th>Bahan Kimia</th>
                                <th class="text-end" style="width:110px">Qty</th>
                                <th style="width:90px">Satuan</th>
                                <th>Catatan</th>
                                <th style="width:40px"></th>
                            </tr>
                        </thead>
                        <tbody id="rcp-pending-tbody">
                            <tr id="rcp-pending-empty-row">
                                <td colspan="6" class="text-center text-muted py-4">Belum ada item ditambahkan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Riwayat penerimaan terbaru -->
        <div class="card mb-3">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><span class="fas fa-history me-2 text-primary"></span>Riwayat Penerimaan Terbaru</h6>
                <p class="text-muted fs-10 mb-0">30 transaksi Penerimaan terakhir untuk gudang &amp; periode yang dipilih</p>
            </div>
            <div class="card-body">
                <table class="table table-hover fs-9 nowrap align-middle" id="rcp-history-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Bahan Kimia</th>
                            <th class="text-end">Qty Masuk</th>
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
<a class="card filter-toggle" href="#rcp-filter-offcanvas" data-bs-toggle="offcanvas" id="rcp-filter-toggle">
    <div class="card-body">
        <span class="fas fa-filter text-primary"></span>
        <span class="filter-label">Filter</span>
        <span class="filter-dot"></span>
    </div>
</a>

<!-- Filter Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="rcp-filter-offcanvas" style="width:320px">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title"><span class="fas fa-filter me-2 text-primary"></span>Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="flex-grow-1">
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="rcp-filter-period">
                    Periode <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="rcp-filter-period" style="width:100%">
                    <option value=""></option>
                </select>
                <div class="form-text fs-10" id="rcp-period-status-hint"></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="rcp-filter-warehouse">
                    Gudang <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="rcp-filter-warehouse" style="width:100%">
                    <option value=""></option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted" for="rcp-filter-date">
                    Tanggal Penerimaan <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control form-control-sm" id="rcp-filter-date">
            </div>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary btn-sm" id="rcp-btn-apply-filter">
                <span class="fas fa-search me-1"></span>Terapkan
            </button>
            <button class="btn btn-subtle-secondary btn-sm" id="rcp-btn-reset-filter">
                <span class="fas fa-times me-1"></span>Reset
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    const StockReceipt = {
        BASE: '<?= base_url() ?>',
        currentPeriodId: null,
        currentPeriodRange: null,
        currentPeriodStatus: null,
        currentWarehouseId: null,
        currentWarehouseText: null,
        currentDate: null,
        pendingItems: [], // { chemical_id, chemical_name, chemical_code, quantity, unit, notes }
        dtHistory: null,

        init() {
            this.initSelect2();
            this.initEvents();
            // Default tanggal penerimaan = hari ini
            const today = new Date().toISOString().slice(0, 10);
            document.getElementById('rcp-filter-date').value = today;
        },

        initSelect2() {
            $('#rcp-filter-period').select2({
                dropdownParent: $('#rcp-filter-offcanvas'),
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

            $('#rcp-filter-warehouse').select2({
                dropdownParent: $('#rcp-filter-offcanvas'),
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

            $('#rcp-input-chemical').select2({
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
        },

        initEvents() {
            document.getElementById('rcp-btn-refresh')?.addEventListener('click', () => this.reload());
            document.getElementById('rcp-btn-apply-filter')?.addEventListener('click', () => this.applyFilter());
            document.getElementById('rcp-btn-reset-filter')?.addEventListener('click', () => this.resetFilter());
            document.getElementById('rcp-btn-add-item')?.addEventListener('click', () => this.addItem());
            document.getElementById('rcp-btn-save')?.addEventListener('click', () => this.save());
            document.getElementById('rcp-input-qty')?.addEventListener('keydown', e => {
                if (e.key === 'Enter') this.addItem();
            });
        },

        applyFilter() {
            const periodData = $('#rcp-filter-period').select2('data')[0];
            const warehouseData = $('#rcp-filter-warehouse').select2('data')[0];
            const date = document.getElementById('rcp-filter-date').value;

            if (!periodData?.id || !warehouseData?.id || !date) {
                this.toast('error', 'Periode, gudang, dan tanggal penerimaan wajib dipilih');
                return;
            }
            if (date < periodData.start_date || date > periodData.end_date) {
                this.toast('error', 'Tanggal penerimaan harus berada dalam rentang periode');
                return;
            }

            this.currentPeriodId = periodData.id;
            this.currentPeriodRange = `${this.fmtDateOnly(periodData.start_date)} — ${this.fmtDateOnly(periodData.end_date)}`;
            this.currentPeriodStatus = periodData.status;
            this.currentWarehouseId = warehouseData.id;
            this.currentWarehouseText = warehouseData.text;
            this.currentDate = date;

            document.getElementById('rcp-period-status-hint').textContent =
                `${periodData.name} (${periodData.code})${periodData.status === 'Closed' ? ' — Ditutup' : ''} · ${this.currentPeriodRange}`;

            this.updateFilterUI();
            bootstrap.Offcanvas.getInstance(document.getElementById('rcp-filter-offcanvas'))?.hide();
            this.reload();
        },

        resetFilter() {
            $('#rcp-filter-period').val(null).trigger('change');
            $('#rcp-filter-warehouse').val(null).trigger('change');
            document.getElementById('rcp-filter-date').value = new Date().toISOString().slice(0, 10);
            document.getElementById('rcp-period-status-hint').textContent = '';
            this.currentPeriodId = null;
            this.currentPeriodRange = null;
            this.currentPeriodStatus = null;
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
            if (this.currentPeriodId) labels.push(`Periode: ${$('#rcp-filter-period').select2('data')[0]?.text ?? ''}`);
            if (this.currentWarehouseText) labels.push(`Gudang: ${this.currentWarehouseText}`);
            if (this.currentDate) labels.push(`Tanggal: ${this.fmtDateOnly(this.currentDate)}`);

            const badge = document.getElementById('rcp-context-badge');
            const toggle = document.getElementById('rcp-filter-toggle');
            if (labels.length) {
                badge.innerHTML = `<span class="fas fa-check-circle me-1"></span>${labels.join(' · ')}`;
                toggle.classList.add('has-filter');
            } else {
                badge.innerHTML = `<span class="fas fa-info-circle me-1"></span>Pilih periode, gudang &amp; tanggal penerimaan`;
                toggle.classList.remove('has-filter');
            }

            document.getElementById('rcp-stat-period').textContent = this.currentPeriodStatus ?? '—';
            document.getElementById('rcp-stat-period-range').textContent = this.currentPeriodRange ?? '';
            const icon = document.getElementById('rcp-stat-period-icon');
            if (this.currentPeriodStatus === 'Closed') {
                icon.className = 'stat-icon bg-danger bg-opacity-10 text-danger';
            } else if (this.currentPeriodStatus === 'Open') {
                icon.className = 'stat-icon bg-success bg-opacity-10 text-success';
            } else {
                icon.className = 'stat-icon bg-secondary bg-opacity-10 text-secondary';
            }
        },

        renderEmpty() {
            document.getElementById('rcp-empty-wrapper').classList.remove('d-none');
            document.getElementById('rcp-form-wrapper').classList.add('d-none');
        },

        async reload() {
            if (!this.currentPeriodId || !this.currentWarehouseId || !this.currentDate) {
                this.renderEmpty();
                return;
            }

            document.getElementById('rcp-empty-wrapper').classList.add('d-none');
            document.getElementById('rcp-form-wrapper').classList.remove('d-none');

            // Banner periode ditutup — form tetap terlihat tapi aksi disimpan diblok.
            const banner = document.getElementById('rcp-status-banner');
            if (this.currentPeriodStatus === 'Closed') {
                banner.className = 'alert alert-subtle-danger d-flex align-items-center gap-2 mb-3';
                banner.innerHTML = `<span class="fas fa-lock"></span> Periode ini sudah <b>Ditutup</b> — transaksi penerimaan tidak bisa dicatat.`;
                banner.classList.remove('d-none');
            } else {
                banner.classList.add('d-none');
            }

            this.pendingItems = [];
            this.renderPending();
            await this.loadHistory();
        },

        // ============================================================
        // TAMBAH ITEM (pending, belum tersimpan ke server)
        // ============================================================
        async addItem() {
            if (this.currentPeriodStatus === 'Closed') {
                this.toast('error', 'Periode sudah ditutup, tidak bisa menambah item');
                return;
            }

            const chemicalData = $('#rcp-input-chemical').select2('data')[0];
            const qty = parseFloat(document.getElementById('rcp-input-qty').value);
            const unit = document.getElementById('rcp-input-unit').value.trim() || 'kg';
            const notes = document.getElementById('rcp-input-notes').value.trim();

            if (!chemicalData?.id) {
                this.toast('error', 'Pilih bahan kimia terlebih dahulu');
                return;
            }
            if (!qty || qty <= 0) {
                this.toast('error', 'Qty harus lebih dari 0');
                return;
            }
            if (this.pendingItems.some(it => String(it.chemical_id) === String(chemicalData.id))) {
                this.toast('error', 'Bahan kimia ini sudah ada di daftar — hapus dulu kalau mau ubah qty');
                return;
            }

            // Wajib: bahan kimia ini harus sudah punya Stok Awal di gudang & periode ini.
            try {
                const res = await this.get(this.BASE +
                    `warehouse/stocks/opening/status?period_id=${this.currentPeriodId}&warehouse_id=${this.currentWarehouseId}&chemical_id=${chemicalData.id}`);
                if (res.status !== 'success' || !res.data?.has_opening_stock) {
                    this.toast('error', `${chemicalData.text} belum punya Stok Awal di gudang & periode ini. Isi Stok Awal dulu (boleh 0) sebelum mencatat Penerimaan.`);
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
                quantity: qty,
                unit,
                notes,
            });

            $('#rcp-input-chemical').val(null).trigger('change');
            document.getElementById('rcp-input-qty').value = '';
            document.getElementById('rcp-input-unit').value = 'kg';
            document.getElementById('rcp-input-notes').value = '';
            document.getElementById('rcp-input-qty').focus();

            this.renderPending();
        },

        removeItem(idx) {
            this.pendingItems.splice(idx, 1);
            this.renderPending();
        },

        renderPending() {
            const tbody = document.getElementById('rcp-pending-tbody');
            document.getElementById('rcp-stat-pending').textContent = this.pendingItems.length;
            document.getElementById('rcp-btn-save').disabled = this.pendingItems.length === 0 || this.currentPeriodStatus === 'Closed';

            if (!this.pendingItems.length) {
                tbody.innerHTML = `<tr id="rcp-pending-empty-row"><td colspan="6" class="text-center text-muted py-4">Belum ada item ditambahkan.</td></tr>`;
                return;
            }

            tbody.innerHTML = this.pendingItems.map((it, idx) => `
                <tr>
                    <td>${idx + 1}</td>
                    <td>
                        <span class="fw-semibold">${this.e(it.chemical_name)}</span>
                        <div class="text-muted small font-monospace">${this.e(it.chemical_code)}</div>
                    </td>
                    <td class="text-end fw-semibold">${this.fmtNumber(it.quantity)}</td>
                    <td>${this.e(it.unit)}</td>
                    <td class="text-muted">${it.notes ? this.e(it.notes) : '<span class="fst-italic">—</span>'}</td>
                    <td class="text-end">
                        <button class="btn btn-subtle-danger btn-sm py-0 px-2" onclick="StockReceipt.removeItem(${idx})" type="button">
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
                fd.set('movement_date', this.currentDate);
                fd.set('rows', JSON.stringify(this.pendingItems.map(it => ({
                    chemical_id: it.chemical_id,
                    quantity: it.quantity,
                    unit: it.unit,
                    notes: it.notes,
                }))));

                const res = await this.post(this.BASE + 'warehouse/stocks/receipt/store', fd);
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    this.pendingItems = [];
                    this.renderPending();
                    await this.loadHistory();
                } else {
                    this.toast('error', res.message ?? 'Gagal menyimpan penerimaan');
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                this.setLoading(false);
            }
        },

        // ============================================================
        // RIWAYAT
        // ============================================================
        async loadHistory() {
            try {
                const res = await this.get(this.BASE +
                    `warehouse/stocks/receipt/recent?period_id=${this.currentPeriodId}&warehouse_id=${this.currentWarehouseId}`);
                const rows = res.status === 'success' ? (res.data ?? []) : [];
                this.buildHistoryTable(rows);

                const todayStr = this.currentDate;
                const todayCount = rows.filter(r => (r.movement_date ?? '').slice(0, 10) === todayStr).length;
                document.getElementById('rcp-stat-today').textContent = todayCount;
            } catch (e) {
                this.buildHistoryTable([]);
            }
        },

        buildHistoryTable(rows) {
            const self = this;
            if ($.fn.DataTable.isDataTable('#rcp-history-table')) {
                this.dtHistory.destroy();
                $('#rcp-history-table tbody').remove();
            }

            this.dtHistory = $('#rcp-history-table').DataTable({
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
                    infoEmpty: 'Belum ada riwayat penerimaan',
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
                        data: 'quantity_in',
                        className: 'text-end fw-semibold text-success',
                        render: d => '+' + self.fmtNumber(d),
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
                        render: id => self.currentPeriodStatus === 'Closed' ? '' : `<button class="btn btn-subtle-danger btn-sm py-0 px-2" onclick="StockReceipt.deleteHistory(${id})" type="button">
                                <span class="fas fa-trash-alt"></span>
                             </button>`,
                    },
                ],
            });
        },

        async deleteHistory(id) {
            const confirmed = await Swal.fire({
                title: 'Batalkan transaksi ini?',
                text: 'Baris penerimaan ini akan dihapus permanen.',
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
                const res = await this.post(this.BASE + 'warehouse/stocks/receipt/delete', fd);
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
            const btn = document.getElementById('rcp-btn-save');
            const ico = document.getElementById('rcp-save-icon');
            if (!btn) return;
            btn.disabled = on || this.pendingItems.length === 0;
            ico.className = on ? 'spinner-border spinner-border-sm me-1' : 'fas fa-save me-1';
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

    $(document).ready(() => StockReceipt.init());
</script>
<?= $this->endSection() ?>