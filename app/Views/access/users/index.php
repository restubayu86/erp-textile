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
        background: rgba(var(--phoenix-primary-rgb), .12);
        color: var(--phoenix-primary);
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

    /* ── Group badges ─────────────────────────────────────────── */
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
    #user-table_wrapper .top {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    #user-table_wrapper .top input {
        width: 300px;
        border-radius: 20px;
        padding: .375rem 1rem;
        text-align: center;
    }

    #user-table_wrapper .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #user-table_wrapper .bottom .dataTables_length {
        flex: 1;
        text-align: left;
        order: 1;
    }

    #user-table_wrapper .bottom .dataTables_paginate {
        flex: 1;
        text-align: center;
        order: 2;
    }

    #user-table_wrapper .bottom .dataTables_info {
        flex: 1;
        text-align: right;
        order: 3;
    }

    #user-table_wrapper .dataTables_filter label,
    #user-table_wrapper .dataTables_length label {
        margin-bottom: 0;
    }

    #user-table_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        margin: 0 .5rem;
        border-radius: .375rem;
    }

    #user-table_wrapper .dataTables_paginate .paginate_button {
        padding: .375rem .75rem;
        margin: 0 .25rem;
        border-radius: .375rem;
    }

    #user-table_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--phoenix-primary);
        border-color: var(--phoenix-primary);
        color: white !important;
    }

    #user-table {
        width: 100% !important;
    }

    @media (max-width: 768px) {
        #user-table_wrapper .bottom {
            flex-direction: column;
            align-items: stretch;
        }

        #user-table_wrapper .bottom .dataTables_length,
        #user-table_wrapper .bottom .dataTables_paginate,
        #user-table_wrapper .bottom .dataTables_info {
            text-align: center;
            flex: auto;
        }
    }

    /* ── Form validation ──────────────────────────────────────── */
    .invalid-feedback {
        display: none;
    }

    .is-invalid~.invalid-feedback,
    .is-invalid+.invalid-feedback {
        display: block;
    }

    /* ── Groups checklist ─────────────────────────────────────── */
    .group-check-item {
        display: flex;
        align-items: flex-start;
        gap: .6rem;
        padding: .6rem .75rem;
        border: 1px solid var(--phoenix-border-color);
        border-radius: .5rem;
        cursor: pointer;
        transition: all .15s;
    }

    .group-check-item:hover {
        border-color: var(--phoenix-primary);
        background: rgba(var(--phoenix-primary-rgb), .04);
    }

    .group-check-item input:checked~.group-check-label .group-check-title {
        color: var(--phoenix-primary);
    }

    .group-check-item input:checked {
        accent-color: var(--phoenix-primary);
    }

    .group-check-title {
        font-weight: 600;
        font-size: .8rem;
    }

    .group-check-desc {
        font-size: .7rem;
        color: var(--phoenix-secondary-color);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$canCreate = canDo('access.users.create');
$canEdit   = canDo('access.users.edit');
$canDelete = canDo('access.users.delete');
?>
<div class="w-100">

    <!-- Page Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <?php foreach ($breadcrumbs as $crumb): ?>
                    <?php if (!empty($crumb['active'])): ?>
                        <li class="breadcrumb-item active"><?= esc((string)(string) $crumb['name']) ?></li>
                    <?php else: ?>
                        <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= esc((string)(string) $crumb['name']) ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </nav>
        <h1 class="h3 fw-bold mb-1"><?= esc((string)$page_title) ?></h1>
        <p class="text-body-tertiary mb-0"><?= esc((string)$page_description) ?></p>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <?php foreach (
            [
                ['id' => 'stat-total',    'label' => 'Total User', 'icon' => 'fa-users',        'color' => 'primary'],
                ['id' => 'stat-active',   'label' => 'Aktif',       'icon' => 'fa-check-circle', 'color' => 'success'],
                ['id' => 'stat-inactive', 'label' => 'Nonaktif',    'icon' => 'fa-times-circle', 'color' => 'secondary'],
                ['id' => 'stat-admin',    'label' => 'Admin & Superadmin', 'icon' => 'fa-user-shield', 'color' => 'danger'],
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
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <div class="d-flex gap-2 align-items-center">
            <select class="form-select form-select-sm" id="filter-group" style="width:200px">
                <option value="">Semua Group</option>
                <?php foreach ($groups as $g): ?>
                    <option value="<?= esc((string)$g['key']) ?>"><?= esc((string)$g['title']) ?></option>
                <?php endforeach; ?>
            </select>
            <select class="form-select form-select-sm" id="filter-status" style="width:160px">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-subtle-secondary btn-sm" id="btn-refresh">
                <span class="fas fa-sync-alt me-1"></span>Refresh
            </button>
            <?php if ($canCreate): ?>
                <button class="btn btn-primary btn-sm" id="btn-create">
                    <span class="fas fa-user-plus me-1"></span>Tambah User
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="user-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Groups</th>
                    <th>Status</th>
                    <th>Terakhir Aktif</th>
                    <th>Dibuat</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- ═══ Modal Tambah / Edit User ════════════════════════════════ -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <span class="fas fa-user-cog"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modal-title">Tambah User</h5>
                        <p class="text-muted fs-10 mb-0" id="modal-subtitle">Buat akun pengguna baru</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-subtle-danger py-2 px-3 fs-10 d-none" id="modal-alert">
                    <span class="fas fa-exclamation-triangle me-1"></span>
                    <span id="modal-alert-text"></span>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-username">
                            Username <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-sm" id="f-username"
                            placeholder="cth: budi.santoso" maxlength="30" autocomplete="off">
                        <div class="invalid-feedback" id="err-username"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-email">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control form-control-sm" id="f-email"
                            placeholder="user@erp-textile.local" maxlength="255" autocomplete="off">
                        <div class="invalid-feedback" id="err-email"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-password">
                            Password <span class="text-danger" id="pass-required">*</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="password" class="form-control form-control-sm" id="f-password"
                                placeholder="Minimal 8 karakter" autocomplete="new-password">
                            <span class="input-group-text" id="toggle-password" style="cursor:pointer">
                                <span class="fas fa-eye"></span>
                            </span>
                        </div>
                        <div class="form-text fs-10" id="pass-hint">Minimal 8 karakter</div>
                        <div class="invalid-feedback" id="err-password"></div>
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

<!-- ═══ Modal Assign Groups ══════════════════════════════════════ -->
<div class="modal fade" id="groupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <span class="fas fa-users-cog"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Assign Group</h5>
                        <p class="text-muted fs-10 mb-0">Atur role untuk <strong id="group-modal-username">—</strong></p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="alert alert-subtle-danger py-2 px-3 fs-10 d-none" id="group-modal-alert">
                    <span class="fas fa-exclamation-triangle me-1"></span>
                    <span id="group-modal-alert-text"></span>
                </div>

                <div class="d-flex flex-column gap-2" id="group-checklist">
                    <?php foreach ($groups as $g): ?>
                        <label class="group-check-item">
                            <input type="checkbox" class="form-check-input mt-1" name="groups[]" value="<?= esc((string)$g['key']) ?>">
                            <span class="group-check-label">
                                <span class="group-check-title d-block"><?= esc((string)$g['title']) ?></span>
                                <span class="group-check-desc"><?= esc((string)$g['description']) ?></span>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Batal
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save-groups">
                    <span class="fas fa-save me-1" id="group-save-icon"></span>
                    <span id="group-save-text">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CAN_EDIT = <?= json_encode($canEdit) ?>;
    const CAN_DELETE = <?= json_encode($canDelete) ?>;
    const CURRENT_USER_ID = <?= (int) (auth()->id() ?? 0) ?>;

    const UserMgmt = {
        BASE: '<?= base_url() ?>',
        dt: null,
        editId: null,
        groupEditId: null,
        filters: {
            group: '',
            status: ''
        },

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

        /* ── Stats (dihitung dari datatable, bukan endpoint terpisah) ── */
        async loadStats() {
            try {
                const d = await this.get(this.BASE + 'access/users/datatables?length=-1');
                if (!d.data) return;
                const rows = d.data;
                const total = rows.length;
                const active = rows.filter(r => r.active == 1).length;
                const inactive = total - active;
                const admins = rows.filter(r => (r.groups || []).some(g => ['superadmin', 'admin'].includes(g))).length;

                document.getElementById('stat-total').textContent = total;
                document.getElementById('stat-active').textContent = active;
                document.getElementById('stat-inactive').textContent = inactive;
                document.getElementById('stat-admin').textContent = admins;
            } catch {}
        },

        /* ── DataTable ────────────────────────────────────────────── */
        initDatatable() {
            const self = this;
            this.dt = $('#user-table').DataTable({
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
                    [1, 'asc']
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
                    url: this.BASE + 'access/users/datatables',
                    type: 'GET',
                    data: d => {
                        d.filter_name = $('#user-table_wrapper .dataTables_filter input').val() || '';
                        d.filter_group = self.filters.group;
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
                        width: '220px'
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
                        width: '110px'
                    },
                    {
                        targets: 5,
                        width: '140px'
                    },
                    {
                        targets: 6,
                        width: '140px'
                    },
                    {
                        targets: 7,
                        width: '120px'
                    },
                ],
                columns: [{
                        data: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: null,
                        render: (d, t, r) => {
                            const initial = (r.username || '?')[0].toUpperCase();
                            const youBadge = r.id == CURRENT_USER_ID ? '<span class="badge bg-primary bg-opacity-10 text-primary ms-1 fs-10">Anda</span>' : '';
                            return `<div class="d-flex align-items-center gap-2">
                        <span class="user-avatar">${self.e(initial)}</span>
                        <div>
                            <div class="fw-semibold">${self.e(r.username)}${youBadge}</div>
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
                        data: 'groups',
                        orderable: false,
                        render: groups => {
                            if (!groups || !groups.length) return '<span class="text-muted fst-italic">Belum ada group</span>';
                            return groups.map(g => {
                                const cls = ['superadmin', 'admin'].includes(g) ? g : '';
                                return `<span class="badge-group ${cls} me-1">${self.e(g)}</span>`;
                            }).join('');
                        }
                    },
                    {
                        data: 'active',
                        render: d => {
                            const ok = !!Number(d);
                            return `<span class="badge-status ${ok?'active':'inactive'}">
                        <span class="fas ${ok?'fa-check-circle':'fa-times-circle'}"></span>${ok?'Aktif':'Nonaktif'}
                    </span>`;
                        }
                    },
                    {
                        data: 'last_active',
                        render: d => self.fmtDate(d)
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
                            const isMe = r.id == CURRENT_USER_ID;
                            let btns = '';

                            if (CAN_EDIT) {
                                btns += `<button class="btn btn-subtle-info btn-sm btn-groups" data-id="${r.id}" data-username="${self.e(r.username)}" data-groups='${JSON.stringify(r.groups||[])}' title="Assign Group">
                                    <span class="fas fa-users-cog"></span>
                                 </button>`;
                                btns += `<button class="btn btn-subtle-primary btn-sm btn-edit" data-id="${r.id}" title="Edit">
                                    <span class="fas fa-pencil-alt"></span>
                                 </button>`;
                                if (!isMe) {
                                    const active = !!Number(r.active);
                                    btns += `<button class="btn btn-subtle-${active?'warning':'success'} btn-sm btn-toggle" data-id="${r.id}" data-active="${active?1:0}" title="${active?'Nonaktifkan':'Aktifkan'}">
                                        <span class="fas ${active?'fa-user-slash':'fa-user-check'}"></span>
                                     </button>`;
                                }
                            }
                            if (CAN_DELETE && !isMe) {
                                btns += `<button class="btn btn-subtle-danger btn-sm btn-delete" data-id="${r.id}" data-name="${self.e(r.username)}" title="Hapus">
                                    <span class="fas fa-trash"></span>
                                 </button>`;
                            }
                            return `<div class="btn-group btn-group-sm">${btns}</div>`;
                        }
                    },
                ],
            });
        },

        /* ── Modal: Create / Edit User ───────────────────────────────── */
        openCreate() {
            this.editId = null;
            this._resetUserModal();
            document.getElementById('modal-title').textContent = 'Tambah User';
            document.getElementById('modal-subtitle').textContent = 'Buat akun pengguna baru';
            document.getElementById('save-text').textContent = 'Simpan';
            document.getElementById('pass-required').classList.remove('d-none');
            document.getElementById('pass-hint').textContent = 'Minimal 8 karakter';
            new bootstrap.Modal(document.getElementById('userModal')).show();
        },

        async openEdit(id) {
            this.editId = id;
            this._resetUserModal();
            document.getElementById('modal-title').textContent = 'Edit User';
            document.getElementById('modal-subtitle').textContent = 'Perbarui informasi user';
            document.getElementById('save-text').textContent = 'Update';
            document.getElementById('pass-required').classList.add('d-none');
            document.getElementById('pass-hint').textContent = 'Kosongkan jika tidak ingin mengubah password';

            this._setUserLoading(true);
            new bootstrap.Modal(document.getElementById('userModal')).show();

            try {
                const d = await this.get(this.BASE + `access/users/get/${id}`);
                if (d.status === 'success' && d.data) {
                    document.getElementById('f-username').value = d.data.username ?? '';
                    document.getElementById('f-email').value = d.data.email ?? '';
                } else {
                    this.toast('error', d.message ?? 'Gagal memuat data');
                    bootstrap.Modal.getInstance(document.getElementById('userModal'))?.hide();
                }
            } catch {
                this.toast('error', 'Gagal memuat data');
            } finally {
                this._setUserLoading(false);
            }
        },

        async saveUser() {
            this._clearUserErrors();
            const fd = new FormData();
            fd.set('username', document.getElementById('f-username').value.trim());
            fd.set('email', document.getElementById('f-email').value.trim());
            const pass = document.getElementById('f-password').value;
            if (pass) fd.set('password', pass);
            if (this.editId) fd.set('id', this.editId);

            this._setUserLoading(true);
            try {
                const res = await this.post(this.BASE + 'access/users/store', fd);
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('userModal'))?.hide();
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else if (res.errors) {
                    this._showUserErrors(res.errors);
                } else {
                    document.getElementById('modal-alert').classList.remove('d-none');
                    document.getElementById('modal-alert-text').textContent = res.message ?? 'Terjadi kesalahan';
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                this._setUserLoading(false);
            }
        },

        _resetUserModal() {
            ['f-username', 'f-email', 'f-password'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.classList.remove('is-invalid');
                }
            });
            document.getElementById('modal-alert').classList.add('d-none');
            this._clearUserErrors();
        },
        _clearUserErrors() {
            document.querySelectorAll('#userModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#userModal .invalid-feedback').forEach(el => el.textContent = '');
        },
        _showUserErrors(errors) {
            const map = {
                username: ['f-username', 'err-username'],
                email: ['f-email', 'err-email'],
                password: ['f-password', 'err-password']
            };
            Object.entries(errors).forEach(([f, msg]) => {
                const [inp, err] = map[f] ?? [];
                if (inp) document.getElementById(inp)?.classList.add('is-invalid');
                if (err) document.getElementById(err).textContent = Array.isArray(msg) ? msg[0] : msg;
            });
        },
        _setUserLoading(on) {
            const btn = document.getElementById('btn-save');
            const ico = document.getElementById('save-icon');
            btn.disabled = on;
            ico.className = on ? 'spinner-border spinner-border-sm me-1' : 'fas fa-save me-1';
        },

        /* ── Modal: Assign Groups ─────────────────────────────────────── */
        openGroupModal(id, username, currentGroups) {
            this.groupEditId = id;
            document.getElementById('group-modal-username').textContent = username;
            document.getElementById('group-modal-alert').classList.add('d-none');

            document.querySelectorAll('#group-checklist input[type=checkbox]').forEach(cb => {
                cb.checked = currentGroups.includes(cb.value);
                cb.disabled = false;
            });

            // Jika user adalah diri sendiri & punya superadmin, disable uncheck superadmin
            if (id == CURRENT_USER_ID) {
                document.querySelectorAll('#group-checklist input[value="superadmin"]').forEach(cb => {
                    if (cb.checked) cb.disabled = true;
                });
            }

            new bootstrap.Modal(document.getElementById('groupModal')).show();
        },

        async saveGroups() {
            const checked = Array.from(document.querySelectorAll('#group-checklist input:checked')).map(cb => cb.value);

            if (checked.length === 0) {
                document.getElementById('group-modal-alert').classList.remove('d-none');
                document.getElementById('group-modal-alert-text').textContent = 'Minimal satu group harus dipilih';
                return;
            }

            const fd = new FormData();
            checked.forEach(g => fd.append('groups[]', g));

            const btn = document.getElementById('btn-save-groups');
            const ico = document.getElementById('group-save-icon');
            btn.disabled = true;
            ico.className = 'spinner-border spinner-border-sm me-1';

            try {
                const res = await this.post(this.BASE + `access/users/assign-groups/${this.groupEditId}`, fd);
                if (res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('groupModal'))?.hide();
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else {
                    document.getElementById('group-modal-alert').classList.remove('d-none');
                    document.getElementById('group-modal-alert-text').textContent = res.message ?? 'Terjadi kesalahan';
                }
            } catch (e) {
                this.toast('error', e.message);
            } finally {
                btn.disabled = false;
                ico.className = 'fas fa-save me-1';
            }
        },

        /* ── Toggle Active ────────────────────────────────────────────── */
        async toggle(id, currentlyActive) {
            const action = currentlyActive ? 'menonaktifkan' : 'mengaktifkan';
            const result = await Swal.fire({
                title: `${currentlyActive ? 'Nonaktifkan' : 'Aktifkan'} User?`,
                html: `User ini akan ${action}.`,
                icon: 'question',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: currentlyActive ? '#f5803e' : '#25b003',
                cancelButtonColor: '#748194',
                confirmButtonText: currentlyActive ? 'Nonaktifkan' : 'Aktifkan',
                cancelButtonText: 'Batal',
            });
            if (!result.isConfirmed) return;
            try {
                const res = await this.post(this.BASE + `access/users/toggle/${id}`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        /* ── Delete ───────────────────────────────────────────────────── */
        async deleteItem(id, name) {
            const result = await Swal.fire({
                title: 'Hapus User?',
                html: `<strong>${name}</strong> akan dihapus dari sistem.`,
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
                const res = await this.post(this.BASE + `access/users/delete/${id}`, new FormData());
                if (res.status === 'success') {
                    this.dt.ajax.reload(null, false);
                    this.loadStats();
                    this.toast('success', res.message);
                } else this.toast('error', res.message);
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        /* ── Events ───────────────────────────────────────────────────── */
        initEvents() {
            document.getElementById('btn-refresh')?.addEventListener('click', () => {
                this.dt.ajax.reload(() => this.loadStats(), false);
            });
            document.getElementById('btn-create')?.addEventListener('click', () => this.openCreate());
            document.getElementById('btn-save')?.addEventListener('click', () => this.saveUser());
            document.getElementById('btn-save-groups')?.addEventListener('click', () => this.saveGroups());

            document.getElementById('filter-group')?.addEventListener('change', e => {
                this.filters.group = e.target.value;
                this.dt.ajax.reload();
            });
            document.getElementById('filter-status')?.addEventListener('change', e => {
                this.filters.status = e.target.value;
                this.dt.ajax.reload();
            });

            document.getElementById('toggle-password')?.addEventListener('click', () => {
                const input = document.getElementById('f-password');
                const icon = document.querySelector('#toggle-password .fas');
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !isHidden);
                icon.classList.toggle('fa-eye-slash', isHidden);
            });

            $(document).on('click', '.btn-edit', e => this.openEdit($(e.currentTarget).data('id')));
            $(document).on('click', '.btn-groups', e => {
                const btn = $(e.currentTarget);
                this.openGroupModal(btn.data('id'), btn.data('username'), btn.data('groups') || []);
            });
            $(document).on('click', '.btn-toggle', e => {
                const btn = $(e.currentTarget);
                this.toggle(btn.data('id'), btn.data('active') == 1);
            });
            $(document).on('click', '.btn-delete', e => {
                const btn = $(e.currentTarget);
                this.deleteItem(btn.data('id'), btn.data('name'));
            });
        },

        /* ── Helpers ──────────────────────────────────────────────────── */
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

    $(document).ready(() => UserMgmt.init());
</script>
<?= $this->endSection() ?>