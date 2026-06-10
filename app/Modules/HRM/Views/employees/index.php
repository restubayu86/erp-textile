<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    /* ── Prevent horizontal page scrollbar ───────────────────────── */
    body {
        overflow-x: hidden;
    }

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

    /* ── Avatar ──────────────────────────────────────────────────── */
    .emp-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .emp-avatar-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .85rem;
        flex-shrink: 0;
        background: rgba(var(--phoenix-primary-rgb), .1);
        color: var(--phoenix-primary);
    }

    /* ── Gender badge ────────────────────────────────────────────── */
    .badge-gender {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
    }

    .badge-gender.male {
        background: rgba(13, 110, 253, .1);
        color: #0d6efd;
    }

    .badge-gender.female {
        background: rgba(220, 53, 69, .1);
        color: #dc3545;
    }

    /* ── Shift badge ─────────────────────────────────────────────── */
    .badge-shift {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        padding: .2rem .5rem;
        border-radius: 20px;
        font-size: .7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    /* ── Status badge ────────────────────────────────────────────── */
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

    .badge-status.active {
        background: rgba(var(--phoenix-success-rgb), .12);
        color: var(--phoenix-success);
        border: 1px solid rgba(var(--phoenix-success-rgb), .25);
    }

    .badge-status.inactive {
        background: rgba(var(--phoenix-secondary-rgb), .12);
        color: var(--phoenix-secondary);
        border: 1px solid rgba(var(--phoenix-secondary-rgb), .25);
    }

    /* ── User badge ──────────────────────────────────────────────── */
    .badge-user {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .25rem .55rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        background: rgba(var(--phoenix-info-rgb), .12);
        color: var(--phoenix-info);
        border: 1px solid rgba(var(--phoenix-info-rgb), .25);
        white-space: nowrap;
        max-width: 130px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── Dept badge ──────────────────────────────────────────────── */
    .badge-dept {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .25rem .55rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        background: rgba(var(--phoenix-primary-rgb), .12);
        color: var(--phoenix-primary);
        border: 1px solid rgba(var(--phoenix-primary-rgb), .25);
        white-space: nowrap;
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
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

    /* ── DataTables layout ───────────────────────────────────────── */
    #employee-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #employee-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: .375rem 1rem;
        text-align: center;
    }

    #employee-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #employee-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #employee-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #employee-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #employee-table_wrapper .dataTables_filter label,
    #employee-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #employee-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 .5rem;
        border-radius: .375rem;
    }

    #employee-table_wrapper .dataTables_paginate .paginate_button {
        padding: .375rem .75rem;
        margin: 0 .25rem;
        border-radius: .375rem;
    }

    #employee-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #employee-table {
        width: 100% !important;
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

    /* ── Select2 ─────────────────────────────────────────────────── */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 31px;
        font-size: .875rem;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 29px;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 29px;
    }

    .select2-dropdown {
        font-size: .875rem;
    }

    .btn-group-sm .btn {
        padding: .25rem .5rem;
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
                            <li class="breadcrumb-item active"><?= esc((string)(string) $crumb['name']) ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= esc((string)(string) $crumb['name']) ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <h1 class="h3 mb-1 fw-bold"><?= esc((string)(string) $page_title) ?></h1>
            <p class="text-body-tertiary mb-0"><?= esc((string)(string) $page_description) ?></p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4 no-print">
        <?php
        $statCards = [
            ['id' => 'stat-total',    'label' => 'Total Karyawan', 'icon' => 'fa-users',       'color' => 'primary'],
            ['id' => 'stat-active',   'label' => 'Aktif',          'icon' => 'fa-check-circle', 'color' => 'success'],
            ['id' => 'stat-male',     'label' => 'Laki-laki',      'icon' => 'fa-mars',         'color' => 'info'],
            ['id' => 'stat-female',   'label' => 'Perempuan',      'icon' => 'fa-venus',        'color' => 'danger'],
        ];
        foreach ($statCards as $s): ?>
            <div class="col-md-3 col-6">
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
            <?php if (canDo('hrm.employees.delete')): ?>
                <a href="<?= site_url('hrm/employees/trash') ?>" class="btn btn-subtle-danger btn-sm">
                    <span class="fas fa-trash-alt me-1"></span>Sampah
                    <span class="badge bg-danger ms-1 d-none" id="trash-badge">0</span>
                </a>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <?php if (canDo('hrm.employees.create')): ?>
                <a href="<?= site_url('hrm/employees/create') ?>" class="btn btn-primary btn-sm">
                    <span class="fas fa-plus me-1"></span>Tambah Karyawan
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Print header -->
    <div class="print-header mb-3">
        <h5 class="fw-bold mb-1">Daftar Karyawan</h5>
        <div class="text-muted small">Dicetak: <span id="print-date"></span></div>
        <hr class="my-2">
    </div>
    <!-- Group By -->
    <div class="d-flex align-items-center gap-2 mb-3 no-print flex-wrap">
        <span class="text-muted fw-semibold" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Group:</span>
        <?php
        $groups = [
            'none'       => 'Tidak Ada',
            'department' => 'Departemen',
            'position'   => 'Posisi',
            'shift'      => 'Shift',
            'gender'     => 'Jenis Kelamin',
        ];
        foreach ($groups as $key => $label): ?>
            <button class="btn btn-sm group-by-btn <?= $key === 'none' ? 'btn-primary' : 'btn-subtle-secondary' ?>"
                data-group="<?= $key ?>">
                <?= $label ?>
            </button>
        <?php endforeach; ?>
    </div>
    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="employee-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Karyawan</th>
                    <th>JK</th>
                    <th>Posisi</th>
                    <th>Departemen</th>
                    <th>Area Kerja</th>
                    <th>Shift</th>
                    <th>Status Kerja</th>
                    <th>Status</th>
                    <th>Telepon</th>
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
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">NIK / Nama</label>
                <input type="text" class="form-control form-control-sm" id="filter-name" placeholder="Cari NIK atau nama...">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Departemen</label>
                <select class="form-select form-select-sm" id="filter-department" style="width:100%">
                    <option value="">Semua Departemen</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Posisi</label>
                <select class="form-select form-select-sm" id="filter-position" style="width:100%">
                    <option value="">Semua Posisi</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Shift</label>
                <select class="form-select form-select-sm" id="filter-shift">
                    <option value="">Semua Shift</option>
                    <option value="NS">Non Shift</option>
                    <option value="A">Shift A</option>
                    <option value="B">Shift B</option>
                    <option value="C">Shift C</option>
                    <option value="D">Shift D</option>
                    <option value="E">Shift E</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Area Kerja</label>
                <select class="form-select form-select-sm" id="filter-work-area">
                    <option value="">Semua Area</option>
                    <?php foreach ($work_areas as $area): ?>
                        <?php if (!empty($area['work_area'])): ?>
                            <option value="<?= esc((string)$area['work_area']) ?>"><?= esc((string)$area['work_area']) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Status Kerja</label>
                <select class="form-select form-select-sm" id="filter-employment-status">
                    <option value="">Semua</option>
                    <option value="tetap">Tetap</option>
                    <option value="kontrak">Kontrak</option>
                    <option value="magang">Magang</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Status</label>
                <select class="form-select form-select-sm" id="filter-status">
                    <option value="">Semua</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CAN_EDIT = <?= json_encode(canDo('hrm.employees.edit')) ?>;
    const CAN_DELETE = <?= json_encode(canDo('hrm.employees.delete')) ?>;

    const Employee = {
        BASE: '<?= base_url() ?>',
        dt: null,
        filters: {
            name: '',
            department: '',
            position: '',
            shift: '',
            work_area: '',
            employment_status: '',
            status: '',
        },

        init() {
            this.initSelect2();
            this.initDatatable();
            this.initEvents();
            this.loadStats();
            document.getElementById('print-date').textContent = new Date().toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        /* ── CSRF ─────────────────────────────────────────────────── */
        csrfName: () => document.querySelector('meta[name="csrf-name"]')?.content ?? '',
        csrfToken: () => document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        updateCsrf(h) {
            const m = document.querySelector('meta[name="csrf-token"]');
            if (m && h) m.content = h;
        },

        /* ── HTTP helpers ─────────────────────────────────────────── */
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

        /* ── Stats ────────────────────────────────────────────────── */
        async loadStats() {
            try {
                const d = await this.get(this.BASE + 'hrm/employees/stats');
                if (d.status !== 'success') return;
                document.getElementById('stat-total').textContent = d.data.total ?? 0;
                document.getElementById('stat-active').textContent = d.data.active ?? 0;
                document.getElementById('stat-male').textContent = d.data.male ?? 0;
                document.getElementById('stat-female').textContent = d.data.female ?? 0;
                const badge = document.getElementById('trash-badge');
                if (badge) {
                    badge.textContent = d.data.trash ?? 0;
                    badge.classList.toggle('d-none', !d.data.trash);
                }
            } catch (e) {
                console.error('loadStats:', e);
            }
        },

        /* ── Select2 ──────────────────────────────────────────────── */
        initSelect2() {
            // Filter: Departemen
            $('#filter-department').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Semua Departemen',
                allowClear: true,
                dropdownParent: $('#filter-offcanvas'),
                ajax: {
                    url: this.BASE + 'hrm/departments/select2',
                    dataType: 'json',
                    delay: 250,
                    data: p => ({
                        search: p.term || ''
                    }),
                    processResults: d => ({
                        results: [{
                                id: '',
                                text: 'Semua Departemen'
                            },
                            ...(d.data ?? []).map(r => ({
                                id: r.id,
                                text: r.name
                            }))
                        ]
                    }),
                    cache: true
                },
                minimumInputLength: 0,
            });

            // Filter: Posisi
            $('#filter-position').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Semua Posisi',
                allowClear: true,
                dropdownParent: $('#filter-offcanvas'),
                ajax: {
                    url: this.BASE + 'hrm/positions/select2',
                    dataType: 'json',
                    delay: 250,
                    data: p => ({
                        search: p.term || ''
                    }),
                    processResults: d => ({
                        results: [{
                                id: '',
                                text: 'Semua Posisi'
                            },
                            ...(d.data ?? []).map(r => ({
                                id: r.id,
                                text: r.name
                            }))
                        ]
                    }),
                    cache: true
                },
                minimumInputLength: 0,
            });
        },

        /* ── DataTable ────────────────────────────────────────────── */
        initDatatable() {
            const self = this;
            this.dt = $('#employee-table').DataTable({
                scrollX: false,
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
                    searchPlaceholder: 'Cari karyawan...',
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
                rowGroup: {
                    dataSrc: null,
                    enable: true
                },
                ajax: {
                    url: this.BASE + 'hrm/employees/datatables',
                    type: 'GET',
                    data: d => {
                        d.filter_name = self.filters.name;
                        d.filter_department = self.filters.department;
                        d.filter_position = self.filters.position;
                        d.filter_shift = self.filters.shift;
                        d.filter_work_area = self.filters.work_area;
                        d.filter_employment_status = self.filters.employment_status;
                        d.filter_status = self.filters.status;
                    },
                    error: () => self.toast('error', 'Gagal memuat data'),
                },
                columnDefs: [{
                        targets: 0,
                        width: '45px'
                    },
                    {
                        targets: 1,
                        width: '200px'
                    },
                    {
                        targets: 2,
                        width: '40px'
                    },
                    {
                        targets: 3,
                        width: '150px'
                    },
                    {
                        targets: 4,
                        width: '130px'
                    },
                    {
                        targets: 5,
                        width: '110px'
                    },
                    {
                        targets: 6,
                        width: '90px'
                    },
                    {
                        targets: 7,
                        width: '90px'
                    },
                    {
                        targets: 8,
                        width: '85px'
                    },
                    {
                        targets: 9,
                        width: '110px'
                    },
                    {
                        targets: 10,
                        width: '110px'
                    },
                    {
                        targets: 11,
                        width: '110px'
                    },
                    {
                        targets: 12,
                        width: '110px'
                    },
                    {
                        targets: 13,
                        width: '110px'
                    },
                    {
                        targets: 14,
                        width: '80px'
                    },
                ],
                columns: [
                    /* 0  No       */
                    {
                        data: 'no',
                        orderable: false,
                        searchable: false
                    },
                    /* 1  Karyawan */
                    {
                        data: null,
                        orderable: true,
                        render: (d, t, r) => {
                            const initial = (r.fullname || '?')[0].toUpperCase();
                            const avatar = r.photo ?
                                `<img src="${self.e(r.photo)}" class="emp-avatar me-2"
                                       onerror="this.src='<?= base_url('assets/img/avatar-default.png') ?>'">` :
                                `<span class="emp-avatar-placeholder me-2">${self.e(initial)}</span>`;
                            return `<div class="d-flex align-items-center">
                                        ${avatar}
                                        <div>
                                            <div class="fw-semibold">
                                                <a href="${self.BASE}hrm/employees/show/${r.id}"
                                                   class="text-primary text-decoration-none">${self.e(r.fullname)}</a>
                                                ${r.nickname ? `<small class="text-body-quaternary ms-1">${self.e(r.nickname)}</small>` : ''}
                                            </div>
                                            <div class="text-muted small font-monospace">${self.e(r.nik)}</div>
                                        </div>
                                    </div>`;
                        }
                    },
                    /* 2  JK       */
                    {
                        data: 'gender',
                        orderable: true,
                        render: d => {
                            if (d === 'L') return `<span class="badge-gender male" title="Laki-laki"><span class="fas fa-mars"></span></span>`;
                            if (d === 'P') return `<span class="badge-gender female" title="Perempuan"><span class="fas fa-venus"></span></span>`;
                            return '<span class="text-muted">—</span>';
                        }
                    },
                    /* 3  Posisi   */
                    {
                        data: 'position_name',
                        render: d => d ?
                            `<span class="fw-semibold fs-9">${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    /* 4  Dept     */
                    {
                        data: 'department_name',
                        render: d => d ?
                            `<span class="badge-dept"><span class="fas fa-building me-1"></span>${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    /* 5  Area     */
                    {
                        data: 'work_area',
                        render: d => d ?
                            `<span class="text-muted fs-9">${self.e(d)}</span>` : '<span class="text-muted">—</span>'
                    },
                    /* 6  Shift    */
                    {
                        data: 'shift',
                        orderable: true,
                        render: d => {
                            if (!d) return '<span class="text-muted">—</span>';
                            const map = {
                                NS: {
                                    text: 'Non-Shift',
                                    cls: 'badge badge-phoenix badge-phoenix-secondary'
                                },
                                A: {
                                    text: 'Shift A',
                                    cls: 'bg-primary  bg-opacity-10 text-primary  border'
                                },
                                B: {
                                    text: 'Shift B',
                                    cls: 'bg-info     bg-opacity-10 text-info     border'
                                },
                                C: {
                                    text: 'Shift C',
                                    cls: 'bg-success  bg-opacity-10 text-success  border'
                                },
                                D: {
                                    text: 'Shift D',
                                    cls: 'bg-warning  bg-opacity-10 text-warning  border'
                                },
                                E: {
                                    text: 'Shift E',
                                    cls: 'bg-danger   bg-opacity-10 text-danger   border'
                                },
                            };
                            const s = map[d] ?? {
                                text: d,
                                cls: 'bg-secondary bg-opacity-10 text-secondary border'
                            };
                            return `<span class="badge-shift ${s.cls}"><span class="fas fa-clock me-1"></span>${s.text}</span>`;
                        }
                    },
                    /* 7  Emp.status */
                    {
                        data: 'employment_status',
                        render: d => {
                            if (!d) return '<span class="text-muted">—</span>';
                            const map = {
                                tetap: {
                                    text: 'Tetap',
                                    cls: 'bg-success  bg-opacity-10 text-success  border'
                                },
                                kontrak: {
                                    text: 'Kontrak',
                                    cls: 'bg-warning  bg-opacity-10 text-warning  border'
                                },
                                magang: {
                                    text: 'Magang',
                                    cls: 'bg-info     bg-opacity-10 text-info     border'
                                },
                            };
                            const s = map[d] ?? {
                                text: d,
                                cls: 'bg-secondary bg-opacity-10 text-secondary border'
                            };
                            return `<span class="badge ${s.cls} rounded-pill">${s.text}</span>`;
                        }
                    },
                    /* 8  Status   */
                    {
                        data: 'status',
                        render: d => {
                            if (!d) return '—';
                            const cfg = d === 'active' ? {
                                cls: 'active',
                                icon: 'fa-check-circle',
                                label: 'Active'
                            } : {
                                cls: 'inactive',
                                icon: 'fa-times-circle',
                                label: 'Inactive'
                            };
                            return `<span class="badge-status ${cfg.cls}">
                                        <span class="fas ${cfg.icon}"></span>${cfg.label}
                                    </span>`;
                        }
                    },
                    /* 9  Phone    */
                    {
                        data: 'phone',
                        render: d => d ?
                            `<a href="tel:${self.e(d)}" class="text-decoration-none fs-9">${self.e(d)}</a>` : '<span class="text-muted">—</span>'
                    },
                    /* 10 Created  */
                    {
                        data: 'created_at',
                        render: d => self.fmtDate(d)
                    },
                    /* 11 Cr. by   */
                    {
                        data: 'created_by_name',
                        render: d => self.fmtUser(d),
                        orderable: false
                    },
                    /* 12 Updated  */
                    {
                        data: 'updated_at',
                        render: d => self.fmtDate(d)
                    },
                    /* 13 Up. by   */
                    {
                        data: 'updated_by_name',
                        render: d => self.fmtUser(d),
                        orderable: false
                    },
                    /* 14 Aksi     */
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render: (d, t, r) => {
                            const view = `<a href="${self.BASE}hrm/employees/show/${r.id}"
                                             class="btn btn-subtle-info btn-sm" title="Detail">
                                              <span class="fas fa-eye"></span>
                                          </a>`;
                            const edit = CAN_EDIT ?
                                `<a href="${self.BASE}hrm/employees/edit/${r.id}"
                                       class="btn btn-subtle-primary btn-sm" title="Edit">
                                       <span class="fas fa-pencil-alt"></span>
                                   </a>` : '';
                            const del = CAN_DELETE ?
                                `<button class="btn btn-subtle-danger btn-sm btn-delete"
                                           data-id="${r.id}" data-name="${self.e(r.fullname)}" title="Hapus">
                                       <span class="fas fa-trash"></span>
                                   </button>` : '';
                            return `<div class="btn-group btn-group-sm">${view}${edit}${del}</div>`;
                        }
                    },
                ],
            });
        },

        /* ── Filter ───────────────────────────────────────────────── */
        applyFilter() {
            this.filters.name = document.getElementById('filter-name').value.trim();
            this.filters.department = $('#filter-department').val() || '';
            this.filters.position = $('#filter-position').val() || '';
            this.filters.shift = document.getElementById('filter-shift').value;
            this.filters.work_area = document.getElementById('filter-work-area').value;
            this.filters.employment_status = document.getElementById('filter-employment-status').value;
            this.filters.status = document.getElementById('filter-status').value;
            this.dt.ajax.reload();
            this.updateFilterUI();
            bootstrap.Offcanvas.getInstance(document.getElementById('filter-offcanvas'))?.hide();
        },

        resetFilter() {
            this.filters = {
                name: '',
                department: '',
                position: '',
                shift: '',
                work_area: '',
                employment_status: '',
                status: ''
            };
            document.getElementById('filter-name').value = '';
            document.getElementById('filter-shift').value = '';
            document.getElementById('filter-work-area').value = '';
            document.getElementById('filter-employment-status').value = '';
            document.getElementById('filter-status').value = '';
            $('#filter-department').val(null).trigger('change');
            $('#filter-position').val(null).trigger('change');
            this.dt.ajax.reload();
            this.updateFilterUI();
        },

        updateFilterUI() {
            const f = this.filters;
            const labels = [];
            if (f.name) labels.push(`Nama: "${f.name}"`);
            if (f.department) labels.push('Departemen terpilih');
            if (f.position) labels.push('Posisi terpilih');
            if (f.shift) labels.push(`Shift: ${f.shift}`);
            if (f.work_area) labels.push(`Area: ${f.work_area}`);
            if (f.employment_status) labels.push(`Kerja: ${f.employment_status}`);
            if (f.status) labels.push(`Status: ${f.status}`);

            document.getElementById('filter-toggle').classList.toggle('has-filter', labels.length > 0);
            document.getElementById('filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('filter-summary').classList.toggle('d-none', labels.length === 0);
        },

        /* ── Delete ───────────────────────────────────────────────── */
        async deleteItem(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Karyawan?',
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
                const res = await this.post(this.BASE + `hrm/employees/delete/${id}`, new FormData());
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

        /* ── Events ───────────────────────────────────────────────── */
        initEvents() {
            document.getElementById('btn-refresh')
                .addEventListener('click', () => this.dt.ajax.reload(() => this.loadStats(), false));

            document.getElementById('btn-apply-filter')
                .addEventListener('click', () => this.applyFilter());

            document.getElementById('btn-reset-filter')
                .addEventListener('click', () => this.resetFilter());

            document.getElementById('filter-name')
                .addEventListener('keypress', e => {
                    if (e.key === 'Enter') this.applyFilter();
                });

            $(document).on('click', '.btn-delete', e => {
                const btn = $(e.currentTarget);
                this.deleteItem(btn.data('id'), btn.data('name'));
            });

            // Group By
            document.querySelectorAll('.group-by-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.group-by-btn').forEach(b => {
                        b.className = 'btn btn-sm group-by-btn btn-subtle-secondary';
                    });
                    btn.className = 'btn btn-sm group-by-btn btn-primary';

                    const colMap = {
                        department: 4,
                        position: 3,
                        shift: 6,
                        gender: 2
                    };
                    const group = btn.dataset.group;

                    if (group === 'none') {
                        this.dt.rowGroup().disable().draw();
                    } else {
                        this.dt.rowGroup().dataSrc(colMap[group]).enable().draw();
                    }
                });
            });
        },

        /* ── Formatters ───────────────────────────────────────────── */
        fmtDate(d) {
            if (!d) return '<span class="text-muted">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">${dt.toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })}</span>
                    <small class="text-muted">${dt.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' })}</small>`;
        },

        fmtUser(name) {
            if (!name) return '<span class="text-muted fst-italic">—</span>';
            return `<span class="badge-user"><span class="fas fa-user-circle"></span>${this.e(name)}</span>`;
        },

        e(s) {
            if (!s) return '';
            return String(s)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
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

    $(document).ready(() => Employee.init());
</script>
<?= $this->endSection() ?>