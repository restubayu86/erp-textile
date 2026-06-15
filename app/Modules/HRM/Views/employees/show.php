<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">
<style>
    body {
        overflow-x: hidden;
    }

    /* ── Page wrapper ─────────────────────────────────────────── */
    .detail-wrapper {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* ── Hero card (foto + identitas utama) ───────────────────── */
    .hero-card {
        border-radius: 1rem;
        border: 1px solid var(--phoenix-border-color);
        overflow: hidden;
        background: var(--phoenix-card-bg);
        margin-bottom: 1.25rem;
    }

    .hero-banner {
        height: 100px;
        background: linear-gradient(135deg,
                rgba(var(--phoenix-primary-rgb), .15) 0%,
                rgba(var(--phoenix-info-rgb), .1) 100%);
        border-bottom: 1px solid var(--phoenix-border-color);
        position: relative;
    }

    .hero-body {
        padding: 0 1.5rem 1.25rem;
        position: relative;
    }

    .hero-avatar-wrap {
        position: relative;
        display: inline-block;
        margin-top: -44px;
        margin-bottom: .75rem;
    }

    .hero-avatar {
        width: 88px;
        height: 88px;
        border-radius: 16px;
        object-fit: cover;
        border: 3px solid var(--phoenix-card-bg);
        box-shadow: 0 4px 12px rgba(0, 0, 0, .12);
        display: block;
    }

    .hero-avatar-placeholder {
        width: 88px;
        height: 88px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        background: rgba(var(--phoenix-primary-rgb), .12);
        color: var(--phoenix-primary);
        border: 3px solid var(--phoenix-card-bg);
        box-shadow: 0 4px 12px rgba(0, 0, 0, .12);
    }

    .hero-status-dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 2px solid var(--phoenix-card-bg);
        position: absolute;
        bottom: 4px;
        right: 4px;
    }

    .hero-status-dot.active {
        background: var(--phoenix-success);
    }

    .hero-status-dot.inactive {
        background: var(--phoenix-secondary);
    }

    /* ── Section card ─────────────────────────────────────────── */
    .info-section {
        border-radius: .75rem;
        border: 1px solid var(--phoenix-border-color);
        margin-bottom: 1.25rem;
        overflow: hidden;
        background: var(--phoenix-card-bg);
    }

    .info-section-header {
        padding: .6rem 1rem;
        background: var(--phoenix-secondary-bg);
        border-bottom: 1px solid var(--phoenix-border-color);
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .info-section-header .sec-icon {
        width: 26px;
        height: 26px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        flex-shrink: 0;
    }

    .info-section-header .sec-title {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--phoenix-body-color);
    }

    .info-section-body {
        padding: 1rem;
    }

    /* ── Info row ─────────────────────────────────────────────── */
    .info-row {
        display: flex;
        flex-direction: column;
        gap: .1rem;
        padding: .55rem 0;
        border-bottom: 1px dashed var(--phoenix-border-color);
    }

    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-row:first-child {
        padding-top: 0;
    }

    .info-label {
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--phoenix-secondary-color);
    }

    .info-value {
        font-size: .875rem;
        color: var(--phoenix-body-color);
        word-break: break-word;
    }

    .info-value.empty {
        color: var(--phoenix-secondary-color);
        font-style: italic;
    }

    /* ── Badges ───────────────────────────────────────────────── */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .3rem .65rem;
        border-radius: 20px;
        font-size: .75rem;
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

    .badge-gender {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .3rem .65rem;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 600;
    }

    .badge-gender.male {
        background: rgba(13, 110, 253, .1);
        color: #0d6efd;
        border: 1px solid rgba(13, 110, 253, .2);
    }

    .badge-gender.female {
        background: rgba(220, 53, 69, .1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, .2);
    }

    .badge-dept {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .3rem .65rem;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 600;
        background: rgba(var(--phoenix-primary-rgb), .12);
        color: var(--phoenix-primary);
        border: 1px solid rgba(var(--phoenix-primary-rgb), .25);
    }

    .badge-employment {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .3rem .65rem;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 600;
    }

    .badge-employment.tetap {
        background: rgba(var(--phoenix-success-rgb), .12);
        color: var(--phoenix-success);
        border: 1px solid rgba(var(--phoenix-success-rgb), .25);
    }

    .badge-employment.kontrak {
        background: rgba(var(--phoenix-warning-rgb), .12);
        color: var(--phoenix-warning);
        border: 1px solid rgba(var(--phoenix-warning-rgb), .25);
    }

    .badge-employment.magang {
        background: rgba(var(--phoenix-info-rgb), .12);
        color: var(--phoenix-info);
        border: 1px solid rgba(var(--phoenix-info-rgb), .25);
    }

    /* ── Shift badge ──────────────────────────────────────────── */
    .shift-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .3rem .65rem;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 600;
    }

    /* ── Meta row (created/updated) ───────────────────────────── */
    .meta-row {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        padding: .65rem 1rem;
        background: var(--phoenix-secondary-bg);
        border-top: 1px solid var(--phoenix-border-color);
        font-size: .72rem;
        color: var(--phoenix-secondary-color);
    }

    .meta-row .meta-item {
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    /* ── Sticky action bar ────────────────────────────────────── */
    .action-bar {
        position: sticky;
        bottom: 0;
        z-index: 100;
        background: var(--phoenix-body-bg);
        border-top: 1px solid var(--phoenix-border-color);
        padding: .75rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .5rem;
        border-radius: 0 0 .75rem .75rem;
    }

    /* ── NIK monospace highlight ──────────────────────────────── */
    .nik-display {
        font-family: var(--phoenix-font-monospace, monospace);
        font-size: .9rem;
        font-weight: 700;
        letter-spacing: .05em;
        color: var(--phoenix-primary);
    }

    /* ── Quick stats bar ──────────────────────────────────────── */
    .quick-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: .75rem 1rem;
        border-right: 1px solid var(--phoenix-border-color);
        flex: 1;
        min-width: 0;
    }

    .quick-stat:last-child {
        border-right: none;
    }

    .quick-stat .qs-label {
        font-size: .6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--phoenix-secondary-color);
        margin-bottom: .2rem;
        text-align: center;
    }

    .quick-stat .qs-value {
        font-size: .8rem;
        font-weight: 600;
        color: var(--phoenix-body-color);
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .quick-stats-bar {
        display: flex;
        border-top: 1px solid var(--phoenix-border-color);
        background: var(--phoenix-secondary-bg);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$emp    = $employee ?? [];
$isEdit = canDo('hrm.employees.edit');
$isDel  = canDo('hrm.employees.delete');

/* ── Helpers ─────────────────────────────────────────────────────── */
$val = fn($key, $fallback = null) => !empty($emp[$key]) ? $emp[$key] : $fallback;

$shiftMap = [
    'NS' => ['label' => 'Non Shift', 'cls' => 'badge-phoenix-secondary', 'icon' => 'fa-moon'],
    'A'  => ['label' => 'Shift A',   'cls' => 'badge-phoenix-primary',   'icon' => 'fa-clock'],
    'B'  => ['label' => 'Shift B',   'cls' => 'badge-phoenix-info',      'icon' => 'fa-clock'],
    'C'  => ['label' => 'Shift C',   'cls' => 'badge-phoenix-success',   'icon' => 'fa-clock'],
    'D'  => ['label' => 'Shift D',   'cls' => 'badge-phoenix-warning',   'icon' => 'fa-clock'],
    'E'  => ['label' => 'Shift E',   'cls' => 'badge-phoenix-danger',    'icon' => 'fa-clock'],
];

$empStatusMap = [
    'tetap'   => ['label' => 'Tetap',   'cls' => 'tetap'],
    'kontrak' => ['label' => 'Kontrak', 'cls' => 'kontrak'],
    'magang'  => ['label' => 'Magang',  'cls' => 'magang'],
];

$initial  = strtoupper(substr($val('fullname', '?'), 0, 1));
$isActive = strtolower($val('status', 'inactive')) === 'active';
$gender   = $val('gender', '');
$shift    = $val('shift', '');
$empSt    = strtolower($val('employment_status', ''));

$joinDate = $val('join_date');
$joinFormatted = '';
if ($joinDate) {
    try {
        $dt = new DateTime($joinDate);
        $joinFormatted = $dt->format('d M Y');
        // Hitung masa kerja
        $now  = new DateTime();
        $diff = $now->diff($dt);
        $tenure = '';
        if ($diff->y > 0) $tenure .= $diff->y . ' tahun ';
        if ($diff->m > 0) $tenure .= $diff->m . ' bulan';
        $tenure = trim($tenure) ?: '< 1 bulan';
    } catch (Exception $e) {
        $joinFormatted = esc($joinDate);
        $tenure = '';
    }
}
?>

<div class="detail-wrapper">

    <!-- Breadcrumb + Title -->
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

    <!-- ── Hero Card ──────────────────────────────────────────── -->
    <div class="hero-card">
        <div class="hero-banner"></div>
        <div class="hero-body">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
                <div class="d-flex align-items-end gap-3">
                    <div class="hero-avatar-wrap">
                        <?php if ($val('photo')): ?>
                            <img src="<?= base_url('uploads/employees/' . esc((string) $val('photo'))) ?>"
                                class="hero-avatar"
                                alt="Foto <?= esc((string) $val('fullname')) ?>"
                                onerror="this.outerHTML='<div class=\'hero-avatar-placeholder\'><?= $initial ?></div>'">
                        <?php else: ?>
                            <div class="hero-avatar-placeholder"><?= $initial ?></div>
                        <?php endif; ?>
                        <span class="hero-status-dot <?= $isActive ? 'active' : 'inactive' ?>"
                            title="<?= $isActive ? 'Active' : 'Inactive' ?>"></span>
                    </div>
                    <div class="mb-1">
                        <h2 class="h4 fw-bold mb-0"><?= esc((string) $val('fullname', '—')) ?></h2>
                        <?php if ($val('nickname')): ?>
                            <div class="text-body-tertiary fs-9 mb-1">"<?= esc((string) $val('nickname')) ?>"</div>
                        <?php endif; ?>
                        <div class="nik-display"><?= esc((string) $val('nik', '—')) ?></div>
                    </div>
                </div>

                <!-- Status + Gender pills -->
                <div class="d-flex gap-2 flex-wrap mb-1">
                    <span class="badge-status <?= $isActive ? 'active' : 'inactive' ?>">
                        <span class="fas <?= $isActive ? 'fa-check-circle' : 'fa-times-circle' ?>"></span>
                        <?= $isActive ? 'Active' : 'Inactive' ?>
                    </span>
                    <?php if ($gender): ?>
                        <span class="badge-gender <?= $gender === 'L' ? 'male' : 'female' ?>">
                            <span class="fas <?= $gender === 'L' ? 'fa-mars' : 'fa-venus' ?>"></span>
                            <?= $gender === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick stats bar -->
        <div class="quick-stats-bar">
            <div class="quick-stat">
                <div class="qs-label">Posisi</div>
                <div class="qs-value fw-semibold"><?= esc((string)($val('position_name') ?? '—')) ?></div>
            </div>
            <div class="quick-stat">
                <div class="qs-label">Departemen</div>
                <div class="qs-value"><?= esc((string)($val('department_name') ?? '—')) ?></div>
            </div>
            <div class="quick-stat">
                <div class="qs-label">Shift</div>
                <div class="qs-value">
                    <?php if ($shift && isset($shiftMap[$shift])): ?>
                        <?= esc((string)$shiftMap[$shift]['label']) ?>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($joinFormatted): ?>
                <div class="quick-stat">
                    <div class="qs-label">Bergabung</div>
                    <div class="qs-value"><?= esc($joinFormatted) ?></div>
                </div>
                <?php if (!empty($tenure)): ?>
                    <div class="quick-stat">
                        <div class="qs-label">Masa Kerja</div>
                        <div class="qs-value text-primary fw-semibold"><?= esc($tenure) ?></div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Meta row -->
        <div class="meta-row">
            <?php if ($val('created_at')): ?>
                <div class="meta-item">
                    <span class="fas fa-plus-circle text-success"></span>
                    Dibuat <?= esc((string) date('d M Y H:i', strtotime($val('created_at')))) ?>
                    <?php if ($val('created_by_name')): ?>
                        oleh <strong><?= esc((string) $val('created_by_name')) ?></strong>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($val('updated_at')): ?>
                <div class="meta-item">
                    <span class="fas fa-edit text-warning"></span>
                    Diupdate <?= esc((string) date('d M Y H:i', strtotime($val('updated_at')))) ?>
                    <?php if ($val('updated_by_name')): ?>
                        oleh <strong><?= esc((string) $val('updated_by_name')) ?></strong>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-3">
        <!-- Kolom Kiri -->
        <div class="col-lg-6">

            <!-- ── SECTION: Identitas ──────────────────────────── -->
            <div class="info-section">
                <div class="info-section-header">
                    <span class="sec-icon bg-primary bg-opacity-10 text-primary"><span class="fas fa-id-card"></span></span>
                    <span class="sec-title">Identitas</span>
                </div>
                <div class="info-section-body">

                    <div class="info-row">
                        <div class="info-label">NIK</div>
                        <div class="info-value">
                            <?php if ($val('nik')): ?>
                                <span class="font-monospace fw-bold"><?= esc((string) $val('nik')) ?></span>
                            <?php else: ?>
                                <span class="empty">—</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value fw-semibold"><?= esc((string)($val('fullname') ?? '—')) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nama Panggilan</div>
                        <div class="info-value">
                            <?= $val('nickname')
                                ? esc((string) $val('nickname'))
                                : '<span class="empty">—</span>' ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Jenis Kelamin</div>
                        <div class="info-value">
                            <?php if ($gender): ?>
                                <span class="badge-gender <?= $gender === 'L' ? 'male' : 'female' ?>">
                                    <span class="fas <?= $gender === 'L' ? 'fa-mars' : 'fa-venus' ?>"></span>
                                    <?= $gender === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                </span>
                            <?php else: ?>
                                <span class="empty">—</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">No. Telepon</div>
                        <div class="info-value">
                            <?php if ($val('phone')): ?>
                                <a href="tel:<?= esc((string) $val('phone')) ?>"
                                    class="text-decoration-none">
                                    <span class="fas fa-phone me-1 text-success fs-10"></span><?= esc((string) $val('phone')) ?>
                                </a>
                            <?php else: ?>
                                <span class="empty">—</span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Kolom Kanan -->
        <div class="col-lg-6">

            <!-- ── SECTION: Penempatan & Jabatan ──────────────── -->
            <div class="info-section">
                <div class="info-section-header">
                    <span class="sec-icon bg-info bg-opacity-10 text-info"><span class="fas fa-briefcase"></span></span>
                    <span class="sec-title">Penempatan &amp; Jabatan</span>
                </div>
                <div class="info-section-body">

                    <div class="info-row">
                        <div class="info-label">Posisi / Jabatan</div>
                        <div class="info-value fw-semibold">
                            <?= $val('position_name')
                                ? esc((string) $val('position_name'))
                                : '<span class="empty">—</span>' ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Departemen</div>
                        <div class="info-value">
                            <?php if ($val('department_name')): ?>
                                <span class="badge-dept">
                                    <span class="fas fa-building"></span>
                                    <?= esc((string) $val('department_name')) ?>
                                </span>
                            <?php else: ?>
                                <span class="empty">—</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Area Kerja</div>
                        <div class="info-value">
                            <?= $val('work_area')
                                ? '<span class="fas fa-map-marker-alt text-muted me-1 fs-10"></span>' . esc((string) $val('work_area'))
                                : '<span class="empty">—</span>' ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Shift</div>
                        <div class="info-value">
                            <?php if ($shift && isset($shiftMap[$shift])): ?>
                                <span class="badge badge-phoenix rounded-pill p-2 fs-10 <?= $shiftMap[$shift]['cls'] ?>">
                                    <span class="fas <?= $shiftMap[$shift]['icon'] ?> me-1"></span>
                                    <?= esc((string)$shiftMap[$shift]['label']) ?>
                                </span>
                            <?php else: ?>
                                <span class="empty">—</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Status Kerja</div>
                        <div class="info-value">
                            <?php if ($empSt && isset($empStatusMap[$empSt])): ?>
                                <span class="badge-employment <?= $empStatusMap[$empSt]['cls'] ?>">
                                    <span class="fas fa-file-contract me-1"></span>
                                    <?= esc((string)$empStatusMap[$empSt]['label']) ?>
                                </span>
                            <?php else: ?>
                                <span class="empty">—</span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- ── SECTION: Tanggal & Status (full width) ──────────── -->
        <div class="col-12">
            <div class="info-section">
                <div class="info-section-header">
                    <span class="sec-icon bg-success bg-opacity-10 text-success"><span class="fas fa-calendar-alt"></span></span>
                    <span class="sec-title">Tanggal &amp; Status</span>
                </div>
                <div class="info-section-body">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="info-row pe-md-4">
                                <div class="info-label">Tanggal Bergabung</div>
                                <div class="info-value">
                                    <?php if ($joinFormatted): ?>
                                        <span class="fas fa-calendar text-success me-1 fs-10"></span>
                                        <?= esc($joinFormatted) ?>
                                        <?php if (!empty($tenure)): ?>
                                            <span class="text-muted fs-10 ms-1">(<?= esc($tenure) ?>)</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="empty">—</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row px-md-4">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    <span class="badge-status <?= $isActive ? 'active' : 'inactive' ?>">
                                        <span class="fas <?= $isActive ? 'fa-check-circle' : 'fa-times-circle' ?>"></span>
                                        <?= $isActive ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row ps-md-4">
                                <div class="info-label">ID Karyawan</div>
                                <div class="info-value">
                                    <span class="font-monospace text-muted">#<?= esc((string) ($val('id') ?? '—')) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /row -->

    <!-- ── Action bar ─────────────────────────────────────────── -->
    <div class="action-bar">
        <a href="<?= site_url('hrm/employees') ?>" class="btn btn-subtle-secondary btn-sm">
            <span class="fas fa-arrow-left me-1"></span>Kembali
        </a>
        <div class="d-flex gap-2">
            <?php if ($isDel): ?>
                <button type="button" class="btn btn-subtle-danger btn-sm" id="btn-delete"
                    data-id="<?= esc((string) $val('id')) ?>"
                    data-name="<?= esc((string) $val('fullname')) ?>">
                    <span class="fas fa-trash me-1"></span>Hapus
                </button>
            <?php endif; ?>
            <?php if ($isEdit): ?>
                <a href="<?= site_url('hrm/employees/edit/' . $val('id')) ?>" class="btn btn-primary btn-sm">
                    <span class="fas fa-pencil-alt me-1"></span>Edit Karyawan
                </a>
            <?php endif; ?>
        </div>
    </div>

</div><!-- /detail-wrapper -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const EmpDetail = {
        BASE: '<?= base_url() ?>',

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

        async deleteEmployee(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Karyawan?',
                html: `<strong>${name}</strong> akan dipindahkan ke sampah.<br>
                       <small class="text-muted">Dapat dipulihkan dari menu Sampah.</small>`,
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
                const res = await this.post(
                    this.BASE + `hrm/employees/delete/${id}`,
                    new FormData()
                );
                if (res.status === 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top-right',
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1800,
                    });
                    setTimeout(() => {
                        window.location.href = this.BASE + 'hrm/employees';
                    }, 1800);
                } else {
                    this.toast('error', res.message ?? 'Gagal menghapus.');
                }
            } catch (e) {
                this.toast('error', e.message);
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

        init() {
            document.getElementById('btn-delete')
                ?.addEventListener('click', function() {
                    EmpDetail.deleteEmployee(this.dataset.id, this.dataset.name);
                });
        },
    };

    document.addEventListener('DOMContentLoaded', () => EmpDetail.init());
</script>
<?= $this->endSection() ?>