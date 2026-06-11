<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    body {
        overflow-x: hidden;
    }

    /* ── Form Wrapper ─────────────────────────────────────────── */
    .form-wrapper {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* ── Section Card ─────────────────────────────────────────── */
    .form-section {
        border-radius: .75rem;
        border: 1px solid var(--phoenix-border-color);
        margin-bottom: 1.25rem;
        overflow: hidden;
        background: var(--phoenix-card-bg);
    }

    .form-section-header {
        padding: .65rem 1rem;
        background: var(--phoenix-secondary-bg);
        border-bottom: 1px solid var(--phoenix-border-color);
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .form-section-header .section-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .8rem;
        flex-shrink: 0;
    }

    .form-section-header .section-title {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--phoenix-body-color);
    }

    .form-section-body {
        padding: 1rem 1rem .5rem;
    }

    /* ── Photo Upload ─────────────────────────────────────────── */
    .photo-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .5rem;
        width: 160px;
        flex-shrink: 0;
    }

    .photo-upload-area {
        width: 160px;
        height: 160px;
        border-radius: 12px;
        border: 2px dashed var(--phoenix-border-color);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        overflow: hidden;
        position: relative;
        background: var(--phoenix-secondary-bg);
    }

    .photo-upload-area:hover {
        border-color: var(--phoenix-primary);
        background: rgba(var(--phoenix-primary-rgb), .05);
    }

    .photo-upload-area.has-photo {
        border-style: solid;
        border-color: var(--phoenix-border-color);
    }

    .photo-upload-area img#photo-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
        display: none;
    }

    .photo-upload-area.has-photo img#photo-preview {
        display: block;
    }

    .photo-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .45);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        opacity: 0;
        transition: opacity .2s;
        z-index: 2;
    }

    .photo-upload-area.has-photo:hover .photo-overlay {
        opacity: 1;
    }

    .photo-overlay span {
        color: #fff;
        font-size: .7rem;
        font-weight: 600;
    }

    .photo-overlay i {
        color: #fff;
        font-size: 1.2rem;
    }

    .photo-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        z-index: 1;
    }

    .photo-upload-area.has-photo .photo-placeholder {
        display: none;
    }

    .photo-placeholder .upload-icon {
        font-size: 1.5rem;
        color: var(--phoenix-secondary-color);
    }

    .photo-placeholder .upload-hint {
        font-size: .65rem;
        color: var(--phoenix-secondary-color);
        text-align: center;
        line-height: 1.3;
    }

    .photo-hint {
        font-size: .65rem;
        color: var(--phoenix-secondary-color);
        text-align: center;
        line-height: 1.4;
    }

    /* ── Validation ───────────────────────────────────────────── */
    /* Hanya satu sistem: custom class is-invalid / is-valid */
    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: var(--phoenix-danger) !important;
    }

    .form-control.is-valid,
    .form-select.is-valid {
        border-color: var(--phoenix-success) !important;
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: .25rem;
        font-size: .75rem;
        color: var(--phoenix-danger);
    }

    .form-control.is-invalid~.invalid-feedback,
    .form-select.is-invalid~.invalid-feedback,
    .is-invalid+.invalid-feedback {
        display: block;
    }

    /* Select2 validation */
    .select2-invalid+.select2-container--bootstrap-5 .select2-selection,
    .s2-is-invalid .select2-selection {
        border-color: var(--phoenix-danger) !important;
    }

    .s2-is-valid .select2-selection {
        border-color: var(--phoenix-success) !important;
    }

    /* ── Misc ─────────────────────────────────────────────────── */
    #dept-display {
        background: var(--phoenix-secondary-bg);
        color: var(--phoenix-secondary-color);
        font-style: italic;
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: 31px;
        font-size: .875rem;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 29px;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 29px;
    }

    .select2-dropdown {
        font-size: .875rem;
    }

    /* ── Sticky Footer ────────────────────────────────────────── */
    .form-footer {
        position: sticky;
        bottom: 0;
        z-index: 100;
        background: var(--phoenix-body-bg);
        border-top: 1px solid var(--phoenix-border-color);
        padding: .75rem 1rem;
        display: flex;
        justify-content: flex-end;
        gap: .5rem;
        border-radius: 0 0 .75rem .75rem;
    }

    /* ── Loading Overlay ──────────────────────────────────────── */
    #loading-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$isEdit = isset($employee) && !empty($employee['id']);
$emp    = $employee ?? [];
?>

<div class="form-wrapper">

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

    <!-- Server error banner (fallback non-AJAX) -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-subtle-danger py-2 px-3 mb-4">
            <span class="fas fa-exclamation-triangle me-2"></span>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1 ps-3">
                <?php foreach ((array) $errors as $err): ?>
                    <li class="fs-10"><?= esc((string) $err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="employee-form" novalidate>
        <?= csrf_field() ?>

        <!-- ── SECTION 1: Identitas ──────────────────────────── -->
        <div class="form-section">
            <div class="form-section-header">
                <span class="section-icon bg-primary bg-opacity-10 text-primary"><span class="fas fa-id-card"></span></span>
                <span class="section-title">Identitas Karyawan</span>
            </div>
            <div class="form-section-body">
                <div class="row g-3">

                    <!-- Foto -->
                    <div class="col-12 col-md-auto">
                        <div class="photo-wrap">
                            <div class="photo-upload-area <?= !empty($emp['photo']) ? 'has-photo' : '' ?>"
                                id="photo-area" onclick="document.getElementById('photo-input').click()">
                                <img id="photo-preview" alt="Foto karyawan"
                                    src="<?= !empty($emp['photo']) ? base_url('uploads/employees/' . esc((string)$emp['photo'])) : '' ?>">
                                <div class="photo-overlay">
                                    <i class="fas fa-camera"></i><span>Ganti Foto</span>
                                </div>
                                <div class="photo-placeholder">
                                    <i class="fas fa-camera upload-icon"></i>
                                    <div class="upload-hint">Klik untuk<br>upload foto</div>
                                </div>
                            </div>
                            <input type="file" id="photo-input" accept="image/jpeg,image/png,image/webp" class="d-none">
                            <button type="button" class="btn btn-subtle-danger btn-sm d-inline-flex align-items-center gap-1"
                                id="btn-remove-photo"
                                style="<?= !empty($emp['photo']) ? '' : 'display:none!important' ?>">
                                <i class="fas fa-times"></i> Hapus
                            </button>
                            <div class="photo-hint">JPG / PNG / WEBP<br>maks. 2 MB</div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="row g-3">
                            <!-- NIK -->
                            <div class="col-md-5">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-nik">
                                    NIK <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm font-monospace"
                                    id="f-nik" name="nik"
                                    value="<?= esc(old('nik', $emp['nik'] ?? '')) ?>"
                                    placeholder="cth: 03.42.01.1234"
                                    maxlength="20" autocomplete="off">
                                <div class="invalid-feedback" id="err-nik"></div>
                            </div>

                            <!-- Nama Lengkap -->
                            <div class="col-md-7">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-fullname">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm"
                                    id="f-fullname" name="fullname"
                                    value="<?= esc(old('fullname', $emp['fullname'] ?? '')) ?>"
                                    placeholder="cth: Budi Santoso"
                                    maxlength="100" autocomplete="off">
                                <div class="invalid-feedback" id="err-fullname"></div>
                            </div>

                            <!-- Nama Panggilan -->
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-nickname">
                                    Nama Panggilan
                                </label>
                                <input type="text" class="form-control form-control-sm"
                                    id="f-nickname" name="nickname"
                                    value="<?= esc(old('nickname', $emp['nickname'] ?? '')) ?>"
                                    placeholder="cth: Budi" maxlength="50">
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-gender">
                                    Jenis Kelamin <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-sm" id="f-gender" name="gender">
                                    <option value="">— Pilih —</option>
                                    <option value="L" <?= old('gender', $emp['gender'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="P" <?= old('gender', $emp['gender'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                                <div class="invalid-feedback" id="err-gender"></div>
                            </div>

                            <!-- No Telepon -->
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-phone">
                                    No. Telepon
                                </label>
                                <input type="text" class="form-control form-control-sm"
                                    id="f-phone" name="phone"
                                    value="<?= esc(old('phone', $emp['phone'] ?? '')) ?>"
                                    placeholder="cth: 08123456789" maxlength="20">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── SECTION 2: Penempatan & Jabatan ───────────────── -->
        <div class="form-section">
            <div class="form-section-header">
                <span class="section-icon bg-info bg-opacity-10 text-info"><span class="fas fa-briefcase"></span></span>
                <span class="section-title">Penempatan &amp; Jabatan</span>
            </div>
            <div class="form-section-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-position">
                            Posisi <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-2 align-items-start">
                            <div class="flex-grow-1">
                                <select class="form-select form-select-sm" id="f-position" name="position_id" style="width:100%">
                                    <?php if (!empty($emp['position_id'])): ?>
                                        <option value="<?= esc((string)$emp['position_id']) ?>" selected>
                                            <?= esc((string)$emp['position_name'] ?? 'Posisi #' . $emp['position_id']) ?>
                                        </option>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback d-block" id="err-position"></div>
                            </div>
                            <button type="button" class="btn btn-subtle-primary btn-sm flex-shrink-0"
                                id="btn-add-position" title="Tambah Posisi Baru"
                                style="height:31px">
                                <span class="fas fa-plus"></span>
                            </button>
                        </div>
                    </div>
                    <!-- Departemen (auto-fill, readonly) -->
                    <div class="col-md-6">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted">
                            Departemen
                            <span class="badge badge-phoenix badge-phoenix-secondary fs-10 ms-1">otomatis dari posisi</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fas fa-building text-muted"></i></span>
                            <input type="text" class="form-control form-control-sm" id="dept-display"
                                placeholder="— pilih posisi terlebih dahulu —" readonly
                                value="<?= esc($emp['department_name'] ?? '') ?>">
                        </div>
                        <input type="hidden" id="dept-id" name="department_id" value="<?= esc($emp['department_id'] ?? '') ?>">
                    </div>

                    <!-- Area Kerja -->
                    <div class="col-md-4">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-work-area">
                            Area Kerja
                        </label>
                        <input type="text" class="form-control form-control-sm"
                            id="f-work-area" name="work_area"
                            value="<?= esc(old('work_area', $emp['work_area'] ?? '')) ?>"
                            placeholder="cth: Lantai 2 - Mesin A" maxlength="100">
                    </div>

                    <!-- Shift -->
                    <div class="col-md-4">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-shift">
                            Shift <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm" id="f-shift" name="shift">
                            <option value="">— Pilih Shift —</option>
                            <?php foreach (['NS' => 'Non Shift', 'A' => 'Shift A', 'B' => 'Shift B', 'C' => 'Shift C', 'D' => 'Shift D', 'E' => 'Shift E'] as $val => $label): ?>
                                <option value="<?= $val ?>" <?= old('shift', $emp['shift'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback" id="err-shift"></div>
                    </div>

                    <!-- Status Kerja -->
                    <div class="col-md-4">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-employment-status">
                            Status Kerja <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm" id="f-employment-status" name="employment_status">
                            <option value="">— Pilih —</option>
                            <?php foreach (['tetap' => 'Tetap', 'kontrak' => 'Kontrak', 'magang' => 'Magang'] as $val => $label): ?>
                                <option value="<?= $val ?>" <?= old('employment_status', $emp['employment_status'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback" id="err-employment-status"></div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ── SECTION 3: Tanggal & Status ───────────────────── -->
        <div class="form-section">
            <div class="form-section-header">
                <span class="section-icon bg-success bg-opacity-10 text-success"><span class="fas fa-calendar-alt"></span></span>
                <span class="section-title">Tanggal &amp; Status</span>
            </div>
            <div class="form-section-body">
                <div class="row g-3">

                    <!-- Tanggal Bergabung -->
                    <div class="col-md-4">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-join-date">
                            Tanggal Bergabung
                        </label>
                        <input type="text" class="form-control form-control-sm flatpickr"
                            id="f-join-date" name="join_date"
                            value="<?= esc(old('join_date', $emp['join_date'] ?? '')) ?>"
                            placeholder="DD-MM-YYYY" autocomplete="off">
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-status">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm" id="f-status" name="status">
                            <?php foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $val => $label): ?>
                                <option value="<?= $val ?>" <?= old('status', $emp['status'] ?? 'active') === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback" id="err-status"></div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Sticky Footer -->
        <div class="form-footer">
            <a href="<?= site_url('hrm/employees') ?>" class="btn btn-subtle-secondary btn-sm">
                <span class="fas fa-times me-1"></span>Batal
            </a>
            <?php if ($isEdit): ?>
                <a href="<?= site_url('hrm/employees/show/' . $employee['id']) ?>" class="btn btn-subtle-info btn-sm">
                    <span class="fas fa-eye me-1"></span>Lihat Detail
                </a>
            <?php endif; ?>
            <button type="button" class="btn btn-primary btn-sm" id="btn-submit">
                <span class="fas fa-save me-1"></span><?= $isEdit ? 'Simpan Perubahan' : 'Simpan Karyawan' ?>
            </button>
        </div>

    </form>
</div>
<div class="modal fade" id="modalAddPosition" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <span class="fas fa-briefcase"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Tambah Posisi</h5>
                        <p class="text-muted fs-10 mb-0">Buat posisi/jabatan baru</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="alert alert-subtle-danger py-2 px-3 fs-10 d-none" id="pos-modal-alert">
                    <span class="fas fa-exclamation-triangle me-1"></span>
                    <span id="pos-modal-alert-text"></span>
                </div>
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted">
                            Nama Posisi <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-sm" id="pos-name"
                            placeholder="cth: Operator Dyeing" maxlength="100">
                        <div class="invalid-feedback" id="pos-err-name"></div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted">
                            Kode <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-sm font-monospace fw-bold"
                            id="pos-code" placeholder="cth: OPD" maxlength="50"
                            style="text-transform:uppercase;letter-spacing:.06em">
                        <div class="invalid-feedback" id="pos-err-code"></div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted">
                            Level
                        </label>
                        <input type="number" class="form-control form-control-sm"
                            id="pos-level" placeholder="cth: 3" min="1" max="99">
                        <div class="form-text fs-10">1 = tertinggi</div>
                        <div class="invalid-feedback" id="pos-err-level"></div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm" id="pos-status">
                            <option value="Active">Active</option>
                            <option value="Draft" selected>Draft</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted">
                            Departemen
                        </label>
                        <select class="form-select form-select-sm" id="pos-department" style="width:100%"></select>
                    </div>

                    <!-- Deskripsi — DIPINDAH KE DALAM row g-3 -->
                    <div class="col-12">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="pos-desc">
                            Deskripsi
                        </label>
                        <textarea class="form-control form-control-sm" id="pos-desc"
                            rows="3" maxlength="500" placeholder="Deskripsi singkat..." style="resize:vertical"></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <div class="invalid-feedback d-block" id="pos-err-desc" style="visibility:hidden">‎</div>
                            <small class="text-muted fs-10 ms-auto"><span id="pos-char-count">0</span>/500</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top bg-body-tertiary px-4 py-3">
                <button type="button" class="btn btn-subtle-secondary btn-sm" data-bs-dismiss="modal">
                    <span class="fas fa-times me-1"></span>Batal
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save-position">
                    <span class="fas fa-save me-1" id="pos-save-icon"></span>
                    <span id="pos-save-text">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Loading Overlay -->
<div id="loading-overlay">
    <div class="text-center text-white">
        <div class="spinner-border mb-2" style="width:3rem;height:3rem;"></div>
        <div id="loading-text" class="fw-semibold">Memproses...</div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    const EmployeeForm = {

        cfg: {
            base: '<?= base_url() ?>',
            csrfName: '<?= csrf_token() ?>',
            csrfHash: '<?= csrf_hash() ?>',
            isEdit: <?= $isEdit ? 'true' : 'false' ?>,
            empId: <?= !empty($employee['id']) ? (int) $employee['id'] : 0 ?>,
            initData: <?= !empty($employee) ? json_encode($employee) : 'null' ?>,
        },

        // Field rules — single source of truth untuk validasi
        rules: [{
                field: 'f-nik',
                name: 'nik',
                err: 'err-nik',
                required: true,
                msg: 'NIK wajib diisi.'
            },
            {
                field: 'f-fullname',
                name: 'fullname',
                err: 'err-fullname',
                required: true,
                msg: 'Nama lengkap wajib diisi.'
            },
            {
                field: 'f-gender',
                name: 'gender',
                err: 'err-gender',
                required: true,
                msg: 'Jenis kelamin wajib dipilih.'
            },
            {
                field: 'f-position',
                name: 'position_id',
                err: 'err-position',
                required: true,
                msg: 'Posisi wajib dipilih.',
                isSelect2: true
            },
            {
                field: 'f-shift',
                name: 'shift',
                err: 'err-shift',
                required: true,
                msg: 'Shift wajib dipilih.'
            },
            {
                field: 'f-employment-status',
                name: 'employment_status',
                err: 'err-employment-status',
                required: true,
                msg: 'Status kerja wajib dipilih.'
            },
            {
                field: 'f-status',
                name: 'status',
                err: 'err-status',
                required: true,
                msg: 'Status wajib dipilih.'
            },
            // Optional fields — masuk FormData tapi tidak divalidasi client-side
            {
                field: 'f-nickname',
                name: 'nickname'
            },
            {
                field: 'f-phone',
                name: 'phone'
            },
            {
                field: 'f-work-area',
                name: 'work_area'
            },
            {
                field: 'f-join-date',
                name: 'join_date'
            },
        ],

        // State foto
        photo: {
            file: null,
            removeExisting: false
        },

        /* ── Init ─────────────────────────────────────────────────── */
        init() {
            this.initFlatpickr();
            this.initSelect2();
            this.initPhoto();
            this.initEvents();

            // Populate edit data setelah Select2 siap
            if (this.cfg.isEdit && this.cfg.initData) {
                this._populateEdit(this.cfg.initData);
            }
        },

        initFlatpickr() {
            flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd-m-Y',
                allowInput: true,
                locale: 'id',
            });
        },

        initSelect2() {
            const self = this;
            $('#f-position').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '— Cari posisi —',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: self.cfg.base + 'hrm/positions/select2',
                    dataType: 'json',
                    delay: 250,
                    data: p => ({
                        search: p.term || ''
                    }),
                    processResults: d => ({
                        results: (d.data ?? []).map(r => ({
                            id: r.id,
                            text: r.name,
                            department_name: r.department_name ?? '',
                            department_id: r.department_id ?? '',
                        }))
                    }),
                    cache: true,
                },
                templateResult: r => {
                    if (r.loading) return r.text;
                    const dept = r.department_name ? `<small class="text-muted ms-1">· ${r.department_name}</small>` : '';
                    return $(`<span>${r.text}${dept}</span>`);
                },
                templateSelection: r => r.text || r.id,
            });

            $('#f-position')
                .on('select2:select', e => {
                    const d = e.params.data;
                    document.getElementById('dept-display').value = d.department_name || '';
                    document.getElementById('dept-id').value = d.department_id || '';
                    self._clearError('f-position', 'err-position', true);
                })
                .on('select2:clear', () => {
                    document.getElementById('dept-display').value = '';
                    document.getElementById('dept-id').value = '';
                });

            $('#pos-department').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '— Pilih Departemen —',
                allowClear: true,
                dropdownParent: $('#modalAddPosition'),
                ajax: {
                    url: this.cfg.base + 'hrm/departments/select2',
                    dataType: 'json',
                    delay: 250,
                    data: p => ({
                        search: p.term || ''
                    }),
                    processResults: d => ({
                        results: (d.data ?? []).map(r => ({
                            id: r.id,
                            text: r.name
                        }))
                    }),
                    cache: true,
                },
                minimumInputLength: 0,
            });
        },

        _populateEdit(data) {
            // Select2 position — append option lalu trigger
            if (data.position_id) {
                const opt = new Option(
                    data.position_name || ('Posisi #' + data.position_id),
                    data.position_id, true, true
                );
                $('#f-position').append(opt).trigger('change');
                document.getElementById('dept-display').value = data.department_name || '';
                document.getElementById('dept-id').value = data.department_id || '';
            }
        },

        /* ── Photo ────────────────────────────────────────────────── */
        initPhoto() {
            const self = this;
            const input = document.getElementById('photo-input');
            const preview = document.getElementById('photo-preview');
            const area = document.getElementById('photo-area');
            const btnRm = document.getElementById('btn-remove-photo');

            const setHasPhoto = has => {
                area.classList.toggle('has-photo', has);
                btnRm.style.display = has ? 'inline-flex' : 'none';
            };

            input.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                    self.toast('error', 'Format tidak didukung. Gunakan JPG, PNG, atau WEBP.');
                    this.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    self.toast('error', 'Ukuran foto maksimal 2 MB.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    setHasPhoto(true);
                    self.photo.file = file;
                    self.photo.removeExisting = false;
                };
                reader.readAsDataURL(file);
            });

            btnRm.addEventListener('click', () => {
                preview.src = '';
                input.value = '';
                self.photo.file = null;
                self.photo.removeExisting = true;
                setHasPhoto(false);
            });
        },

        /* ── Validation ───────────────────────────────────────────── */
        validate() {
            this.clearAllErrors();
            let firstInvalidEl = null;
            let valid = true;

            this.rules.forEach(rule => {
                if (!rule.required) return; // optional: skip

                const el = document.getElementById(rule.field);
                if (!el) return;

                const val = rule.isSelect2 ? ($('#' + rule.field).val() || '') : el.value.trim();
                const ok = val !== '';

                if (!ok) {
                    valid = false;
                    this._setError(rule.field, rule.err, rule.msg, rule.isSelect2);
                    if (!firstInvalidEl) firstInvalidEl = el;
                }
            });

            if (firstInvalidEl) {
                // Scroll ke elemen pertama yang error
                firstInvalidEl.closest('.form-section')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            return valid;
        },

        _setError(fieldId, errId, msg, isSelect2 = false) {
            const el = document.getElementById(fieldId);
            if (!el) return;
            el.classList.add('is-invalid');
            if (isSelect2) $('#' + fieldId).next('.select2-container').addClass('s2-is-invalid');
            const errEl = document.getElementById(errId);
            if (errEl) errEl.textContent = msg;
        },

        _clearError(fieldId, errId, isSelect2 = false) {
            const el = document.getElementById(fieldId);
            if (!el) return;
            el.classList.remove('is-invalid');
            el.classList.add('is-valid');
            if (isSelect2) $('#' + fieldId).next('.select2-container').removeClass('s2-is-invalid').addClass('s2-is-valid');
            const errEl = document.getElementById(errId);
            if (errEl) errEl.textContent = '';
        },

        clearAllErrors() {
            this.rules.forEach(rule => {
                const el = document.getElementById(rule.field);
                if (el) el.classList.remove('is-invalid', 'is-valid');
                if (rule.isSelect2) {
                    $('#' + rule.field).next('.select2-container')
                        .removeClass('s2-is-invalid s2-is-valid');
                }
                const errEl = document.getElementById(rule.err);
                if (errEl) errEl.textContent = '';
            });
        },

        applyServerErrors(errors) {
            // Map nama field server → id elemen
            const serverMap = {
                nik: {
                    field: 'f-nik',
                    err: 'err-nik'
                },
                fullname: {
                    field: 'f-fullname',
                    err: 'err-fullname'
                },
                gender: {
                    field: 'f-gender',
                    err: 'err-gender'
                },
                position_id: {
                    field: 'f-position',
                    err: 'err-position',
                    isSelect2: true
                },
                shift: {
                    field: 'f-shift',
                    err: 'err-shift'
                },
                employment_status: {
                    field: 'f-employment-status',
                    err: 'err-employment-status'
                },
                status: {
                    field: 'f-status',
                    err: 'err-status'
                },
            };

            let first = null;
            Object.entries(errors).forEach(([key, msg]) => {
                const m = serverMap[key];
                if (!m) return;
                const errMsg = Array.isArray(msg) ? msg[0] : msg;
                this._setError(m.field, m.err, errMsg, m.isSelect2 ?? false);
                if (!first) first = document.getElementById(m.field);
            });

            first?.closest('.form-section')?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        },

        /* ── Save ─────────────────────────────────────────────────── */
        async save() {
            if (!this.validate()) return;

            const btn = document.getElementById('btn-submit');
            this._btnLoading(btn, true);
            this._showLoading('Menyimpan data karyawan...');

            try {
                // 1. Simpan data utama (tanpa foto)
                const fd = this._buildFormData();
                const res = await this._post(this.cfg.base + 'hrm/employees/store', fd);

                if (res.status !== 'success') {
                    if (res.errors) this.applyServerErrors(res.errors);
                    else this.toast('error', res.message || 'Terjadi kesalahan.');
                    return;
                }

                const savedId = res.id ?? this.cfg.empId;

                // 2. Upload / hapus foto jika ada perubahan
                await this._handlePhoto(savedId);

                this.toast('success', res.message || 'Data berhasil disimpan.');
                setTimeout(() => {
                    window.location.href = this.cfg.base + 'hrm/employees';
                }, 1200);

            } catch (e) {
                console.error(e);
                this.toast('error', 'Gagal mengirim data. Periksa koneksi Anda.');
            } finally {
                this._btnLoading(btn, false);
                this._hideLoading();
            }
        },

        _buildFormData() {
            const fd = new FormData();

            // Tambahkan id jika edit
            if (this.cfg.isEdit) fd.set('id', this.cfg.empId);

            // Ambil nilai semua field dari rules
            this.rules.forEach(rule => {
                const el = document.getElementById(rule.field);
                if (!el) return;
                const val = rule.isSelect2 ? ($('#' + rule.field).val() || '') : el.value.trim();
                fd.set(rule.name, val);
            });

            // department_id (hidden)
            fd.set('department_id', document.getElementById('dept-id').value);

            return fd;
        },

        async _handlePhoto(empId) {
            if (this.photo.file) {
                // Upload foto baru
                const fd = new FormData();
                fd.set('photo', this.photo.file);
                await this._post(this.cfg.base + `hrm/employees/upload-photo/${empId}`, fd);
            } else if (this.photo.removeExisting && this.cfg.isEdit) {
                // Hapus foto lama
                await this._post(this.cfg.base + `hrm/employees/delete-photo/${empId}`, new FormData());
            }
        },

        /* ── HTTP ─────────────────────────────────────────────────── */
        async _post(url, fd) {
            fd.set(this.cfg.csrfName, this.cfg.csrfHash);
            const r = await fetch(url, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.cfg.csrfHash
                },
            });
            const data = await r.json();
            if (data?.csrfHash) {
                this.cfg.csrfHash = data.csrfHash;
                const m = document.querySelector('meta[name="csrf-token"]');
                if (m) m.content = data.csrfHash;
            }
            return data;
        },

        /* ── Events ───────────────────────────────────────────────── */
        initEvents() {
            document.getElementById('btn-submit')
                ?.addEventListener('click', () => this.save());

            document.getElementById('employee-form')
                ?.addEventListener('submit', e => e.preventDefault());

            // Clear error on change — hanya required fields
            this.rules.filter(r => r.required).forEach(rule => {
                const el = document.getElementById(rule.field);
                if (!el || rule.isSelect2) return;
                el.addEventListener('change', () => this._clearError(rule.field, rule.err));
                el.addEventListener('input', () => this._clearError(rule.field, rule.err));
            });

            // Auto-capitalize fullname on blur
            document.getElementById('f-fullname')
                ?.addEventListener('blur', function() {
                    this.value = this.value.trim().replace(/\b\w/g, c => c.toUpperCase());
                });

            // Auto-uppercase NIK on blur
            document.getElementById('f-nik')
                ?.addEventListener('blur', function() {
                    this.value = this.value.trim().toUpperCase();
                });

            //add position
            document.getElementById('btn-add-position')
                ?.addEventListener('click', () => this.openAddPosition());
            document.getElementById('btn-save-position')
                ?.addEventListener('click', () => this.savePosition());
            document.getElementById('pos-desc')?.addEventListener('input', e => {
                document.getElementById('pos-char-count').textContent = e.target.value.length;
            });
        },

        openAddPosition() {
            ['pos-name', 'pos-code', 'pos-level', 'pos-desc'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.classList.remove('is-invalid');
                }
            });

            // Reset error texts — pakai optional chaining
            ['pos-err-name', 'pos-err-code', 'pos-err-level', 'pos-err-desc'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = '';
            });

            document.getElementById('pos-status').value = 'Draft';
            document.getElementById('pos-char-count').textContent = '0';
            document.getElementById('pos-modal-alert').classList.add('d-none');
            $('#pos-department').val(null).trigger('change');
            new bootstrap.Modal(document.getElementById('modalAddPosition')).show();
        },

        async savePosition() {
            // Validasi minimal
            const name = document.getElementById('pos-name').value.trim();
            const code = document.getElementById('pos-code').value.trim();
            if (!name) {
                document.getElementById('pos-name').classList.add('is-invalid');
                document.getElementById('pos-err-name').textContent = 'Nama posisi wajib diisi.';
                return;
            }
            if (!code) {
                document.getElementById('pos-code').classList.add('is-invalid');
                document.getElementById('pos-err-code').textContent = 'Kode wajib diisi.';
                return;
            }

            const btn = document.getElementById('btn-save-position');
            btn.disabled = true;
            document.getElementById('pos-save-icon').className = 'spinner-border spinner-border-sm me-1';
            document.getElementById('pos-save-text').textContent = 'Menyimpan...';

            const fd = new FormData();
            fd.set('position_name', name);
            fd.set('position_code', code.toUpperCase());
            fd.set('position_level', document.getElementById('pos-level').value || '');
            fd.set('department_id', $('#pos-department').val() || '');
            fd.set('status', document.getElementById('pos-status').value);
            fd.set('description', document.getElementById('pos-desc').value || '');

            try {
                const res = await this._post(this.cfg.base + 'hrm/positions/store', fd);

                if (res.status === 'success') {
                    // Auto-select posisi baru di Select2
                    const newOpt = new Option(name, res.id, true, true);
                    $('#f-position').append(newOpt).trigger('change');

                    // Trigger select2:select untuk auto-fill departemen
                    // Fetch department info dari server
                    const posRes = await fetch(this.cfg.base + `hrm/positions/get/${res.id}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).then(r => r.json());

                    if (posRes.status === 'success') {
                        document.getElementById('dept-display').value = posRes.data.department_name || '';
                        document.getElementById('dept-id').value = posRes.data.department_id || '';
                    }

                    bootstrap.Modal.getInstance(document.getElementById('modalAddPosition'))?.hide();
                    this._clearError('f-position', 'err-position', true);
                    this.toast('success', `Posisi "${name}" berhasil ditambahkan.`);
                } else if (res.errors) {
                    const errMap = {
                        position_name: ['pos-name', 'pos-err-name'],
                        position_code: ['pos-code', 'pos-err-code'],
                        position_level: ['pos-level', 'pos-err-level'],
                    };
                    Object.entries(res.errors).forEach(([f, msg]) => {
                        const [inp, err] = errMap[f] ?? [];
                        if (inp) document.getElementById(inp)?.classList.add('is-invalid');
                        if (err) document.getElementById(err).textContent = Array.isArray(msg) ? msg[0] : msg;
                    });
                } else {
                    document.getElementById('pos-modal-alert').classList.remove('d-none');
                    document.getElementById('pos-modal-alert-text').textContent = res.message || 'Terjadi kesalahan.';
                }
            } catch (e) {
                this.toast('error', 'Gagal menyimpan posisi.');
            } finally {
                btn.disabled = false;
                document.getElementById('pos-save-icon').className = 'fas fa-save me-1';
                document.getElementById('pos-save-text').textContent = 'Simpan';
            }
        },

        /* ── UI Helpers ───────────────────────────────────────────── */
        _showLoading(msg = 'Memproses...') {
            document.getElementById('loading-text').textContent = msg;
            document.getElementById('loading-overlay').style.display = 'flex';
        },
        _hideLoading() {
            document.getElementById('loading-overlay').style.display = 'none';
        },
        _btnLoading(btn, on) {
            if (!btn) return;
            if (on) {
                btn.disabled = true;
                btn._orig = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            } else {
                btn.disabled = false;
                btn.innerHTML = btn._orig ?? btn.innerHTML;
            }
        },
        toast(type, msg) {
            Swal.fire({
                toast: true,
                position: 'top-right',
                icon: type,
                title: msg,
                showConfirmButton: false,
                timer: type === 'success' ? 2000 : 4000,
                timerProgressBar: true,
            });
        },
    };

    $(document).ready(() => EmployeeForm.init());
</script>
<?= $this->endSection() ?>