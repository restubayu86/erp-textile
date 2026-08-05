<?= $this->extend('templates/layout') ?>

<?= $this->section('styles') ?>
<style>
    .stat-card { border: none; border-radius: 1rem; }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0; }
    .info-label { font-size: .65rem; text-transform: uppercase; letter-spacing: .06em; color: var(--phoenix-secondary-color); margin-bottom: .2rem; }
    .info-value { font-weight: 700; font-size: 1.5rem; line-height: 1; }
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

    <?php if ($currentPeriod): ?>
        <div class="alert alert-subtle-primary d-flex align-items-center mb-4">
            <span class="fas fa-calendar-check me-2"></span>
            Periode berjalan: <strong class="ms-1"><?= esc((string)($currentPeriod['period_name'] ?? $currentPeriod['name'] ?? '-')) ?></strong>
        </div>
    <?php else: ?>
        <div class="alert alert-subtle-warning d-flex align-items-center mb-4">
            <span class="fas fa-exclamation-triangle me-2"></span>
            Belum ada periode produksi yang diaktifkan.
            <?php if (canDo('warehouse.periods.view')): ?>
                <a href="<?= site_url('warehouse/master/periods') ?>" class="ms-2">Atur periode →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-4 col-6">
            <a href="<?= site_url('warehouse/master/chemicals') ?>" class="text-decoration-none">
                <div class="card stat-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Bahan Kimia</div>
                            <div class="info-value text-primary"><?= (int) $totalChemicals ?></div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary"><span class="fas fa-vial"></span></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-6">
            <a href="<?= site_url('warehouse/formulations') ?>" class="text-decoration-none">
                <div class="card stat-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Formulasi</div>
                            <div class="info-value text-success"><?= (int) $totalFormulations ?></div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success"><span class="fas fa-flask"></span></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-6">
            <a href="<?= site_url('warehouse/master/warehouses') ?>" class="text-decoration-none">
                <div class="card stat-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="info-label">Gudang</div>
                            <div class="info-value text-info"><?= (int) $totalWarehouses ?></div>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info"><span class="fas fa-warehouse"></span></div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Akses Cepat</h5>
            <div class="d-flex flex-wrap gap-2">
                <?php if (canDo('warehouse.stock_opening.view')): ?>
                    <a href="<?= site_url('warehouse/stocks/opening') ?>" class="btn btn-subtle-primary btn-sm"><span class="fas fa-box-open me-1"></span>Stok Awal</a>
                <?php endif; ?>
                <?php if (canDo('warehouse.formulations.manage')): ?>
                    <a href="<?= site_url('warehouse/formulations/create') ?>" class="btn btn-subtle-success btn-sm"><span class="fas fa-plus me-1"></span>Buat Formulasi</a>
                <?php endif; ?>
                <?php if (canDo('warehouse.chemicals.create')): ?>
                    <a href="<?= site_url('warehouse/master/chemicals') ?>" class="btn btn-subtle-secondary btn-sm"><span class="fas fa-vial me-1"></span>Tambah Kimia</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
