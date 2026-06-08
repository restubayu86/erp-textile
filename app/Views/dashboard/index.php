<?= $this->extend('templates/layout') ?>

<?= $this->section('content') ?>

<?php if (session('login_success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                theme: 'bootstrap-5',
                icon: 'success',
                title: 'Login Berhasil',
                html: 'Selamat datang, <b><?= esc((string)session('user_name')) ?></b>!',
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
<?php endif ?>

<div class="pb-5">

    <!-- ── Page Header ── -->
    <div class="mb-8">
        <h2 class="mb-2">Dashboard</h2>
        <h5 class="text-body-tertiary fw-semibold">
            Ringkasan aktivitas produksi dan gudang hari ini
        </h5>
    </div>

    <!-- ── Stat Ringkas (Baris Atas) ── -->
    <div class="row align-items-center g-4 mb-6">

        <?php if (canDo('production.work_orders.view')): ?>
            <div class="col-12 col-md-auto">
                <div class="d-flex align-items-center">
                    <span class="fa-stack" style="min-height:46px;min-width:46px;">
                        <span class="fa-solid fa-square fa-stack-2x dark__text-opacity-50 text-primary-light"
                            data-fa-transform="down-4 rotate--10 left-4"></span>
                        <span class="fa-solid fa-circle fa-stack-2x stack-circle text-stats-circle-primary"
                            data-fa-transform="up-4 right-3 grow-2"></span>
                        <span class="fa-stack-1x fa-solid fa-industry text-primary"
                            data-fa-transform="shrink-2 up-8 right-6"></span>
                    </span>
                    <div class="ms-3">
                        <h4 class="mb-0"><?= $totalWoActive ?? 0 ?> Work Order</h4>
                        <p class="text-body-secondary fs-9 mb-0">Sedang berjalan</p>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php if (canDo('production.work_orders.view')): ?>
            <div class="col-12 col-md-auto">
                <div class="d-flex align-items-center">
                    <span class="fa-stack" style="min-height:46px;min-width:46px;">
                        <span class="fa-solid fa-square fa-stack-2x dark__text-opacity-50 text-warning-light"
                            data-fa-transform="down-4 rotate--10 left-4"></span>
                        <span class="fa-solid fa-circle fa-stack-2x stack-circle text-stats-circle-warning"
                            data-fa-transform="up-4 right-3 grow-2"></span>
                        <span class="fa-stack-1x fa-solid fa-pause text-warning"
                            data-fa-transform="shrink-2 up-8 right-6"></span>
                    </span>
                    <div class="ms-3">
                        <h4 class="mb-0"><?= $totalWoDraft ?? 0 ?> Work Order</h4>
                        <p class="text-body-secondary fs-9 mb-0">Menunggu proses</p>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php if (canDo('warehouse.chemicals.view')): ?>
            <div class="col-12 col-md-auto">
                <div class="d-flex align-items-center">
                    <span class="fa-stack" style="min-height:46px;min-width:46px;">
                        <span class="fa-solid fa-square fa-stack-2x dark__text-opacity-50 text-danger-light"
                            data-fa-transform="down-4 rotate--10 left-4"></span>
                        <span class="fa-solid fa-circle fa-stack-2x stack-circle text-stats-circle-danger"
                            data-fa-transform="up-4 right-3 grow-2"></span>
                        <span class="fa-stack-1x fa-solid fa-triangle-exclamation text-danger"
                            data-fa-transform="shrink-2 up-8 right-6"></span>
                    </span>
                    <div class="ms-3">
                        <h4 class="mb-0"><?= $totalLowStock ?? 0 ?> Bahan</h4>
                        <p class="text-body-secondary fs-9 mb-0">Stok hampir habis</p>
                    </div>
                </div>
            </div>
        <?php endif ?>

    </div>

    <hr class="bg-body-secondary mb-6 mt-2" />

    <!-- ── Baris Utama ── -->
    <div class="row g-4">

        <!-- Kolom Kiri: Chart WO per Bulan -->
        <div class="col-12 col-xxl-6">

            <div class="row flex-between-center mb-4 g-3">
                <div class="col-auto">
                    <h3>Work Order per Bulan</h3>
                    <p class="text-body-tertiary lh-sm mb-0">
                        Total lot kain yang diproses per bulan
                    </p>
                </div>
                <div class="col-8 col-sm-4">
                    <select class="form-select form-select-sm" id="select-wo-year">
                        <option value="<?= date('Y') ?>"><?= date('Y') ?></option>
                        <option value="<?= date('Y') - 1 ?>"><?= date('Y') - 1 ?></option>
                    </select>
                </div>
            </div>

            <div id="chart-wo-monthly" style="min-height:320px;width:100%;"></div>

        </div>

        <!-- Kolom Kanan: 4 Stat Cards -->
        <div class="col-12 col-xxl-6">
            <div class="row g-3">

                <!-- Card: Work Order Selesai -->
                <?php if (canDo('production.work_orders.view')): ?>
                    <div class="col-12 col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-1">
                                            WO Selesai
                                            <span class="badge badge-phoenix badge-phoenix-success rounded-pill fs-9 ms-2">
                                                <span class="badge-label">Bulan ini</span>
                                            </span>
                                        </h5>
                                        <h6 class="text-body-tertiary">7 hari terakhir</h6>
                                    </div>
                                    <h4><?= $woCompleted7d ?? 0 ?></h4>
                                </div>
                                <div class="d-flex justify-content-center px-4 py-4">
                                    <div id="chart-wo-status" style="height:85px;width:115px;"></div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bullet-item bg-success me-2"></div>
                                        <h6 class="text-body fw-semibold flex-1 mb-0">Completed</h6>
                                        <h6 class="text-body fw-semibold mb-0"><?= $woCompletedPct ?? 0 ?>%</h6>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="bullet-item bg-primary-subtle me-2"></div>
                                        <h6 class="text-body fw-semibold flex-1 mb-0">In Progress</h6>
                                        <h6 class="text-body fw-semibold mb-0"><?= $woActivePct ?? 0 ?>%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

                <!-- Card: Karyawan Aktif -->
                <?php if (canDo('hrm.employees.view')): ?>
                    <div class="col-12 col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-1">Karyawan Aktif</h5>
                                        <h6 class="text-body-tertiary">Total terdaftar</h6>
                                    </div>
                                    <h4><?= $totalEmployees ?? 0 ?></h4>
                                </div>
                                <div class="pb-0 pt-4">
                                    <div id="chart-employees-dept" style="height:180px;width:100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

                <!-- Card: Stok Bahan Kimia -->
                <?php if (canDo('warehouse.chemicals.view')): ?>
                    <div class="col-12 col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-2">Stok Bahan Kimia</h5>
                                        <h6 class="text-body-tertiary">Kategori teratas</h6>
                                    </div>
                                </div>
                                <div class="pb-4 pt-3">
                                    <div id="chart-chemicals-category" style="height:115px;width:100%;"></div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bullet-item bg-primary me-2"></div>
                                        <h6 class="text-body fw-semibold flex-1 mb-0">Pewarna</h6>
                                        <h6 class="text-body fw-semibold mb-0" id="pct-dye">—</h6>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bullet-item bg-primary-lighter me-2"></div>
                                        <h6 class="text-body fw-semibold flex-1 mb-0">Kimia Pembantu</h6>
                                        <h6 class="text-body fw-semibold mb-0" id="pct-auxiliary">—</h6>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="bullet-item bg-info-dark me-2"></div>
                                        <h6 class="text-body fw-semibold flex-1 mb-0">Finishing Agent</h6>
                                        <h6 class="text-body fw-semibold mb-0" id="pct-finishing">—</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

                <!-- Card: Status Mesin -->
                <?php if (canDo('production.machines.view')): ?>
                    <div class="col-12 col-md-6">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-2">Status Mesin</h5>
                                        <h6 class="text-body-tertiary">Aktif vs Idle</h6>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center pt-3 flex-1">
                                    <div id="chart-machine-status" style="height:100%;width:100%;"></div>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bullet-item bg-primary me-2"></div>
                                        <h6 class="text-body fw-semibold flex-1 mb-0">Aktif / Running</h6>
                                        <h6 class="text-body fw-semibold mb-0"><?= $machineActivePct ?? 0 ?>%</h6>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="bullet-item bg-primary-subtle me-2"></div>
                                        <h6 class="text-body fw-semibold flex-1 mb-0">Idle / Maintenance</h6>
                                        <h6 class="text-body fw-semibold mb-0"><?= $machineIdlePct ?? 0 ?>%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

            </div>
        </div>

    </div>

    <!-- ── Tabel WO Terbaru ── -->
    <?php if (canDo('production.work_orders.view')): ?>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Work Order Terbaru</h5>
                        <a href="<?= base_url('production/work-orders') ?>" class="btn btn-sm btn-phoenix-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0 fs-9">
                                <thead class="bg-body-tertiary">
                                    <tr>
                                        <th class="ps-3">No. WO</th>
                                        <th>Artikel</th>
                                        <th>Lot (kg)</th>
                                        <th>Warna Target</th>
                                        <th>Mesin</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recentWorkOrders)): ?>
                                        <?php foreach ($recentWorkOrders as $wo): ?>
                                            <tr>
                                                <td class="ps-3 fw-semibold"><?= esc((string)$wo['wo_number']) ?></td>
                                                <td><?= esc((string)$wo['article']) ?></td>
                                                <td><?= number_format($wo['qty_kg'], 1) ?></td>
                                                <td><?= esc((string)$wo['color_target']) ?></td>
                                                <td><?= esc((string)$wo['machine_name'] ?? '—') ?></td>
                                                <td>
                                                    <?php
                                                    $statusMap = [
                                                        'draft'     => ['warning', 'Draft'],
                                                        'active'    => ['primary', 'Berjalan'],
                                                        'completed' => ['success', 'Selesai'],
                                                        'cancelled' => ['danger',  'Dibatalkan'],
                                                    ];
                                                    $s = $statusMap[$wo['status']] ?? ['secondary', $wo['status']];
                                                    ?>
                                                    <span class="badge badge-phoenix badge-phoenix-<?= $s[0] ?>">
                                                        <?= $s[1] ?>
                                                    </span>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($wo['created_at'])) ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-body-tertiary py-4">
                                                Belum ada Work Order
                                            </td>
                                        </tr>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        const textColor = isDark ? '#9fa6bc' : '#525b75';
        const gridColor = isDark ? '#3e465b' : '#e3e6ed';
        const primaryColor = '#2c7be5';
        const successColor = '#00d27a';

        // ── 1. Chart: Work Order per Bulan (Bar) ─────────────────────────
        const woMonthlyEl = document.getElementById('chart-wo-monthly');
        if (woMonthlyEl) {
            const woMonthlyData = <?= json_encode($woMonthlyData ?? array_fill(0, 12, 0)) ?>;
            new ApexCharts(woMonthlyEl, {
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'Nunito Sans, sans-serif',
                },
                series: [{
                    name: 'Work Order',
                    data: woMonthlyData
                }],
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    labels: {
                        style: {
                            colors: textColor
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: textColor
                        }
                    }
                },
                grid: {
                    borderColor: gridColor,
                    strokeDashArray: 4
                },
                colors: [primaryColor],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                },
            }).render();
        }

        // ── 2. Chart: Status WO (Donut kecil) ────────────────────────────
        const woStatusEl = document.getElementById('chart-wo-status');
        if (woStatusEl) {
            const completed = <?= (int)($woCompletedPct ?? 52) ?>;
            const active = <?= (int)($woActivePct    ?? 48) ?>;
            new ApexCharts(woStatusEl, {
                chart: {
                    type: 'donut',
                    height: 85,
                    sparkline: {
                        enabled: true
                    }
                },
                series: [completed, active],
                labels: ['Completed', 'In Progress'],
                colors: [successColor, primaryColor + '55'],
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                },
            }).render();
        }

        // ── 3. Chart: Karyawan per Departemen (Bar horizontal) ───────────
        const deptEl = document.getElementById('chart-employees-dept');
        if (deptEl) {
            const deptLabels = <?= json_encode(!empty($employeesByDept) ? array_column($employeesByDept, 'name')  : ['Dyeing', 'Finishing', 'QC', 'Gudang', 'HRD']) ?>;
            const deptValues = <?= json_encode(!empty($employeesByDept) ? array_column($employeesByDept, 'total') : [0, 0, 0, 0, 0]) ?>;
            new ApexCharts(deptEl, {
                chart: {
                    type: 'bar',
                    height: 180,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'Nunito Sans, sans-serif',
                },
                series: [{
                    name: 'Karyawan',
                    data: deptValues
                }],
                xaxis: {
                    categories: deptLabels,
                    labels: {
                        style: {
                            colors: textColor,
                            fontSize: '11px'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                },
                yaxis: {
                    labels: {
                        show: false
                    }
                },
                grid: {
                    show: false
                },
                colors: [primaryColor],
                plotOptions: {
                    bar: {
                        borderRadius: 3,
                        horizontal: true,
                        barHeight: '60%'
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '11px'
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                },
            }).render();
        }

        // ── 4. Chart: Kategori Bahan Kimia (Donut) ───────────────────────
        const chemEl = document.getElementById('chart-chemicals-category');
        if (chemEl) {
            const chemData = <?= json_encode($chemicalCategoryData   ?? [0, 0, 0]) ?>;
            const chemLabels = <?= json_encode($chemicalCategoryLabels ?? ['Pewarna', 'Kimia Pembantu', 'Finishing Agent']) ?>;
            new ApexCharts(chemEl, {
                chart: {
                    type: 'donut',
                    height: 115,
                    sparkline: {
                        enabled: true
                    }
                },
                series: chemData,
                labels: chemLabels,
                colors: [primaryColor, primaryColor + '88', '#0097a7'],
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                },
            }).render();

            const total = chemData.reduce((a, b) => a + b, 0);
            if (total > 0) {
                document.getElementById('pct-dye').textContent = Math.round(chemData[0] / total * 100) + '%';
                document.getElementById('pct-auxiliary').textContent = Math.round(chemData[1] / total * 100) + '%';
                document.getElementById('pct-finishing').textContent = Math.round(chemData[2] / total * 100) + '%';
            }
        }

        // ── 5. Chart: Status Mesin (Donut) ───────────────────────────────
        const machineEl = document.getElementById('chart-machine-status');
        if (machineEl) {
            const mActive = <?= (int)($machineActivePct ?? 0) ?>;
            const mIdle = <?= (int)($machineIdlePct   ?? 0) ?>;
            new ApexCharts(machineEl, {
                chart: {
                    type: 'donut',
                    height: 200,
                    fontFamily: 'Nunito Sans, sans-serif',
                },
                series: [mActive, mIdle],
                labels: ['Aktif / Running', 'Idle / Maintenance'],
                colors: [primaryColor, primaryColor + '33'],
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Aktif',
                                    color: textColor,
                                    formatter: () => mActive + '%',
                                },
                            }
                        }
                    },
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                },
            }).render();
        }

    });
</script>
<?= $this->endSection() ?>