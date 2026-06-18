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

    /* ── Avatar ───────────────────────────────────────────────── */
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .85rem;
        flex-shrink: 0;
        background: rgba(var(--phoenix-danger-rgb), .12);
        color: var(--phoenix-danger);
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

    .badge-status.deleted {
        background: rgba(var(--phoenix-danger-rgb), .12);
        color: var(--phoenix-danger);
        border: 1px solid rgba(var(--phoenix-danger-rgb), .25);
    }

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

    /* ── Deleted Row Highlight ────────────────────────────────── */
    .table-deleted-row {
        background-color: rgba(var(--phoenix-danger-rgb), .04);
        border-left: 3px solid var(--phoenix-danger);
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

    .btn-group-sm .btn {
        padding: .5rem .75rem;
        font-size: .7rem;
    }

    /* ── Warning Banner ───────────────────────────────────────── */
    .trash-warning {
        background: linear-gradient(135deg, rgba(var(--phoenix-danger-rgb), .08) 0%, rgba(var(--phoenix-warning-rgb), .04) 100%);
        border: 1px solid rgba(var(--phoenix-danger-rgb), .2);
        border-radius: 1rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .trash-warning-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(var(--phoenix-danger-rgb), .12);
        color: var(--phoenix-danger);
        font-size: 1.25rem;
        flex-shrink: 0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$canRestore = canDo('access.users.delete');
$canPurge   = canDo('access.users.purge');
?>
<div class="w-100">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= site_url('access/users') ?>">User Management</a></li>
                    <li class="breadcrumb-item active">Sampah</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-1"><span class="fas fa-trash-alt text-danger me-2"></span>Sampah User</h1>
            <p class="text-body-tertiary mb-0">Data user yang sudah dihapus dapat dipulihkan atau dihapus permanen</p>
        </div>
    </div>

    <!-- Warning Banner -->
    <div class="trash-warning">
        <div class="d-flex gap-3">
            <div class="trash-warning-icon">
                <span class="fas fa-exclamation-triangle"></span>
            </div>
            <div class="flex-grow-1">
                <h6 class="fw-bold text-danger mb-1">
                    <span class="fas fa-info-circle me-1"></span>Data di Sampah Bersifat Sementara
                </h6>
                <p class="text-body-secondary fs-9 mb-0">
                    Data yang berada di sampah akan <strong>dihapus secara permanen</strong> setelah periode retensi berakhir 
                    (biasanya 30 hari). Anda dapat <strong>memulihkan data</strong> atau <strong>menghapusnya sekarang</strong>.
                </p>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Total Terhapus</div>
                            <div class="info-value text-danger" id="stat-total-trash">—</div>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <span class="fas fa-trash"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Akan Dihapus</div>
                            <div class="info-value text-warning" id="stat-expiring-soon">—</div>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <span class="fas fa-hourglass-end"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <div class="d-flex gap-2">
            <?php if ($canPurge): ?>
                <button class="btn btn-subtle-danger btn-sm" id="btn-empty-trash" style="display:none;">
                    <span class="fas fa-broom me-1"></span>Kosongkan Sampah
                </button>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <a href="<?= site_url('access/users') ?>" class="btn btn-primary btn-sm">
                <span class="fas fa-arrow-left me-1"></span>Kembali ke User
            </a>
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
                    <th>Dihapus Pada</th>
                    <th>Dihapus Oleh</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>

</div>

<!-- ═══ Modal: Konfirmasi Restore ════════════════════════════════ -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <span class="fas fa-undo"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Pulihkan User</h5>
                        <p class="text-muted fs-10 mb-0">User akan dipulihkan ke data aktif</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-subtle-info py-2 px-3 fs-10 mb-3">
                    <span class="fas fa-info-circle me-1"></span>
                    <strong>Pulihkan <span id="restore-username">—</span>?</strong>
                    <br>User ini akan dikembalikan ke daftar user aktif.
                </div>
                <div class="alert alert-subtle-warning py-2 px-3 fs-10 d-none" id="restore-error">
                    <span class="fas fa-exclamation-triangle me-1"></span>
                    <span id="restore-error-text"></span>
                </div>
            </div>

            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Batal
                </button>
                <button type="button" class="btn btn-success btn-sm" id="btn-confirm-restore">
                    <span class="fas fa-undo me-1" id="restore-icon"></span>
                    <span id="restore-text">Pulihkan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══ Modal: Konfirmasi Purge (Permanen) ══════════════════════ -->
<div class="modal fade" id="purgeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

            <div class="modal-header border-bottom border-danger py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <span class="fas fa-exclamation"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-danger">Hapus Permanen</h5>
                        <p class="text-muted fs-10 mb-0">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-danger" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-danger py-2 px-3 fs-10 mb-3">
                    <span class="fas fa-exclamation-circle me-1"></span>
                    <strong>Peringatan!</strong> User <strong id="purge-username">—</strong> akan dihapus <strong>PERMANEN</strong>. 
                    Data tidak dapat dipulihkan lagi.
                </div>
                <div class="form-check form-check-lg">
                    <input class="form-check-input" type="checkbox" id="purge-confirm" required>
                    <label class="form-check-label fw-semibold fs-9" for="purge-confirm">
                        Ya, saya memahami bahwa ini tidak dapat dibatalkan. Hapus sekarang.
                    </label>
                </div>
                <div class="alert alert-subtle-danger py-2 px-3 fs-10 d-none" id="purge-error">
                    <span class="fas fa-exclamation-triangle me-1"></span>
                    <span id="purge-error-text"></span>
                </div>
            </div>

            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Batal
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="btn-confirm-purge" disabled>
                    <span class="fas fa-trash me-1" id="purge-icon"></span>
                    <span id="purge-text">Hapus Permanen</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══ Modal: Kosongkan Sampah ══════════════════════════════════ -->
<div class="modal fade" id="emptyTrashModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

            <div class="modal-header border-bottom border-danger py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <span class="fas fa-broom"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-danger">Kosongkan Sampah</h5>
                        <p class="text-muted fs-10 mb-0">Hapus semua user di sampah</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-danger" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-danger py-2 px-3 fs-10 mb-3">
                    <span class="fas fa-exclamation-circle me-1"></span>
                    <strong>Peringatan!</strong> Semua data user di sampah 
                    (<span id="trash-count-confirm">—</span> item) akan dihapus <strong>PERMANEN</strong>. 
                    Tindakan ini tidak dapat dibatalkan.
                </div>
                <div class="form-check form-check-lg">
                    <input class="form-check-input" type="checkbox" id="empty-trash-confirm" required>
                    <label class="form-check-label fw-semibold fs-9" for="empty-trash-confirm">
                        Ya, hapus semua user di sampah sekarang.
                    </label>
                </div>
            </div>

            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Batal
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="btn-confirm-empty" disabled>
                    <span class="fas fa-broom me-1" id="empty-icon"></span>
                    <span id="empty-text">Kosongkan Sampah</span>
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CAN_RESTORE = <?= json_encode($canRestore) ?>;
    const CAN_PURGE = <?= json_encode($canPurge) ?>;

    const UserTrash = {
        BASE: '<?= base_url() ?>',
        dt: null,
        restoreId: null,
        purgeId: null,

        init() {
            this.initDatatable();
            this.initEvents();
            this.loadStats();
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
                const d = await this.get(this.BASE + 'access/users/trash-stats');
                if (d.status !== 'success') return;
                document.getElementById('stat-total-trash').textContent = d.data.total ?? 0;
                document.getElementById('stat-expiring-soon').textContent = d.data.expiring_soon ?? 0;
                document.getElementById('btn-empty-trash').style.display = (d.data.total > 0) ? 'inline-block' : 'none';
            } catch {}
        },

        /* ── DataTable ────────────────────────────────────────────── */
        initDatatable() {
            const self = this;
            this.dt = $('#user-trash-table').DataTable({
                scrollX: true,
                responsive: false,
                autoWidth: true,
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
                columnDefs: [
                    {targets: 0, width: '45px'},
                    {targets: 1, width: '200px'},
                    {targets: 2, width: '180px'},
                    {targets: 3, width: '180px'},
                    {targets: 4, width: '200px'},
                    {targets: 5, width: '140px'},
                    {targets: 6, width: '140px'},
                    {targets: 7, width: '140px'},
                ],
                columns: [
                    {
                        data: 'no',
                        orderable: false,
                        searchable: false
                    },
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
                    {
                        data: 'email',
                        render: d => d ? self.e(d) : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'employee_name',
                        render: d => d ? self.e(d) : '<span class="text-muted fst-italic">—</span>'
                    },
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
                    {
                        data: 'deleted_at',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: 'deleted_by_user',
                        render: d => d ? self.e(d) : '<span class="text-muted">—</span>'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end',
                        render: (d, t, r) => {
                            let btns = '';

                            if (CAN_RESTORE) {
                                btns += `<button class="btn btn-subtle-success btn-sm btn-restore" 
                                    data-id="${r.id}" 
                                    data-username="${self.e(r.username)}" 
                                    title="Pulihkan">
                                    <span class="fas fa-undo"></span>
                                </button>`;
                            }

                            if (CAN_PURGE) {
                                btns += `<button class="btn btn-subtle-danger btn-sm btn-purge" 
                                    data-id="${r.id}" 
                                    data-username="${self.e(r.username)}" 
                                    title="Hapus Permanen">
                                    <span class="fas fa-trash"></span>
                                </button>`;
                            }

                            return `<div class="btn-group btn-group-sm">${btns}</div>`;
                        }
                    },
                ],
                rowCallback: (row, data) => {
                    $(row).addClass('table-deleted-row');
                }
            });
        },

        /* ── Restore ──────────────────────────────────────────────── */
        openRestoreModal(id, username) {
            this.restoreId = id;
            document.getElementById('restore-username').textContent = username;
            document.getElementById('restore-error').classList.add('d-none');
            new bootstrap.Modal(document.getElementById('restoreModal')).show();
        },

        async confirmRestore() {
            const btn = document.getElementById('btn-confirm-restore');
            const icon = document.getElementById('restore-icon');
            btn.disabled = true;
            icon.className = 'spinner-border spinner-border-sm me-1';

            try {
                const res = await this.post(this.BASE + `access/users/restore/${this.restoreId}`, new FormData());
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('restoreModal'))?.hide();
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else {
                    document.getElementById('restore-error').classList.remove('d-none');
                    document.getElementById('restore-error-text').textContent = res.message ?? 'Terjadi kesalahan';
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                btn.disabled = false;
                icon.className = 'fas fa-undo me-1';
            }
        },

        /* ── Purge ────────────────────────────────────────────────── */
        openPurgeModal(id, username) {
            this.purgeId = id;
            document.getElementById('purge-username').textContent = username;
            document.getElementById('purge-confirm').checked = false;
            document.getElementById('purge-error').classList.add('d-none');
            document.getElementById('btn-confirm-purge').disabled = true;
            new bootstrap.Modal(document.getElementById('purgeModal')).show();
        },

        async confirmPurge() {
            if (!document.getElementById('purge-confirm').checked) {
                alert('Anda harus mengkonfirmasi sebelum melanjutkan');
                return;
            }

            const btn = document.getElementById('btn-confirm-purge');
            const icon = document.getElementById('purge-icon');
            btn.disabled = true;
            icon.className = 'spinner-border spinner-border-sm me-1';

            try {
                const res = await this.post(this.BASE + `access/users/purge/${this.purgeId}`, new FormData());
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('purgeModal'))?.hide();
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else {
                    document.getElementById('purge-error').classList.remove('d-none');
                    document.getElementById('purge-error-text').textContent = res.message ?? 'Terjadi kesalahan';
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                btn.disabled = false;
                icon.className = 'fas fa-trash me-1';
            }
        },

        /* ── Empty Trash ──────────────────────────────────────────── */
        openEmptyTrashModal(total) {
            document.getElementById('trash-count-confirm').textContent = total;
            document.getElementById('empty-trash-confirm').checked = false;
            document.getElementById('btn-confirm-empty').disabled = true;
            new bootstrap.Modal(document.getElementById('emptyTrashModal')).show();
        },

        async confirmEmptyTrash() {
            if (!document.getElementById('empty-trash-confirm').checked) {
                alert('Anda harus mengkonfirmasi sebelum melanjutkan');
                return;
            }

            const btn = document.getElementById('btn-confirm-empty');
            const icon = document.getElementById('empty-icon');
            btn.disabled = true;
            icon.className = 'spinner-border spinner-border-sm me-1';

            try {
                const res = await this.post(this.BASE + 'access/users/empty-trash', new FormData());
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('emptyTrashModal'))?.hide();
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else {
                    this.toast('error', res.message ?? 'Terjadi kesalahan');
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                btn.disabled = false;
                icon.className = 'fas fa-broom me-1';
            }
        },

        /* ── Events ───────────────────────────────────────────────── */
        initEvents() {
            document.getElementById('btn-refresh')?.addEventListener('click', () => {
                this.dt.ajax.reload(() => this.loadStats(), false);
            });

            document.getElementById('btn-empty-trash')?.addEventListener('click', () => {
                const total = this.dt.settings()[0].aoData.length;
                if (total === 0) {
                    alert('Sampah kosong');
                    return;
                }
                this.openEmptyTrashModal(total);
            });

            document.getElementById('btn-confirm-restore')?.addEventListener('click', () => this.confirmRestore());
            document.getElementById('btn-confirm-purge')?.addEventListener('click', () => this.confirmPurge());
            document.getElementById('btn-confirm-empty')?.addEventListener('click', () => this.confirmEmptyTrash());

            document.getElementById('purge-confirm')?.addEventListener('change', e => {
                document.getElementById('btn-confirm-purge').disabled = !e.target.checked;
            });

            document.getElementById('empty-trash-confirm')?.addEventListener('change', e => {
                document.getElementById('btn-confirm-empty').disabled = !e.target.checked;
            });

            $(document).on('click', '.btn-restore', e => {
                const btn = $(e.currentTarget);
                this.openRestoreModal(btn.data('id'), btn.data('username'));
            });

            $(document).on('click', '.btn-purge', e => {
                const btn = $(e.currentTarget);
                this.openPurgeModal(btn.data('id'), btn.data('username'));
            });
        },

        /* ── Helpers ──────────────────────────────────────────────── */
        e(s) {
            if (!s) return '';
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        },
        fmtDate(d) {
            if (!d) return '<span class="text-muted">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">${dt.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})}</span>
                <small class="text-muted">${dt.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</small>`;
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
