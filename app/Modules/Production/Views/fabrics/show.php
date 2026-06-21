<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    /* ── Prevent horizontal page scrollbar ───────────────────────── */
    body {
        overflow-x: hidden;
    }

    .info-label {
        font-size: .65rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--phoenix-secondary-color);
        margin-bottom: .2rem;
    }

    .info-value {
        font-weight: 600;
        font-size: .95rem;
    }

    /* ── Status badge ───────────────────────────────────────────── */
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

    /* ── DataTables ──────────────────────────────────────────────── */
    #flow-table_wrapper {
        max-width: 100%;
    }

    #flow-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #flow-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #flow-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #flow-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #flow-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #flow-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #flow-table_wrapper .dataTables_filter label,
    #flow-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #flow-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #flow-table_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #flow-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #flow-table {
        width: 100% !important;
    }

    .btn-group-sm .btn {
        padding: .5rem .75rem;
        font-size: .7rem;
    }

    /* ── Step rows (dynamic table di modal) ───────────────────────── */
    .step-row .step-no-badge {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .8rem;
        background: rgba(var(--phoenix-primary-rgb), .1);
        color: var(--phoenix-primary);
        flex-shrink: 0;
    }

    .step-row .btn-remove-step {
        flex-shrink: 0;
    }

    #steps-empty {
        display: none;
    }

    #steps-container:empty+#steps-empty,
    #steps-container.is-empty+#steps-empty {
        display: block;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-100">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
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
            <h1 class="h3 mb-1 fw-bold"><?= esc((string)$fabric['fabric_name']) ?></h1>
            <p class="text-body-tertiary mb-0 font-monospace"><?= esc((string)$fabric['fabric_code']) ?></p>
        </div>
        <a href="<?= site_url('production/master/fabrics') ?>" class="btn btn-subtle-secondary btn-sm">
            <span class="fas fa-arrow-left me-1"></span>Kembali
        </a>
    </div>

    <!-- Fabric Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <div class="info-label">Kode</div>
                    <div class="info-value font-monospace"><?= esc((string)$fabric['fabric_code']) ?></div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="info-label">Status</div>
                    <div class="info-value" id="fabric-status-badge">—</div>
                </div>
                <div class="col-md-6">
                    <div class="info-label">Deskripsi</div>
                    <div class="info-value fw-normal">
                        <?= $fabric['description'] ? esc((string)$fabric['description']) : '<span class="text-muted fst-italic">Tidak ada deskripsi</span>' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar Flow Process -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <h5 class="fw-bold mb-0"><span class="fas fa-route me-2 text-primary"></span>Flow Process</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <?php if (canDo('production.flow-processes.create')): ?>
                <button class="btn btn-primary btn-sm" id="btn-create" type="button">
                    <span class="fas fa-plus me-1"></span>Tambah Flow Process
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Table Flow Process -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="flow-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Flow Process</th>
                    <th>Jumlah Step</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Tambah / Edit Flow Process -->
<div class="modal fade" id="flowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                        style="width:48px;height:48px;border-radius:12px;">
                        <span class="fas fa-route"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modal-title">Tambah Flow Process</h5>
                        <p class="text-muted fs-10 mb-0" id="modal-subtitle">Buat template proses baru</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-subtle-danger py-2 px-3 fs-10 d-none" id="modal-alert">
                    <span class="fas fa-exclamation-triangle me-1"></span>
                    <span id="modal-alert-text"></span>
                </div>

                <!-- Header Flow Process -->
                <div class="card border mb-3" style="border-radius:.75rem">
                    <div class="card-body p-3">
                        <p class="fs-10 fw-bold text-uppercase text-primary mb-3" style="letter-spacing:.08em">
                            <span class="fas fa-clipboard-list me-1"></span>Informasi Flow Process
                        </p>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-flow-name">
                                    Nama Flow <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="f-flow-name"
                                    placeholder="Nama template proses" maxlength="150" autocomplete="off">
                                <div class="invalid-feedback" id="err-flow_name"></div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-flow-code">
                                    Kode Flow <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm font-monospace fw-bold"
                                    id="f-flow-code" placeholder="Kode flow" maxlength="30" autocomplete="off"
                                    style="text-transform:uppercase;letter-spacing:.06em">
                                <div class="invalid-feedback" id="err-flow_code"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-flow-status">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-sm" id="f-flow-status">
                                    <option value="Active">Active</option>
                                    <option value="Draft" selected>Draft</option>
                                    <option value="Archived">Archived</option>
                                </select>
                                <div class="invalid-feedback" id="err-status"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-flow-desc">
                                    Deskripsi
                                </label>
                                <textarea class="form-control form-control-sm" id="f-flow-desc"
                                    rows="2" maxlength="500" placeholder="Deskripsi singkat..." style="resize:vertical"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Steps -->
                <div class="card border mb-0" style="border-radius:.75rem">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="fs-10 fw-bold text-uppercase text-primary mb-0" style="letter-spacing:.08em">
                                <span class="fas fa-list-ol me-1"></span>Tahapan Proses
                            </p>
                            <button type="button" class="btn btn-subtle-primary btn-sm" id="btn-add-step">
                                <span class="fas fa-plus me-1"></span>Tambah Step
                            </button>
                        </div>

                        <div id="steps-container" class="d-flex flex-column gap-2"></div>
                        <div id="steps-empty" class="text-center text-muted py-3 fs-9">
                            <span class="fas fa-inbox me-1"></span>Belum ada step. Klik "Tambah Step" untuk menambahkan.
                        </div>
                        <div class="invalid-feedback d-block mt-2" id="err-steps" style="display:none"></div>
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

<!-- Template baris step (di-clone via JS) -->
<template id="step-row-template">
    <div class="step-row d-flex align-items-center gap-2">
        <div class="step-no-badge"></div>
        <select class="form-select form-select-sm step-process-name" style="flex:1"></select>
        <button type="button" class="btn btn-subtle-danger btn-sm btn-remove-step" title="Hapus step">
            <span class="fas fa-trash"></span>
        </button>
    </div>
</template>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CAN_EDIT_FLOW = <?= json_encode(canDo('production.flow-processes.edit')) ?>;
    const CAN_DELETE_FLOW = <?= json_encode(canDo('production.flow-processes.delete')) ?>;
    const FABRIC_ID = <?= (int)$fabric['id'] ?>;
    const FABRIC_STATUS = <?= json_encode($fabric['status']) ?>;

    const FabricDetail = {
        BASE: '<?= base_url() ?>',
        dt: null,
        editId: null,
        stepCounter: 0,
        processNameOptions: [],

        init() {
            this.renderFabricStatus();
            this.initDatatable();
            this.initEvents();
            this.initFieldEvents();
            this.loadProcessNameOptions();
        },

        renderFabricStatus() {
            document.getElementById('fabric-status-badge').innerHTML = this.fmtStatus(FABRIC_STATUS);
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

        async loadProcessNameOptions() {
            try {
                const d = await this.get(this.BASE + 'production/master/flow-processes/process-names');
                this.processNameOptions = d.data ?? [];
            } catch {
                this.processNameOptions = [];
            }
        },

        initDatatable() {
            const self = this;
            this.dt = $('#flow-table').DataTable({
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
                    searchPlaceholder: 'Cari flow process...',
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
                    url: this.BASE + 'production/master/flow-processes/datatables',
                    type: 'GET',
                    data: d => {
                        d.fabric_id = FABRIC_ID;
                    },
                    error: () => self.toast('error', 'Gagal memuat data'),
                },
                columnDefs: [{
                        targets: 0,
                        width: '50px'
                    },
                    {
                        targets: 1,
                        width: '250px'
                    },
                    {
                        targets: 2,
                        width: '120px'
                    },
                    {
                        targets: 3,
                        width: '100px'
                    },
                    {
                        targets: 4,
                        width: '130px'
                    },
                    {
                        targets: 5,
                        width: '100px'
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
                            `<div class="fw-semibold">${self.e(r.flow_name)}</div>
                             <div class="text-muted small font-monospace">${self.e(r.flow_code)}</div>`
                    },
                    {
                        data: 'step_count',
                        render: d => `<span class="badge badge-phoenix badge-phoenix-secondary">${d ?? 0} step</span>`
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
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end',
                        render: (d, t, r) => {
                            const edit = CAN_EDIT_FLOW ?
                                `<button class="btn btn-subtle-primary btn-sm btn-edit" data-id="${r.id}"><span class="fas fa-pencil-alt"></span></button>` :
                                '';
                            const del = CAN_DELETE_FLOW ?
                                `<button class="btn btn-subtle-danger btn-sm btn-delete" data-id="${r.id}" data-name="${self.e(r.flow_name)}"><span class="fas fa-trash"></span></button>` :
                                '';
                            return `<div class="btn-group btn-group-sm">${edit}${del}</div>`;
                        }
                    },
                ],
            });
        },

        // ── Steps (dynamic rows) ────────────────────────────────────
        addStepRow(processName = '') {
            this.stepCounter++;
            const tpl = document.getElementById('step-row-template');
            const clone = tpl.content.cloneNode(true);
            const row = clone.querySelector('.step-row');
            row.dataset.rowId = this.stepCounter;

            const select = row.querySelector('.step-process-name');
            select.id = `step-process-${this.stepCounter}`;

            const container = document.getElementById('steps-container');
            container.appendChild(row);
            container.classList.remove('is-empty');

            // Init select2 tags untuk row ini
            $(select).select2({
                dropdownParent: $('#flowModal'),
                placeholder: 'Pilih atau ketik nama proses...',
                tags: true,
                width: '100%',
                data: this.processNameOptions.map(p => ({
                    id: p,
                    text: p
                })),
            });

            if (processName) {
                if (!$(select).find(`option[value="${CSS.escape(processName)}"]`).length) {
                    $(select).append(new Option(processName, processName, true, true));
                }
                $(select).val(processName).trigger('change');
            }

            this.renumberSteps();
        },

        removeStepRow(row) {
            const select = row.querySelector('.step-process-name');
            $(select).select2('destroy');
            row.remove();
            this.renumberSteps();
        },

        renumberSteps() {
            const rows = document.querySelectorAll('#steps-container .step-row');
            rows.forEach((row, idx) => {
                row.querySelector('.step-no-badge').textContent = idx + 1;
            });
            document.getElementById('steps-container').classList.toggle('is-empty', rows.length === 0);
        },

        collectSteps() {
            const rows = document.querySelectorAll('#steps-container .step-row');
            return Array.from(rows).map((row, idx) => ({
                step_no: idx + 1,
                process_name: $(row.querySelector('.step-process-name')).val() || '',
            })).filter(s => s.process_name.trim() !== '');
        },

        clearSteps() {
            document.querySelectorAll('#steps-container .step-row .step-process-name').forEach(el => {
                $(el).select2('destroy');
            });
            document.getElementById('steps-container').innerHTML = '';
            document.getElementById('steps-container').classList.add('is-empty');
            this.stepCounter = 0;
        },

        // ── Modal open/close ─────────────────────────────────────────
        openCreate() {
            this.editId = null;
            this.resetModal();
            document.getElementById('modal-title').textContent = 'Tambah Flow Process';
            document.getElementById('modal-subtitle').textContent = 'Buat template proses baru';
            document.getElementById('save-text').textContent = 'Simpan';
            new bootstrap.Modal(document.getElementById('flowModal')).show();
            this.addStepRow();
        },

        async openEdit(id) {
            this.editId = id;
            this.resetModal();
            document.getElementById('modal-title').textContent = 'Edit Flow Process';
            document.getElementById('modal-subtitle').textContent = 'Perbarui template proses';
            document.getElementById('save-text').textContent = 'Update';
            this.setLoading(true);
            new bootstrap.Modal(document.getElementById('flowModal')).show();
            try {
                const d = await this.get(this.BASE + `production/master/flow-processes/get/${id}`);
                if (d.status === 'success' && d.data) {
                    document.getElementById('f-flow-name').value = d.data.flow_name ?? '';
                    document.getElementById('f-flow-code').value = d.data.flow_code ?? '';
                    document.getElementById('f-flow-desc').value = d.data.description ?? '';
                    document.getElementById('f-flow-status').value = d.data.status ?? 'Draft';

                    if (d.data.flow_name) this.markValid('f-flow-name');
                    if (d.data.flow_code) this.markValid('f-flow-code');
                    if (d.data.status) this.markValid('f-flow-status');

                    const steps = d.data.steps ?? [];
                    if (steps.length) {
                        steps.forEach(s => this.addStepRow(s.process_name));
                    } else {
                        this.addStepRow();
                    }
                } else {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    bootstrap.Modal.getInstance(document.getElementById('flowModal'))?.hide();
                }
            } catch {
                this.toast('error', 'Gagal memuat data');
            } finally {
                this.setLoading(false);
            }
        },

        async save() {
            this.clearErrors();
            const steps = this.collectSteps();

            if (steps.length === 0) {
                const el = document.getElementById('err-steps');
                el.textContent = 'Minimal 1 step harus diisi';
                el.style.display = 'block';
                return;
            }

            const fd = new FormData();
            fd.set('fabric_id', FABRIC_ID);
            fd.set('flow_name', document.getElementById('f-flow-name').value.trim());
            fd.set('flow_code', document.getElementById('f-flow-code').value.trim().toUpperCase());
            fd.set('description', document.getElementById('f-flow-desc').value.trim());
            fd.set('status', document.getElementById('f-flow-status').value);
            fd.set('steps', JSON.stringify(steps));
            if (this.editId) fd.set('id', this.editId);

            this.setLoading(true);
            try {
                const res = await this.post(this.BASE + 'production/master/flow-processes/store', fd);
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('flowModal'))?.hide();
                    this.dt.ajax.reload(null, false);
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
            ['f-flow-name', 'f-flow-code', 'f-flow-desc'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.classList.remove('is-invalid', 'is-valid');
                }
            });
            document.getElementById('f-flow-status').value = 'Draft';
            document.getElementById('modal-alert').classList.add('d-none');
            this.clearSteps();
            this.clearErrors();
        },

        clearErrors() {
            document.querySelectorAll('#flowModal .is-invalid, #flowModal .is-valid').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });
            document.querySelectorAll('#flowModal .invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.visibility = '';
                el.style.display = '';
            });
            document.getElementById('modal-alert').classList.add('d-none');
        },

        initFieldEvents() {
            [{
                    input: 'f-flow-name',
                    required: true
                },
                {
                    input: 'f-flow-code',
                    required: true
                },
                {
                    input: 'f-flow-status',
                    required: true
                },
                {
                    input: 'f-flow-desc',
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
                flow_name: ['f-flow-name', 'err-flow_name'],
                flow_code: ['f-flow-code', 'err-flow_code'],
                status: ['f-flow-status', 'err-status'],
                steps: [null, 'err-steps'],
            };
            Object.entries(errors).forEach(([f, msg]) => {
                const [inp, err] = map[f] ?? [];
                const text = Array.isArray(msg) ? msg[0] : msg;
                if (inp && err) {
                    this.markInvalid(inp, err, text);
                } else if (err) {
                    const el = document.getElementById(err);
                    if (el) {
                        el.textContent = text;
                        el.style.display = 'block';
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
                title: 'Hapus Flow Process?',
                html: `<strong>${name}</strong> akan dihapus.<br><small class="text-muted">Aksi ini tidak bisa dibatalkan.</small>`,
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
                const res = await this.post(this.BASE + `production/master/flow-processes/delete/${id}`, new FormData());
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

        initEvents() {
            document.getElementById('btn-refresh')?.addEventListener('click', () => {
                this.dt.ajax.reload(null, false);
            });
            document.getElementById('btn-create')?.addEventListener('click', () => this.openCreate());
            document.getElementById('btn-save')?.addEventListener('click', () => this.save());
            document.getElementById('btn-add-step')?.addEventListener('click', () => this.addStepRow());

            document.getElementById('f-flow-code')?.addEventListener('input', e => {
                e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_\-]/g, '');
            });

            document.getElementById('flowModal')
                ?.addEventListener('hide.bs.modal', () => {
                    if (document.activeElement instanceof HTMLElement) {
                        document.activeElement.blur();
                    }
                });

            $(document).on('click', '.btn-remove-step', e => {
                this.removeStepRow($(e.currentTarget).closest('.step-row')[0]);
            });

            $(document).on('click', '.btn-edit', e => this.openEdit($(e.currentTarget).data('id')));
            $(document).on('click', '.btn-delete', e => {
                const btn = $(e.currentTarget);
                this.deleteItem(btn.data('id'), btn.data('name'));
            });
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

        fmtDate(d) {
            if (!d) return '<span class="text-muted">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">${dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                    <small class="text-muted">${dt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</small>`;
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

    $(document).ready(() => FabricDetail.init());
</script>
<?= $this->endSection() ?>