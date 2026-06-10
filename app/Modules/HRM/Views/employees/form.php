<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    /* ── Prevent horizontal scrollbar ─────────────────────────────── */
    body {
        overflow-x: hidden;
    }

    /* ── Form Wrapper ── Tengah ───────────────────────────────────── */
    .form-wrapper {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* ── Page Header tetap di kiri ────────────────────────────────── */
    .page-header {
        text-align: left;
        margin-bottom: 1.5rem;
    }

    .page-header .breadcrumb {
        justify-content: flex-start;
        padding-left: 0;
    }

    .page-header h1,
    .page-header p {
        text-align: left;
    }

    /* ── Section Card ─────────────────────────────────────────────── */
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

    .form-section-header span.section-icon {
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

    /* ── Photo Upload ─────────────────────────────────────────────── */
    .photo-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .5rem;
        width: 130px;
        flex-shrink: 0;
    }

    .photo-upload-area {
        width: 120px;
        height: 120px;
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

    #btn-remove-photo {
        font-size: .68rem;
        padding: .2rem .6rem;
        display: none;
    }

    .photo-upload-area.has-photo~#btn-remove-photo,
    .show-remove #btn-remove-photo {
        display: inline-flex;
    }

    .photo-hint {
        font-size: .65rem;
        color: var(--phoenix-secondary-color);
        text-align: center;
        line-height: 1.4;
    }

    /* ── Form Validation (Bootstrap style) ────────────────────────── */
    .needs-validation .was-validated .form-control:valid,
    .needs-validation .was-validated .form-select:valid {
        border-color: var(--phoenix-success);
        padding-right: calc(1.5em + .75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(.375em + .1875rem) center;
        background-size: calc(.75em + .375rem) calc(.75em + .375rem);
    }

    .needs-validation .was-validated .form-control:invalid,
    .needs-validation .was-validated .form-select:invalid {
        border-color: var(--phoenix-danger);
        padding-right: calc(1.5em + .75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(.375em + .1875rem) center;
        background-size: calc(.75em + .375rem) calc(.75em + .375rem);
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: .25rem;
        font-size: .75rem;
        color: var(--phoenix-danger);
    }

    .was-validated .form-control:invalid~.invalid-feedback,
    .was-validated .form-select:invalid~.invalid-feedback,
    .form-control.is-invalid~.invalid-feedback,
    .form-select.is-invalid~.invalid-feedback {
        display: block;
    }

    /* ── Select2 Validation ───────────────────────────────────────── */
    .select2-selection.is-invalid {
        border-color: var(--phoenix-danger) !important;
    }

    .select2-selection.is-valid {
        border-color: var(--phoenix-success) !important;
    }

    .was-validated .select2-container--bootstrap-5 .select2-selection--single.is-invalid {
        border-color: var(--phoenix-danger) !important;
    }

    .was-validated .select2-container--bootstrap-5 .select2-selection--single.is-valid {
        border-color: var(--phoenix-success) !important;
    }

    /* ── NIK Format Hint ──────────────────────────────────────────── */
    .nik-format-hint {
        font-size: .68rem;
        color: var(--phoenix-secondary-color);
        font-family: monospace;
    }

    /* ── Select2 Sizing ───────────────────────────────────────────── */
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

    /* ── Department Display ───────────────────────────────────────── */
    #dept-display {
        background: var(--phoenix-secondary-bg);
        color: var(--phoenix-secondary-color);
        font-style: italic;
    }

    /* ── Sticky Footer ────────────────────────────────────────────── */
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$isEdit     = isset($employee) && !empty($employee['id']);
$formAction = $isEdit
    ? site_url("hrm/employees/{$employee['id']}/update")
    : site_url('hrm/employees/store');
$emp        = $employee ?? [];
?>

<div class="form-wrapper">

    <!-- Page Header (tetap di kiri) -->
    <div class="page-header">
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
        <h1 class="h3 fw-bold mb-1"><?= esc((string)$page_title) ?></h1>
        <p class="text-body-tertiary mb-0"><?= esc((string)$page_description) ?></p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-subtle-danger py-2 px-3 mb-4">
            <span class="fas fa-exclamation-triangle me-2"></span>
            <strong>Terdapat kesalahan, periksa kembali form:</strong>
            <ul class="mb-0 mt-1 ps-3">
                <?php foreach ($errors as $err): ?>
                    <li class="fs-10"><?= esc((string)$err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= $formAction ?>" method="post" enctype="multipart/form-data" id="employee-form" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="POST">
        <?php endif; ?>

        <!-- ========================================================= -->
        <!-- SECTION 1 — IDENTITAS                                     -->
        <!-- ========================================================= -->
        <div class="form-section">
            <div class="form-section-header">
                <span class="section-icon bg-primary bg-opacity-10 text-primary">
                    <span class="fas fa-id-card"></span>
                </span>
                <span class="section-title">Identitas Karyawan</span>
            </div>
            <div class="form-section-body">
                <div class="row g-3">

                    <!-- Foto -->
                    <div class="col-12 col-md-auto">
                        <div class="photo-wrap" id="photo-wrap">
                            <div class="photo-upload-area <?= !empty($emp['photo']) ? 'has-photo' : '' ?>" id="photo-area"
                                onclick="document.getElementById('photo-input').click()">
                                <img src="<?= !empty($emp['photo']) ? base_url('uploads/employees/' . esc($emp['photo'])) : '' ?>"
                                    id="photo-preview" alt="Foto karyawan">
                                <div class="photo-overlay">
                                    <i class="fas fa-camera"></i>
                                    <span>Ganti Foto</span>
                                </div>
                                <div class="photo-placeholder">
                                    <i class="fas fa-camera upload-icon"></i>
                                    <div class="upload-hint">Klik untuk<br>upload foto</div>
                                </div>
                            </div>
                            <input type="file" id="photo-input" name="photo" accept="image/jpeg,image/png,image/webp" class="d-none">
                            <button type="button" class="btn btn-subtle-danger btn-sm align-items-center gap-1" id="btn-remove-photo"
                                style="<?= !empty($emp['photo']) ? 'display:inline-flex' : 'display:none' ?>">
                                <i class="fas fa-times"></i> Hapus foto
                            </button>
                            <input type="hidden" name="remove_photo" id="remove-photo" value="0">
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
                                <input type="text"
                                    class="form-control form-control-sm font-monospace <?= isset($errors['nik']) ? 'is-invalid' : '' ?>"
                                    id="f-nik" name="nik"
                                    value="<?= old('nik', $emp['nik'] ?? '') ?>"
                                    placeholder="cth: 03.42.01.1234"
                                    maxlength="30" autocomplete="off">
                                <div class="nik-format-hint mt-1">Format bebas — sesuai aturan perusahaan</div>
                                <?php if (isset($errors['nik'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['nik']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Nama Lengkap -->
                            <div class="col-md-7">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-fullname">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-sm <?= isset($errors['fullname']) ? 'is-invalid' : '' ?>"
                                    id="f-fullname" name="fullname"
                                    value="<?= old('fullname', $emp['fullname'] ?? '') ?>"
                                    placeholder="cth: Budi Santoso"
                                    maxlength="100" autocomplete="off">
                                <?php if (isset($errors['fullname'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['fullname']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Nama Panggilan -->
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-nickname">
                                    Nama Panggilan
                                </label>
                                <input type="text" class="form-control form-control-sm"
                                    id="f-nickname" name="nickname"
                                    value="<?= old('nickname', $emp['nickname'] ?? '') ?>"
                                    placeholder="cth: Budi" maxlength="50">
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-gender">
                                    Jenis Kelamin <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-sm <?= isset($errors['gender']) ? 'is-invalid' : '' ?>"
                                    id="f-gender" name="gender">
                                    <option value="">— Pilih —</option>
                                    <option value="L" <?= old('gender', $emp['gender'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="P" <?= old('gender', $emp['gender'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                                <?php if (isset($errors['gender'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['gender']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- No Telepon -->
                            <div class="col-md-4">
                                <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-phone">
                                    No. Telepon
                                </label>
                                <input type="text" class="form-control form-control-sm"
                                    id="f-phone" name="phone"
                                    value="<?= old('phone', $emp['phone'] ?? '') ?>"
                                    placeholder="cth: 08123456789" maxlength="20">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- SECTION 2 — PENEMPATAN & JABATAN                          -->
        <!-- ========================================================= -->
        <div class="form-section">
            <div class="form-section-header">
                <span class="section-icon bg-info bg-opacity-10 text-info">
                    <span class="fas fa-briefcase"></span>
                </span>
                <span class="section-title">Penempatan &amp; Jabatan</span>
            </div>
            <div class="form-section-body">
                <div class="row g-3">

                    <!-- Posisi (Select2 AJAX) -->
                    <div class="col-md-6">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-position">
                            Posisi <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm <?= isset($errors['position_id']) ? 'is-invalid' : '' ?>"
                            id="f-position" name="position_id" style="width:100%">
                            <?php if (!empty($emp['position_id'])): ?>
                                <option value="<?= $emp['position_id'] ?>" selected>
                                    <?= esc($emp['position_name'] ?? 'Posisi #' . $emp['position_id']) ?>
                                </option>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($errors['position_id'])): ?>
                            <div class="invalid-feedback d-block"><?= esc($errors['position_id']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Departemen (auto-fill) -->
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
                            value="<?= old('work_area', $emp['work_area'] ?? '') ?>"
                            placeholder="cth: Lantai 2 - Mesin A" maxlength="100">
                    </div>

                    <!-- Shift -->
                    <div class="col-md-4">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-shift">
                            Shift <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm <?= isset($errors['shift']) ? 'is-invalid' : '' ?>"
                            id="f-shift" name="shift">
                            <option value="">— Pilih Shift —</option>
                            <?php
                            $shifts = ['NS' => 'Non Shift', 'A' => 'Shift A', 'B' => 'Shift B', 'C' => 'Shift C', 'D' => 'Shift D', 'E' => 'Shift E'];
                            $selShift = old('shift', $emp['shift'] ?? '');
                            foreach ($shifts as $val => $label): ?>
                                <option value="<?= $val ?>" <?= $selShift === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['shift'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['shift']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Status Kerja -->
                    <div class="col-md-4">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-employment-status">
                            Status Kerja <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm <?= isset($errors['employment_status']) ? 'is-invalid' : '' ?>"
                            id="f-employment-status" name="employment_status">
                            <option value="">— Pilih —</option>
                            <?php
                            $empStatuses = ['tetap' => 'Tetap', 'kontrak' => 'Kontrak', 'magang' => 'Magang'];
                            $selEmpSt = old('employment_status', $emp['employment_status'] ?? '');
                            foreach ($empStatuses as $val => $label): ?>
                                <option value="<?= $val ?>" <?= $selEmpSt === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['employment_status'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['employment_status']) ?></div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- SECTION 3 — TANGGAL & STATUS                              -->
        <!-- ========================================================= -->
        <div class="form-section">
            <div class="form-section-header">
                <span class="section-icon bg-success bg-opacity-10 text-success">
                    <span class="fas fa-calendar-alt"></span>
                </span>
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
                            value="<?= old('join_date', $emp['join_date'] ?? '') ?>"
                            placeholder="DD-MM-YYYY" autocomplete="off">
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label class="form-label fs-9 fw-semibold text-uppercase text-muted" for="f-status">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm <?= isset($errors['status']) ? 'is-invalid' : '' ?>"
                            id="f-status" name="status">
                            <?php
                            $selStatus = old('status', $emp['status'] ?? 'active');
                            foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $val => $label): ?>
                                <option value="<?= $val ?>" <?= $selStatus === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['status'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['status']) ?></div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Form Footer (Sticky) -->
        <div class="form-footer">
            <a href="<?= site_url('hrm/employees') ?>" class="btn btn-subtle-secondary btn-sm">
                <span class="fas fa-times me-1"></span>Batal
            </a>
            <?php if ($isEdit): ?>
                <a href="<?= site_url('hrm/employees/' . $employee['id']) ?>" class="btn btn-subtle-info btn-sm">
                    <span class="fas fa-eye me-1"></span>Lihat Detail
                </a>
            <?php endif; ?>
            <button type="button" class="btn btn-primary btn-sm" id="btn-submit">
                <span class="fas fa-save me-1"></span><?= $isEdit ? 'Simpan Perubahan' : 'Simpan Karyawan' ?>
            </button>
        </div>

    </form>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div class="text-center text-white">
        <div class="spinner-border mb-2" style="width:3rem;height:3rem;"></div>
        <div class="loading-text fw-semibold">Loading...</div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
    const EmployeeForm = {
        config: {
            baseUrl: '<?= base_url() ?>',
            csrfName: '<?= csrf_token() ?>',
            csrfHash: '<?= csrf_hash() ?>',
            isEdit: <?= $isEdit ? 'true' : 'false' ?>,
            empId: <?= !empty($employee['id']) ? (int)$employee['id'] : 0 ?>,
            editData: <?= !empty($employee) ? json_encode($employee) : 'null' ?>,
        },

        init() {
            this.initFlatpickr();
            this.initSelect2();
            this.initPhoto();
            this.initEventHandlers();
            if (this.config.isEdit && this.config.editData) {
                this.populateForm(this.config.editData);
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
            $('#f-position').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '— Cari posisi —',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: this.config.baseUrl + 'hrm/positions/select2',
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
                        })),
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

            $('#f-position').on('select2:select', e => {
                const d = e.params.data;
                document.getElementById('dept-display').value = d.department_name || '';
                document.getElementById('dept-id').value = d.department_id || '';
                this.clearFieldError('position_id');
            });

            $('#f-position').on('select2:clear', () => {
                document.getElementById('dept-display').value = '';
                document.getElementById('dept-id').value = '';
            });
        },

        populateForm(data) {
            if (data.position_id) {
                const opt = new Option(data.position_name || 'Posisi #' + data.position_id, data.position_id, true, true);
                $('#f-position').append(opt);
                document.getElementById('dept-display').value = data.department_name || '';
                document.getElementById('dept-id').value = data.department_id || '';
            }
        },

        initPhoto() {
            const input = document.getElementById('photo-input');
            const preview = document.getElementById('photo-preview');
            const area = document.getElementById('photo-area');
            const btnRemove = document.getElementById('btn-remove-photo');
            const flagRemove = document.getElementById('remove-photo');

            const setHasPhoto = show => {
                area.classList.toggle('has-photo', show);
                btnRemove.style.display = show ? 'inline-flex' : 'none';
            };

            if (preview.src && preview.src !== window.location.href) setHasPhoto(true);

            input.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                if (file.size > 2 * 1024 * 1024) {
                    EmployeeForm.showToast('error', 'Ukuran Berlebih', 'Foto maksimal 2 MB.');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    flagRemove.value = '0';
                    setHasPhoto(true);
                };
                reader.readAsDataURL(file);
            });

            btnRemove.addEventListener('click', () => {
                preview.src = '';
                input.value = '';
                flagRemove.value = '1';
                setHasPhoto(false);
            });
        },

        validateForm() {
            const form = document.getElementById('employee-form');
            form.classList.add('was-validated');
            this.clearAllErrors();

            let valid = true;

            const rules = [{
                    id: 'f-nik',
                    msg: 'NIK wajib diisi.',
                    check: () => document.getElementById('f-nik').value.trim() !== ''
                },
                {
                    id: 'f-fullname',
                    msg: 'Nama lengkap wajib diisi.',
                    check: () => document.getElementById('f-fullname').value.trim() !== ''
                },
                {
                    id: 'f-gender',
                    msg: 'Jenis kelamin wajib dipilih.',
                    check: () => document.getElementById('f-gender').value !== ''
                },
                {
                    id: 'f-position',
                    msg: 'Posisi wajib dipilih.',
                    check: () => !!$('#f-position').val(),
                    isSelect2: true
                },
                {
                    id: 'f-shift',
                    msg: 'Shift wajib dipilih.',
                    check: () => document.getElementById('f-shift').value !== ''
                },
                {
                    id: 'f-employment-status',
                    msg: 'Status kerja wajib dipilih.',
                    check: () => document.getElementById('f-employment-status').value !== ''
                },
                {
                    id: 'f-status',
                    msg: 'Status wajib dipilih.',
                    check: () => document.getElementById('f-status').value !== ''
                },
            ];

            rules.forEach(rule => {
                const ok = rule.check();
                if (!ok) {
                    valid = false;
                    this.markInvalid(rule.id, rule.msg, rule.isSelect2);
                } else {
                    this.markValid(rule.id, rule.isSelect2);
                }
            });

            if (!valid) {
                document.querySelector('.is-invalid')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            return valid;
        },

        markInvalid(fieldId, msg, isSelect2 = false) {
            const el = document.getElementById(fieldId);
            if (!el) return;
            el.classList.add('is-invalid');
            if (isSelect2) {
                $(el).next('.select2-container').find('.select2-selection').addClass('is-invalid');
            }
            let fb = el.parentElement?.querySelector('.invalid-feedback');
            if (!fb && !isSelect2) {
                fb = document.createElement('div');
                fb.className = 'invalid-feedback';
                el.insertAdjacentElement('afterend', fb);
            }
            if (fb) fb.textContent = msg;
        },

        markValid(fieldId, isSelect2 = false) {
            const el = document.getElementById(fieldId);
            if (!el) return;
            el.classList.remove('is-invalid');
            el.classList.add('is-valid');
            if (isSelect2) {
                $(el).next('.select2-container').find('.select2-selection').removeClass('is-invalid').addClass('is-valid');
            }
        },

        clearAllErrors() {
            document.querySelectorAll('.is-invalid, .is-valid').forEach(el => el.classList.remove('is-invalid', 'is-valid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            $('.select2-selection').removeClass('is-invalid is-valid');
        },

        clearFieldError(fieldId) {
            const el = document.getElementById(fieldId);
            if (el) el.classList.remove('is-invalid');
        },

        displayServerErrors(errors) {
            const map = {
                nik: 'f-nik',
                fullname: 'f-fullname',
                nickname: 'f-nickname',
                gender: 'f-gender',
                phone: 'f-phone',
                position_id: 'f-position',
                work_area: 'f-work-area',
                shift: 'f-shift',
                employment_status: 'f-employment-status',
                join_date: 'f-join-date',
                status: 'f-status',
            };
            Object.entries(errors).forEach(([field, msg]) => {
                const id = map[field] ?? field;
                this.markInvalid(id, msg, field === 'position_id');
            });
            document.querySelector('.is-invalid')?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        },

        save() {
            if (!this.validateForm()) return;

            const btn = document.getElementById('btn-submit');
            this.setButtonLoading(btn, true, 'Menyimpan...');
            this.showLoading('Menyimpan data karyawan...');

            const form = document.getElementById('employee-form');
            const formData = new FormData(form);
            if (this.config.isEdit) formData.set('id', this.config.empId);

            this.fetchPost(
                    this.config.baseUrl + (this.config.isEdit ? `hrm/employees/${this.config.empId}/update` : 'hrm/employees/store'),
                    formData
                )
                .then(data => this.handleServerResponse(data))
                .catch(err => {
                    console.error(err);
                    this.showToast('error', 'Error', 'Gagal mengirim data. Periksa koneksi Anda.');
                })
                .finally(() => {
                    this.setButtonLoading(btn, false);
                    this.hideLoading();
                });
        },

        handleServerResponse(data) {
            if (data?.status === 'success') {
                this.showToast('success', 'Berhasil', data.message || 'Data berhasil disimpan');
                setTimeout(() => window.location.href = this.config.baseUrl + 'hrm/employees', 1200);
                return;
            }
            if (data?.errors) {
                this.displayServerErrors(data.errors);
                this.showToast('error', 'Validasi Gagal', 'Periksa kembali isian yang ditandai merah.');
                return;
            }
            this.showToast('error', 'Gagal', data?.message || 'Terjadi kesalahan. Coba lagi.');
        },

        fetchPost(url, formData) {
            formData.set(this.config.csrfName, this.config.csrfHash);
            return fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': this.config.csrfHash
                    },
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    if (data?.csrfHash) {
                        this.config.csrfHash = data.csrfHash;
                        document.querySelector('meta[name="csrf-token"]').content = data.csrfHash;
                    }
                    return data;
                });
        },

        showLoading(msg) {
            const el = document.getElementById('loadingOverlay');
            el.querySelector('.loading-text').textContent = msg;
            el.style.display = 'flex';
        },

        hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        },

        setButtonLoading(btn, isLoading, loadingText = 'Loading...') {
            if (!btn) return;
            if (isLoading) {
                btn.disabled = true;
                btn.dataset.origHtml = btn.innerHTML;
                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>${loadingText}`;
            } else {
                btn.disabled = false;
                if (btn.dataset.origHtml) btn.innerHTML = btn.dataset.origHtml;
            }
        },

        showToast(type, title, message) {
            Swal.fire({
                title,
                text: message,
                icon: type,
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: type === 'success' ? 2000 : 3500,
                timerProgressBar: true
            });
        },

        initEventHandlers() {
            document.getElementById('btn-submit')?.addEventListener('click', () => this.save());
            document.getElementById('employee-form')?.addEventListener('submit', e => e.preventDefault());

            ['f-nik', 'f-fullname', 'f-nickname', 'f-gender', 'f-phone', 'f-work-area', 'f-shift', 'f-employment-status', 'f-join-date', 'f-status'].forEach(id => {
                document.getElementById(id)?.addEventListener('change', () => this.clearFieldError(id));
            });

            document.getElementById('f-nik')?.addEventListener('blur', function() {
                this.value = this.value.trim();
            });
            document.getElementById('f-fullname')?.addEventListener('blur', function() {
                this.value = this.value.trim().replace(/\b\w/g, c => c.toUpperCase());
            });
        },
    };

    $(document).ready(() => EmployeeForm.init());
</script>
<?= $this->endSection() ?>