<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    /* ── Stat Cards ──────────────────────────────────────────────── */
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

    /* ── Filter toggle ───────────────────────────────────────────── */
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

    /* ── DataTables ──────────────────────────────────────────────── */
    #machine-table_wrapper {
        overflow-x: hidden;
    }

    #machine-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #machine-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #machine-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #machine-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #machine-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #machine-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #machine-table_wrapper .dataTables_filter label,
    #machine-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #machine-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #machine-table_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #machine-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #machine-table {
        width: 100% !important;
    }

    /* ── User badge ──────────────────────────────────────────────── */
    .badge-user {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .7rem;
        font-weight: 600;
        padding: .25rem .55rem;
        border-radius: 20px;
        border: 1px solid transparent;
    }

    .badge-user .fas {
        font-size: .65rem;
        opacity: .8;
    }

    /* ── Print ───────────────────────────────────────────────────── */
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
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
    }

    .select2-container .select2-selection--single {
        height: calc(1.5em + 1.1rem + 2px) !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

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
        ['id' => 'stat-total',       'label' => 'Total',       'icon' => 'fa-cogs',          'color' => 'primary'],
        ['id' => 'stat-active',      'label' => 'Active',      'icon' => 'fa-check-circle',  'color' => 'success'],
        ['id' => 'stat-maintenance', 'label' => 'Maintenance', 'icon' => 'fa-tools',          'color' => 'info'],
        ['id' => 'stat-draft',       'label' => 'Draft',       'icon' => 'fa-pencil-alt',     'color' => 'warning'],
        ['id' => 'stat-archived',    'label' => 'Archived',    'icon' => 'fa-archive',        'color' => 'secondary'],
    ];
    foreach ($stats as $s): ?>
        <div class="col-md col-6">
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
        <?php if (canDo('production.machines.delete')): ?>
            <a href="<?= site_url('production/machines/trash') ?>" class="btn btn-subtle-danger btn-sm">
                <span class="fas fa-trash-alt me-1"></span>Sampah
                <span class="badge bg-danger ms-1 d-none" id="trash-badge">0</span>
            </a>
        <?php endif; ?>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh" type="button">
            <span class="fas fa-sync-alt me-1"></span>Refresh
        </button>
        <?php if (canDo('production.machines.create')): ?>
            <button class="btn btn-primary btn-sm" id="btn-create" type="button">
                <span class="fas fa-plus me-1"></span>Tambah Mesin
            </button>
        <?php endif; ?>
    </div>
</div>

<!-- Print header -->
<div class="print-header mb-3">
    <h5 class="fw-bold mb-1">Daftar Mesin</h5>
    <div class="text-muted small">Dicetak: <span id="print-date"></span></div>
    <hr class="my-2">
</div>

<!-- Table -->
<div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
    <table class="table table-hover fs-9 nowrap align-middle" id="machine-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Mesin</th>
                <th>Departemen</th>
                <th>Kapasitas</th>
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

<!-- Filter toggle -->
<a class="card filter-toggle no-print" href="#filter-offcanvas" data-bs-toggle="offcanvas" id="filter-toggle">
    <div class="card-body">
        <span class="fas fa-filter text-primary"></span>
        <span class="filter-label">Filter</span>
        <span class="filter-dot"></span>
    </div>
</a>

<!-- Filter Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="filter-offcanvas" style="width:300px">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title"><span class="fas fa-filter me-2 text-primary"></span>Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="flex-grow-1">
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Nama / Kode</label>
                <input type="text" class="form-control form-control-sm" id="filter-name" placeholder="Cari...">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Departemen</label>
                <select class="form-select form-select-sm" id="filter-department">
                    <option value="">Semua Departemen</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Status</label>
                <select class="form-select form-select-sm" id="filter-status">
                    <option value="">Semua</option>
                    <option value="Active">Active</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Draft">Draft</option>
                    <option value="Archived">Archived</option>
                </select>
            </div>
        </div>
        <div id="filter-summary" class="mb-3 d-none">
            <div class="alert alert-subtle-info py-2 px-3 mb-0 fs-10">
                <span class="fas fa-info-circle me-1"></span>
                <span id="filter-summary-text"></span>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary btn-sm" id="btn-apply-filter">
                <span class="fas fa-search me-1"></span>Terapkan
            </button>
            <button class="btn btn-subtle-secondary btn-sm" id="btn-reset-filter">
                <span class="fas fa-times me-1"></span>Reset
            </button>
        </div>
    </div>
</div>

<!-- Modal Tambah / Edit -->
<div class="modal fade" id="machineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4" id="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary" id="modal-icon">
                        <span class="fas fa-cogs"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modal-title">Tambah Mesin</h5>
                        <p class="text-muted fs-10 mb-0" id="modal-subtitle">Buat data mesin baru</p>
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
                            <span class="fas fa-clipboard-list me-1"></span>Informasi Mesin
                        </p>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-name">
                                    Nama Mesin <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="f-name"
                                    placeholder="cth: Jet Dyeing 01" maxlength="100" autocomplete="off">
                                <div class="invalid-feedback" id="err-machine_name"></div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-code">
                                    Kode Mesin <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm font-monospace fw-bold"
                                    id="f-code" placeholder="cth: JET-01" maxlength="50" autocomplete="off"
                                    style="text-transform:uppercase;letter-spacing:.06em">
                                <div class="invalid-feedback" id="err-machine_code"></div>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-department">
                                    Departemen
                                </label>
                                <select class="form-select form-select-sm" id="f-department" style="width:100%">
                                    <option value="">— Pilih Departemen —</option>
                                </select>
                                <div class="invalid-feedback" id="err-department_id"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-capacity">
                                    Kapasitas
                                </label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="f-capacity"
                                    placeholder="cth: 500">
                                <div class="invalid-feedback" id="err-capacity"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-capacity-unit">
                                    Satuan
                                </label>
                                <input type="text" class="form-control form-control-sm" id="f-capacity-unit"
                                    placeholder="kg" maxlength="20">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-status">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-sm" id="f-status">
                                    <option value="Active">Active</option>
                                    <option value="Draft" selected>Draft</option>
                                    <option value="Maintenance">Maintenance</option>
                                    <option value="Archived">Archived</option>
                                </select>
                                <div class="invalid-feedback" id="err-status"></div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-desc">
                                    Deskripsi
                                </label>
                                <textarea class="form-control form-control-sm" id="f-desc"
                                    rows="3" maxlength="500" placeholder="Deskripsi singkat..." style="resize:vertical"></textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <div class="invalid-feedback d-block" id="err-description" style="visibility:hidden">‎</div>
                                    <small class="text-muted fs-10 ms-auto"><span id="char-count">0</span>/500</small>
                                </div>
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
    const CAN_EDIT_MACHINE = <?= json_encode(canDo('production.machines.edit')) ?>;
    const CAN_DELETE_MACHINE = <?= json_encode(canDo('production.machines.delete')) ?>;

    const Machine = {
        BASE: '<?= base_url() ?>',
        dt: null,
        editId: null,
        filters: {
            name: '',
            department: '',
            status: ''
        },

        init() {
            this.initSelect2();
            this.initDatatable();
            this.initEvents();
            this.loadStats();
            this.loadDepartmentFilterOptions();
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

        initSelect2() {
            $('#f-department').select2({
                dropdownParent: $('#machineModal'),
                placeholder: '— Pilih Departemen —',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: this.BASE + 'hrm/departments/select2',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: (data.data ?? []).map(d => ({
                            id: d.id,
                            text: d.department ?? d.name
                        }))
                    }),
                },
            });
        },

        async loadDepartmentFilterOptions() {
            try {
                const d = await this.get(this.BASE + 'hrm/departments/select2');
                const sel = document.getElementById('filter-department');
                (d.data ?? []).forEach(dept => {
                    const opt = document.createElement('option');
                    opt.value = dept.id;
                    opt.textContent = dept.department ?? dept.name;
                    sel.appendChild(opt);
                });
            } catch {}
        },

        async loadStats() {
            try {
                const d = await this.get(this.BASE + 'production/machines/stats');
                if (d.status !== 'success') return;
                document.getElementById('stat-total').textContent = d.data.total ?? 0;
                document.getElementById('stat-active').textContent = d.data.active ?? 0;
                document.getElementById('stat-maintenance').textContent = d.data.maintenance ?? 0;
                document.getElementById('stat-draft').textContent = d.data.draft ?? 0;
                document.getElementById('stat-archived').textContent = d.data.archived ?? 0;
                const badge = document.getElementById('trash-badge');
                if (badge) {
                    badge.textContent = d.data.trash ?? 0;
                    badge.classList.toggle('d-none', !d.data.trash);
                }
            } catch {}
        },

        initDatatable() {
            const self = this;
            this.dt = $('#machine-table').DataTable({
                scrollX: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [
                    [-1, 10, 25, 50, 100],
                    ['Semua', 10, 25, 50, 100]
                ],
                order: [
                    [1, 'asc']
                ],
                dom: '<"top"f>rt<"bottom"lpi>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari mesin...',
                    lengthMenu: 'Tampil _MENU_ / halaman',
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
                    url: this.BASE + 'production/machines/datatables',
                    type: 'GET',
                    data: d => {
                        d.filter_name = self.filters.name;
                        d.filter_department = self.filters.department;
                        d.filter_status = self.filters.status;
                    },
                    error: () => self.toast('error', 'Gagal memuat data'),
                },
                columnDefs: [{
                        targets: 0,
                        width: '40px'
                    },
                    {
                        targets: 1,
                        width: '200px'
                    },
                    {
                        targets: 2,
                        width: '140px'
                    },
                    {
                        targets: 3,
                        width: '110px'
                    },
                    {
                        targets: 4,
                        width: '110px'
                    },
                    {
                        targets: 5,
                        width: '110px'
                    },
                    {
                        targets: 6,
                        width: '110px'
                    },
                    {
                        targets: 7,
                        width: '110px'
                    },
                    {
                        targets: 8,
                        width: '110px'
                    },
                    {
                        targets: 9,
                        width: '70px'
                    },
                ],
                columns: [{
                        data: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: null,
                        render: (d, t, r) =>
                            `<div class="fw-semibold">${self.e(r.machine_name)}</div>
                             <div class="text-muted small font-monospace">${self.e(r.machine_code)}</div>`
                    },
                    {
                        data: 'department_name',
                        render: d => d ?
                            `<span class="text-body">${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: null,
                        render: (d, t, r) => (r.capacity !== null && r.capacity !== undefined && r.capacity !== '') ?
                            `<span class="fw-semibold">${self.e(r.capacity)}</span> <span class="text-muted">${self.e(r.capacity_unit ?? '')}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'status',
                        render: d => {
                            const m = {
                                Active: ['badge-phoenix-success', 'fa-check-circle'],
                                Maintenance: ['badge-phoenix-info', 'fa-tools'],
                                Draft: ['badge-phoenix-warning', 'fa-pencil-alt'],
                                Archived: ['badge-phoenix-secondary', 'fa-archive'],
                            };
                            const [cls, ico] = m[d] ?? m.Draft;
                            return `<span class="badge badge-phoenix fs-10 ${cls}">
                                        <span class="badge-label">${d}</span>
                                        <span class="ms-1 fas ${ico}"></span>
                                    </span>`;
                        }
                    },
                    {
                        data: 'created_at',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: 'created_by_name',
                        render: d => self.fmtUser(d, 'created')
                    },
                    {
                        data: 'updated_at',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: 'updated_by_name',
                        render: d => self.fmtUser(d, 'updated')
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render: (d, t, r) => {
                            const edit = CAN_EDIT_MACHINE ?
                                `<button class="btn btn-subtle-primary btn-sm btn-edit" data-id="${r.id}">
                                       <span class="fas fa-pencil-alt"></span>
                                   </button>` : '';
                            const del = CAN_DELETE_MACHINE ?
                                `<button class="btn btn-subtle-danger btn-sm btn-delete"
                                       data-id="${r.id}" data-name="${self.e(r.machine_name)}">
                                       <span class="fas fa-trash"></span>
                                   </button>` : '';
                            return `<div class="btn-group btn-group-sm">${edit}${del}</div>`;
                        }
                    },
                ],
            });
        },

        openCreate() {
            this.editId = null;
            this.resetModal();
            document.getElementById('modal-title').textContent = 'Tambah Mesin';
            document.getElementById('modal-subtitle').textContent = 'Buat data mesin baru';
            document.getElementById('save-text').textContent = 'Simpan';
            new bootstrap.Modal(document.getElementById('machineModal')).show();
        },

        async openEdit(id) {
            this.editId = id;
            this.resetModal();
            document.getElementById('modal-title').textContent = 'Edit Mesin';
            document.getElementById('modal-subtitle').textContent = 'Perbarui data mesin';
            document.getElementById('save-text').textContent = 'Update';
            this.setLoading(true);
            new bootstrap.Modal(document.getElementById('machineModal')).show();
            try {
                const d = await this.get(this.BASE + `production/machines/get/${id}`);
                if (d.status === 'success' && d.data) {
                    document.getElementById('f-name').value = d.data.machine_name ?? '';
                    document.getElementById('f-code').value = d.data.machine_code ?? '';
                    document.getElementById('f-capacity').value = d.data.capacity ?? '';
                    document.getElementById('f-capacity-unit').value = d.data.capacity_unit ?? '';
                    document.getElementById('f-desc').value = d.data.description ?? '';
                    document.getElementById('f-status').value = d.data.status ?? 'Draft';
                    document.getElementById('char-count').textContent = (d.data.description ?? '').length;

                    if (d.data.department_id) {
                        const opt = new Option(d.data.department_name ?? 'Departemen', d.data.department_id, true, true);
                        $('#f-department').append(opt).trigger('change');
                    } else {
                        $('#f-department').val(null).trigger('change');
                    }
                } else {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    bootstrap.Modal.getInstance(document.getElementById('machineModal'))?.hide();
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
            fd.set('machine_name', document.getElementById('f-name').value.trim());
            fd.set('machine_code', document.getElementById('f-code').value.trim().toUpperCase());
            fd.set('department_id', $('#f-department').val() ?? '');
            fd.set('capacity', document.getElementById('f-capacity').value);
            fd.set('capacity_unit', document.getElementById('f-capacity-unit').value.trim());
            fd.set('description', document.getElementById('f-desc').value.trim());
            fd.set('status', document.getElementById('f-status').value);
            if (this.editId) fd.set('id', this.editId);

            this.setLoading(true);
            try {
                const res = await this.post(this.BASE + 'production/machines/store', fd);
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('machineModal'))?.hide();
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
            ['f-name', 'f-code', 'f-capacity', 'f-capacity-unit', 'f-desc'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.classList.remove('is-invalid');
                }
            });
            document.getElementById('f-status').value = 'Draft';
            document.getElementById('char-count').textContent = '0';
            document.getElementById('modal-alert').classList.add('d-none');
            $('#f-department').empty().val(null).trigger('change');
            this.clearErrors();
        },

        clearErrors() {
            document.querySelectorAll('#machineModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#machineModal .invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.visibility = '';
            });
        },

        showErrors(errors) {
            const map = {
                machine_name: ['f-name', 'err-machine_name'],
                machine_code: ['f-code', 'err-machine_code'],
                department_id: ['f-department', 'err-department_id'],
                capacity: ['f-capacity', 'err-capacity'],
                description: ['f-desc', 'err-description'],
                status: ['f-status', 'err-status'],
            };
            Object.entries(errors).forEach(([f, msg]) => {
                const [inp, err] = map[f] ?? [];
                if (inp) document.getElementById(inp)?.classList.add('is-invalid');
                if (err) {
                    const el = document.getElementById(err);
                    if (el) {
                        el.textContent = Array.isArray(msg) ? msg[0] : msg;
                        el.style.visibility = 'visible';
                    }
                }
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
                title: 'Hapus Mesin?',
                html: `<strong>${name}</strong> akan dipindahkan ke sampah.<br>
                       <small class="text-muted">Dapat dipulihkan dari menu Sampah.</small>`,
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
                const res = await this.post(this.BASE + `production/machines/delete/${id}`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        applyFilter() {
            this.filters.name = document.getElementById('filter-name').value.trim();
            this.filters.department = document.getElementById('filter-department').value;
            this.filters.status = document.getElementById('filter-status').value;
            this.dt.ajax.reload();
            this.updateFilterUI();
            bootstrap.Offcanvas.getInstance(document.getElementById('filter-offcanvas'))?.hide();
        },

        resetFilter() {
            this.filters = {
                name: '',
                department: '',
                status: ''
            };
            document.getElementById('filter-name').value = '';
            document.getElementById('filter-department').value = '';
            document.getElementById('filter-status').value = '';
            this.dt.ajax.reload();
            this.updateFilterUI();
        },

        updateFilterUI() {
            const labels = [];
            if (this.filters.name) labels.push(`Nama: "${this.filters.name}"`);
            if (this.filters.department) {
                const sel = document.getElementById('filter-department');
                const txt = sel.options[sel.selectedIndex]?.text;
                if (txt) labels.push(`Dept: ${txt}`);
            }
            if (this.filters.status) labels.push(`Status: ${this.filters.status}`);
            document.getElementById('filter-toggle').classList.toggle('has-filter', labels.length > 0);
            document.getElementById('filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('filter-summary').classList.toggle('d-none', labels.length === 0);
        },

        initEvents() {
            document.getElementById('btn-refresh')?.addEventListener('click', () => {
                this.dt.ajax.reload(() => this.loadStats(), false);
            });
            document.getElementById('btn-create')?.addEventListener('click', () => this.openCreate());
            document.getElementById('btn-save')?.addEventListener('click', () => this.save());
            document.getElementById('btn-apply-filter')?.addEventListener('click', () => this.applyFilter());
            document.getElementById('btn-reset-filter')?.addEventListener('click', () => this.resetFilter());

            document.getElementById('f-desc')?.addEventListener('input', e => {
                document.getElementById('char-count').textContent = e.target.value.length;
            });
            document.getElementById('f-code')?.addEventListener('input', e => {
                e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_\-]/g, '');
            });

            $(document).on('click', '.btn-edit', e => this.openEdit($(e.currentTarget).data('id')));
            $(document).on('click', '.btn-delete', e => {
                const btn = $(e.currentTarget);
                this.deleteItem(btn.data('id'), btn.data('name'));
            });
        },

        e(s) {
            if (s === null || s === undefined) return '';
            return String(s)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        },

        fmtDate(d) {
            if (!d) return '<span class="text-muted">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">${dt.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})}</span>
                    <small class="text-muted">${dt.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</small>`;
        },

        // type: 'created' → primary | 'updated' → info
        fmtUser(d, type = 'created') {
            if (!d) return '<span class="text-muted fst-italic">—</span>';
            const icon = type === 'updated' ? 'fa-user-edit' : 'fa-user-plus';
            const color = type === 'updated' ? 'info' : 'primary';
            return `<span class="badge-user text-${color}"
                         style="background:rgba(var(--phoenix-${color}-rgb),.1);
                                border-color:rgba(var(--phoenix-${color}-rgb),.25);">
                        <span class="fas ${icon}"></span>${this.e(d)}
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
                timerProgressBar: true,
            });
        },
    };

    $(document).ready(() => Machine.init());
</script>
<?= $this->endSection() ?>