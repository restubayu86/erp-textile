<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    body {
        overflow-x: hidden;
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
        background: rgba(var(--phoenix-danger-rgb), .1);
        color: var(--phoenix-danger);
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

    /* ── User / Dept badge ────────────────────────────────────── */
    .badge-user {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .25rem .55rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        background: rgba(var(--phoenix-danger-rgb), .12);
        color: var(--phoenix-danger);
        border: 1px solid rgba(var(--phoenix-danger-rgb), .25);
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

    /* ── DataTables layout ────────────────────────────────────── */
    #trash-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #trash-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: .375rem 1rem;
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
        margin: 0 .5rem;
        border-radius: .375rem;
    }

    #trash-table_wrapper .dataTables_paginate .paginate_button {
        padding: .375rem .75rem;
        margin: 0 .25rem;
        border-radius: .375rem;
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
        padding: .25rem .5rem;
        font-size: .7rem;
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
                        <li class="breadcrumb-item active"><?= esc((string) $crumb['name']) ?></li>
                    <?php else: ?>
                        <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= esc((string) $crumb['name']) ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </nav>
        <h1 class="h3 fw-bold mb-1"><?= esc((string) $page_title) ?></h1>
        <p class="text-body-tertiary mb-0"><?= esc((string) $page_description) ?></p>
    </div>

    <!-- Info banner -->
    <div class="alert alert-subtle-warning d-flex align-items-start gap-2 py-2 mb-3">
        <span class="fas fa-circle-info mt-1"></span>
        <span class="small">
            Karyawan di sampah <strong>tidak aktif</strong> dan tidak muncul di daftar utama.
            Pulihkan untuk menggunakannya kembali, atau hapus permanen untuk membersihkan data.
        </span>
    </div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <a href="<?= site_url('hrm/employees') ?>" class="btn btn-subtle-secondary btn-sm">
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
        <table class="table table-hover fs-9 nowrap align-middle" id="trash-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Karyawan</th>
                    <th>JK</th>
                    <th>Posisi</th>
                    <th>Departemen</th>
                    <th>Shift</th>
                    <th>Status Kerja</th>
                    <th>Status</th>
                    <th>Dihapus</th>
                    <th>Dihapus Oleh</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?= $this->include('templates/footer') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const EmployeeTrash = {
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
            this.dt = $('#trash-table').DataTable({
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
                    [8, 'desc']
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
                ajax: {
                    url: this.BASE + 'hrm/employees/trash-datatables',
                    type: 'GET',
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
                        width: '140px'
                    },
                    {
                        targets: 5,
                        width: '90px'
                    },
                    {
                        targets: 6,
                        width: '100px'
                    },
                    {
                        targets: 7,
                        width: '90px'
                    },
                    {
                        targets: 8,
                        width: '120px'
                    },
                    {
                        targets: 9,
                        width: '130px'
                    },
                    {
                        targets: 10,
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

                    /* 1 Karyawan */
                    {
                        data: null,
                        render: (d, t, r) => {
                            const initial = (r.fullname || '?')[0].toUpperCase();
                            const avatar = r.photo ?
                                `<img src="${self.BASE}uploads/employees/${self.e(r.photo)}" class="emp-avatar me-2" onerror="this.style.display='none'">` :
                                `<span class="emp-avatar-placeholder me-2">${self.e(initial)}</span>`;
                            return `<div class="d-flex align-items-center">${avatar}
                        <div>
                            <div class="fw-semibold">${self.e(r.fullname)}
                                ${r.nickname ? `<small class="text-body-quaternary ms-1">(${self.e(r.nickname)})</small>` : ''}
                            </div>
                            <div class="text-muted small font-monospace">${self.e(r.nik)}</div>
                        </div>
                    </div>`;
                        }
                    },

                    /* 2 JK */
                    {
                        data: 'gender',
                        render: d => {
                            if (d === 'L') return `<span class="badge-gender male" title="Laki-laki"><span class="fas fa-mars"></span></span>`;
                            if (d === 'P') return `<span class="badge-gender female" title="Perempuan"><span class="fas fa-venus"></span></span>`;
                            return '<span class="text-muted">—</span>';
                        }
                    },

                    /* 3 Posisi */
                    {
                        data: 'position_name',
                        render: d =>
                            d ? `<span class="fw-semibold fs-9">${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },

                    /* 4 Departemen */
                    {
                        data: 'department_name',
                        render: d =>
                            d ? `<span class="badge-dept"><span class="fas fa-building me-1"></span>${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },

                    /* 5 Shift */
                    {
                        data: 'shift',
                        render: d => {
                            if (!d) return '<span class="text-muted">—</span>';
                            const m = {
                                NS: ['badge-phoenix-secondary', 'Non-Shift'],
                                A: ['badge-phoenix-primary', 'Shift A'],
                                B: ['badge-phoenix-info', 'Shift B'],
                                C: ['badge-phoenix-success', 'Shift C'],
                                D: ['badge-phoenix-warning', 'Shift D'],
                                E: ['badge-phoenix-danger', 'Shift E'],
                            };
                            const [cls, label] = m[d] ?? ['badge-phoenix-secondary', d];
                            return `<span class="badge badge-phoenix rounded-pill p-2 fs-10 ${cls}"><span class="fas fa-clock me-1"></span>${label}</span>`;
                        }
                    },

                    /* 6 Status Kerja */
                    {
                        data: 'employment_status',
                        render: d => {
                            if (!d) return '<span class="text-muted">—</span>';
                            const m = {
                                tetap: ['badge-phoenix-success', 'Tetap'],
                                kontrak: ['badge-phoenix-warning', 'Kontrak'],
                                magang: ['badge-phoenix-info', 'Magang'],
                            };
                            const [cls, label] = m[d.toLowerCase()] ?? ['badge-phoenix-secondary', d];
                            return `<span class="badge badge-phoenix p-1 fs-10 ${cls}">${label}</span>`;
                        }
                    },

                    /* 7 Status */
                    {
                        data: 'status',
                        render: d => {
                            if (!d) return '—';
                            const ok = d.toLowerCase() === 'active';
                            return `<span class="badge-status ${ok ? 'active' : 'inactive'}">
                        <span class="fas ${ok ? 'fa-check-circle' : 'fa-times-circle'}"></span>
                        ${ok ? 'Active' : 'Inactive'}
                    </span>`;
                        }
                    },

                    /* 8 Dihapus */
                    {
                        data: 'deleted_at',
                        orderable: true,
                        render: d => {
                            if (!d) return '<span class="text-muted">—</span>';
                            const dt = new Date(d);
                            return `<span class="text-danger d-block">${dt.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})}</span>
                            <small class="text-muted">${dt.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</small>`;
                        }
                    },

                    /* 9 Dihapus Oleh */
                    {
                        data: 'deleted_by_name',
                        orderable: false,
                        render: d =>
                            d ? `<span class="badge-user"><span class="fas fa-user-circle me-1"></span>${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },

                    /* 10 Aksi */
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end',
                        render: (d, t, r) => `
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-subtle-success btn-restore"
                            data-id="${r.id}" data-name="${self.e(r.fullname)}" title="Pulihkan">
                            <span class="fas fa-rotate-left"></span>
                        </button>
                        <button class="btn btn-subtle-danger btn-force-delete"
                            data-id="${r.id}" data-name="${self.e(r.fullname)}" title="Hapus Permanen">
                            <span class="fas fa-trash-alt"></span>
                        </button>
                    </div>`
                    },
                ],
            });
        },

        /* ── Restore ──────────────────────────────────────────────── */
        async restore(id, name) {
            const r = await Swal.fire({
                title: 'Pulihkan Karyawan?',
                html: `<strong>"${name}"</strong> akan dipulihkan dan aktif kembali.`,
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
                const res = await this.post(this.BASE + `hrm/employees/restore/${id}`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        /* ── Force Delete ─────────────────────────────────────────── */
        async forceDelete(id, name) {
            const r = await Swal.fire({
                title: 'Hapus Permanen?',
                html: `<strong>"${name}"</strong> akan dihapus permanen.<br>
                   <span class="text-danger small">Semua data karyawan ini tidak bisa dipulihkan.</span>`,
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
                const res = await this.post(this.BASE + `hrm/employees/force-delete/${id}`, new FormData());
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
                html: `${count} karyawan akan dihapus <strong>permanen</strong>.<br>
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
                const res = await this.post(this.BASE + 'hrm/employees/empty-trash', new FormData());
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
                this.restore(b.data('id'), b.data('name'));
            });
            $(document).on('click', '.btn-force-delete', e => {
                const b = $(e.currentTarget);
                this.forceDelete(b.data('id'), b.data('name'));
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

    $(document).ready(() => EmployeeTrash.init());
</script>
<?= $this->endSection() ?>