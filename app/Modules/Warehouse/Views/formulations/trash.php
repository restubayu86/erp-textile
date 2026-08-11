<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    body {
        overflow-x: hidden;
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

    .btn-group-sm .btn {
        padding: .5rem .75rem;
        font-size: .7rem;
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

    /* ─── Modal Preview Versi ────────────────────────────────────────── */
    .modal-content {
        border-radius: 0.75rem;
    }

    .modal-header {
        border-bottom: 1px solid var(--phoenix-border-color);
        padding: 1rem 1.25rem;
    }

    .modal-body {
        padding: 1.25rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-footer {
        border-top: 1px solid var(--phoenix-border-color);
        padding: 0.75rem 1.25rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .barcode-row {
        text-align: center;
        margin-top: 5px;
        padding: 5px 0;
        border-top: 1px dashed #ddd;
    }

    .barcode-row img {
        max-width: 100%;
        max-height: 40px;
    }

    .barcode-row .barcode-text {
        font-size: 7pt;
        font-family: 'Courier New', monospace;
        letter-spacing: 0.1em;
        margin-top: 2px;
        color: #333;
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
        <div class="d-flex gap-2 no-print">
            <a href="<?= site_url('warehouse/formulations') ?>" class="btn btn-subtle-secondary btn-sm">
                <span class="fas fa-arrow-left me-1"></span>Kembali
            </a>
            <button class="btn btn-danger btn-sm" id="btn-empty-trash" type="button">
                <span class="fas fa-trash-alt me-1"></span>Kosongkan Sampah
            </button>
        </div>
    </div>

    <!-- Print header -->
    <div class="print-header mb-3">
        <h5 class="fw-bold mb-1">Sampah Formulasi</h5>
        <div class="text-muted small">Dicetak: <span id="print-date"></span></div>
        <hr class="my-2">
    </div>

    <!-- Table -->
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis py-5 border-y">
        <table class="table table-hover fs-9 nowrap align-middle" id="trash-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Formulasi</th>
                    <th>Proses</th>
                    <th>Sub Proses</th>
                    <th>Status</th>
                    <th>Versi</th>
                    <th>Dihapus</th>
                    <th>Oleh</th>
                    <th class="text-end no-print">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- ─── Modal Preview Versi ────────────────────────────────────────── -->
<div class="modal fade" id="versionPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <span class="fas fa-eye"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="preview-title">Preview Versi</h5>
                        <p class="text-muted fs-10 mb-0" id="preview-subtitle">—</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-subtle-secondary btn-sm" id="btn-print-preview">
                        <span class="fas fa-print me-1"></span>Print
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body px-4 py-3" id="preview-body">
                <div class="text-center text-muted py-4">Memuat...</div>
            </div>
            <div class="modal-footer border-top bg-body-tertiary">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const FormulationTrash = {
        BASE: '<?= base_url() ?>',
        dt: null,

        // ============================================================
        // FORMATTER
        // ============================================================

        formatNumber(value) {
            if (value === null || value === undefined || value === '') return '—';
            const num = parseFloat(value);
            if (isNaN(num)) return '—';
            if (Number.isInteger(num)) {
                return num.toString();
            }
            return num.toFixed(4).replace(/\.?0+$/, '');
        },

        formatValue(value) {
            if (value === null || value === undefined || value === '') return '—';
            const num = parseFloat(value);
            if (isNaN(num)) return '—';
            return this.formatNumber(num);
        },

        generateBarcode(text) {
            if (!text) return '';
            return `https://barcode.tec-it.com/barcode.ashx?data=${encodeURIComponent(text)}&code=Code128&dpi=96&width=300&height=60`;
        },

        // ============================================================
        // INIT
        // ============================================================

        init() {
            this.initDatatable();
            this.initEvents();
            document.getElementById('print-date').textContent = new Date().toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        // ============================================================
        // CSRF & AJAX HELPERS
        // ============================================================

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

        // ============================================================
        // HELPERS
        // ============================================================

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

        fmtDate(d) {
            if (!d) return '<span class="text-muted fst-italic">—</span>';
            const dt = new Date(d);
            return `<span class="d-block">${dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                    <small class="text-muted">${dt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</small>`;
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

        // ============================================================
        // PREVIEW VERSION
        // ============================================================

        async previewVersion(formulationId, versionId) {
            if (!formulationId || !versionId) {
                this.toast('error', 'Data formulasi tidak lengkap');
                return;
            }

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('versionPreviewModal'));
            const body = document.getElementById('preview-body');
            body.innerHTML = '<div class="text-center text-muted py-4"><span class="spinner-border spinner-border-sm me-2"></span>Memuat...</div>';
            modal.show();

            try {
                const d = await this.get(this.BASE + `warehouse/formulations/${formulationId}/versions/${versionId}/detail`);
                if (d.status !== 'success') {
                    body.innerHTML = `<div class="text-center text-danger py-4">${this.e(d.message || 'Gagal memuat data')}</div>`;
                    return;
                }

                const data = d.data;
                const barcode = this.generateBarcode(data.formulation.formulation_code);
                document.getElementById('preview-title').textContent = `${this.e(data.formulation.formulation_name)} - Versi #${data.version.version_no}`;
                document.getElementById('preview-subtitle').textContent = `Status: ${data.version.status} · Nilai: ${this.formatValue(data.version.output_percentage)}`;

                let itemsHtml = '';
                if (data.items && data.items.length) {
                    itemsHtml = `
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis</th>
                                        <th>Bahan / Label</th>
                                        <th>Nilai</th>
                                        <th>Satuan</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.items.map((item, i) => `
                                        <tr>
                                            <td>${i + 1}</td>
                                            <td><span class="badge badge-phoenix badge-phoenix-${item.composition_type === 'chemical' ? 'primary' : 'info'} fs-10">${item.composition_type === 'chemical' ? 'Chemical' : 'Softener Water'}</span></td>
                                            <td>${item.composition_type === 'chemical' ? this.e(item.chemical_name) + (item.chemical_code ? ` <small class="text-muted">(${this.e(item.chemical_code)})</small>` : '') : this.e(item.custom_label)}</td>
                                            <td style="text-align: right;"><strong>${this.formatValue(item.percentage)}</strong></td>
                                            <td>${item.unit ? this.e(item.unit) : '<span class="text-muted fst-italic">—</span>'}</td>
                                            <td>${item.notes ? this.e(item.notes) : '<span class="text-muted fst-italic">—</span>'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total</td>
                                        <td><strong>${this.formatValue(data.items.reduce((sum, item) => sum + parseFloat(item.percentage || 0), 0))}</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    `;
                } else {
                    itemsHtml = '<div class="text-center text-muted py-3">Tidak ada komposisi</div>';
                }

                body.innerHTML = `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Kode</small>
                                    <strong>${this.e(data.formulation.formulation_code)}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Proses</small>
                                    <span class="badge badge-phoenix badge-phoenix-secondary fs-10">${this.e(data.formulation.process_type)}</span>
                                    ${data.formulation.process_sub_type ? `<span class="badge badge-phoenix badge-phoenix-secondary fs-10 ms-1">${this.e(data.formulation.process_sub_type)}</span>` : ''}
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Group</small>
                                    ${data.formulation.group_name ? this.e(data.formulation.group_name) : '<span class="text-muted fst-italic">—</span>'}
                                </div>
                                ${data.formulation.description ? `<div class="col-12"><small class="text-muted d-block">Deskripsi</small><p class="mb-0">${this.e(data.formulation.description)}</p></div>` : ''}
                            </div>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-3">Komposisi</h6>
                    ${itemsHtml}
                    <div class="barcode-row">
                        <img src="${barcode}" alt="Barcode" onerror="this.style.display='none'">
                        <div class="barcode-text">${this.e(data.formulation.formulation_code)}</div>
                    </div>
                    <div class="text-muted small mt-3">Dibuat: ${data.version.created_at ? new Date(data.version.created_at).toLocaleString('id-ID') : '-'} · ${data.version.created_by_name ? `Oleh: ${this.e(data.version.created_by_name)}` : ''}</div>
                `;

                // Print handler
                const logoPath = '<?= base_url('assets/img/app/logo-regency-footer.png') ?>';
                document.getElementById('btn-print-preview').onclick = () => {
                    const printWindow = window.open('', '_blank', 'width=400,height=650');
                    const printItems = data.items || [];
                    const totalValue = printItems.reduce((sum, item) => sum + parseFloat(item.percentage || 0), 0);

                    const itemRows = printItems.map((item, i) => {
                        const label = item.composition_type === 'chemical' ? (item.chemical_name || '-') : (item.custom_label || '-');
                        const unit = item.unit ? this.e(item.unit) : '';
                        return `
                            <tr>
                                <td class="col-no">${i + 1}</td>
                                <td class="col-name">${this.e(label)}${unit ? `<span class="unit"> (${unit})</span>` : ''}</td>
                                <td class="col-pct">${this.formatValue(item.percentage)}</td>
                            </tr>
                        `;
                    }).join('');

                    printWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Kartu Formulasi</title>
                            <style>
                                @page { size: 7.75cm 19cm; margin: 0.5cm; }
                                body { margin: 0; padding: 0; background: white; font-family: 'Courier New', Courier, monospace; font-size: 6.3pt; width: 6.75cm; height: 18cm; }
                                .card { border: 1px solid #000; width: 100%; height: 100%; padding: 2mm; box-sizing: border-box; overflow: hidden; display: flex; flex-direction: column; }
                                .dashed { border-top: 1px dashed #000; margin: 3px 0; }
                                .center { text-align: center; }
                                .header-logo { display: block; margin: 0 auto 2px; max-height: 22px; max-width: 70%; }
                                .company-name { font-weight: bold; font-size: 8pt; letter-spacing: 0.03em; text-transform: uppercase; }
                                .company-sub { font-size: 6.8pt; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; }
                                .formulation-name { font-weight: bold; font-size: 7pt; text-transform: uppercase; margin-top: 3px; word-break: break-word; }
                                .meta-row { display: flex; justify-content: space-between; font-size: 6pt; }
                                .meta-row span:last-child { text-align: right; }
                                table.items { width: 100%; border-collapse: collapse; margin-top: 2px; }
                                table.items th { text-align: left; font-size: 5.6pt; text-transform: uppercase; border-bottom: 1px dashed #000; padding: 1px 0; }
                                table.items td { padding: 1px 0; vertical-align: top; font-size: 6.2pt; }
                                .col-no { width: 10%; }
                                .col-name { width: 62%; word-break: break-word; }
                                .col-pct { width: 28%; text-align: right; }
                                .unit { font-size: 5.4pt; }
                                .total-row { display: flex; justify-content: space-between; font-weight: bold; font-size: 6.8pt; margin-top: 3px; }
                                .footer { margin-top: 4px; font-size: 5.2pt; text-align: center; }
                                .status-tag { display: inline-block; border: 1px solid #000; padding: 0 3px; font-size: 5.6pt; font-weight: bold; }
                                .barcode-row { text-align: center; margin-top: 3px; padding-top: 3px; border-top: 1px dashed #000; }
                                .barcode-row img { max-width: 100%; max-height: 30px; }
                                .barcode-text { font-size: 5pt; font-family: 'Courier New', monospace; letter-spacing: 0.1em; margin-top: 2px; }
                            </style>
                        </head>
                        <body>
                            <div class="card">
                                <div class="center">
                                    <img class="header-logo" src="${logoPath}" alt="Logo" onerror="this.style.display='none'">
                                    <div class="company-name">PT. SINAR CONTINENTAL</div>
                                    <div class="company-sub">KARTU FORMULASI</div>
                                </div>
                                <div class="dashed"></div>
                                <div class="formulation-name">${this.e(data.formulation.formulation_name)}</div>
                                <div class="meta-row"><span>Kode: ${this.e(data.formulation.formulation_code)}</span><span>Versi #${data.version.version_no}</span></div>
                                <div class="meta-row">
                                    <span>${this.e(data.formulation.process_type)}${data.formulation.process_sub_type ? '/' + this.e(data.formulation.process_sub_type) : ''}</span>
                                    <span class="status-tag">${this.e(data.version.status)}</span>
                                </div>
                                ${data.formulation.group_name ? `<div class="meta-row"><span>Group: ${this.e(data.formulation.group_name)}</span><span></span></div>` : ''}
                                <div class="dashed"></div>
                                ${printItems.length > 0 ? `
                                <table class="items">
                                    <thead><tr><th class="col-no">#</th><th class="col-name">Bahan</th><th class="col-pct">Nilai</th></tr></thead>
                                    <tbody>${itemRows}</tbody>
                                </table>
                                ` : '<div class="center">Tidak ada komposisi</div>'}
                                <div class="dashed"></div>
                                <div class="total-row"><span>TOTAL</span><span>${this.formatValue(totalValue)}</span></div>
                                <div class="barcode-row">
                                    <img src="${this.generateBarcode(data.formulation.formulation_code)}" alt="Barcode" onerror="this.style.display='none'">
                                    <div class="barcode-text">${this.e(data.formulation.formulation_code)}</div>
                                </div>
                                <div class="dashed"></div>
                                <div class="footer">
                                    Dicetak: ${new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })} ${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}<br>
                                    PT. Sinar Continental
                                </div>
                            </div>
                        </body>
                        </html>
                    `);
                    printWindow.document.close();
                    printWindow.onload = () => {
                        printWindow.focus();
                        printWindow.print();
                    };
                };
            } catch (e) {
                body.innerHTML = `<div class="text-center text-danger py-4">Gagal memuat data</div>`;
            }
        },

        // ============================================================
        // DATATABLE
        // ============================================================

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
                    [1, 'asc']
                ],
                dom: '<"top"f>rt<"bottom"lpi>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari di sampah...',
                    lengthMenu: '_MENU_ / hal',
                    info: 'Tampil _START_–_END_ dari _TOTAL_',
                    infoEmpty: 'Sampah kosong',
                    zeroRecords: 'Data tidak ditemukan',
                    paginate: {
                        previous: '‹',
                        next: '›'
                    },
                    processing: '<span class="spinner-border spinner-border-sm text-primary"></span>',
                },
                ajax: {
                    url: this.BASE + 'warehouse/formulations/trash-datatables',
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
                        width: '80px'
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
                        width: '130px'
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
                        data: 'status',
                        render: d => self.fmtStatus(d)
                    },
                    {
                        data: 'version_no',
                        render: d => d ? `<span class="version-badge">v${self.e(d)}</span>` : '<span class="text-muted fst-italic">—</span>'
                    },
                    {
                        data: 'deleted_at',
                        render: d => self.fmtDate(d)
                    },
                    {
                        data: 'deleted_by_name',
                        render: (d, t, r) => self.fmtUser(d, r.deleted_by_employee)
                    },
                    // Di render columns - gunakan current_version_id
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-end no-print',
                        render: (d, t, r) => {
                            // Gunakan current_version_id dari data
                            const versionId = r.current_version_id;
                            return `
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-subtle-primary btn-sm btn-preview-trash" 
                                    data-formulation="${r.id}" 
                                    data-version="${versionId}" 
                                    title="Preview"
                                    ${!versionId ? 'disabled' : ''}>
                                    <span class="fas fa-eye"></span>
                                </button>
                                <button class="btn btn-subtle-success btn-sm btn-restore" 
                                    data-id="${r.id}" 
                                    data-name="${self.e(r.formulation_name)}" 
                                    title="Pulihkan">
                                    <span class="fas fa-undo"></span>
                                </button>
                                <button class="btn btn-subtle-danger btn-sm btn-force-delete" 
                                    data-id="${r.id}" 
                                    data-name="${self.e(r.formulation_name)}" 
                                    title="Hapus Permanen">
                                    <span class="fas fa-trash"></span>
                                </button>
                            </div>
                        `;
                        }
                    },
                ],
            });
        },

        // ============================================================
        // EVENTS
        // ============================================================

        initEvents() {
            // Preview button - di trash
            $(document).on('click', '.btn-preview-trash', async e => {
                const btn = $(e.currentTarget);
                const formulationId = btn.data('formulation');
                const versionId = btn.data('version');

                console.log('Preview clicked:', {
                    formulationId,
                    versionId
                }); // Debug

                if (!formulationId) {
                    this.toast('error', 'ID formulasi tidak ditemukan');
                    return;
                }

                if (!versionId) {
                    this.toast('error', 'Versi tidak tersedia untuk formulasi ini');
                    return;
                }

                await this.previewVersion(formulationId, versionId);
            });

            // Restore button - dengan konfirmasi
            $(document).on('click', '.btn-restore', async e => {
                const id = $(e.currentTarget).data('id');
                const name = $(e.currentTarget).data('name') || 'Formulasi ini';

                const result = await Swal.fire({
                    title: 'Pulihkan Formulasi?',
                    html: `Formulasi <strong>${this.e(name)}</strong> akan dipulihkan dari sampah.`,
                    icon: 'question',
                    showCancelButton: true,
                    reverseButtons: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#748194',
                    confirmButtonText: '<span class="fas fa-undo me-1"></span>Ya, Pulihkan',
                    cancelButtonText: 'Batal',
                });

                if (!result.isConfirmed) return;

                try {
                    const res = await this.post(this.BASE + `warehouse/formulations/${id}/restore`, new FormData());
                    this.toast(res.status === 'success' ? 'success' : 'error', res.message);
                    if (res.status === 'success') {
                        this.dt.ajax.reload(null, false);
                    }
                } catch (err) {
                    this.toast('error', err.message);
                }
            });

            // Force delete button - dengan konfirmasi
            $(document).on('click', '.btn-force-delete', async e => {
                const id = $(e.currentTarget).data('id');
                const name = $(e.currentTarget).data('name');

                const result = await Swal.fire({
                    title: 'Hapus Permanen?',
                    html: `Formulasi <strong>${this.e(name)}</strong> akan dihapus permanen dan tidak dapat dipulihkan.`,
                    icon: 'error',
                    showCancelButton: true,
                    reverseButtons: true,
                    confirmButtonColor: '#e63757',
                    cancelButtonColor: '#748194',
                    confirmButtonText: '<span class="fas fa-trash me-1"></span>Ya, Hapus Permanen',
                    cancelButtonText: 'Batal',
                });

                if (!result.isConfirmed) return;

                try {
                    const res = await this.post(this.BASE + `warehouse/formulations/${id}/force-delete`, new FormData());
                    this.toast(res.status === 'success' ? 'success' : 'error', res.message);
                    if (res.status === 'success') {
                        this.dt.ajax.reload(null, false);
                    }
                } catch (err) {
                    this.toast('error', err.message);
                }
            });

            // Empty trash button - dengan konfirmasi
            document.getElementById('btn-empty-trash')?.addEventListener('click', async () => {
                const result = await Swal.fire({
                    title: 'Kosongkan Sampah?',
                    html: 'Semua formulasi di sampah akan dihapus permanen.<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>',
                    icon: 'error',
                    showCancelButton: true,
                    reverseButtons: true,
                    confirmButtonColor: '#e63757',
                    cancelButtonColor: '#748194',
                    confirmButtonText: '<span class="fas fa-trash me-1"></span>Ya, Kosongkan',
                    cancelButtonText: 'Batal',
                });

                if (!result.isConfirmed) return;

                try {
                    const res = await this.post(this.BASE + 'warehouse/formulations/empty-trash', new FormData());
                    this.toast(res.status === 'success' ? 'success' : 'error', res.message);
                    if (res.status === 'success') {
                        this.dt.ajax.reload(null, false);
                    }
                } catch (err) {
                    this.toast('error', err.message);
                }
            });
        },
    };

    $(document).ready(() => FormulationTrash.init());
</script>
<?= $this->endSection() ?>