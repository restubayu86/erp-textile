<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    .item-row select, .item-row input { min-width: 0; }
    .item-row .select2-container { width: 100% !important; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-100">
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
    <p class="text-body-tertiary mb-4"><?= esc((string)$page_description) ?></p>

    <form id="formulation-form">
        <input type="hidden" id="f-id" value="<?= esc((string)($formulation['id'] ?? '')) ?>">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">Informasi Formulasi</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Kode Formulasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="f-code" maxlength="50"
                                    value="<?= esc((string)($formulation['formulation_code'] ?? '')) ?>" required>
                                <div class="invalid-feedback" id="err-formulation_code"></div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Nama Formulasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="f-name" maxlength="150"
                                    value="<?= esc((string)($formulation['formulation_name'] ?? '')) ?>" required>
                                <div class="invalid-feedback" id="err-formulation_name"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jenis Proses <span class="text-danger">*</span></label>
                                <select class="form-select" id="f-process-type" required>
                                    <?php $pt = $formulation['process_type'] ?? 'Dyeing'; ?>
                                    <option value="Dyeing" <?= $pt === 'Dyeing' ? 'selected' : '' ?>>Dyeing</option>
                                    <option value="Finishing" <?= $pt === 'Finishing' ? 'selected' : '' ?>>Finishing</option>
                                    <option value="Other" <?= $pt === 'Other' ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hasil / Batch <span class="text-danger">*</span></label>
                                <input type="number" step="0.001" class="form-control" id="f-output-qty"
                                    value="<?= esc((string)($formulation['output_quantity'] ?? '1')) ?>" required>
                                <div class="invalid-feedback" id="err-output_quantity"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Satuan Hasil</label>
                                <input type="text" class="form-control" id="f-output-unit" maxlength="20"
                                    placeholder="kg, liter" value="<?= esc((string)($formulation['output_unit'] ?? '')) ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="f-description" rows="2" maxlength="500"><?= esc((string)($formulation['description'] ?? '')) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Resep Bahan Kimia</h5>
                        <button type="button" class="btn btn-subtle-primary btn-sm" id="btn-add-item">
                            <span class="fas fa-plus me-1"></span>Tambah Baris
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:40%">Bahan Kimia</th>
                                        <th style="width:20%">Takaran</th>
                                        <th style="width:15%">Satuan</th>
                                        <th style="width:20%">Catatan</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="item-rows"></tbody>
                            </table>
                        </div>
                        <div class="text-muted small mt-2" id="item-empty-msg">Belum ada bahan kimia, klik "Tambah Baris".</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Status & Simpan</h5></div>
                    <div class="card-body">
                        <label class="form-label">Status</label>
                        <select class="form-select mb-3" id="f-status">
                            <?php $st = $formulation['status'] ?? 'Draft'; ?>
                            <option value="Draft" <?= $st === 'Draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="Active" <?= $st === 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Archived" <?= $st === 'Archived' ? 'selected' : '' ?>>Archived</option>
                        </select>
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <span class="fas fa-save me-1"></span>Simpan Formulasi
                        </button>
                        <a href="<?= site_url('warehouse/formulations') ?>" class="btn btn-subtle-secondary w-100">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<template id="item-row-template">
    <tr class="item-row">
        <td>
            <select class="form-select form-select-sm item-chemical" required></select>
        </td>
        <td><input type="number" step="0.0001" class="form-control form-control-sm item-qty" required></td>
        <td><input type="text" class="form-control form-control-sm item-unit" maxlength="20" placeholder="kg"></td>
        <td><input type="text" class="form-control form-control-sm item-notes" maxlength="255"></td>
        <td class="text-end"><button type="button" class="btn btn-subtle-danger btn-sm btn-remove-item"><span class="fas fa-trash"></span></button></td>
    </tr>
</template>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const FormulationForm = {
        BASE: '<?= base_url() ?>',
        existingItems: <?= json_encode($formulation['items'] ?? []) ?>,

        init() {
            this.initEvents();
            if (this.existingItems.length) {
                this.existingItems.forEach(item => this.addRow(item));
            } else {
                this.addRow();
            }
            this.toggleEmptyMsg();
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

        addRow(item = null) {
            const tpl = document.getElementById('item-row-template');
            const row = tpl.content.cloneNode(true).querySelector('tr');
            document.getElementById('item-rows').appendChild(row);

            const $select = $(row).find('.item-chemical');
            $select.select2({
                theme: 'bootstrap-5',
                placeholder: '— Pilih Bahan Kimia —',
                width: '100%',
                ajax: {
                    url: this.BASE + 'warehouse/master/chemicals/select2',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ search: params.term }),
                    processResults: data => ({
                        results: (data.data ?? []).map(c => ({ id: c.id, text: `${c.name} (${c.code})` }))
                    }),
                },
            });

            if (item) {
                const opt = new Option(`${item.chemical_name} (${item.chemical_code})`, item.chemical_id, true, true);
                $select.append(opt).trigger('change');
                row.querySelector('.item-qty').value = item.quantity ?? '';
                row.querySelector('.item-unit').value = item.unit ?? '';
                row.querySelector('.item-notes').value = item.notes ?? '';
            }

            row.querySelector('.btn-remove-item').addEventListener('click', () => {
                row.remove();
                this.toggleEmptyMsg();
            });

            this.toggleEmptyMsg();
        },

        toggleEmptyMsg() {
            const has = document.getElementById('item-rows').children.length > 0;
            document.getElementById('item-empty-msg').classList.toggle('d-none', has);
        },

        collectItems() {
            const rows = document.querySelectorAll('#item-rows .item-row');
            const items = [];
            rows.forEach(row => {
                const chemicalId = $(row).find('.item-chemical').val();
                const qty = row.querySelector('.item-qty').value;
                if (!chemicalId || !qty) return;
                items.push({
                    chemical_id: chemicalId,
                    quantity: qty,
                    unit: row.querySelector('.item-unit').value,
                    notes: row.querySelector('.item-notes').value,
                });
            });
            return items;
        },

        clearErrors() {
            document.querySelectorAll('.invalid-feedback').forEach(e => e.textContent = '');
            document.querySelectorAll('.is-invalid').forEach(e => e.classList.remove('is-invalid'));
        },

        showErrors(errors) {
            const map = {
                formulation_code: 'f-code',
                formulation_name: 'f-name',
                output_quantity: 'f-output-qty',
            };
            Object.entries(errors ?? {}).forEach(([key, msg]) => {
                const fieldId = map[key];
                if (fieldId) {
                    document.getElementById(fieldId)?.classList.add('is-invalid');
                    const errEl = document.getElementById('err-' + key);
                    if (errEl) errEl.textContent = msg;
                } else {
                    this.toast('error', msg);
                }
            });
        },

        async submit(e) {
            e.preventDefault();
            this.clearErrors();

            const items = this.collectItems();
            if (!items.length) {
                this.toast('error', 'Minimal 1 bahan kimia harus diisi dalam resep');
                return;
            }

            const fd = new FormData();
            const id = document.getElementById('f-id').value;
            if (id) fd.set('id', id);
            fd.set('formulation_code', document.getElementById('f-code').value.trim());
            fd.set('formulation_name', document.getElementById('f-name').value.trim());
            fd.set('process_type', document.getElementById('f-process-type').value);
            fd.set('output_quantity', document.getElementById('f-output-qty').value);
            fd.set('output_unit', document.getElementById('f-output-unit').value.trim());
            fd.set('description', document.getElementById('f-description').value.trim());
            fd.set('status', document.getElementById('f-status').value);
            fd.set('items', JSON.stringify(items));

            try {
                const res = await this.post(this.BASE + 'warehouse/formulations/store', fd);
                if (res.status === 'success') {
                    this.toast('success', res.message);
                    setTimeout(() => window.location.href = this.BASE + 'warehouse/formulations', 900);
                } else if (res.errors) {
                    this.showErrors(res.errors);
                } else {
                    this.toast('error', res.message ?? 'Gagal menyimpan');
                }
            } catch (err) {
                this.toast('error', err.message);
            }
        },

        initEvents() {
            document.getElementById('btn-add-item')?.addEventListener('click', () => this.addRow());
            document.getElementById('formulation-form')?.addEventListener('submit', e => this.submit(e));
        },

        toast(type, msg) {
            Swal.fire({ toast: true, position: 'top-right', icon: type, title: msg, showConfirmButton: false, timer: type === 'success' ? 2000 : 3500, timerProgressBar: true });
        },
    };

    $(document).ready(() => FormulationForm.init());
</script>
<?= $this->endSection() ?>
