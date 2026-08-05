<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
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
        <div class="d-flex gap-2">
            <a href="<?= site_url('warehouse/formulations') ?>" class="btn btn-subtle-secondary btn-sm">
                <span class="fas fa-arrow-left me-1"></span>Kembali
            </a>
            <button class="btn btn-danger btn-sm" id="btn-empty-trash" type="button">
                <span class="fas fa-trash-alt me-1"></span>Kosongkan Sampah
            </button>
        </div>
    </div>

    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="trash-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Formulasi</th>
                    <th>Proses</th>
                    <th>Status</th>
                    <th>Dihapus</th>
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
    const FormulationTrash = {
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
                method: 'POST', body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': this.csrfToken() }
            });
            const d = await r.json();
            if (d?.csrfHash) this.updateCsrf(d.csrfHash);
            return d;
        },

        initDatatable() {
            const self = this;
            this.dt = $('#trash-table').DataTable({
                responsive: true, processing: true, serverSide: true, pageLength: 25,
                order: [[1, 'asc']],
                dom: '<"top"f>rt<"bottom"lpi>',
                language: {
                    search: '', searchPlaceholder: 'Cari...',
                    lengthMenu: '_MENU_ / hal', info: 'Tampil _START_–_END_ dari _TOTAL_',
                    infoEmpty: 'Sampah kosong', zeroRecords: 'Data tidak ditemukan',
                    paginate: { previous: '‹', next: '›' },
                    processing: '<span class="spinner-border spinner-border-sm text-primary"></span>',
                },
                ajax: {
                    url: this.BASE + 'warehouse/formulations/trash-datatables',
                    type: 'GET',
                },
                columns: [
                    { data: 'no', orderable: false, searchable: false },
                    { data: null, render: (d, t, r) =>
                        `<span class="fw-semibold">${self.e(r.formulation_name)}</span>
                         <div class="text-muted small font-monospace">${self.e(r.formulation_code)}</div>` },
                    { data: 'process_type' },
                    { data: 'status' },
                    { data: 'deleted_at', render: d => d ? new Date(d).toLocaleString('id-ID') : '-' },
                    { data: 'deleted_by_name', render: d => d ?? '-' },
                    { data: null, orderable: false, searchable: false, className: 'text-end',
                        render: (d, t, r) => `
                            <button class="btn btn-subtle-success btn-sm btn-restore" data-id="${r.id}"><span class="fas fa-undo"></span></button>
                            <button class="btn btn-subtle-danger btn-sm btn-force-delete" data-id="${r.id}" data-name="${self.e(r.formulation_name)}"><span class="fas fa-trash"></span></button>
                        ` },
                ],
            });
        },

        initEvents() {
            $(document).on('click', '.btn-restore', async e => {
                const id = $(e.currentTarget).data('id');
                const res = await this.post(this.BASE + `warehouse/formulations/${id}/restore`, new FormData());
                this.toast(res.status === 'success' ? 'success' : 'error', res.message);
                if (res.status === 'success') this.dt.ajax.reload();
            });

            $(document).on('click', '.btn-force-delete', async e => {
                const id = $(e.currentTarget).data('id');
                const name = $(e.currentTarget).data('name');
                const result = await Swal.fire({
                    title: 'Hapus Permanen?',
                    html: `Formulasi <b>${this.e(name)}</b> akan dihapus permanen dan tidak dapat dipulihkan.`,
                    icon: 'warning', showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus Permanen', cancelButtonText: 'Batal',
                    confirmButtonColor: '#d63939',
                });
                if (!result.isConfirmed) return;
                const res = await this.post(this.BASE + `warehouse/formulations/${id}/force-delete`, new FormData());
                this.toast(res.status === 'success' ? 'success' : 'error', res.message);
                if (res.status === 'success') this.dt.ajax.reload();
            });

            document.getElementById('btn-empty-trash')?.addEventListener('click', async () => {
                const result = await Swal.fire({
                    title: 'Kosongkan Sampah?',
                    text: 'Semua formulasi di sampah akan dihapus permanen.',
                    icon: 'warning', showCancelButton: true,
                    confirmButtonText: 'Ya, Kosongkan', cancelButtonText: 'Batal',
                    confirmButtonColor: '#d63939',
                });
                if (!result.isConfirmed) return;
                const res = await this.post(this.BASE + 'warehouse/formulations/empty-trash', new FormData());
                this.toast(res.status === 'success' ? 'success' : 'error', res.message);
                if (res.status === 'success') this.dt.ajax.reload();
            });
        },

        e(s) {
            if (s === null || s === undefined) return '';
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        },

        toast(type, msg) {
            Swal.fire({ toast: true, position: 'top-right', icon: type, title: msg, showConfirmButton: false, timer: type === 'success' ? 2000 : 3500, timerProgressBar: true });
        },
    };

    $(document).ready(() => FormulationTrash.init());
</script>
<?= $this->endSection() ?>
