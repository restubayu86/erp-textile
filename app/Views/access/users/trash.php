<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    body {
        overflow-x: hidden;
    }

    /* ── Avatar ───────────────────────────────────────────────── */
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .85rem;
        flex-shrink: 0;
        background: rgba(var(--phoenix-danger-rgb), .1);
        color: var(--phoenix-danger);
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

    /* ── Group badge ──────────────────────────────────────────── */
    .badge-group {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        padding: .2rem .5rem;
        border-radius: 14px;
        font-size: .68rem;
        font-weight: 600;
        background: rgba(var(--phoenix-info-rgb), .12);
        color: var(--phoenix-info);
        border: 1px solid rgba(var(--phoenix-info-rgb), .25);
        white-space: nowrap;
    }

    .badge-group.superadmin {
        background: rgba(var(--phoenix-danger-rgb), .12);
        color: var(--phoenix-danger);
        border-color: rgba(var(--phoenix-danger-rgb), .25);
    }

    .badge-group.admin {
        background: rgba(var(--phoenix-warning-rgb), .12);
        color: var(--phoenix-warning);
        border-color: rgba(var(--phoenix-warning-rgb), .25);
    }

    /* ── DataTables layout ────────────────────────────────────── */
    #user-trash-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #user-trash-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: .375rem 1rem;
        text-align: center;
    }

    #user-trash-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #user-trash-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #user-trash-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #user-trash-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #user-trash-table_wrapper .dataTables_filter label,
    #user-trash-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #user-trash-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 .5rem;
        border-radius: .375rem;
    }

    #user-trash-table_wrapper .dataTables_paginate .paginate_button {
        padding: .375rem .75rem;
        margin: 0 .25rem;
        border-radius: .375rem;
    }

    #user-trash-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #user-trash-table {
        width: 100% !important;
    }

    .btn-group-sm .btn {
        padding: .5rem .75rem;
        font-size: .7rem;
    }

    @media (max-width: 768px) {
        #user-trash-table_wrapper .bottom {
            flex-direction: column;
            align-items: stretch;
        }

        #user-trash-table_wrapper .bottom .dataTables_length,
        #user-trash-table_wrapper .bottom .dataTables_paginate,
        #user-trash-table_wrapper .bottom .dataTables_info {
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
                <li class="breadcrumb-item"><a href="<?= site_url('access/users') ?>">User Management</a></li>
                <li class="breadcrumb-item active">Sampah</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold mb-1">Sampah User</h1>
        <p class="text-body-tertiary mb-0">User yang sudah dihapus dapat dipulihkan atau dihapus permanen</p>
    </div>

    <!-- Info banner -->
    <div class="alert alert-subtle-warning d-flex align-items-start gap-2 py-2 mb-3">
        <span class="fas fa-circle-info mt-1"></span>
        <span class="small">
            User di sampah <strong>tidak aktif</strong> dan tidak dapat login.
            Pulihkan untuk mengaktifkan kembali, atau hapus permanen untuk membersihkan data.
        </span>
    </div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <a href="<?= site_url('access/users') ?>" class="btn btn-subtle-secondary btn-sm">
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
        <table class="table table-hover fs-9 nowrap align-middle" id="user-trash-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Karyawan</th>
                    <th>Groups</th>
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
    const UserTrash = {
        BASE: '<?= base_url() ?>',
        dt: null,

        init() {
            this.initDatatable();
            this.initEvents();
        },

        /* ── CSRF ─────────────────────────────────────────────────── */
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

        /* ── DataTable ────────────────────────────────────────────── */
        initDatatable() {
            const self = this;
            this.dt = $('#user-trash-table').DataTable({
                responsive: false,
                scrollX: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [
                    [-1, 10, 25, 50, 100],
                    ['Semua', 10, 25, 50, 100]
                ],
                order: [
                    [6, 'desc']
                ],
                dom: '<"top"f>rt<"bottom"lpi>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari username / email...',
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
                    url: this.BASE + 'access/users/trash-datatables',
                    type: 'GET',
                    error: () => self.toast('error', 'Gagal memuat data'),
                },
                columnDefs: [{
                        targets: 0,
                        width: '45px'
                    },
                    {
                        targets: 1,
                        width: '180px'
                    },
                    {
                        targets: 2,
                        width: '160px'
                    },
                    {
                        targets: 3,
                        width: '160px'
                    },
                    {
                        targets: 4,
                        width: '200px'
                    },
                    {
                        targets: 5,
                        width: '100px'
                    },
                    {
                        targets: 6,
                        width: '130px'
                    },
                    {
                        targets: 7,
                        width: '130px'
                    },
                    {
                        targets: 8,
                        width: '90px'
                    },
                ],
                columns: [
                    /* 0 No */
                    {
                        data: 'no',
                        orderable: false,
                        searchable: false
                    },

                    /* 1 User */
                    {
                        data: null,
                        render: (d, t, r) => {
                            const initial = (r.username || '?')[0].toUpperCase();
                            return `<div class="d-flex align-items-center gap-2">
                        <span class="user-avatar">${self.e(initial)}</span>
                        <div>
                            <div class="fw-semibold">${self.e(r.username)}</div>
                            <div class="text-muted small">ID: ${r.id}</div>
                        </div>
                    </div>`;
                        }
                    },

                    /* 2 Email */
                    {
                        data: 'email',
                        render: d => d ? self.e(d) : '<span class="text-muted fst-italic">—</span>'
                    },

                    /* 3 Karyawan */
                    {
                        data: 'employee_name',
                        render: (d, t, r) => {
                            if (!d) return '<span class="text-muted fst-italic">—</span>';
                            const nik = r.employee_nik ? `<small class="text-muted ms-1">· ${self.e(r.employee_nik)}</small>` : '';
                            return `<span>${self.e(d)}${nik}</span>`;
                        }
                    },

                    /* 4 Groups */
                    {
                        data: 'groups',
                        orderable: false,
                        render: groups => {
                            if (!groups || !groups.length) return '<span class="text-muted fst-italic">Tidak ada</span>';
                            return groups.map(g => {
                                const cls = ['superadmin', 'admin'].includes(g) ? g : '';
                                return `<span class="badge-group ${cls} me-1">${self.e(g)}</span>`;
                            }).join('');
                        }
                    },

                    /* 5 Status */
                    {
                        data: 'active',
                        render: d => {
                            const ok = !!Number(d);
                            return `<span class="badge-status ${ok ? 'active' : 'inactive'}">
                        <span class="fas ${ok ? 'fa-check-circle' : 'fa-times-circle'}"></span>
                        ${ok ? 'Aktif' : 'Nonaktif'}
                    </span>`;
                        }
                    },

                    /* 6 Dihapus */
                    {
                        data: 'deleted_at',
                        orderable: true,
                        render: d => {
                            if (!d) return '<span class="text-muted">—</span>';
                            const dt = new Date(d);
                            return `<span class="text-danger d-block">${dt.toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'})}</span>
                            <small class="text-muted">${dt.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</small>`;
                        }
                    },

                    /* 7 Dihapus Oleh */
                    {
                        data: 'deleted_by_name',
                        orderable: false,
                        render: d => d ? self.e(d) : '<span class="text-muted fst-italic">—</span>'
                    },

                    /* 8 Aksi */
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end',
                        render: (d, t, r) => `
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-subtle-success btn-restore"
                            data-id="${r.id}" data-username="${self.e(r.username)}" title="Pulihkan">
                            <span class="fas fa-rotate-left"></span>
                        </button>
                        <button class="btn btn-subtle-danger btn-force-delete"
                            data-id="${r.id}" data-username="${self.e(r.username)}" title="Hapus Permanen">
                            <span class="fas fa-trash-alt"></span>
                        </button>
                    </div>`
                    },
                ],
            });
        },

        /* ── Restore ──────────────────────────────────────────────── */
        async restore(id, username) {
            const r = await Swal.fire({
                title: 'Pulihkan User?',
                html: `<strong>"${username}"</strong> akan dipulihkan dan dapat login kembali.`,
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
                const res = await this.post(this.BASE + `access/users/restore/${id}`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        /* ── Force Delete ─────────────────────────────────────────── */
        async forceDelete(id, username) {
            const r = await Swal.fire({
                title: 'Hapus Permanen?',
                html: `<strong>"${username}"</strong> akan dihapus permanen.<br>
                   <span class="text-danger small">Semua data user tidak bisa dipulihkan.</span>`,
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
                const res = await this.post(this.BASE + `access/users/purge/${id}`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        /* ── Empty Trash ──────────────────────────────────────────── */
        async emptyTrash() {
            const count = this.dt.rows().count();
            if (count === 0) {
                this.toast('info', 'Sampah sudah kosong');
                return;
            }
            const r = await Swal.fire({
                title: 'Kosongkan Semua Sampah?',
                html: `${count} user akan dihapus <strong>permanen</strong>.<br>
                   <span class="text-danger small">Semua data tidak bisa dipulihkan.</span>`,
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
                const res = await this.post(this.BASE + 'access/users/empty-trash', new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        /* ── Events ───────────────────────────────────────────────── */
        initEvents() {
            document.getElementById('btn-refresh')
                ?.addEventListener('click', () => this.dt.ajax.reload(null, false));
            document.getElementById('btn-empty-trash')
                ?.addEventListener('click', () => this.emptyTrash());
            $(document).on('click', '.btn-restore', e => {
                const b = $(e.currentTarget);
                this.restore(b.data('id'), b.data('username'));
            });
            $(document).on('click', '.btn-force-delete', e => {
                const b = $(e.currentTarget);
                this.forceDelete(b.data('id'), b.data('username'));
            });
        },

        /* ── Helpers ──────────────────────────────────────────────── */
        e(s) {
            if (!s) return '';
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
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

    $(document).ready(() => UserTrash.init());
</script>
<?= $this->endSection() ?>