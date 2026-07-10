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

    .badge-current {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .2rem .5rem;
        border-radius: 20px;
        font-size: .68rem;
        font-weight: 700;
        background-color: rgba(var(--phoenix-primary-rgb), .12);
        color: var(--phoenix-primary);
        border: 1px solid rgba(var(--phoenix-primary-rgb), .25);
    }

    #period-table_wrapper {
        max-width: 100%;
    }

    #period-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #period-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #period-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #period-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #period-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #period-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #period-table_wrapper .dataTables_filter label,
    #period-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #period-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #period-table_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #period-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #period-table {
        width: 100% !important;
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

    .btn-group-sm .btn {
        padding: .5rem .75rem;
        font-size: .7rem;
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
        <?php
        $stats = [
            ['id' => 'stat-total',  'label' => 'Total',  'icon' => 'fa-calendar-alt',   'color' => 'primary'],
            ['id' => 'stat-open',   'label' => 'Open',   'icon' => 'fa-lock-open',      'color' => 'success'],
            ['id' => 'stat-closed', 'label' => 'Closed', 'icon' => 'fa-lock',           'color' => 'secondary'],
        ];
        foreach ($stats as $s): ?>
            <div class="col-md-4 col-6">
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
            <?php if (canDo('warehouse.periods.delete')): ?>
                <a href="<?= site_url('warehouse/master/periods/trash') ?>" class="btn btn-subtle-danger btn-sm">
                    <span class="fas fa-trash-alt me-1"></span>Sampah
                    <span class="badge bg-danger ms-1 d-none" id="trash-badge">0</span>
                </a>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <?php if (canDo('warehouse.periods.create')): ?>
                <button class="btn btn-primary btn-sm" id="btn-create" type="button">
                    <span class="fas fa-plus me-1"></span>Tambah Periode
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Print header -->
    <div class="print-header mb-3">
        <h5 class="fw-bold mb-1">Daftar Periode</h5>
        <div class="text-muted small">Dicetak: <span id="print-date"></span></div>
        <hr class="my-2">
    </div>

    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="period-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Rentang Tanggal</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Oleh</th>
                    <th>Diupdate</th>
                    <th>Oleh</th>
                    <th class="text-end no-print">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Tambah / Edit -->
<div class="modal fade" id="periodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4" id="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary" id="modal-icon">
                        <span class="fas fa-calendar-alt"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modal-title">Tambah Periode</h5>
                        <p class="text-muted fs-10 mb-0" id="modal-subtitle">Buat data periode baru</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-subtle-danger py-2 px-3 fs-10 d-none" id="modal-alert">
                    <span class="fas fa-exclamation-triangle me-1"></span>
                    <span id="modal-alert-text"></span>
                </div>

                <div class="card border mb-0" style="border-radius:.75rem">
                    <div class="card-body p-3">
                        <p class="fs-10 fw-bold text-uppercase text-primary mb-3" style="letter-spacing:.08em">
                            <span class="fas fa-clipboard-list me-1"></span>Informasi Periode
                        </p>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-name">
                                    Nama Periode <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="f-name"
                                    placeholder="cth: Juli 2026" maxlength="50" autocomplete="off">
                                <div class="invalid-feedback" id="err-period_name"></div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-code">
                                    Kode <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm font-monospace fw-bold"
                                    id="f-code" placeholder="cth: 2026-07" maxlength="20" autocomplete="off"
                                    style="text-transform:uppercase;letter-spacing:.06em">
                                <div class="invalid-feedback" id="err-period_code"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-start">
                                    Tanggal Mulai <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control form-control-sm" id="f-start">
                                <div class="invalid-feedback" id="err-start_date"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-end">
                                    Tanggal Akhir <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control form-control-sm" id="f-end">
                                <div class="invalid-feedback" id="err-end_date"></div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="f-current">
                                    <label class="form-check-label fs-9 fw-semibold" for="f-current">
                                        Jadikan periode aktif berjalan
                                    </label>
                                </div>
                                <div class="form-text fs-10">Periode aktif lain otomatis dinonaktifkan.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-notes">
                                    Catatan
                                </label>
                                <textarea class="form-control form-control-sm" id="f-notes"
                                    rows="2" maxlength="500" placeholder="Catatan singkat..." style="resize:vertical"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Batal
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save">
                    <span class="fas fa-save me-1" id="save-icon"></span>
                    <span id="save-text">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CAN_EDIT_PERIOD = <?= json_encode(canDo('warehouse.periods.edit')) ?>;
    const CAN_DELETE_PERIOD = <?= json_encode(canDo('warehouse.periods.delete')) ?>;

    const Period = {
        BASE: '<?= base_url() ?>',
        dt: null,
        editId: null,
        filters: {
            name: '',
            status: ''
        },

        init() {
            this.initDatatable();
            this.initEvents();
            this.initFieldEvents();
            this.loadStats();
            document.getElementById('print-date').textContent = new Date().toLocaleDateString('id-ID', {
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

        async loadStats() {
            try {
                const d = await this.get(this.BASE + 'warehouse/master/periods/stats');
                if (d.status !== 'success') return;
                document.getElementById('stat-total').textContent = d.data.total ?? 0;
                document.getElementById('stat-open').textContent = d.data.open ?? 0;
                document.getElementById('stat-closed').textContent = d.data.closed ?? 0;
                const badge = document.getElementById('trash-badge');
                if (badge) {
                    badge.textContent = d.data.trash ?? 0;
                    badge.classList.toggle('d-none', !d.data.trash);
                }
            } catch {}
        },

        initDatatable() {
            const self = this;
            this.dt = $('#period-table').DataTable({
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
                    [2, 'desc']
                ],
                dom: '<"top"f>rt<"bottom"lpi>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari periode...',
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
                    url: this.BASE + 'warehouse/master/periods/datatables',
                    type: 'GET',
                    data: d => {
                        d.filter_name = self.filters.name;
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
                        width: '180px'
                    },
                    {
                        targets: 2,
                        width: '180px'
                    },
                    {
                        targets: 3,
                        width: '110px'
                    },
                    {
                        targets: 4,
                        width: '120px'
                    },
                    {
                        targets: 5,
                        width: '130px'
                    },
                    {
                        targets: 6,
                        width: '120px'
                    },
                    {
                        targets: 7,
                        width: '130px'
                    },
                    {
                        targets: 8,
                        width: '140px'
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
                            `<span class="fw-semibold">${self.e(r.period_name)}</span>
                             <div class="text-muted small font-monospace">${self.e(r.period_code)}</div>
                             ${Number(r.is_current) === 1 ? '<span class="badge-current mt-1 d-inline-flex"><span class="fas fa-star"></span>Aktif</span>' : ''}`
                    },
                    {
                        data: null,
                        render: (d, t, r) => `${self.fmtDateOnly(r.start_date)} <span class="text-muted">s/d</span> ${self.fmtDateOnly(r.end_date)}`
                    },
                    {
                        data: 'status',
                        render: d => self.fmtStatus(d)
                    },
                    {
                        data: 'created_at',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: 'created_by_name',
                        render: (d, t, r) => self.fmtUser(d, r.created_by_employee)
                    },
                    {
                        data: 'updated_at',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: 'updated_by_name',
                        render: (d, t, r) => self.fmtUser(d, r.updated_by_employee)
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render: (d, t, r) => {
                            const isOpen = r.status === 'Open';
                            const isCurrent = Number(r.is_current) === 1;

                            const setCurrent = (CAN_EDIT_PERIOD && isOpen && !isCurrent) ?
                                `<button class="btn btn-subtle-warning btn-sm btn-set-current" data-id="${r.id}" data-name="${self.e(r.period_name)}" title="Jadikan Periode Aktif">
                                    <span class="fas fa-star"></span>
                                </button>` : '';
                            const close = (CAN_EDIT_PERIOD && isOpen) ?
                                `<button class="btn btn-subtle-secondary btn-sm btn-close-period" data-id="${r.id}" data-name="${self.e(r.period_name)}" title="Tutup Periode">
                                    <span class="fas fa-lock"></span>
                                </button>` : '';
                            const edit = (CAN_EDIT_PERIOD && isOpen) ?
                                `<button class="btn btn-subtle-primary btn-sm btn-edit" data-id="${r.id}" title="Edit"><span class="fas fa-pencil-alt"></span></button>` :
                                '';
                            const del = (CAN_DELETE_PERIOD && isOpen && !isCurrent) ?
                                `<button class="btn btn-subtle-danger btn-sm btn-delete" data-id="${r.id}" data-name="${self.e(r.period_name)}" title="Hapus"><span class="fas fa-trash"></span></button>` :
                                '';
                            return `<div class="btn-group btn-group-sm">${setCurrent}${close}${edit}${del}</div>`;
                        }
                    },
                ],
            });
        },

        openCreate() {
            this.editId = null;
            this.resetModal();
            document.getElementById('modal-title').textContent = 'Tambah Periode';
            document.getElementById('modal-subtitle').textContent = 'Buat data periode baru';
            document.getElementById('save-text').textContent = 'Simpan';
            new bootstrap.Modal(document.getElementById('periodModal')).show();
        },

        async openEdit(id) {
            this.editId = id;
            this.resetModal();
            document.getElementById('modal-title').textContent = 'Edit Periode';
            document.getElementById('modal-subtitle').textContent = 'Perbarui data periode';
            document.getElementById('save-text').textContent = 'Update';
            this.setLoading(true);
            new bootstrap.Modal(document.getElementById('periodModal')).show();
            try {
                const d = await this.get(this.BASE + `warehouse/master/periods/${id}`);
                if (d.status === 'success' && d.data) {
                    document.getElementById('f-name').value = d.data.period_name ?? '';
                    document.getElementById('f-code').value = d.data.period_code ?? '';
                    document.getElementById('f-start').value = d.data.start_date ?? '';
                    document.getElementById('f-end').value = d.data.end_date ?? '';
                    document.getElementById('f-notes').value = d.data.notes ?? '';
                    document.getElementById('f-current').checked = Number(d.data.is_current) === 1;

                    if (d.data.period_name) this.markValid('f-name');
                    if (d.data.period_code) this.markValid('f-code');
                    if (d.data.start_date) this.markValid('f-start');
                    if (d.data.end_date) this.markValid('f-end');
                } else {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    bootstrap.Modal.getInstance(document.getElementById('periodModal'))?.hide();
                }
            } catch {
                this.toast('error', 'Gagal memuat data');
            } finally {
                this.setLoading(false);
            }
        },

        async save() {
            this.clearErrors();
            const fd = new FormData();
            fd.set('period_name', document.getElementById('f-name').value.trim());
            fd.set('period_code', document.getElementById('f-code').value.trim().toUpperCase());
            fd.set('start_date', document.getElementById('f-start').value);
            fd.set('end_date', document.getElementById('f-end').value);
            fd.set('notes', document.getElementById('f-notes').value.trim());
            fd.set('is_current', document.getElementById('f-current').checked ? '1' : '0');
            if (this.editId) fd.set('id', this.editId);

            this.setLoading(true);
            try {
                const res = await this.post(this.BASE + 'warehouse/master/periods/store', fd);
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('periodModal'))?.hide();
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else if (res.errors) {
                    this.showErrors(res.errors);
                } else {
                    document.getElementById('modal-alert').classList.remove('d-none');
                    document.getElementById('modal-alert-text').textContent = res.message ?? 'Terjadi kesalahan';
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                this.setLoading(false);
            }
        },

        resetModal() {
            ['f-name', 'f-code', 'f-start', 'f-end', 'f-notes'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.classList.remove('is-invalid', 'is-valid');
                }
            });
            document.getElementById('f-current').checked = false;
            document.getElementById('modal-alert').classList.add('d-none');
            this.clearErrors();
        },

        clearErrors() {
            document.querySelectorAll('#periodModal .is-invalid, #periodModal .is-valid').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });
            document.querySelectorAll('#periodModal .invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.visibility = '';
            });
            document.getElementById('modal-alert').classList.add('d-none');
        },

        initFieldEvents() {
            [{
                    input: 'f-name',
                    required: true
                },
                {
                    input: 'f-code',
                    required: true
                },
                {
                    input: 'f-start',
                    required: true
                },
                {
                    input: 'f-end',
                    required: true
                },
                {
                    input: 'f-notes',
                    required: false
                },
            ].forEach(({
                input,
                required
            }) => {
                const el = document.getElementById(input);
                if (!el) return;

                const revalidate = () => {
                    const val = el.value.trim();
                    if (val) {
                        el.classList.remove('is-invalid');
                        el.classList.add('is-valid');
                    } else if (required) {
                        el.classList.remove('is-valid');
                        el.classList.add('is-invalid');
                    } else {
                        el.classList.remove('is-valid', 'is-invalid');
                    }
                };

                el.addEventListener('input', revalidate);
                el.addEventListener('change', revalidate);
            });
        },

        markValid(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('is-invalid');
            el.classList.add('is-valid');
        },

        markInvalid(id, errId, msg) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('is-invalid');
                el.classList.remove('is-valid');
            }
            const errEl = document.getElementById(errId);
            if (errEl) {
                errEl.textContent = msg;
                errEl.style.visibility = 'visible';
            }
        },

        showErrors(errors) {
            const map = {
                period_name: ['f-name', 'err-period_name'],
                period_code: ['f-code', 'err-period_code'],
                start_date: ['f-start', 'err-start_date'],
                end_date: ['f-end', 'err-end_date'],
            };
            Object.entries(errors).forEach(([f, msg]) => {
                const [inp, err] = map[f] ?? [];
                if (inp && err) this.markInvalid(inp, err, Array.isArray(msg) ? msg[0] : msg);
            });
        },

        setLoading(on) {
            const btn = document.getElementById('btn-save');
            const ico = document.getElementById('save-icon');
            btn.disabled = on;
            ico.className = on ? 'spinner-border spinner-border-sm me-1' : 'fas fa-save me-1';
        },

        async deleteItem(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Periode?',
                html: `<strong>${name}</strong> akan dipindahkan ke sampah.<br><small class="text-muted">Dapat dipulihkan dari menu Sampah.</small>`,
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
                const res = await this.post(this.BASE + `warehouse/master/periods/${id}/delete`, new FormData());
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

        async setCurrentItem(id, name) {
            const result = await Swal.fire({
                title: 'Jadikan Periode Aktif?',
                html: `<strong>${name}</strong> akan dijadikan periode aktif berjalan. Periode aktif sebelumnya akan otomatis dinonaktifkan.`,
                icon: 'question',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#f5803e',
                cancelButtonColor: '#748194',
                confirmButtonText: '<span class="fas fa-star me-1"></span>Jadikan Aktif',
                cancelButtonText: 'Batal',
            });
            if (!result.isConfirmed) return;
            try {
                const res = await this.post(this.BASE + `warehouse/master/periods/${id}/set-current`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.toast('success', res.message);
                } else {
                    this.toast('error', res.message);
                }
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        async closePeriodItem(id, name) {
            const result = await Swal.fire({
                title: 'Tutup Periode?',
                html: `<strong>${name}</strong> akan ditutup dan <u>tidak bisa diedit atau dihapus lagi</u> setelah ini.`,
                icon: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#748194',
                cancelButtonColor: '#748194',
                confirmButtonText: '<span class="fas fa-lock me-1"></span>Tutup Periode',
                cancelButtonText: 'Batal',
            });
            if (!result.isConfirmed) return;
            try {
                const res = await this.post(this.BASE + `warehouse/master/periods/${id}/close`, new FormData());
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

        applyFilter() {
            this.filters.name = document.getElementById('filter-name')?.value.trim() ?? '';
            this.filters.status = document.getElementById('filter-status')?.value ?? '';
            this.dt.ajax.reload();
        },

        initEvents() {
            document.getElementById('btn-refresh')?.addEventListener('click', () => {
                this.dt.ajax.reload(() => this.loadStats(), false);
            });
            document.getElementById('btn-create')?.addEventListener('click', () => this.openCreate());
            document.getElementById('btn-save')?.addEventListener('click', () => this.save());

            document.getElementById('f-code')?.addEventListener('input', e => {
                e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_\-]/g, '');
            });

            document.getElementById('periodModal')
                ?.addEventListener('hide.bs.modal', () => {
                    if (document.activeElement instanceof HTMLElement) {
                        document.activeElement.blur();
                    }
                });

            $(document).on('click', '.btn-edit', e => this.openEdit($(e.currentTarget).data('id')));
            $(document).on('click', '.btn-delete', e => {
                const btn = $(e.currentTarget);
                this.deleteItem(btn.data('id'), btn.data('name'));
            });
            $(document).on('click', '.btn-set-current', e => {
                const btn = $(e.currentTarget);
                this.setCurrentItem(btn.data('id'), btn.data('name'));
            });
            $(document).on('click', '.btn-close-period', e => {
                const btn = $(e.currentTarget);
                this.closePeriodItem(btn.data('id'), btn.data('name'));
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

        fmtDate(d) {
            if (!d) return '<span class="text-muted">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">${dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                    <small class="text-muted">${dt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</small>`;
        },

        fmtDateOnly(d) {
            if (!d) return '<span class="text-muted">—</span>';
            const dt = new Date(d);
            return dt.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        },

        fmtStatus(status) {
            if (!status) return '<span class="text-muted fst-italic">—</span>';

            let statusClass = '';
            let statusIcon = '';

            switch (status.toLowerCase()) {
                case 'open':
                    statusClass = 'open badge badge-phoenix badge-phoenix-success rounded-pill fs-10 p-2 px-2';
                    statusIcon = 'fa-lock-open';
                    break;
                case 'closed':
                    statusClass = 'closed badge badge-phoenix badge-phoenix-danger rounded-pill fs-10 p-2 px-2';
                    statusIcon = 'fa-lock';
                    break;
                default:
                    statusClass = 'open badge badge-phoenix badge-phoenix-info rounded-pill fs-10 p-2 px-2';
                    statusIcon = 'fa-lock-open';
            }

            return `<span class="badge-status ${statusClass}">
                <span class="fas ${statusIcon}"></span>
                ${this.e(status)}
            </span>`;
        },

        fmtUser(name, employeeName = null) {
            if (!name && !employeeName) return '<span class="text-muted fst-italic">—</span>';

            if (!employeeName) {
                return `<span class="badge badge-phoenix badge-phoenix-info rounded-pill fs-10 p-1 px-2" title="Username: ${this.e(name)}">
                    <span class="fas fa-user-circle me-1"></span>${this.e(name)}
                </span>`;
            }

            return `<span class="badge badge-phoenix badge-phoenix-primary rounded-pill fs-10 p-1 px-3"
                 title="Karyawan: ${this.e(employeeName)}&#013;Username: ${this.e(name)}"
                 style="cursor:help;border-radius:50px;display:inline-flex;align-items:center;gap:0.3rem;">
                <span class="fas fa-user me-1"></span>
                ${this.e(employeeName)}
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
    };

    $(document).ready(() => Period.init());
</script>
<?= $this->endSection() ?>