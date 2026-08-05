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

    #formulation-table_wrapper {
        max-width: 100%;
    }

    #formulation-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #formulation-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #formulation-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #formulation-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #formulation-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #formulation-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #formulation-table_wrapper .dataTables_filter label,
    #formulation-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #formulation-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #formulation-table_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #formulation-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #formulation-table {
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

    .select2-container .select2-selection--single {
        height: calc(1.5em + 1.1rem + 2px) !important;
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

    .formulation-code {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        font-size: .8rem;
        background: rgba(var(--phoenix-primary-rgb), .06);
        padding: .15rem .5rem;
        border-radius: 4px;
        border: 1px solid rgba(var(--phoenix-primary-rgb), .1);
        display: inline-block;
    }

    .version-badge {
        font-size: .6rem;
        padding: .15rem .4rem;
        border-radius: 3px;
        background: rgba(var(--phoenix-secondary-rgb), .08);
        color: var(--phoenix-secondary-color);
        border: 1px solid rgba(var(--phoenix-secondary-rgb), .15);
        white-space: nowrap;
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
            ['id' => 'stat-total',    'label' => 'Total',    'icon' => 'fa-flask',        'color' => 'primary'],
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
            <?php if (canDo('warehouse.formulations.manage')): ?>
                <a href="<?= site_url('warehouse/formulations/trash') ?>" class="btn btn-subtle-danger btn-sm">
                    <span class="fas fa-trash-alt me-1"></span>Sampah
                    <span class="badge bg-danger ms-1 d-none" id="trash-badge">0</span>
                </a>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <?php if (canDo('warehouse.formulations.manage')): ?>
                <a href="<?= site_url('warehouse/formulations/create') ?>" class="btn btn-primary btn-sm">
                    <span class="fas fa-plus me-1"></span>Tambah Formulasi
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Print header -->
    <div class="print-header mb-3">
        <h5 class="fw-bold mb-1">Daftar Formulasi</h5>
        <div class="text-muted small">Dicetak: <span id="print-date"></span></div>
        <hr class="my-2">
    </div>

    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="formulation-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Formulasi</th>
                    <th>Proses</th>
                    <th>Sub Proses</th>
                    <th>Hasil / Batch</th>
                    <th>Jumlah <br> Kimia</th>
                    <th>Status</th>
                    <th>Versi</th>
                    <th>Dibuat Oleh</th>
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
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Proses</label>
                <select class="form-select form-select-sm" id="filter-process-type">
                    <option value="">Semua</option>
                    <option value="Dyeing">Dyeing</option>
                    <option value="Finishing">Finishing</option>
                    <option value="Other">Lainnya</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Sub Proses</label>
                <select class="form-select form-select-sm" id="filter-sub-process-type">
                    <option value="">Semua</option>
                    <option value="Dyeing">Dyeing</option>
                    <option value="Dipping">Dipping</option>
                    <option value="Coating">Coating</option>
                    <option value="Spray">Spray</option>
                    <option value="Coating_Foam">Coating Foam</option>
                    <option value="Finishing">Finishing</option>
                    <option value="Other">Lainnya</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold fs-9 text-uppercase text-muted">Group</label>
                <select class="form-select form-select-sm" id="filter-group" style="width:100%">
                    <option value=""></option>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CAN_MANAGE_FORMULATION = <?= json_encode(canDo('warehouse.formulations.manage')) ?>;

    const Formulation = {
        BASE: '<?= base_url() ?>',
        dt: null,
        filters: {
            name: '',
            process_type: '',
            sub_process_type: '',
            group: '',
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

        initSelect2() {
            $('#filter-group').select2({
                dropdownParent: $('#filter-offcanvas'),
                theme: 'bootstrap-5',
                placeholder: '— Semua Group —',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: this.BASE + 'warehouse/formulations/groups/select2',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: (data.data ?? []).map(g => ({
                            id: g.id,
                            text: g.name
                        }))
                    }),
                },
            });
        },

        async loadStats() {
            try {
                const d = await this.get(this.BASE + 'warehouse/formulations/stats');
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
            } catch {}
        },

        initDatatable() {
            const self = this;
            this.dt = $('#formulation-table').DataTable({
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
                    [1, 'asc']
                ],
                dom: '<"top"f>rt<"bottom"lpi>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari formulasi...',
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
                    url: this.BASE + 'warehouse/formulations/datatables',
                    type: 'GET',
                    data: d => {
                        d.filter_name = self.filters.name;
                        d.filter_process_type = self.filters.process_type;
                        d.filter_sub_process_type = self.filters.sub_process_type;
                        d.filter_group_id = self.filters.group;
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
                        width: '200px'
                    },
                    {
                        targets: 2,
                        width: '100px'
                    },
                    {
                        targets: 3,
                        width: '120px'
                    },
                    {
                        targets: 4,
                        width: '100px'
                    },
                    {
                        targets: 5,
                        width: '100px'
                    },
                    {
                        targets: 6,
                        width: '100px'
                    },
                    {
                        targets: 7,
                        width: '80px'
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
                            `<span class="fw-semibold">${self.e(r.formulation_name)}</span>
                             <div class="text-muted small"><span class="formulation-code">${self.e(r.formulation_code)}</span></div>
                             ${r.group_name ? `<div class="text-muted small"><span class="fas fa-tag me-1" style="font-size:.6rem;"></span>${self.e(r.group_name)}</div>` : ''}`
                    },
                    {
                        data: 'process_type',
                        render: d => {
                            const colors = {
                                'Dyeing': 'primary',
                                'Finishing': 'info',
                                'Other': 'secondary'
                            };
                            return `<span class="badge badge-phoenix badge-phoenix-${colors[d] || 'secondary'} fs-10 p-2 px-2">${self.e(d)}</span>`;
                        }
                    },
                    {
                        data: 'process_sub_type',
                        render: d => {
                            const labels = {
                                'Dyeing': 'Dyeing',
                                'Dipping': 'Dipping',
                                'Coating': 'Coating',
                                'Spray': 'Spray',
                                'Coating_Foam': 'Coating Foam',
                                'Finishing': 'Finishing',
                                'Other': 'Lainnya'
                            };
                            return `<span class="badge badge-phoenix badge-phoenix-secondary fs-10 p-2 px-2">${self.e(labels[d] || d)}</span>`;
                        }
                    },
                    {
                        data: null,
                        render: (d, t, r) => {
                            const val = r.output_percentage ?? 0;
                            return `<span class="fw-semibold">${Number(val).toFixed(2)}%</span>`;
                        }
                    },
                    {
                        data: 'item_count',
                        render: d => Number(d) > 0 ?
                            `<span class="badge badge-phoenix badge-phoenix-success fs-10 p-2 px-2">${self.e(d)} item</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'status',
                        render: d => self.fmtStatus(d)
                    },
                    {
                        data: 'version_no',
                        render: d => d ? `<span class="version-badge">v${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'created_by_name',
                        render: (d, t, r) => self.fmtUser(d, r.created_by_employee)
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render: (d, t, r) => {
                            const edit = CAN_MANAGE_FORMULATION ?
                                `<a href="${self.BASE}warehouse/formulations/${r.id}/edit" class="btn btn-subtle-primary btn-sm" title="Edit"><span class="fas fa-pencil-alt"></span></a>` :
                                '';
                            const del = CAN_MANAGE_FORMULATION ?
                                `<button class="btn btn-subtle-danger btn-sm btn-delete" data-id="${r.id}" data-name="${self.e(r.formulation_name)}" title="Hapus"><span class="fas fa-trash"></span></button>` :
                                '';
                            return `<div class="btn-group btn-group-sm">${edit}${del}</div>`;
                        }
                    },
                ],
            });
        },

        applyFilter() {
            this.filters.name = document.getElementById('filter-name').value.trim();
            this.filters.process_type = document.getElementById('filter-process-type').value;
            this.filters.sub_process_type = document.getElementById('filter-sub-process-type').value;
            this.filters.group = $('#filter-group').val() ?? '';
            this.filters.status = document.getElementById('filter-status').value;
            this.dt.ajax.reload();
            this.updateFilterUI();
            bootstrap.Offcanvas.getInstance(document.getElementById('filter-offcanvas'))?.hide();
        },

        resetFilter() {
            this.filters = {
                name: '',
                process_type: '',
                sub_process_type: '',
                group: '',
                status: ''
            };
            document.getElementById('filter-name').value = '';
            document.getElementById('filter-process-type').value = '';
            document.getElementById('filter-sub-process-type').value = '';
            $('#filter-group').val(null).trigger('change');
            document.getElementById('filter-status').value = '';
            this.dt.ajax.reload();
            this.updateFilterUI();
        },

        updateFilterUI() {
            const labels = [];
            if (this.filters.name) labels.push(`Nama: "${this.filters.name}"`);
            if (this.filters.process_type) labels.push(`Proses: ${this.filters.process_type}`);
            if (this.filters.sub_process_type) {
                const labelsMap = {
                    'Dyeing': 'Dyeing',
                    'Dipping': 'Dipping',
                    'Coating': 'Coating',
                    'Spray': 'Spray',
                    'Coating_Foam': 'Coating Foam',
                    'Finishing': 'Finishing',
                    'Other': 'Lainnya'
                };
                labels.push(`Sub Proses: ${labelsMap[this.filters.sub_process_type] || this.filters.sub_process_type}`);
            }
            if (this.filters.group) {
                const txt = $('#filter-group option:selected').text();
                if (txt) labels.push(`Group: ${txt}`);
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

            document.getElementById('btn-apply-filter')?.addEventListener('click', () => this.applyFilter());
            document.getElementById('btn-reset-filter')?.addEventListener('click', () => this.resetFilter());

            // Enter key on filter name
            document.getElementById('filter-name')?.addEventListener('keyup', e => {
                if (e.key === 'Enter') this.applyFilter();
            });

            $(document).on('click', '.btn-delete', e => {
                const btn = $(e.currentTarget);
                this.deleteItem(btn.data('id'), btn.data('name'));
            });
        },

        async deleteItem(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Formulasi?',
                html: `Formulasi <strong>${this.e(name)}</strong> akan dipindahkan ke sampah.<br><small class="text-muted">Dapat dipulihkan dari menu Sampah.</small>`,
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
                const res = await this.post(this.BASE + `warehouse/formulations/${id}/delete`, new FormData());
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

        e(s) {
            if (s === null || s === undefined) return '';
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        },

        fmtStatus(status) {
            if (!status) return '<span class="text-muted fst-italic">—</span>';
            let cls = 'draft',
                icon = 'fa-pencil-alt';
            const s = status.toLowerCase();
            if (s === 'active') {
                cls = 'active';
                icon = 'fa-check-circle';
            } else if (s === 'archived') {
                cls = 'archived';
                icon = 'fa-archive';
            }
            return `<span class="badge-status ${cls}"><span class="fas ${icon}"></span>${this.e(status)}</span>`;
        },

        fmtUser(name, employeeName = null) {
            if (!name && !employeeName) return '<span class="text-muted fst-italic">—</span>';
            if (!employeeName) {
                return `<span class="badge badge-phoenix badge-phoenix-info rounded-pill fs-10 p-2 px-2" title="Username: ${this.e(name)}">
                    <span class="fas fa-user-circle me-1"></span>${this.e(name)}
                </span>`;
            }
            return `<span class="badge badge-phoenix badge-phoenix-primary rounded-pill fs-10 p-2 px-3"
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

    $(document).ready(() => Formulation.init());
</script>
<?= $this->endSection() ?>