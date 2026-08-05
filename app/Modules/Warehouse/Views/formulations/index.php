<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    .stat-card { border: none; border-radius: 1rem; transition: all .2s; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.1); }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0; }
    .info-label { font-size: .65rem; text-transform: uppercase; letter-spacing: .06em; color: var(--phoenix-secondary-color); margin-bottom: .2rem; }
    .info-value { font-weight: 700; font-size: 1.5rem; line-height: 1; }
    .badge-status { display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .55rem; border-radius: 20px; font-size: .72rem; font-weight: 600; white-space: nowrap; }
    .badge-status.active { background-color: rgba(var(--phoenix-success-rgb), .12); color: var(--phoenix-success); border: 1px solid rgba(var(--phoenix-success-rgb), .25); }
    .badge-status.draft { background-color: rgba(var(--phoenix-warning-rgb), .12); color: var(--phoenix-warning); border: 1px solid rgba(var(--phoenix-warning-rgb), .25); }
    .badge-status.archived { background-color: rgba(var(--phoenix-secondary-rgb), .12); color: var(--phoenix-secondary); border: 1px solid rgba(var(--phoenix-secondary-rgb), .25); }
    .badge-status .fas { font-size: .7rem; flex-shrink: 0; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-100">
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

    <div class="row g-3 mb-4">
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

    <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
        <div class="d-flex gap-2 align-items-center">
            <select id="filter-process-type" class="form-select form-select-sm" style="width:160px">
                <option value="">Semua Proses</option>
                <option value="Dyeing">Dyeing</option>
                <option value="Finishing">Finishing</option>
                <option value="Other">Lainnya</option>
            </select>
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

    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="formulation-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Formulasi</th>
                    <th>Proses</th>
                    <th>Hasil / Batch</th>
                    <th>Jml Kimia</th>
                    <th>Status</th>
                    <th>Dibuat Oleh</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CAN_MANAGE_FORMULATION = <?= json_encode(canDo('warehouse.formulations.manage')) ?>;

    const Formulation = {
        BASE: '<?= base_url() ?>',
        dt: null,
        filters: { process_type: '' },

        init() {
            this.initDatatable();
            this.initEvents();
            this.loadStats();
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
                method: 'POST', body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': this.csrfToken() }
            });
            const d = await r.json();
            if (d?.csrfHash) this.updateCsrf(d.csrfHash);
            return d;
        },

        async get(url) {
            const r = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            return r.json();
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
                responsive: true, scrollX: true, processing: true, serverSide: true,
                pageLength: 25,
                lengthMenu: [[-1, 10, 25, 50, 100], ['Semua', 10, 25, 50, 100]],
                order: [[1, 'asc']],
                dom: '<"top"f>rt<"bottom"lpi>',
                language: {
                    search: '', searchPlaceholder: 'Cari formulasi...',
                    lengthMenu: '_MENU_ / hal', info: 'Tampil _START_–_END_ dari _TOTAL_',
                    infoEmpty: 'Tidak ada data', zeroRecords: 'Data tidak ditemukan',
                    paginate: { previous: '‹', next: '›' },
                    processing: '<span class="spinner-border spinner-border-sm text-primary"></span>',
                },
                ajax: {
                    url: this.BASE + 'warehouse/formulations/datatables',
                    type: 'GET',
                    data: d => { d.filter_process_type = self.filters.process_type; },
                    error: () => self.toast('error', 'Gagal memuat data'),
                },
                columns: [
                    { data: 'no', orderable: false, searchable: false },
                    { data: null, render: (d, t, r) =>
                        `<span class="fw-semibold">${self.e(r.formulation_name)}</span>
                         <div class="text-muted small font-monospace">${self.e(r.formulation_code)}</div>` },
                    { data: 'process_type', render: d => `<span class="badge badge-phoenix badge-phoenix-secondary fs-10">${self.e(d)}</span>` },
                    { data: null, render: (d, t, r) => `${self.e(r.output_quantity)} ${self.e(r.output_unit ?? '')}` },
                    { data: 'item_count', render: d => Number(d) > 0 ? `<span class="badge badge-phoenix badge-phoenix-success fs-10">${self.e(d)} kimia</span>` : '<span class="text-muted fst-italic">—</span>' },
                    { data: 'status', render: d => self.fmtStatus(d) },
                    { data: 'created_by_name', render: d => d ? `<span class="badge badge-phoenix badge-phoenix-info rounded-pill fs-10">${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>' },
                    { data: null, orderable: false, searchable: false, className: 'text-end',
                        render: (d, t, r) => `
                            <a href="${self.BASE}warehouse/formulations/${r.id}/edit" class="btn btn-subtle-primary btn-sm"><span class="fas fa-pencil-alt"></span></a>
                            ${CAN_MANAGE_FORMULATION ? `<button class="btn btn-subtle-danger btn-sm btn-delete" data-id="${r.id}" data-name="${self.e(r.formulation_name)}"><span class="fas fa-trash"></span></button>` : ''}
                        ` },
                ],
            });
        },

        initEvents() {
            document.getElementById('btn-refresh')?.addEventListener('click', () => this.dt.ajax.reload());
            document.getElementById('filter-process-type')?.addEventListener('change', e => {
                this.filters.process_type = e.target.value;
                this.dt.ajax.reload();
            });

            $(document).on('click', '.btn-delete', e => {
                const id = $(e.currentTarget).data('id');
                const name = $(e.currentTarget).data('name');
                this.confirmDelete(id, name);
            });
        },

        async confirmDelete(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Formulasi?',
                html: `Formulasi <b>${this.e(name)}</b> akan dipindahkan ke sampah.`,
                icon: 'warning', showCancelButton: true,
                confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal',
                confirmButtonColor: '#d63939',
            });
            if (!result.isConfirmed) return;

            try {
                const res = await this.post(this.BASE + `warehouse/formulations/${id}/delete`, new FormData());
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    this.dt.ajax.reload();
                    this.loadStats();
                } else {
                    this.toast('error', res.message ?? 'Gagal menghapus');
                }
            } catch (e) {
                this.toast('error', e.message);
            }
        },

        e(s) {
            if (s === null || s === undefined) return '';
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        },

        fmtStatus(status) {
            if (!status) return '<span class="text-muted fst-italic">—</span>';
            let cls = 'draft', icon = 'fa-pencil-alt';
            const s = status.toLowerCase();
            if (s === 'active') { cls = 'active'; icon = 'fa-check-circle'; }
            else if (s === 'archived') { cls = 'archived'; icon = 'fa-archive'; }
            return `<span class="badge-status ${cls}"><span class="fas ${icon}"></span>${this.e(status)}</span>`;
        },

        toast(type, msg) {
            Swal.fire({ toast: true, position: 'top-right', icon: type, title: msg, showConfirmButton: false, timer: type === 'success' ? 2000 : 3500, timerProgressBar: true });
        },
    };

    $(document).ready(() => Formulation.init());
</script>
<?= $this->endSection() ?>
