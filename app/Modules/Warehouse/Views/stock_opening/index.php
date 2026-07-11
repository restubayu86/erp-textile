<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    body {
        overflow-x: hidden;
    }

    .select2-container .select2-selection--single {
        height: calc(1.5em + 1.1rem + 2px) !important;
    }

    #stock-grid-wrapper {
        max-width: 100%;
        overflow-x: auto;
    }

    #stock-grid-table {
        width: 100%;
    }

    #stock-grid-table th {
        position: sticky;
        top: 0;
        background: var(--phoenix-body-bg);
        z-index: 1;
        white-space: nowrap;
    }

    #stock-grid-table input.qty-input {
        text-align: right;
        max-width: 140px;
    }

    #stock-grid-table input.unit-input {
        max-width: 90px;
    }

    .grid-scroll {
        max-height: 60vh;
        overflow-y: auto;
        border: 1px solid var(--phoenix-border-color);
        border-radius: .5rem;
    }

    .row-touched {
        background-color: rgba(var(--phoenix-warning-rgb), .06);
    }

    .badge-wh-count {
        font-size: .68rem;
        font-weight: 600;
    }

    .empty-placeholder {
        padding: 3rem 1rem;
        text-align: center;
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

    <!-- Filter bar -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Periode <span class="text-danger">*</span></label>
                    <select class="form-select form-select-sm" id="f-period" style="width:100%">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Gudang <span class="text-danger">*</span></label>
                    <select class="form-select form-select-sm" id="f-warehouse" style="width:100%">
                        <option value="__combined__">— Gabungan (Semua Gudang) —</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-9 fw-semibold text-uppercase text-muted">Cari Bahan Kimia</label>
                    <input type="text" class="form-control form-control-sm" id="f-search" placeholder="Nama / kode..." disabled>
                </div>
            </div>
        </div>
    </div>

    <!-- Status banner -->
    <div id="status-banner" class="d-none mb-3"></div>

    <!-- Grid -->
    <div id="grid-container">
        <div class="empty-placeholder">
            <span class="fas fa-boxes fa-2x mb-2 d-block opacity-50"></span>
            Pilih periode dan gudang terlebih dahulu untuk menampilkan data stok awal.
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3 d-none" id="action-bar">
        <button class="btn btn-subtle-secondary btn-sm" id="btn-reset-grid" type="button">
            <span class="fas fa-undo me-1"></span>Batalkan Perubahan
        </button>
        <button class="btn btn-primary btn-sm" id="btn-save-grid" type="button">
            <span class="fas fa-save me-1" id="save-icon"></span>
            <span id="save-text">Simpan Stok Awal</span>
        </button>
    </div>
</div>

<!-- Modal Rincian per Gudang (mode Gabungan) -->
<div class="modal fade" id="breakdownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="breakdown-title">Rincian per Gudang</h5>
                    <p class="text-muted fs-10 mb-0" id="breakdown-subtitle"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div id="breakdown-body"></div>
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
        currentPeriodId: null,
        currentWarehouseId: null,
        periodStatus: null,
        rows: [],
        touched: new Set(),

        init() {
            this.initSelect2();
            this.initEvents();
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
            $('#f-period').select2({
                width: '100%',
                theme: 'bootstrap-5',
                placeholder: '— Pilih Periode —',
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
                            text: `${p.name} (${p.code})${p.status === 'Closed' ? ' — Ditutup' : ''}`
                        }))
                    }),
                },
            });

            $('#f-warehouse').select2({
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
            $('#f-period').on('change', () => this.onFilterChange());
            $('#f-warehouse').on('change', () => this.onFilterChange());

            document.getElementById('f-search')?.addEventListener('input', e => this.applySearch(e.target.value));
            document.getElementById('btn-save-grid')?.addEventListener('click', () => this.save());
            document.getElementById('btn-reset-grid')?.addEventListener('click', () => this.reload());

            $(document).on('click', '.btn-breakdown', e => {
                const btn = $(e.currentTarget);
                this.openBreakdown(btn.data('chemical-id'), btn.data('chemical-name'));
            });
        },

        onFilterChange() {
            const periodId = $('#f-period').val();
            const warehouseId = $('#f-warehouse').val();
            document.getElementById('f-search').disabled = !periodId || !warehouseId;
            document.getElementById('f-search').value = '';

            if (!periodId || !warehouseId) {
                this.renderEmpty();
                return;
            }

            this.currentPeriodId = periodId;
            this.currentWarehouseId = warehouseId;
            this.reload();
        },

        async reload() {
            if (!this.currentPeriodId || !this.currentWarehouseId) return;

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
                this.rows = d.data;
                this.periodStatus = d.period_status;
                this.touched.clear();
                this.renderBanner(d.is_initialized, d.period_status);
                this.renderEditableGrid();
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
                this.rows = d.data;
                this.renderBanner(null, null, true);
                this.renderCombinedGrid();
            } catch (e) {
                this.toast('error', 'Gagal memuat data');
                this.renderEmpty();
            }
        },

        renderBanner(isInitialized, periodStatus, isCombined = false) {
            const banner = document.getElementById('status-banner');
            banner.classList.remove('d-none');

            if (isCombined) {
                banner.innerHTML = `
                    <div class="alert alert-subtle-info py-2 px-3 fs-9 mb-0">
                        <span class="fas fa-layer-group me-1"></span>
                        Mode gabungan — total stok awal dari seluruh gudang untuk periode ini. Klik baris untuk lihat rincian per gudang. Mode ini hanya untuk melihat, bukan untuk mengedit.
                    </div>`;
                document.getElementById('action-bar').classList.add('d-none');
                return;
            }

            if (periodStatus === 'Closed') {
                banner.innerHTML = `
                    <div class="alert alert-subtle-secondary py-2 px-3 fs-9 mb-0">
                        <span class="fas fa-lock me-1"></span>
                        Periode ini sudah <strong>ditutup</strong>. Stok awal tidak bisa diubah lagi.
                    </div>`;
                document.getElementById('action-bar').classList.add('d-none');
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
            if (CAN_CREATE_OPENING) document.getElementById('action-bar').classList.remove('d-none');
        },

        showLoading() {
            document.getElementById('grid-container').innerHTML = `
                <div class="empty-placeholder">
                    <span class="spinner-border spinner-border-sm text-primary me-2"></span>Memuat data...
                </div>`;
        },

        renderEmpty() {
            document.getElementById('status-banner').classList.add('d-none');
            document.getElementById('action-bar').classList.add('d-none');
            document.getElementById('grid-container').innerHTML = `
                <div class="empty-placeholder">
                    <span class="fas fa-boxes fa-2x mb-2 d-block opacity-50"></span>
                    Pilih periode dan gudang terlebih dahulu untuk menampilkan data stok awal.
                </div>`;
        },

        renderEditableGrid() {
            const readOnly = this.periodStatus === 'Closed' || !CAN_CREATE_OPENING;

            const rowsHtml = this.rows.map((r, i) => `
                <tr data-chemical-id="${r.chemical_id}" data-row-index="${i}">
                    <td class="text-muted">${i + 1}</td>
                    <td>
                        <span class="fw-semibold">${this.e(r.chemical_name)}</span>
                        <div class="text-muted small font-monospace">${this.e(r.chemical_code)}</div>
                    </td>
                    <td>${r.category_name ? `<span class="badge badge-phoenix badge-phoenix-secondary fs-10">${this.e(r.category_name)}</span>` : '<span class="text-muted fst-italic">—</span>'}</td>
                    <td>
                        <input type="number" step="0.001" min="0" class="form-control form-control-sm qty-input"
                            value="${r.quantity ?? ''}" placeholder="0" ${readOnly ? 'disabled' : ''}>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm unit-input"
                            value="${this.e(r.unit ?? '')}" placeholder="satuan" ${readOnly ? 'disabled' : ''}>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm notes-input"
                            value="${this.e(r.notes ?? '')}" placeholder="opsional" ${readOnly ? 'disabled' : ''}>
                    </td>
                </tr>
            `).join('');

            document.getElementById('grid-container').innerHTML = `
                <div class="grid-scroll">
                    <table class="table table-hover fs-9 align-middle mb-0" id="stock-grid-table">
                        <thead>
                            <tr>
                                <th style="width:40px">No</th>
                                <th style="width:220px">Bahan Kimia</th>
                                <th style="width:140px">Kategori</th>
                                <th style="width:150px">Stok Awal</th>
                                <th style="width:110px">Satuan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>${rowsHtml || `<tr><td colspan="6" class="empty-placeholder">Belum ada bahan kimia aktif.</td></tr>`}</tbody>
                    </table>
                </div>
            `;

            if (!readOnly) {
                document.querySelectorAll('#stock-grid-table input').forEach(el => {
                    el.addEventListener('input', e => {
                        e.target.closest('tr')?.classList.add('row-touched');
                        this.touched.add(e.target.closest('tr')?.dataset.chemicalId);
                    });
                });
            }
        },

        renderCombinedGrid() {
            const rowsHtml = this.rows.map((r, i) => `
                <tr class="btn-breakdown" style="cursor:pointer" data-chemical-id="${r.chemical_id}" data-chemical-name="${this.e(r.chemical_name)}">
                    <td class="text-muted">${i + 1}</td>
                    <td>
                        <span class="fw-semibold">${this.e(r.chemical_name)}</span>
                        <div class="text-muted small font-monospace">${this.e(r.chemical_code)}</div>
                    </td>
                    <td>${r.category_name ? `<span class="badge badge-phoenix badge-phoenix-secondary fs-10">${this.e(r.category_name)}</span>` : '<span class="text-muted fst-italic">—</span>'}</td>
                    <td class="fw-semibold">${this.fmtNumber(r.quantity)} <span class="text-muted fw-normal">${this.e(r.unit ?? '')}</span></td>
                    <td><span class="badge badge-phoenix badge-phoenix-info badge-wh-count">${r.warehouse_count} gudang</span></td>
                    <td class="text-end"><span class="fas fa-chevron-right text-muted"></span></td>
                </tr>
            `).join('');

            document.getElementById('grid-container').innerHTML = `
                <div class="grid-scroll">
                    <table class="table table-hover fs-9 align-middle mb-0" id="stock-grid-table">
                        <thead>
                            <tr>
                                <th style="width:40px">No</th>
                                <th style="width:220px">Bahan Kimia</th>
                                <th style="width:140px">Kategori</th>
                                <th style="width:160px">Total Stok Awal</th>
                                <th style="width:110px">Tersebar di</th>
                                <th style="width:40px"></th>
                            </tr>
                        </thead>
                        <tbody>${rowsHtml || `<tr><td colspan="6" class="empty-placeholder">Belum ada bahan kimia aktif.</td></tr>`}</tbody>
                    </table>
                </div>
            `;
        },

        applySearch(term) {
            term = term.trim().toLowerCase();
            document.querySelectorAll('#stock-grid-table tbody tr').forEach(tr => {
                if (!term) {
                    tr.style.display = '';
                    return;
                }
                const text = tr.textContent.toLowerCase();
                tr.style.display = text.includes(term) ? '' : 'none';
            });
        },

        async openBreakdown(chemicalId, chemicalName) {
            document.getElementById('breakdown-title').textContent = chemicalName;
            document.getElementById('breakdown-subtitle').textContent = 'Rincian stok awal per gudang';
            document.getElementById('breakdown-body').innerHTML = `<div class="text-center py-3"><span class="spinner-border spinner-border-sm text-primary"></span></div>`;
            new bootstrap.Modal(document.getElementById('breakdownModal')).show();

            try {
                const d = await this.get(this.BASE +
                    `warehouse/stocks/opening/breakdown?period_id=${this.currentPeriodId}&chemical_id=${chemicalId}`);
                if (d.status !== 'success' || !d.data.length) {
                    document.getElementById('breakdown-body').innerHTML = `<p class="text-muted text-center mb-0">Belum ada data di gudang manapun.</p>`;
                    return;
                }
                const rows = d.data.map(r => `
                    <tr>
                        <td>${this.e(r.warehouse_name)} <span class="text-muted small">(${this.e(r.warehouse_code)})</span></td>
                        <td class="text-end fw-semibold">${this.fmtNumber(r.quantity)} ${this.e(r.unit ?? '')}</td>
                    </tr>
                `).join('');
                document.getElementById('breakdown-body').innerHTML = `
                    <table class="table table-sm fs-9 mb-0">
                        <thead><tr><th>Gudang</th><th class="text-end">Stok Awal</th></tr></thead>
                        <tbody>${rows}</tbody>
                    </table>`;
            } catch {
                document.getElementById('breakdown-body').innerHTML = `<p class="text-danger text-center mb-0">Gagal memuat rincian.</p>`;
            }
        },

        async save() {
            const rows = [];
            document.querySelectorAll('#stock-grid-table tbody tr[data-chemical-id]').forEach(tr => {
                rows.push({
                    chemical_id: tr.dataset.chemicalId,
                    quantity: tr.querySelector('.qty-input')?.value || 0,
                    unit: tr.querySelector('.unit-input')?.value || '',
                    notes: tr.querySelector('.notes-input')?.value || '',
                });
            });

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
            const btn = document.getElementById('btn-save-grid');
            const ico = document.getElementById('save-icon');
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