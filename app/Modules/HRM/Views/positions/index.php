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
        background-color: rgba(var(--phoenix-success-rgb), .12);
        color: var(--phoenix-success);
        border: 1px solid rgba(var(--phoenix-success-rgb), .25);
    }

    .badge-status.draft {
        background-color: rgba(var(--phoenix-warning-rgb), .12);
        color: var(--phoenix-warning);
        border: 1px solid rgba(var(--phoenix-warning-rgb), .25);
    }

    .badge-status.archived {
        background-color: rgba(var(--phoenix-secondary-rgb), .12);
        color: var(--phoenix-secondary);
        border: 1px solid rgba(var(--phoenix-secondary-rgb), .25);
    }

    .badge-status .fas {
        font-size: .7rem;
        flex-shrink: 0;
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
        background-color: rgba(var(--phoenix-info-rgb), .12);
        color: var(--phoenix-info);
        border: 1px solid rgba(var(--phoenix-info-rgb), .25);
        white-space: nowrap;
        max-width: 130px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .badge-user .fa-user-circle {
        font-size: .8rem;
        flex-shrink: 0;
    }

    /* ── Department badge ────────────────────────────────────────── */
    .badge-dept {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .25rem .55rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        background-color: rgba(var(--phoenix-primary-rgb), .12);
        color: var(--phoenix-primary);
        border: 1px solid rgba(var(--phoenix-primary-rgb), .25);
        white-space: nowrap;
        max-width: 130px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .badge-dept .fa-building {
        font-size: .7rem;
        flex-shrink: 0;
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

    /* ── DataTables Custom Layout ────────────────────────────────── */
    #position-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #position-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #position-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    /* Length Menu di KIRI */
    #position-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    /* Pagination di TENGAH */
    #position-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    /* Info di KANAN */
    #position-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #position-table_wrapper .dataTables_filter label,
    #position-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #position-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #position-table_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #position-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #position-table {
        width: 100% !important;
    }

    /* ── Print ───────────────────────────────────────────────────── */
    .print-header {
        display: none;
    }

    /* Select2 custom styling */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 31px;
        font-size: 0.875rem;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 29px;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 29px;
    }

    .select2-dropdown {
        font-size: 0.875rem;
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
                            <li class="breadcrumb-item active"><?= esc((string)(string)$crumb['name']) ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= esc((string)(string)$crumb['name']) ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <h1 class="h3 mb-1 fw-bold"><?= esc((string)(string)$page_title) ?></h1>
            <p class="text-body-tertiary mb-0"><?= esc((string)(string)$page_description) ?></p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4 no-print">
        <?php
        $stats = [
            ['id' => 'stat-total',    'label' => 'Total',    'icon' => 'fa-briefcase',     'color' => 'primary'],
            ['id' => 'stat-active',   'label' => 'Active',   'icon' => 'fa-check-circle', 'color' => 'success'],
            ['id' => 'stat-draft',    'label' => 'Draft',    'icon' => 'fa-pencil-alt',   'color' => 'warning'],
            ['id' => 'stat-archived', 'label' => 'Archived', 'icon' => 'fa-archive',      'color' => 'secondary'],
        ];
        foreach ($stats as $s): ?>
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
            <?php if (canDo('hrm.positions.delete')): ?>
                <a href="<?= site_url('hrm/positions/trash') ?>" class="btn btn-subtle-danger btn-sm">
                    <span class="fas fa-trash-alt me-1"></span>Sampah
                    <span class="badge bg-danger ms-1 d-none" id="trash-badge">0</span>
                </a>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <?php if (canDo('hrm.positions.create')): ?>
                <button class="btn btn-primary btn-sm" id="btn-create" type="button">
                    <span class="fas fa-plus me-1"></span>Tambah Posisi
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Print header -->
    <div class="print-header mb-3">
        <h5 class="fw-bold mb-1">Daftar Posisi</h5>
        <div class="text-muted small">Dicetak: <span id="print-date"></span></div>
        <hr class="my-2">
    </div>

    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="position-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Posisi</th>
                    <th>Deskripsi</th>
                    <th>Departemen</th>
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
                <select class="form-select form-select-sm" id="filter-department" style="width: 100%;">
                    <option value="">Semua Departemen</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Status</label>
                <select class="form-select form-select-sm" id="filter-status">
                    <option value="">Semua</option>
                    <option value="Active">Active</option>
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
<div class="modal fade" id="positionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4" id="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary" id="modal-icon">
                        <span class="fas fa-briefcase"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modal-title">Tambah Posisi</h5>
                        <p class="text-muted fs-10 mb-0" id="modal-subtitle">Buat posisi/jabatan baru</p>
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
                            <span class="fas fa-clipboard-list me-1"></span>Informasi Posisi
                        </p>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-name">
                                    Nama Posisi <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="f-name"
                                    placeholder="cth: Kepala Dyeing" maxlength="100" autocomplete="off">
                                <div class="invalid-feedback" id="err-name"></div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-code">
                                    Kode <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm font-monospace fw-bold"
                                    id="f-code" placeholder="cth: KDY" maxlength="50" autocomplete="off"
                                    style="text-transform:uppercase;letter-spacing:.06em">
                                <div class="form-text fs-10">Maks. 50 karakter</div>
                                <div class="invalid-feedback" id="err-code"></div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-department">
                                    Departemen
                                </label>
                                <select class="form-select form-select-sm" id="f-department" style="width: 100%;">
                                    <option value="">Pilih Departemen</option>
                                </select>
                                <div class="invalid-feedback" id="err-department"></div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-status">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-sm" id="f-status">
                                    <option value="Active">Active</option>
                                    <option value="Draft" selected>Draft</option>
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
                                    <div class="invalid-feedback d-block" id="err-desc" style="visibility:hidden">‎</div>
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
    const CAN_EDIT_POSITION = <?= json_encode(canDo('hrm.positions.edit')) ?>;
    const CAN_DELETE_POSITION = <?= json_encode(canDo('hrm.positions.delete')) ?>;

    const Position = {
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
                const d = await this.get(this.BASE + 'hrm/positions/stats');
                if (d.status !== 'success') return;
                document.getElementById('stat-total').textContent = d.data.total ?? 0;
                document.getElementById('stat-active').textContent = d.data.active ?? 0;
                document.getElementById('stat-draft').textContent = d.data.draft ?? 0;
                document.getElementById('stat-archived').textContent = d.data.archived ?? 0;
                const badge = document.getElementById('trash-badge');
                if (badge) {
                    badge.textContent = d.data.trash ?? 0;
                    badge.classList.toggle('d-none', !d.data.trash);
                }
            } catch (e) {
                console.error('Error loading stats:', e);
            }
        },

        initSelect2() {
            // Select2 untuk form modal
            $('#f-department').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Departemen',
                allowClear: true,
                dropdownParent: $('#positionModal'),
                ajax: {
                    url: this.BASE + 'hrm/departments/select2',
                    dataType: 'json',
                    delay: 250,
                    data: (params) => ({
                        search: params.term || ''
                    }),
                    processResults: (data) => ({
                        results: data.data.map(item => ({
                            id: item.id,
                            text: `${item.code} - ${item.name}`
                        }))
                    }),
                    cache: true
                },
                minimumInputLength: 0,
                templateResult: (item) => {
                    if (item.loading) return item.text;
                    return $(`<div>${item.text}</div>`);
                },
                templateSelection: (item) => item.text || 'Pilih Departemen'
            });

            // Select2 untuk filter offcanvas
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
                    data: (params) => ({
                        search: params.term || ''
                    }),
                    processResults: (data) => ({
                        results: [{
                            id: '',
                            text: 'Semua Departemen'
                        }, ...data.data.map(item => ({
                            id: item.id,
                            text: `${item.code} - ${item.name}`
                        }))]
                    }),
                    cache: true
                },
                minimumInputLength: 0,
                templateResult: (item) => {
                    if (item.loading) return item.text;
                    if (item.id === '') return $(`<div class="fw-bold">${item.text}</div>`);
                    return $(`<div>${item.text}</div>`);
                },
                templateSelection: (item) => item.text || 'Semua Departemen'
            });
        },

        initDatatable() {
            const self = this;
            this.dt = $('#position-table').DataTable({
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
                    searchPlaceholder: 'Cari posisi...',
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
                    url: this.BASE + 'hrm/positions/datatables',
                    type: 'GET',
                    data: (d) => {
                        d.filter_name = self.filters.name;
                        d.filter_department = self.filters.department;
                        d.filter_status = self.filters.status;
                    },
                    error: () => self.toast('error', 'Gagal memuat data'),
                },
                columnDefs: [{
                        targets: 0,
                        width: '50px'
                    },
                    {
                        targets: 1,
                        width: '200px'
                    },
                    {
                        targets: 2,
                        width: '250px'
                    },
                    {
                        targets: 3,
                        width: '150px'
                    },
                    {
                        targets: 4,
                        width: '100px'
                    },
                    {
                        targets: 5,
                        width: '120px'
                    },
                    {
                        targets: 6,
                        width: '130px'
                    },
                    {
                        targets: 7,
                        width: '120px'
                    },
                    {
                        targets: 8,
                        width: '130px'
                    },
                    {
                        targets: 9,
                        width: '80px'
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
                            `<div class="fw-semibold">${self.e(r.position_name)}</div>
                             <div class="text-muted small font-monospace">${self.e(r.position_code)}</div>`
                    },
                    {
                        data: 'description',
                        render: (d) => d ?
                            `<span class="text-muted">${self.e(d.substring(0, 60))}${d.length > 60 ? '…' : ''}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'department_name',
                        render: (d) => d ?
                            `<span class="badge-dept"><span class="fas fa-building"></span>${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'status',
                        render: (d) => self.fmtStatus(d)
                    },
                    {
                        data: 'created_at',
                        render: (d) => self.fmtDate(d)
                    },
                    {
                        data: 'created_by_name',
                        render: (d) => self.fmtUser(d)
                    },
                    {
                        data: 'updated_at',
                        render: (d) => self.fmtDate(d)
                    },
                    {
                        data: 'updated_by_name',
                        render: (d) => self.fmtUser(d)
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render: (d, t, r) => {
                            const edit = CAN_EDIT_POSITION ?
                                `<button class="btn btn-subtle-primary btn-sm btn-edit" data-id="${r.id}">
                                    <span class="fas fa-pencil-alt"></span>
                                </button>` : '';
                            const del = CAN_DELETE_POSITION ?
                                `<button class="btn btn-subtle-danger btn-sm btn-delete" data-id="${r.id}" data-name="${self.e(r.position_name)}">
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
            document.getElementById('modal-title').textContent = 'Tambah Posisi';
            document.getElementById('modal-subtitle').textContent = 'Buat posisi/jabatan baru';
            document.getElementById('modal-header').classList.remove('mode-edit');
            document.getElementById('save-text').textContent = 'Simpan';
            $('#f-department').val(null).trigger('change');
            new bootstrap.Modal(document.getElementById('positionModal')).show();
        },

        async openEdit(id) {
            this.editId = id;
            this.resetModal();
            document.getElementById('modal-title').textContent = 'Edit Posisi';
            document.getElementById('modal-subtitle').textContent = 'Perbarui data posisi';
            document.getElementById('modal-header').classList.add('mode-edit');
            document.getElementById('save-text').textContent = 'Update';
            this.setLoading(true);
            new bootstrap.Modal(document.getElementById('positionModal')).show();

            try {
                const d = await this.get(this.BASE + `hrm/positions/get/${id}`);
                if (d.status === 'success' && d.data) {
                    document.getElementById('f-name').value = d.data.position_name ?? '';
                    document.getElementById('f-code').value = d.data.position_code ?? '';
                    document.getElementById('f-desc').value = d.data.description ?? '';
                    document.getElementById('f-status').value = d.data.status ?? 'Draft';
                    document.getElementById('char-count').textContent = (d.data.description ?? '').length;

                    if (d.data.department_id) {
                        const option = new Option(
                            d.data.department_name || d.data.department_id,
                            d.data.department_id,
                            true,
                            true
                        );
                        $('#f-department').append(option).trigger('change');
                    }
                } else {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    bootstrap.Modal.getInstance(document.getElementById('positionModal'))?.hide();
                }
            } catch (error) {
                console.error('Error:', error);
                this.toast('error', 'Gagal memuat data');
            } finally {
                this.setLoading(false);
            }
        },

        async save() {
            this.clearErrors();
            const fd = new FormData();
            fd.set('position_name', document.getElementById('f-name').value.trim());
            fd.set('position_code', document.getElementById('f-code').value.trim().toUpperCase());
            fd.set('department_id', $('#f-department').val() || '');
            fd.set('description', document.getElementById('f-desc').value.trim());
            fd.set('status', document.getElementById('f-status').value);
            if (this.editId) fd.set('id', this.editId);

            this.setLoading(true);
            try {
                const res = await this.post(this.BASE + 'hrm/positions/store', fd);
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('positionModal'))?.hide();
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
            ['f-name', 'f-code', 'f-desc'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.classList.remove('is-invalid');
                }
            });
            $('#f-department').val(null).trigger('change');
            document.getElementById('f-status').value = 'Draft';
            document.getElementById('char-count').textContent = '0';
            document.getElementById('modal-alert').classList.add('d-none');
            this.clearErrors();
        },

        clearErrors() {
            document.querySelectorAll('#positionModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#positionModal .invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.visibility = '';
            });
        },

        showErrors(errors) {
            const map = {
                position_name: ['f-name', 'err-name'],
                position_code: ['f-code', 'err-code'],
                department_id: ['f-department', 'err-department'],
                description: ['f-desc', 'err-desc'],
                status: ['f-status', 'err-status']
            };
            Object.entries(errors).forEach(([f, msg]) => {
                const [inp, err] = map[f] ?? [];
                if (inp) {
                    const el = document.getElementById(inp);
                    if (el) el.classList.add('is-invalid');
                }
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
            if (btn) btn.disabled = on;
            if (ico) ico.className = on ? 'spinner-border spinner-border-sm me-1' : 'fas fa-save me-1';
        },

        async deleteItem(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Posisi?',
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
                const res = await this.post(this.BASE + `hrm/positions/delete/${id}`, new FormData());
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
            this.filters.name = document.getElementById('filter-name')?.value.trim() || '';
            this.filters.department = $('#filter-department').val() || '';
            this.filters.status = document.getElementById('filter-status')?.value || '';
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
            const filterName = document.getElementById('filter-name');
            if (filterName) filterName.value = '';
            $('#filter-department').val(null).trigger('change');
            const filterStatus = document.getElementById('filter-status');
            if (filterStatus) filterStatus.value = '';
            this.dt.ajax.reload();
            this.updateFilterUI();
        },

        updateFilterUI() {
            const labels = [];
            if (this.filters.name) labels.push(`Nama: "${this.filters.name}"`);
            if (this.filters.department) labels.push(`Departemen terpilih`);
            if (this.filters.status) labels.push(`Status: ${this.filters.status}`);

            const toggle = document.getElementById('filter-toggle');
            if (toggle) toggle.classList.toggle('has-filter', labels.length > 0);

            const summaryText = document.getElementById('filter-summary-text');
            if (summaryText) summaryText.textContent = labels.join(' · ');

            const summary = document.getElementById('filter-summary');
            if (summary) summary.classList.toggle('d-none', labels.length === 0);
        },

        initEvents() {
            const refreshBtn = document.getElementById('btn-refresh');
            if (refreshBtn) refreshBtn.addEventListener('click', () => {
                this.dt.ajax.reload(() => this.loadStats(), false);
            });

            const createBtn = document.getElementById('btn-create');
            if (createBtn) createBtn.addEventListener('click', () => this.openCreate());

            const saveBtn = document.getElementById('btn-save');
            if (saveBtn) saveBtn.addEventListener('click', () => this.save());

            const applyFilterBtn = document.getElementById('btn-apply-filter');
            if (applyFilterBtn) applyFilterBtn.addEventListener('click', () => this.applyFilter());

            const resetFilterBtn = document.getElementById('btn-reset-filter');
            if (resetFilterBtn) resetFilterBtn.addEventListener('click', () => this.resetFilter());

            const descField = document.getElementById('f-desc');
            if (descField) descField.addEventListener('input', (e) => {
                const charCount = document.getElementById('char-count');
                if (charCount) charCount.textContent = e.target.value.length;
            });

            const codeField = document.getElementById('f-code');
            if (codeField) codeField.addEventListener('input', (e) => {
                e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_\-]/g, '');
            });

            $(document).on('click', '.btn-edit', (e) => {
                const id = $(e.currentTarget).data('id');
                this.openEdit(id);
            });

            $(document).on('click', '.btn-delete', (e) => {
                const btn = $(e.currentTarget);
                this.deleteItem(btn.data('id'), btn.data('name'));
            });
        },

        fmtStatus(status) {
            if (!status) return '<span class="text-muted fst-italic">—</span>';

            let statusClass = '';
            let statusIcon = '';

            switch (status.toLowerCase()) {
                case 'active':
                    statusClass = 'active';
                    statusIcon = 'fa-check-circle';
                    break;
                case 'draft':
                    statusClass = 'draft';
                    statusIcon = 'fa-pencil-alt';
                    break;
                case 'archived':
                    statusClass = 'archived';
                    statusIcon = 'fa-archive';
                    break;
                default:
                    statusClass = 'draft';
                    statusIcon = 'fa-pencil-alt';
            }

            return `<span class="badge-status ${statusClass}">
                        <span class="fas ${statusIcon}"></span>
                        ${this.e(status)}
                    </span>`;
        },

        fmtUser(name) {
            if (!name) return '<span class="text-muted fst-italic">—</span>';
            return `<span class="badge-user">
                        <span class="fas fa-user-circle"></span>
                        ${this.e(name)}
                    </span>`;
        },

        fmtDate(d) {
            if (!d) return '<span class="text-muted">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">${dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                    <small class="text-muted">${dt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</small>`;
        },

        e(s) {
            if (!s) return '';
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
                timer: type === 'success' ? 2000 : 3500,
                timerProgressBar: true
            });
        },
    };

    $(document).ready(() => Position.init());
</script>
<?= $this->endSection() ?>