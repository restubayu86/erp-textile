<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    body {
        overflow-x: hidden;
    }

    /* ── Stat Cards ───────────────────────────────────────────── */
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

    /* ── Badges ───────────────────────────────────────────────── */
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

    .badge-status.draft {
        background: rgba(var(--phoenix-warning-rgb), .12);
        color: var(--phoenix-warning);
        border: 1px solid rgba(var(--phoenix-warning-rgb), .25);
    }

    .badge-status.archived {
        background: rgba(var(--phoenix-secondary-rgb), .12);
        color: var(--phoenix-secondary);
        border: 1px solid rgba(var(--phoenix-secondary-rgb), .25);
    }

    .badge-status .fas {
        font-size: .7rem;
        flex-shrink: 0;
    }

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
        max-width: 130px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .badge-level {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        font-size: .68rem;
        font-weight: 700;
        background: rgba(var(--phoenix-primary-rgb), .1);
        color: var(--phoenix-primary);
        border: 1px solid rgba(var(--phoenix-primary-rgb), .2);
        flex-shrink: 0;
    }

    /* ── Filter toggle ────────────────────────────────────────── */
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

    /* ── DataTables layout ────────────────────────────────────── */
    #position-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #position-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: .375rem 1rem;
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

    #position-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #position-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

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
        margin: 0 .5rem;
        border-radius: .375rem;
    }

    #position-table_wrapper .dataTables_paginate .paginate_button {
        padding: .375rem .75rem;
        margin: 0 .25rem;
        border-radius: .375rem;
    }

    #position-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #position-table {
        width: 100% !important;
    }

    /* ── Print ────────────────────────────────────────────────── */
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

    /* ── Select2 ──────────────────────────────────────────────── */
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
                            <li class="breadcrumb-item active"><?= esc((string) $crumb['name']) ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= esc((string) $crumb['name']) ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <h1 class="h3 mb-1 fw-bold"><?= esc((string) $page_title) ?></h1>
            <p class="text-body-tertiary mb-0"><?= esc((string) $page_description) ?></p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4 no-print">
        <?php foreach (
            [
                ['id' => 'stat-total',    'label' => 'Total',    'icon' => 'fa-briefcase',    'color' => 'primary'],
                ['id' => 'stat-active',   'label' => 'Active',   'icon' => 'fa-check-circle', 'color' => 'success'],
                ['id' => 'stat-draft',    'label' => 'Draft',    'icon' => 'fa-pencil-alt',   'color' => 'warning'],
                ['id' => 'stat-archived', 'label' => 'Archived', 'icon' => 'fa-archive',      'color' => 'secondary'],
            ] as $s
        ): ?>
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
                    <th>Lv</th>
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
                <select class="form-select form-select-sm" id="filter-department" style="width:100%"></select>
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
            <button class="btn btn-primary btn-sm" id="btn-apply-filter"><span class="fas fa-search me-1"></span>Terapkan</button>
            <button class="btn btn-subtle-secondary btn-sm" id="btn-reset-filter"><span class="fas fa-times me-1"></span>Reset</button>
        </div>
    </div>
</div>

<!-- Modal Tambah / Edit -->
<div class="modal fade" id="positionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
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

                            <!-- Nama Posisi -->
                            <div class="col-md-8">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-name">
                                    Nama Posisi <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="f-name"
                                    placeholder="cth: Kepala Dyeing" maxlength="100" autocomplete="off">
                                <div class="invalid-feedback" id="err-name"></div>
                            </div>
                            <!-- Kode -->
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-code">
                                    Kode <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm font-monospace fw-bold"
                                    id="f-code" placeholder="cth: KDY" maxlength="50" autocomplete="off"
                                    style="text-transform:uppercase;letter-spacing:.06em">
                                <div class="form-text fs-10">Maks. 50 karakter</div>
                                <div class="invalid-feedback" id="err-code"></div>
                            </div>
                            <!-- Level -->
                            <div class="col-md-3">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-level">
                                    Level
                                </label>
                                <input type="number" class="form-control form-control-sm text-center"
                                    id="f-level" placeholder="—" min="1" max="99">
                                <div class="form-text fs-10">1 = tertinggi</div>
                                <div class="invalid-feedback" id="err-level"></div>
                            </div>


                            <!-- Status -->
                            <div class="col-md-4">
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

                            <!-- Departemen -->
                            <div class="col-5">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-department">
                                    Departemen
                                </label>
                                <select class="form-select form-select-sm" id="f-department" style="width:100%">
                                    <option value="">Pilih Departemen</option>
                                </select>
                                <div class="invalid-feedback" id="err-department"></div>
                            </div>

                            <!-- Deskripsi -->
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
    const CAN_EDIT = <?= json_encode(canDo('hrm.positions.edit')) ?>;
    const CAN_DELETE = <?= json_encode(canDo('hrm.positions.delete')) ?>;

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

        /* ── CSRF ─────────────────────────────────────────────────── */
        csrfName: () => document.querySelector('meta[name="csrf-name"]')?.content ?? '',
        csrfToken: () => document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        updateCsrf(h) {
            const m = document.querySelector('meta[name="csrf-token"]');
            if (m && h) m.content = h;
        },

        /* ── HTTP ─────────────────────────────────────────────────── */
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
                console.error('loadStats:', e);
            }
        },

        /* ── Select2 ──────────────────────────────────────────────── */
        initSelect2() {
            $('#f-department').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '— Pilih Departemen —',
                allowClear: true,
                dropdownParent: $('#positionModal'),
                ajax: {
                    url: this.BASE + 'hrm/departments/select2',
                    dataType: 'json',
                    delay: 250,
                    data: p => ({
                        search: p.term || ''
                    }),
                    processResults: d => ({
                        results: (d.data ?? []).map(r => ({
                            id: r.id,
                            text: `${r.code} - ${r.name}`
                        }))
                    }),
                    cache: true,
                },
                minimumInputLength: 0,
            });

            $('#filter-department').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '— Semua Departemen —',
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
                                text: '— Semua Departemen —'
                            },
                            ...(d.data ?? []).map(r => ({
                                id: r.id,
                                text: `${r.code} - ${r.name}`
                            }))
                        ]
                    }),
                    cache: true,
                },
                minimumInputLength: 0,
            });
        },

        /* ── DataTable ────────────────────────────────────────────── */
        initDatatable() {
            const self = this;
            this.dt = $('#position-table').DataTable({
                scrollX: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [
                    [-1, 10, 25, 50, 100],
                    ['Semua', 10, 25, 50, 100]
                ],
                order: [
                    [1, 'asc'],
                    [2, 'asc']
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
                    data: d => {
                        d.filter_name = self.filters.name;
                        d.filter_department = self.filters.department;
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
                        width: '45px',
                        className: 'text-center'
                    },
                    {
                        targets: 2,
                        width: '200px'
                    },
                    {
                        targets: 3,
                        width: '220px'
                    },
                    {
                        targets: 4,
                        width: '140px'
                    },
                    {
                        targets: 5,
                        width: '100px'
                    },
                    {
                        targets: 6,
                        width: '110px'
                    },
                    {
                        targets: 7,
                        width: '120px'
                    },
                    {
                        targets: 8,
                        width: '110px'
                    },
                    {
                        targets: 9,
                        width: '120px'
                    },
                    {
                        targets: 10,
                        width: '80px'
                    },
                ],
                columns: [
                    /* 0  No     */
                    {
                        data: 'no',
                        orderable: false,
                        searchable: false
                    },
                    /* 1  Level  */
                    {
                        data: 'position_level',
                        render: d => d ?
                            `<span class="badge-level" title="Level ${d}">${d}</span>` : '<span class="text-muted">—</span>'
                    },
                    /* 2  Posisi */
                    {
                        data: null,
                        render: (d, t, r) =>
                            `<div class="fw-semibold">${self.e(r.position_name)}</div>
                             <div class="text-muted small font-monospace">${self.e(r.position_code)}</div>`
                    },
                    /* 3  Desk.  */
                    {
                        data: 'description',
                        render: d => d ?
                            `<span class="text-muted">${self.e(d.substring(0, 60))}${d.length > 60 ? '…' : ''}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    /* 4  Dept   */
                    {
                        data: 'department_name',
                        render: d => d ?
                            `<span class="badge-dept"><span class="fas fa-building me-1"></span>${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    /* 5  Status */
                    {
                        data: 'status',
                        render: d => self.fmtStatus(d)
                    },
                    /* 6  Cr.at  */
                    {
                        data: 'created_at',
                        render: d => self.fmtDate(d)
                    },
                    /* 7  Cr.by  */
                    {
                        data: 'created_by_name',
                        render: (d, t, r) => self._fmtUser(d, r.created_by_employee),
                        orderable: false
                    },
                    /* 8  Up.at  */
                    {
                        data: 'updated_at',
                        render: d => self.fmtDate(d)
                    },
                    /* 9  Up.by  */
                    {
                        data: 'updated_by_name',
                        render: (d, t, r) => self._fmtUser(d, r.updated_by_employee),
                        orderable: false
                    },
                    /* 10 Aksi   */
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render: (d, t, r) => {
                            const edit = CAN_EDIT ?
                                `<button class="btn btn-subtle-primary btn-sm btn-edit" data-id="${r.id}" title="Edit">
                                       <span class="fas fa-pencil-alt"></span>
                                   </button>` : '';
                            const del = CAN_DELETE ?
                                `<button class="btn btn-subtle-danger btn-sm btn-delete" data-id="${r.id}" data-name="${self.e(r.position_name)}" title="Hapus">
                                       <span class="fas fa-trash"></span>
                                   </button>` : '';
                            return `<div class="btn-group btn-group-sm">${edit}${del}</div>`;
                        }
                    },
                ],
            });
        },

        /* ── Modal ────────────────────────────────────────────────── */
        openCreate() {
            this.editId = null;
            this._resetModal();
            document.getElementById('modal-title').textContent = 'Tambah Posisi';
            document.getElementById('modal-subtitle').textContent = 'Buat posisi/jabatan baru';
            document.getElementById('save-text').textContent = 'Simpan';
            new bootstrap.Modal(document.getElementById('positionModal')).show();
        },

        async openEdit(id) {
            this.editId = id;
            this._resetModal();
            document.getElementById('modal-title').textContent = 'Edit Posisi';
            document.getElementById('modal-subtitle').textContent = 'Perbarui data posisi';
            document.getElementById('save-text').textContent = 'Update';
            this._setLoading(true);
            new bootstrap.Modal(document.getElementById('positionModal')).show();

            try {
                const d = await this.get(this.BASE + `hrm/positions/get/${id}`);
                if (d.status !== 'success' || !d.data) {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    bootstrap.Modal.getInstance(document.getElementById('positionModal'))?.hide();
                    return;
                }

                const data = d.data;
                document.getElementById('f-name').value = data.position_name ?? '';
                document.getElementById('f-code').value = data.position_code ?? '';
                document.getElementById('f-level').value = data.position_level ?? '';
                document.getElementById('f-desc').value = data.description ?? '';
                document.getElementById('f-status').value = data.status ?? 'Draft';
                document.getElementById('char-count').textContent = (data.description ?? '').length;

                if (data.department_id) {
                    $('#f-department')
                        .append(new Option(data.department_name || data.department_id, data.department_id, true, true))
                        .trigger('change');
                }

                // ── Tandai is-valid setelah populate ─────────────────
                if (data.position_name) document.getElementById('f-name').classList.add('is-valid');
                if (data.position_code) document.getElementById('f-code').classList.add('is-valid');
                if (data.status) document.getElementById('f-status').classList.add('is-valid');
                if (data.position_level) document.getElementById('f-level').classList.add('is-valid');
                if (data.department_id) document.getElementById('f-department').classList.add('is-valid');
                // ─────────────────────────────────────────────────────

            } catch (e) {
                this.toast('error', 'Gagal memuat data');
            } finally {
                this._setLoading(false);
            }
        },

        async save() {
            this._clearErrors();

            const fd = new FormData();
            fd.set('position_name', document.getElementById('f-name').value.trim());
            fd.set('position_code', document.getElementById('f-code').value.trim().toUpperCase());
            fd.set('position_level', document.getElementById('f-level').value.trim());
            fd.set('department_id', $('#f-department').val() || '');
            fd.set('description', document.getElementById('f-desc').value.trim());
            fd.set('status', document.getElementById('f-status').value);
            if (this.editId) fd.set('id', this.editId);

            this._setLoading(true);
            try {
                const res = await this.post(this.BASE + 'hrm/positions/store', fd);

                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('positionModal'))?.hide();
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else if (res.errors) {
                    this._showErrors(res.errors);
                } else {
                    document.getElementById('modal-alert').classList.remove('d-none');
                    document.getElementById('modal-alert-text').textContent = res.message ?? 'Terjadi kesalahan';
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                this._setLoading(false);
            }
        },

        /* ── Delete ───────────────────────────────────────────────── */
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

        /* ── Filter ───────────────────────────────────────────────── */
        applyFilter() {
            this.filters.name = document.getElementById('filter-name').value.trim();
            this.filters.department = $('#filter-department').val() || '';
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
            document.getElementById('filter-status').value = '';
            $('#filter-department').val(null).trigger('change');
            this.dt.ajax.reload();
            this.updateFilterUI();
        },

        updateFilterUI() {
            const f = this.filters;
            const labels = [];
            if (f.name) labels.push(`Nama: "${f.name}"`);
            if (f.department) labels.push('Departemen terpilih');
            if (f.status) labels.push(`Status: ${f.status}`);
            document.getElementById('filter-toggle').classList.toggle('has-filter', labels.length > 0);
            document.getElementById('filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('filter-summary').classList.toggle('d-none', labels.length === 0);
        },

        /* ── Events ───────────────────────────────────────────────── */
        initEvents() {
            document.getElementById('btn-refresh')
                .addEventListener('click', () => this.dt.ajax.reload(() => this.loadStats(), false));
            document.getElementById('btn-create')
                ?.addEventListener('click', () => this.openCreate());
            document.getElementById('btn-save')
                .addEventListener('click', () => this.save());
            document.getElementById('btn-apply-filter')
                .addEventListener('click', () => this.applyFilter());
            document.getElementById('btn-reset-filter')
                .addEventListener('click', () => this.resetFilter());
            document.getElementById('filter-name')
                .addEventListener('keypress', e => {
                    if (e.key === 'Enter') this.applyFilter();
                });

            document.getElementById('f-desc')
                ?.addEventListener('input', e => {
                    document.getElementById('char-count').textContent = e.target.value.length;
                });
            document.getElementById('f-code')
                ?.addEventListener('input', e => {
                    e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_\-]/g, '');
                });

            // ── Fix aria-hidden focus warning ────────────────────────
            document.getElementById('positionModal')
                ?.addEventListener('hide.bs.modal', () => {
                    if (document.activeElement instanceof HTMLElement) {
                        document.activeElement.blur();
                    }
                });
            // ─────────────────────────────────────────────────────────

            // ── Live field validation ────────────────────────────────
            this._initFieldEvents();
            // ─────────────────────────────────────────────────────────

            $(document).on('click', '.btn-edit', e => Position.openEdit($(e.currentTarget).data('id')));
            $(document).on('click', '.btn-delete', e => {
                const btn = $(e.currentTarget);
                Position.deleteItem(btn.data('id'), btn.data('name'));
            });
        },

        /* ── Private helpers ──────────────────────────────────────── */
        _resetModal() {
            ['f-name', 'f-code', 'f-desc', 'f-level'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.classList.remove('is-invalid', 'is-valid');
                }
            });
            document.getElementById('f-status').value = 'Draft';
            document.getElementById('f-status').classList.remove('is-invalid', 'is-valid');
            document.getElementById('char-count').textContent = '0';
            document.getElementById('modal-alert').classList.add('d-none');
            $('#f-department').val(null).trigger('change');
            this._clearErrors();
        },

        _clearErrors() {
            document.querySelectorAll('#positionModal .is-invalid, #positionModal .is-valid')
                .forEach(el => el.classList.remove('is-invalid', 'is-valid'));
            document.querySelectorAll('#positionModal .invalid-feedback')
                .forEach(el => {
                    el.textContent = '';
                    el.style.visibility = '';
                });
            document.getElementById('modal-alert')?.classList.add('d-none');
        },

        _showErrors(errors) {
            const map = {
                position_name: ['f-name', 'err-name'],
                position_code: ['f-code', 'err-code'],
                position_level: ['f-level', 'err-level'],
                department_id: ['f-department', 'err-department'],
                description: ['f-desc', 'err-desc'],
                status: ['f-status', 'err-status'],
            };
            Object.entries(errors).forEach(([f, msg]) => {
                const [inp, err] = map[f] ?? [];
                if (inp) {
                    const el = document.getElementById(inp);
                    if (el) {
                        el.classList.add('is-invalid');
                        el.classList.remove('is-valid');
                    }
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

        _initFieldEvents() {
            [{
                    id: 'f-name',
                    required: true
                },
                {
                    id: 'f-code',
                    required: true
                },
                {
                    id: 'f-status',
                    required: true
                },
                {
                    id: 'f-level',
                    required: false,
                    numeric: true,
                    min: 1,
                    max: 99
                },
                {
                    id: 'f-desc',
                    required: false
                },
            ].forEach(({
                id,
                required,
                numeric,
                min,
                max
            }) => {
                const el = document.getElementById(id);
                if (!el) return;

                const revalidate = () => {
                    // Jangan aktif sebelum pernah disentuh
                    if (!el.classList.contains('is-invalid') && !el.classList.contains('is-valid')) return;

                    const val = el.value.trim();
                    let ok = true;

                    if (required && val === '') {
                        ok = false;
                    } else if (numeric && val !== '') {
                        const n = Number(val);
                        if (isNaN(n) || n < (min ?? 1) || n > (max ?? 99)) ok = false;
                    }

                    // Optional kosong → netral
                    if (!required && val === '') {
                        el.classList.remove('is-valid', 'is-invalid');
                        const errEl = document.getElementById(id.replace('f-', 'err-'));
                        if (errEl) errEl.textContent = '';
                        return;
                    }

                    el.classList.toggle('is-valid', ok);
                    el.classList.toggle('is-invalid', !ok);
                    const errEl = document.getElementById(id.replace('f-', 'err-'));
                    if (errEl && ok) errEl.textContent = '';
                };

                el.addEventListener('input', revalidate);
                el.addEventListener('change', revalidate);
            });

            // Select2 f-department — opsional
            $('#f-department')
                .on('select2:select', () => {
                    const el = document.getElementById('f-department');
                    if (el) {
                        el.classList.remove('is-invalid');
                        el.classList.add('is-valid');
                    }
                })
                .on('select2:clear', () => {
                    document.getElementById('f-department')?.classList.remove('is-valid', 'is-invalid');
                });
        },

        _setLoading(on) {
            const btn = document.getElementById('btn-save');
            const ico = document.getElementById('save-icon');
            if (btn) btn.disabled = on;
            if (ico) ico.className = on ? 'spinner-border spinner-border-sm me-1' : 'fas fa-save me-1';
        },

        /* ── Formatters ───────────────────────────────────────────── */
        fmtStatus(status) {
            if (!status) return '<span class="text-muted fst-italic">—</span>';
            const map = {
                active: {
                    cls: 'active',
                    icon: 'fa-check-circle'
                },
                draft: {
                    cls: 'draft',
                    icon: 'fa-pencil-alt'
                },
                archived: {
                    cls: 'archived',
                    icon: 'fa-archive'
                },
            };
            const s = map[status.toLowerCase()] ?? {
                cls: 'draft',
                icon: 'fa-pencil-alt'
            };
            return `<span class="badge-status ${s.cls}"><span class="fas ${s.icon}"></span>${this.e(status)}</span>`;
        },
        /* HTML escape — cegah XSS di semua kolom render */
        _e(s) {
            if (s == null) return '';
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        },
        _fmtUser(name, employeeName = null) {
            if (!name && !employeeName) return '<span class="text-muted fst-italic">—</span>';

            // Jika tidak ada employeeName, tampilkan username saja
            if (!employeeName) {
                return `<span class="badge badge-phoenix badge-phoenix-info fs-10 p-1 px-2" 
                     title="Username: ${this._e(name)}">
                    <span class="fas fa-user-circle me-1"></span>${this._e(name)}
                </span>`;
            }

            // Tampilkan employee name sebagai badge utama, username sebagai badge kecil
            return `<span class="badge badge-phoenix badge-phoenix-primary fs-10 p-1 px-3" 
                 title="Karyawan: ${this._e(employeeName)}&#013;Username: ${this._e(name)}"
                 style="cursor:help;border-radius:50px;display:inline-flex;align-items:center;gap:0.3rem;">
                <span class="fas fa-user me-1"></span>
                ${this._e(employeeName)}
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
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
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

    $(document).ready(() => Position.init());
</script>
<?= $this->endSection() ?>