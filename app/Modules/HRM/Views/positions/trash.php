<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    body {
        overflow-x: hidden;
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
        background-color: rgba(var(--phoenix-danger-rgb), .12);
        color: var(--phoenix-danger);
        border: 1px solid rgba(var(--phoenix-danger-rgb), .25);
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

    /* ── Status badge (sama seperti user badge) ──────────────────── */
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

    /* ── DataTables Custom Layout ────────────────────────────────── */
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

    /* Length Menu di KIRI */
    #trash-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    /* Pagination di TENGAH */
    #trash-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    /* Info di KANAN */
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

    @media (max-width: 768px) {
        #trash-table_wrapper .bottom {
            flex-direction: column;
            align-items: stretch;
        }

        #trash-table_wrapper .bottom .dataTables_length,
        #trash-table_wrapper .bottom .dataTables_paginate,
        #trash-table_wrapper .bottom .dataTables_info {
            text-align: center;
            flex: auto;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-100">

    <!-- Page Header -->
    <div class="mb-4">
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
        <h1 class="h3 fw-bold mb-1"><?= esc((string)$page_title) ?></h1>
        <p class="text-body-tertiary mb-0"><?= esc((string)$page_description) ?></p>
    </div>

    <!-- Info banner -->
    <div class="alert alert-subtle-warning d-flex align-items-start gap-2 py-2 mb-3">
        <span class="fas fa-circle-info mt-1"></span>
        <span class="small">
            Posisi di sampah <strong>tidak aktif</strong>.
            Pulihkan untuk menggunakannya kembali atau hapus permanen untuk membersihkan data.
        </span>
    </div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <a href="<?= site_url('hrm/positions') ?>" class="btn btn-subtle-secondary btn-sm">
            <span class="fas fa-arrow-left me-1"></span>Kembali
        </a>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <button class="btn btn-subtle-danger btn-sm" id="btn-empty-trash">
                <span class="fas fa-fire me-1"></span>Kosongkan Sampah
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle w-100" id="trash-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Posisi</th>
                    <th>Deskripsi</th>
                    <th>Departemen</th>
                    <th>Status</th>
                    <th>Dihapus</th>
                    <th>Dihapus Oleh</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const PositionTrash = {
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
                scrollX: false,
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [
                    [-1, 10, 25, 50, 100],
                    ['Semua', 10, 25, 50, 100]
                ],
                order: [
                    [5, 'desc']
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
                    url: this.BASE + 'hrm/positions/trash-datatables',
                    type: 'GET',
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
                        render: d =>
                            d ? `<span class="text-muted">${self.e(d.substring(0,60))}${d.length>60?'…':''}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'department_name',
                        render: d => d ?
                            `<span class="badge-dept"><span class="fas fa-building"></span>${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'status',
                        render: d => self.fmtStatus(d)
                    },
                    {
                        data: 'deleted_at',
                        render: d => {
                            if (!d) return '<span class="text-muted">—</span>';
                            const dt = new Date(d);
                            return `<span class="text-danger d-block">${dt.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})}</span>
                                    <small class="text-muted">${dt.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</small>`;
                        }
                    },
                    {
                        data: 'deleted_by_name',
                        render: d => self.fmtUser(d)
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end',
                        render: (d, t, r) => `
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-subtle-success btn-restore" data-id="${r.id}" data-name="${self.e(r.position_name)}" title="Pulihkan">
                                    <span class="fas fa-rotate-left"></span>
                                </button>
                                <button class="btn btn-subtle-danger btn-force-delete" data-id="${r.id}" data-name="${self.e(r.position_name)}" title="Hapus Permanen">
                                    <span class="fas fa-trash-alt"></span>
                                </button>
                            </div>`
                    },
                ],
            });
        },

        // fmtStatus dengan CSS yang sama seperti fmtUser
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

        // fmtUser untuk deleted_by
        fmtUser(name) {
            if (!name) return '<span class="text-muted fst-italic">—</span>';
            return `<span class="badge-user">
                        <span class="fas fa-user-circle"></span>
                        ${this.e(name)}
                    </span>`;
        },

        async restore(id, name) {
            const r = await Swal.fire({
                title: 'Pulihkan Posisi?',
                html: `<strong>"${name}"</strong> akan dipulihkan dengan status <strong>Draft</strong>.`,
                icon: 'question',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#25b003',
                cancelButtonColor: '#748194',
                confirmButtonText: '<span class="fas fa-rotate-left me-1"></span>Pulihkan',
                cancelButtonText: 'Batal',
            });
            if (!r.isConfirmed) return;
            try {
                const res = await this.post(this.BASE + `hrm/positions/restore/${id}`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        async forceDelete(id, name) {
            const r = await Swal.fire({
                title: 'Hapus Permanen?',
                html: `<strong>"${name}"</strong> akan dihapus permanen.<br><span class="text-danger small">Tidak bisa dibatalkan.</span>`,
                icon: 'error',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#e63757',
                cancelButtonColor: '#748194',
                confirmButtonText: '<span class="fas fa-trash-alt me-1"></span>Hapus Permanen',
                cancelButtonText: 'Batal',
                input: 'checkbox',
                inputValue: 0,
                inputPlaceholder: 'Saya yakin ingin menghapus permanen',
                preConfirm: c => {
                    if (!c) {
                        Swal.showValidationMessage('Centang konfirmasi terlebih dahulu');
                        return false;
                    }
                    return true;
                }
            });
            if (!r.isConfirmed) return;
            try {
                const res = await this.post(this.BASE + `hrm/positions/force-delete/${id}`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        async emptyTrash() {
            const count = this.dt.rows().count();
            if (count === 0) {
                this.toast('info', 'Sampah sudah kosong');
                return;
            }
            const r = await Swal.fire({
                title: 'Kosongkan Semua Sampah?',
                html: `${count} posisi akan dihapus <strong>permanen</strong>.<br><span class="text-danger small">Tidak bisa dibatalkan.</span>`,
                icon: 'error',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#e63757',
                cancelButtonColor: '#748194',
                confirmButtonText: '<span class="fas fa-fire me-1"></span>Kosongkan',
                cancelButtonText: 'Batal',
                input: 'checkbox',
                inputValue: 0,
                inputPlaceholder: 'Saya yakin ingin mengosongkan sampah',
                preConfirm: c => {
                    if (!c) {
                        Swal.showValidationMessage('Centang konfirmasi terlebih dahulu');
                        return false;
                    }
                    return true;
                }
            });
            if (!r.isConfirmed) return;
            try {
                const res = await this.post(this.BASE + 'hrm/positions/empty-trash', new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        initEvents() {
            document.getElementById('btn-refresh')?.addEventListener('click', () => this.dt.ajax.reload(null, false));
            document.getElementById('btn-empty-trash')?.addEventListener('click', () => this.emptyTrash());
            $(document).on('click', '.btn-restore', e => {
                const b = $(e.currentTarget);
                this.restore(b.data('id'), b.data('name'));
            });
            $(document).on('click', '.btn-force-delete', e => {
                const b = $(e.currentTarget);
                this.forceDelete(b.data('id'), b.data('name'));
            });
        },

        e(s) {
            if (!s) return '';
            return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
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

    $(document).ready(() => PositionTrash.init());
</script>
<?= $this->endSection() ?>