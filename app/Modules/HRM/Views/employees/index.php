<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
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

    /* ── Avatar ───────────────────────────────────────────────── */
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

    /* ── Gender badge ─────────────────────────────────────────── */
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

    /* ── Status badge ─────────────────────────────────────────── */
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

    /* ── Dept badge ───────────────────────────────────────────── */
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
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
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

    /* ── RowGroup style ───────────────────────────────────────── */
    table.dataTable tbody tr.dtrg-group td {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        padding: .45rem .75rem !important;
        background: var(--phoenix-secondary-bg) !important;
        color: var(--phoenix-primary);
        border-bottom: 2px solid var(--phoenix-primary) !important;
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

    /* ── Crop modal ───────────────────────────────────────────── */
    .crop-container {
        max-height: 500px;
        overflow: hidden;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-100">

    <!-- ── Page Header ──────────────────────────────────────────── -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <?php foreach ($breadcrumbs as $crumb): ?>
                        <?php if (!empty($crumb['active'])): ?>
                            <li class="breadcrumb-item active"><?= esc((string) $crumb['name']) ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item">
                                <a href="<?= $crumb['url'] ?>"><?= esc((string) $crumb['name']) ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <h1 class="h3 mb-1 fw-bold"><?= esc((string) $page_title) ?></h1>
            <p class="text-body-tertiary mb-0"><?= esc((string) $page_description) ?></p>
        </div>
    </div>

    <!-- ── Stat Cards ───────────────────────────────────────────── -->
    <div class="row g-3 mb-4 no-print">
        <?php $statCards = [
            ['id' => 'stat-total',  'label' => 'Total Karyawan', 'icon' => 'fa-users',       'color' => 'primary'],
            ['id' => 'stat-active', 'label' => 'Aktif',          'icon' => 'fa-check-circle', 'color' => 'success'],
            ['id' => 'stat-male',   'label' => 'Laki-laki',      'icon' => 'fa-mars',         'color' => 'info'],
            ['id' => 'stat-female', 'label' => 'Perempuan',      'icon' => 'fa-venus',        'color' => 'danger'],
        ]; ?>
        <?php foreach ($statCards as $s): ?>
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

    <!-- ── Toolbar ──────────────────────────────────────────────── -->
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
            <div class="btn-group">
                <button type="button" class="btn btn-subtle-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="fas fa-download me-1"></span>Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#" id="btn-export-excel">
                            <span class="fas fa-file-excel text-success me-2"></span>Excel (XLSX)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" id="btn-export-pdf">
                            <span class="fas fa-file-pdf text-danger me-2"></span>PDF
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" id="btn-print">
                            <span class="fas fa-print text-primary me-2"></span>Print
                        </a>
                    </li>
                </ul>
            </div>
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

    <!-- ── Group By ─────────────────────────────────────────────── -->
    <div class="d-flex align-items-center gap-2 mb-3 no-print flex-wrap">
        <span class="text-muted fw-semibold" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Group:</span>
        <?php foreach (
            [
                'none'       => 'Tidak Ada',
                'department' => 'Departemen',
                'position'   => 'Posisi',
                'shift'      => 'Shift',
                'gender'     => 'Jenis Kelamin',
            ] as $key => $label
        ): ?>
            <button
                class="btn btn-sm group-by-btn <?= $key === 'none' ? 'btn-primary' : 'btn-subtle-secondary' ?>"
                data-group="<?= $key ?>"><?= $label ?></button>
        <?php endforeach; ?>
    </div>

    <!-- ── Print header (hanya muncul saat print browser) ──────── -->
    <div class="print-header mb-3">
        <h5 class="fw-bold mb-1">Daftar Karyawan</h5>
        <div class="text-muted small">Dicetak: <span id="print-date"></span></div>
        <hr class="my-2">
    </div>

    <!-- ── Table ────────────────────────────────────────────────── -->
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

</div><!-- /w-100 -->

<!-- ── Filter toggle ────────────────────────────────────────────── -->
<a class="card filter-toggle no-print" href="#filter-offcanvas" data-bs-toggle="offcanvas" id="filter-toggle">
    <div class="card-body">
        <span class="fas fa-filter text-primary"></span>
        <span class="filter-label">Filter</span>
        <span class="filter-dot"></span>
    </div>
</a>

<!-- ── Filter Offcanvas ──────────────────────────────────────────── -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="filter-offcanvas" style="width:300px">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">
            <span class="fas fa-filter me-2 text-primary"></span>Filter
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="flex-grow-1">

            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">NIK / Nama</label>
                <input type="text" class="form-control form-control-sm" id="filter-name"
                    placeholder="Cari NIK atau nama...">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Departemen</label>
                <select class="form-select form-select-sm" id="filter-department" style="width:100%"></select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Posisi</label>
                <select class="form-select form-select-sm" id="filter-position" style="width:100%"></select>
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
                            <option value="<?= esc((string) $area['work_area']) ?>">
                                <?= esc((string) $area['work_area']) ?>
                            </option>
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

<!-- ── Modal Crop Photo ──────────────────────────────────────────── -->
<div class="modal fade" id="modalCropPhoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3">

            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <span class="fas fa-crop-alt"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Crop Foto</h5>
                        <p class="text-muted fs-10 mb-0">Potong dan sesuaikan foto karyawan</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="crop-container">
                    <img id="crop-image" src="" alt="Crop Image" style="max-width:100%;">
                </div>
                <div class="row g-2 mt-3">
                    <div class="col-md-4">
                        <label class="form-label fs-10 fw-semibold text-uppercase text-muted">Aspect Ratio</label>
                        <select class="form-select form-select-sm" id="crop-aspect">
                            <option value="1:1">1:1 (Persegi)</option>
                            <option value="4:3">4:3</option>
                            <option value="3:4">3:4</option>
                            <option value="16:9">16:9</option>
                            <option value="9:16">9:16</option>
                            <option value="free">Free</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-10 fw-semibold text-uppercase text-muted">Rotate</label>
                        <div class="d-flex gap-2">
                            <button class="btn btn-subtle-secondary btn-sm" id="crop-rotate-left" title="Putar kiri">
                                <span class="fas fa-undo"></span>
                            </button>
                            <button class="btn btn-subtle-secondary btn-sm" id="crop-rotate-right" title="Putar kanan">
                                <span class="fas fa-redo"></span>
                            </button>
                            <button class="btn btn-subtle-secondary btn-sm" id="crop-reset" title="Reset">
                                <span class="fas fa-undo-alt"></span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-10 fw-semibold text-uppercase text-muted">Zoom</label>
                        <div class="d-flex gap-2">
                            <button class="btn btn-subtle-secondary btn-sm" id="crop-zoom-out" title="Zoom out">
                                <span class="fas fa-search-minus"></span>
                            </button>
                            <button class="btn btn-subtle-secondary btn-sm" id="crop-zoom-in" title="Zoom in">
                                <span class="fas fa-search-plus"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Batal
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-crop-save">
                    <span class="fas fa-check me-1"></span>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    /* ================================================================
   Konstanta dari PHP
   ================================================================ */
    const CAN_EDIT = <?= json_encode(canDo('hrm.employees.edit')) ?>;
    const CAN_DELETE = <?= json_encode(canDo('hrm.employees.delete')) ?>;

    /* ================================================================
       Employee — objek utama
       ================================================================ */
    const Employee = {
        BASE: '<?= base_url() ?>',

        /* state */
        dt: null,
        currentGroup: 'none',
        cropper: null,
        cropFile: null,
        currentEmployeeId: null,

        filters: {
            name: '',
            department: '',
            position: '',
            shift: '',
            work_area: '',
            employment_status: '',
            status: '',
        },

        /* ── INIT ─────────────────────────────────────────────────── */
        init() {
            this.initSelect2();
            this.initDatatable();
            this.initCropperModal();
            this.initEvents();
            this.loadStats();
            this._setPrintDate();
        },

        _setPrintDate() {
            const el = document.getElementById('print-date');
            if (el) {
                el.textContent = new Date().toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                });
            }
        },

        /* ── CSRF helpers ─────────────────────────────────────────── */
        _csrfName: () => document.querySelector('meta[name="csrf-name"]')?.content ?? '',
        _csrfToken: () => document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        _updateCsrf(hash) {
            const m = document.querySelector('meta[name="csrf-token"]');
            if (m && hash) m.content = hash;
        },

        /* ── HTTP helpers ─────────────────────────────────────────── */
        async _post(url, fd) {
            /* FIX: pakai _csrfName() agar sesuai konfigurasi CI4, bukan hardcoded */
            fd.set(this._csrfName(), this._csrfToken());
            const r = await fetch(url, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this._csrfToken(),
                },
            });
            if (r.status === 403) throw new Error('Sesi habis, muat ulang halaman');
            const d = await r.json();
            if (d?.csrfHash) this._updateCsrf(d.csrfHash);
            return d;
        },

        async _get(url) {
            const r = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            return r.json();
        },

        /* ── Loading overlay ──────────────────────────────────────── */
        _showLoading(msg = 'Memproses...') {
            const el = document.getElementById('loading-overlay');
            const tx = document.getElementById('loading-text');
            if (tx) tx.textContent = msg;
            if (el) el.style.display = 'flex';
        },
        _hideLoading() {
            const el = document.getElementById('loading-overlay');
            if (el) el.style.display = 'none';
        },

        /* ── Stats ────────────────────────────────────────────────── */
        async loadStats() {
            try {
                const d = await this._get(this.BASE + 'hrm/employees/stats');
                if (d.status !== 'success') return;

                document.getElementById('stat-total').textContent = d.data.total ?? 0;
                document.getElementById('stat-active').textContent = d.data.active ?? 0;
                document.getElementById('stat-male').textContent = d.data.male ?? 0;
                document.getElementById('stat-female').textContent = d.data.female ?? 0;

                const badge = document.getElementById('trash-badge');
                if (badge) {
                    badge.textContent = d.data.trash ?? 0;
                    badge.classList.toggle('d-none', !(d.data.trash));
                }
            } catch (e) {
                console.error('loadStats:', e);
            }
        },

        /* ── Select2 ──────────────────────────────────────────────── */
        initSelect2() {
            const base = this.BASE;
            const parent = $('#filter-offcanvas');

            const s2Config = (url, allText) => ({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                dropdownParent: parent,
                minimumInputLength: 0,
                ajax: {
                    url,
                    dataType: 'json',
                    delay: 250,
                    data: p => ({
                        search: p.term || ''
                    }),
                    processResults: d => ({
                        results: [{
                                id: '',
                                text: allText
                            },
                            ...(d.data ?? []).map(r => ({
                                id: r.id,
                                text: r.name
                            })),
                        ],
                    }),
                    cache: true,
                },
            });

            $('#filter-department').select2({
                ...s2Config(base + 'hrm/departments/select2', '— Semua Departemen —'),
                placeholder: 'Semua Departemen',
            });

            $('#filter-position').select2({
                ...s2Config(base + 'hrm/positions/select2', '— Semua Posisi —'),
                placeholder: 'Semua Posisi',
            });
        },

        /* ── DataTable ────────────────────────────────────────────── */
        initDatatable() {
            const self = this;

            this.dt = $('#employee-table').DataTable({
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
                    enable: false,
                    dataSrc: 'department_name',
                    emptyMessage: '—',
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
                        d.group_by = self.currentGroup !== 'none' ? self.currentGroup : '';
                    },
                    error: () => self.toast('error', 'Gagal memuat data'),
                },

                columnDefs: [{
                        targets: 0,
                        width: '45px'
                    },
                    {
                        targets: 1,
                        width: '220px'
                    },
                    {
                        targets: 2,
                        width: '50px'
                    },
                    {
                        targets: 3,
                        width: '150px'
                    },
                    {
                        targets: 4,
                        width: '150px'
                    },
                    {
                        targets: 5,
                        width: '120px'
                    },
                    {
                        targets: 6,
                        width: '90px'
                    },
                    {
                        targets: 7,
                        width: '100px'
                    },
                    {
                        targets: 8,
                        width: '90px'
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
                        width: '100px'
                    },
                ],

                columns: [
                    /* 0  — No */
                    {
                        data: 'no',
                        orderable: false,
                        searchable: false
                    },

                    /* 1  — Karyawan */
                    {
                        data: null,
                        render(d, t, r) {
                            const initial = (r.fullname || '?')[0].toUpperCase();
                            const avatar = r.photo ?
                                `<img src="${self.BASE}uploads/employees/${self._e(r.photo)}"
                                   class="emp-avatar me-2"
                                   onerror="this.onerror=null;this.src='<?= base_url('assets/img/avatar-default.png') ?>';">` :
                                `<span class="emp-avatar-placeholder me-2">${self._e(initial)}</span>`;
                            return `<div class="d-flex align-items-center">${avatar}<div>
                            <div class="fw-semibold">
                                <a href="${self.BASE}hrm/employees/show/${r.id}"
                                   class="text-primary text-decoration-none">${self._e(r.fullname)}</a>
                                ${r.nickname ? `<small class="text-body-quaternary ms-1">(${self._e(r.nickname)})</small>` : ''}
                            </div>
                            <div class="text-muted small font-monospace">${self._e(r.nik)}</div>
                        </div></div>`;
                        },
                    },

                    /* 2  — JK */
                    {
                        data: 'gender',
                        render(d) {
                            if (d === 'L') return `<span class="badge-gender male"  title="Laki-laki"><span class="fas fa-mars"></span></span>`;
                            if (d === 'P') return `<span class="badge-gender female" title="Perempuan"><span class="fas fa-venus"></span></span>`;
                            return '<span class="text-muted">—</span>';
                        },
                    },

                    /* 3  — Posisi */
                    {
                        data: 'position_name',
                        render(d) {
                            if (!d) return '<span class="text-muted fst-italic">—</span>';
                            const isManager = /manager|manajer|kadiv|direktur/i.test(d);
                            const star = isManager ? `<span class="fas fa-star text-warning me-1"></span>` : '';
                            return `<span class="fw-semibold fs-9">${star}${self._e(d)}</span>`;
                        },
                    },

                    /* 4  — Departemen */
                    {
                        data: 'department_name',
                        render(d) {
                            return d && d !== '—' ?
                                `<span class="badge-dept"><span class="fas fa-building me-1"></span>${self._e(d)}</span>` :
                                '<span class="text-muted fst-italic">—</span>';
                        },
                    },

                    /* 5  — Area Kerja */
                    {
                        data: 'work_area',
                        render: d => d ?
                            `<span class="text-muted fs-9">${self._e(d)}</span>` : '<span class="text-muted">—</span>',
                    },

                    /* 6  — Shift */
                    {
                        data: 'shift',
                        render(d) {
                            if (!d) return '<span class="text-muted">—</span>';
                            const map = {
                                NS: {
                                    t: 'Non-Shift',
                                    c: 'badge-phoenix-secondary'
                                },
                                A: {
                                    t: 'Shift A',
                                    c: 'badge-phoenix-primary'
                                },
                                B: {
                                    t: 'Shift B',
                                    c: 'badge-phoenix-info'
                                },
                                C: {
                                    t: 'Shift C',
                                    c: 'badge-phoenix-success'
                                },
                                D: {
                                    t: 'Shift D',
                                    c: 'badge-phoenix-warning'
                                },
                                E: {
                                    t: 'Shift E',
                                    c: 'badge-phoenix-danger'
                                },
                            };
                            const s = map[d] ?? {
                                t: d,
                                c: 'badge-phoenix-secondary'
                            };
                            return `<span class="badge badge-phoenix rounded-pill p-2 fs-10 ${s.c}">
                                    <span class="fas fa-clock me-1"></span>${s.t}
                                </span>`;
                        },
                    },

                    /* 7  — Status Kerja */
                    {
                        data: 'employment_status',
                        render(d) {
                            if (!d) return '<span class="text-muted">—</span>';
                            const map = {
                                tetap: {
                                    t: 'Tetap',
                                    c: 'badge-phoenix-success'
                                },
                                kontrak: {
                                    t: 'Kontrak',
                                    c: 'badge-phoenix-warning'
                                },
                                magang: {
                                    t: 'Magang',
                                    c: 'badge-phoenix-info'
                                },
                            };
                            const s = map[d.toLowerCase()] ?? {
                                t: d,
                                c: 'badge-phoenix-secondary'
                            };
                            return `<span class="badge badge-phoenix p-1 fs-10 ${s.c}">${s.t}</span>`;
                        },
                    },

                    /* 8  — Status */
                    {
                        data: 'status',
                        render(d) {
                            if (!d) return '—';
                            const ok = d.toLowerCase() === 'active';
                            return `<span class="badge-status ${ok ? 'active' : 'inactive'}">
                                    <span class="fas ${ok ? 'fa-check-circle' : 'fa-times-circle'}"></span>
                                    ${ok ? 'Active' : 'Inactive'}
                                </span>`;
                        },
                    },

                    /* 9  — Telepon */
                    {
                        data: 'phone',
                        render: d => d ?
                            `<a href="tel:${self._e(d)}" class="text-decoration-none fs-9">${self._e(d)}</a>` : '<span class="text-muted">—</span>',
                    },

                    /* 10 — Dibuat */
                    {
                        data: 'created_at',
                        render: d => self._fmtDate(d)
                    },

                    /* 11 — Oleh (created) */
                    {
                        data: 'created_by_name',
                        render: (d, t, r) => self._fmtUser(d, r.created_by_employee),
                        orderable: false
                    },

                    /* 12 — Diupdate */
                    {
                        data: 'updated_at',
                        render: d => self._fmtDate(d)
                    },

                    /* 13 — Oleh (updated) */
                    {
                        data: 'updated_by_name',
                        render: (d, t, r) => self._fmtUser(d, r.updated_by_employee),
                        orderable: false
                    },

                    /* 14 — Aksi */
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render(d, t, r) {
                            const view = `<a href="${self.BASE}hrm/employees/show/${r.id}"
                                         class="btn btn-subtle-info btn-sm" title="Detail">
                                         <span class="fas fa-eye"></span></a>`;
                            const edit = CAN_EDIT ?
                                `<a href="${self.BASE}hrm/employees/edit/${r.id}"
                                  class="btn btn-subtle-primary btn-sm" title="Edit">
                                  <span class="fas fa-pencil-alt"></span></a>` :
                                '';
                            const del = CAN_DELETE ?
                                `<button class="btn btn-subtle-danger btn-sm btn-delete"
                                       data-id="${r.id}" data-name="${self._e(r.fullname)}" title="Hapus">
                                       <span class="fas fa-trash"></span></button>` :
                                '';
                            return `<div class="btn-group btn-group-sm">${view}${edit}${del}</div>`;
                        },
                    },
                ],
            });
        },

        /* ── Group By ─────────────────────────────────────────────── */
        _groupSrcMap: {
            department: 'department_name',
            position: 'position_name',
            shift: 'shift',
            gender: row => row.gender === 'L' ? 'Laki-laki' : 'Perempuan',
        },

        groupBy(group) {
            this.currentGroup = group;

            document.querySelectorAll('.group-by-btn').forEach(b => {
                b.className = 'btn btn-sm group-by-btn ' +
                    (b.dataset.group === group ? 'btn-primary' : 'btn-subtle-secondary');
            });

            if (group === 'none') {
                this.dt.rowGroup().disable();
            } else {
                const src = this._groupSrcMap[group];
                if (src) this.dt.rowGroup().dataSrc(src).enable();
            }

            this.dt.ajax.reload();
        },

        /* ── Cropper modal ────────────────────────────────────────── */
        initCropperModal() {
            const self = this;
            const image = document.getElementById('crop-image');

            $('#modalCropPhoto').on('shown.bs.modal', () => {
                if (self.cropper) self.cropper.destroy();
                self.cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            });

            $('#modalCropPhoto').on('hidden.bs.modal', () => {
                if (self.cropper) {
                    self.cropper.destroy();
                    self.cropper = null;
                }
                /* reset input agar change event bisa trigger lagi untuk file yang sama */
                const inp = document.getElementById('photo-input');
                if (inp) inp.value = '';
            });

            document.getElementById('crop-aspect').addEventListener('change', function() {
                if (!self.cropper) return;
                if (this.value === 'free') {
                    self.cropper.setAspectRatio(NaN);
                } else {
                    const [w, h] = this.value.split(':').map(Number);
                    self.cropper.setAspectRatio(w / h);
                }
            });

            document.getElementById('crop-rotate-left').addEventListener('click', () => self.cropper?.rotate(-90));
            document.getElementById('crop-rotate-right').addEventListener('click', () => self.cropper?.rotate(90));
            document.getElementById('crop-reset').addEventListener('click', () => self.cropper?.reset());
            document.getElementById('crop-zoom-in').addEventListener('click', () => self.cropper?.zoom(0.1));
            document.getElementById('crop-zoom-out').addEventListener('click', () => self.cropper?.zoom(-0.1));
            document.getElementById('btn-crop-save').addEventListener('click', () => this.saveCrop());
        },

        /* FIX: employeeId selalu diambil dari dataset, bukan regex URL */
        openCropModal(file, employeeId) {
            if (!employeeId) {
                this.toast('error', 'ID karyawan tidak ditemukan');
                return;
            }
            this.cropFile = file;
            this.currentEmployeeId = parseInt(employeeId, 10);

            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('crop-image').src = e.target.result;
                $('#modalCropPhoto').modal('show');
            };
            reader.readAsDataURL(file);
        },

        async saveCrop() {
            if (!this.cropper) return;

            try {
                const canvas = this.cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });
                if (!canvas) {
                    this.toast('error', 'Gagal memproses crop');
                    return;
                }

                const blob = await new Promise(res => canvas.toBlob(res, 'image/jpeg', 0.9));
                if (!blob) {
                    this.toast('error', 'Gagal konversi gambar');
                    return;
                }

                this._showLoading('Mengupload foto...');
                $('#modalCropPhoto').modal('hide');

                /*
                 * FIX: gunakan _csrfName() / _csrfToken() agar konsisten
                 * dengan konfigurasi CI4, bukan field hardcoded 'csrf_token'
                 */
                const fd = new FormData();
                fd.append('photo', blob, 'cropped.jpg');
                fd.set(this._csrfName(), this._csrfToken());

                const res = await fetch(
                    this.BASE + `hrm/employees/upload-photo/${this.currentEmployeeId}`, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this._csrfToken(),
                        },
                    }
                );

                const data = await res.json();
                if (data?.csrfHash) this._updateCsrf(data.csrfHash);

                if (data.status === 'success') {
                    /* update preview jika sedang di halaman edit */
                    const preview = document.getElementById('photo-preview');
                    if (preview) {
                        preview.src = data.data.photo_url;
                        document.getElementById('photo-area')?.classList.add('has-photo');
                        const btnRemove = document.getElementById('btn-remove-photo');
                        if (btnRemove) btnRemove.style.display = 'inline-flex';
                    }
                    this.toast('success', data.message);
                    this.dt?.ajax.reload(null, false);
                } else {
                    const errors = data.errors ?? {};
                    const errorMsg = Object.values(errors).join(', ') || data.message || 'Upload gagal';
                    this.toast('error', errorMsg);
                }
            } catch (e) {
                console.error('saveCrop error:', e);
                this.toast('error', 'Gagal crop: ' + e.message);
            } finally {
                this._hideLoading();
                this.cropFile = null;
                this.currentEmployeeId = null;
            }
        },

        /*
         * FIX: employeeId diambil dari data-employee-id pada elemen input,
         * tidak lagi dari regex URL — bekerja di halaman manapun
         */
        handlePhotoUpload(input) {
            const file = input.files[0];
            const employeeId = input.dataset.employeeId;

            if (!file) {
                this.toast('warning', 'Pilih foto terlebih dahulu');
                return;
            }

            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                this.toast('error', 'Format tidak didukung. Gunakan JPG, PNG, atau WEBP.');
                input.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                this.toast('error', 'Ukuran foto maksimal 2 MB.');
                input.value = '';
                return;
            }

            this.openCropModal(file, employeeId);
        },

        async removePhoto(employeeId) {
            const result = await Swal.fire({
                title: 'Hapus Foto?',
                text: 'Foto karyawan akan dihapus permanen.',
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
                const res = await this._post(
                    this.BASE + `hrm/employees/delete-photo/${employeeId}`,
                    new FormData()
                );
                if (res.status === 'success') {
                    const preview = document.getElementById('photo-preview');
                    if (preview) preview.src = '';
                    document.getElementById('photo-area')?.classList.remove('has-photo');
                    const btnRemove = document.getElementById('btn-remove-photo');
                    if (btnRemove) btnRemove.style.display = 'none';
                    this.toast('success', res.message);
                    this.dt?.ajax.reload(null, false);
                } else {
                    this.toast('error', res.message);
                }
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        /* ── Export helpers ───────────────────────────────────────── */
        _buildExportParams() {
            const f = this.filters;
            const p = new URLSearchParams();
            if (f.name) p.set('filter_name', f.name);
            if (f.department) p.set('filter_department', f.department);
            if (f.position) p.set('filter_position', f.position);
            if (f.shift) p.set('filter_shift', f.shift);
            if (f.work_area) p.set('filter_work_area', f.work_area);
            if (f.employment_status) p.set('filter_employment_status', f.employment_status);
            if (f.status) p.set('filter_status', f.status);
            if (this.currentGroup !== 'none') p.set('group_by', this.currentGroup);
            return p;
        },

        exportToExcel() {
            window.location.href = this.BASE + 'hrm/employees/export?' +
                this._buildExportParams().toString() + '&format=excel';
        },
        exportToPdf() {
            window.location.href = this.BASE + 'hrm/employees/export?' +
                this._buildExportParams().toString() + '&format=pdf';
        },
        print() {
            window.open(this.BASE + 'hrm/employees/print?' +
                this._buildExportParams().toString(), '_blank');
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
            this._updateFilterUI();
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
                status: '',
            };
            document.getElementById('filter-name').value = '';
            document.getElementById('filter-shift').value = '';
            document.getElementById('filter-work-area').value = '';
            document.getElementById('filter-employment-status').value = '';
            document.getElementById('filter-status').value = '';
            $('#filter-department').val(null).trigger('change');
            $('#filter-position').val(null).trigger('change');

            this.dt.ajax.reload();
            this._updateFilterUI();
        },

        _updateFilterUI() {
            const f = this.filters;
            const labels = [];
            if (f.name) labels.push(`Nama: "${f.name}"`);
            if (f.department) labels.push('Departemen terpilih');
            if (f.position) labels.push('Posisi terpilih');
            if (f.shift) labels.push(`Shift: ${f.shift}`);
            if (f.work_area) labels.push(`Area: ${f.work_area}`);
            if (f.employment_status) labels.push(`Kerja: ${f.employment_status}`);
            if (f.status) labels.push(`Status: ${f.status}`);

            document.getElementById('filter-toggle')
                .classList.toggle('has-filter', labels.length > 0);
            document.getElementById('filter-summary-text').textContent = labels.join(' · ');
            document.getElementById('filter-summary')
                .classList.toggle('d-none', labels.length === 0);
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
                const res = await this._post(
                    this.BASE + `hrm/employees/delete/${id}`,
                    new FormData()
                );
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
            /* toolbar */
            document.getElementById('btn-refresh')
                .addEventListener('click', () => this.dt.ajax.reload(() => this.loadStats(), false));
            document.getElementById('btn-export-excel')
                .addEventListener('click', e => {
                    e.preventDefault();
                    this.exportToExcel();
                });
            document.getElementById('btn-export-pdf')
                .addEventListener('click', e => {
                    e.preventDefault();
                    this.exportToPdf();
                });
            document.getElementById('btn-print')
                .addEventListener('click', e => {
                    e.preventDefault();
                    this.print();
                });

            /* filter */
            document.getElementById('btn-apply-filter')
                .addEventListener('click', () => this.applyFilter());
            document.getElementById('btn-reset-filter')
                .addEventListener('click', () => this.resetFilter());
            document.getElementById('filter-name')
                .addEventListener('keypress', e => {
                    if (e.key === 'Enter') this.applyFilter();
                });

            /* group by */
            document.querySelectorAll('.group-by-btn').forEach(btn =>
                btn.addEventListener('click', () => this.groupBy(btn.dataset.group))
            );

            /* delete (delegated) */
            $(document).on('click', '.btn-delete', e => {
                const btn = $(e.currentTarget);
                this.deleteItem(btn.data('id'), btn.data('name'));
            });

            /*
             * FIX: photo-input mengambil employeeId dari data-employee-id pada elemen itu sendiri.
             * Tidak ada lagi regex dari URL — bekerja di semua halaman.
             */
            $(document).on('change', '#photo-input', e => {
                this.handlePhotoUpload(e.currentTarget);
            });

            /* remove photo */
            $(document).on('click', '#btn-remove-photo', e => {
                const employeeId = e.currentTarget.dataset.employeeId;
                if (employeeId) this.removePhoto(parseInt(employeeId, 10));
            });
        },

        /* ── Formatters ───────────────────────────────────────────── */
        _fmtDate(d) {
            if (!d) return '<span class="text-muted">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">
                    ${dt.toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })}
                </span>
                <small class="text-muted">
                    ${dt.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' })}
                </small>`;
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