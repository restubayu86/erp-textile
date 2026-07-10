<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    body {
        overflow-x: hidden;
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

    .badge-status.open {
        background-color: rgba(var(--phoenix-success-rgb), .12);
        color: var(--phoenix-success);
        border: 1px solid rgba(var(--phoenix-success-rgb), .25);
    }

    .badge-status.closed {
        background-color: rgba(var(--phoenix-secondary-rgb), .12);
        color: var(--phoenix-secondary);
        border: 1px solid rgba(var(--phoenix-secondary-rgb), .25);
    }

    .badge-status .fas {
        font-size: .7rem;
        flex-shrink: 0;
    }

    #trash-table_wrapper {
        max-width: 100%;
    }

    #trash-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #trash-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        text-align: center;
    }

    #trash-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #trash-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #trash-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #trash-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #trash-table_wrapper .dataTables_filter label,
    #trash-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #trash-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 0.5rem;
        border-radius: 0.375rem;
    }

    #trash-table_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }

    #trash-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #trash-table {
        width: 100% !important;
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

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <a href="<?= site_url('warehouse/master/periods') ?>" class="btn btn-subtle-secondary btn-sm">
            <span class="fas fa-arrow-left me-1"></span>Kembali ke Daftar
        </a>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh" type="button">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <?php if (canDo('warehouse.periods.delete')): ?>
                <button class="btn btn-danger btn-sm" id="btn-empty-trash" type="button">
                    <span class="fas fa-fire me-1"></span>Kosongkan Sampah
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="alert alert-subtle-warning py-2 px-3 fs-9 mb-3">
        <span class="fas fa-info-circle me-1"></span>
        Data di sini akan tetap tersimpan sampai dipulihkan atau dihapus permanen.
    </div>

    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="trash-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th>Dihapus pada</th>
                    <th>Oleh</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CAN_DELETE_PERIOD = <?= json_encode(canDo('warehouse.periods.delete')) ?>;

    const PeriodTrash = {
        BASE: '<?= base_url() ?>',
        dt: null,

        init() {
            this.initDatatable();
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

        initDatatable() {
            const self = this;
            this.dt = $('#trash-table').DataTable({
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
                    [3, 'desc']
                ],
                dom: '<"top"f>rt<"bottom"lpi>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari periode...',
                    lengthMenu: '_MENU_ / hal',
                    info: 'Tampil _START_–_END_ dari _TOTAL_',
                    infoEmpty: 'Tidak ada data',
                    zeroRecords: 'Sampah kosong',
                    paginate: {
                        previous: '‹',
                        next: '›'
                    },
                    processing: '<span class="spinner-border spinner-border-sm text-primary"></span>',
                },
                ajax: {
                    url: this.BASE + 'warehouse/master/periods/trash-datatables',
                    type: 'GET',
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
                        width: '110px'
                    },
                    {
                        targets: 3,
                        width: '150px'
                    },
                    {
                        targets: 4,
                        width: '140px'
                    },
                    {
                        targets: 5,
                        width: '110px'
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
                            `<span class="fw-semibold">${self.e(r.period_name)}</span>
                             <div class="text-muted small font-monospace">${self.e(r.period_code)}</div>`
                    },
                    {
                        data: 'status',
                        render: d => self.fmtStatus(d)
                    },
                    {
                        data: 'deleted_at',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: 'deleted_by_name',
                        render: (d, t, r) => self.fmtUser(d, r.deleted_by_employee)
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end',
                        render: (d, t, r) => {
                            const restore = CAN_DELETE_PERIOD ?
                                `<button class="btn btn-subtle-success btn-sm btn-restore" data-id="${r.id}" data-name="${self.e(r.period_name)}" title="Pulihkan">
                                    <span class="fas fa-trash-restore"></span>
                                </button>` : '';
                            const forceDel = CAN_DELETE_PERIOD ?
                                `<button class="btn btn-subtle-danger btn-sm btn-force-delete" data-id="${r.id}" data-name="${self.e(r.period_name)}" title="Hapus Permanen">
                                    <span class="fas fa-trash"></span>
                                </button>` : '';
                            return `<div class="btn-group btn-group-sm">${restore}${forceDel}</div>`;
                        }
                    },
                ],
            });
        },

        async restoreItem(id, name) {
            const result = await Swal.fire({
                title: 'Pulihkan Periode?',
                html: `<strong>${name}</strong> akan dikembalikan ke daftar aktif.`,
                icon: 'question',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#00d27a',
                cancelButtonColor: '#748194',
                confirmButtonText: '<span class="fas fa-trash-restore me-1"></span>Pulihkan',
                cancelButtonText: 'Batal',
            });
            if (!result.isConfirmed) return;
            try {
                const res = await this.post(this.BASE + `warehouse/master/periods/${id}/restore`, new FormData());
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

        async forceDeleteItem(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Permanen?',
                html: `<strong>${name}</strong> akan dihapus permanen dan <u>tidak bisa dipulihkan lagi</u>.`,
                icon: 'error',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#e63757',
                cancelButtonColor: '#748194',
                confirmButtonText: '<span class="fas fa-fire me-1"></span>Hapus Permanen',
                cancelButtonText: 'Batal',
            });
            if (!result.isConfirmed) return;
            try {
                const res = await this.post(this.BASE + `warehouse/master/periods/${id}/force-delete`, new FormData());
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

        async emptyTrash() {
            const result = await Swal.fire({
                title: 'Kosongkan Sampah?',
                html: `Semua periode di sampah akan dihapus permanen dan <u>tidak bisa dipulihkan lagi</u>.`,
                icon: 'error',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#e63757',
                cancelButtonColor: '#748194',
                confirmButtonText: '<span class="fas fa-fire me-1"></span>Kosongkan',
                cancelButtonText: 'Batal',
            });
            if (!result.isConfirmed) return;
            try {
                const res = await this.post(this.BASE + 'warehouse/master/periods/empty-trash', new FormData());
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
            document.getElementById('btn-empty-trash')?.addEventListener('click', () => this.emptyTrash());

            $(document).on('click', '.btn-restore', e => {
                const btn = $(e.currentTarget);
                this.restoreItem(btn.data('id'), btn.data('name'));
            });
            $(document).on('click', '.btn-force-delete', e => {
                const btn = $(e.currentTarget);
                this.forceDeleteItem(btn.data('id'), btn.data('name'));
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

        fmtStatus(status) {
            if (!status) return '<span class="text-muted fst-italic">—</span>';

            let statusClass = '';
            let statusIcon = '';

            switch (status.toLowerCase()) {
                case 'open':
                    statusClass = 'open';
                    statusIcon = 'fa-lock-open';
                    break;
                case 'closed':
                    statusClass = 'closed';
                    statusIcon = 'fa-lock';
                    break;
                default:
                    statusClass = 'open';
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

    $(document).ready(() => PeriodTrash.init());
</script>
<?= $this->endSection() ?>