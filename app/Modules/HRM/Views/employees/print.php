<?php
// Guard: hanya load helper jika tersedia
if (function_exists('helper') && is_file(APPPATH . 'Helpers/company_helper.php')) {
    helper('company');
    $company = getCompanyProfile();
} else {
    $company = [
        'name'    => 'PT. Textile Dyeing & Finishing',
        'address' => '',
        'phone'   => '',
        'email'   => '',
    ];
}

// Nama user aktif (Shield)
$currentUser = auth()->user()->username ?? (session()->get('username') ?? 'System');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Print Data Karyawan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            background: #f0f2f5;
        }

        /* ── Toolbar ─────────────────────────────────────────── */
        .no-print {
            background: #2c3e50;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
        }

        .btn {
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all .2s;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
            transform: translateY(-1px);
        }

        /* ── Container ───────────────────────────────────────── */
        .print-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, .1);
        }

        .print-content {
            padding: 30px;
        }

        /* ── Header ──────────────────────────────────────────── */
        .print-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .logo-left,
        .logo-right {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-left img,
        .logo-right img {
            max-width: 100%;
            max-height: 80px;
        }

        .company-info {
            flex: 1;
            text-align: center;
        }

        .company-name {
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            color: #2c3e50;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 11px;
            color: #7f8c8d;
            line-height: 1.4;
            margin-bottom: 3px;
        }

        .document-title h1 {
            font-size: 18px;
            margin: 10px 0 5px;
            text-transform: uppercase;
            color: #2c3e50;
            font-weight: 600;
        }

        .document-title h4 {
            font-size: 12px;
            margin: 1px 0 5px;
            text-transform: uppercase;
            color: #2c3e50;
            font-weight: 600;
        }

        .document-subtitle {
            font-size: 12px;
            color: #7f8c8d;
        }

        /* ── Filter Info ─────────────────────────────────────── */
        .filter-info {
            background: #ecf0f1;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 11px;
        }

        .filter-info table {
            width: 100%;
            border: none;
            margin-top: 0;
            box-shadow: none;
        }

        .filter-info td {
            padding: 4px 8px;
            color: #2c3e50;
            border: none;
        }

        /* ── Table ───────────────────────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11px;
        }

        th {
            background: #34495e;
            color: white;
            padding: 10px 8px;
            font-weight: 600;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        td {
            border: 1px solid #e0e0e0;
            padding: 8px;
            color: #2c3e50;
        }

        tr:nth-child(even) td {
            background: #fafafa;
        }

        .group-row td {
            background: #e8f4fd !important;
            font-weight: 600;
            color: #2980b9;
            border-left: 3px solid #2980b9;
        }

        .subtotal-row td {
            background: #fef9e7 !important;
            font-weight: 600;
            color: #e67e22;
        }

        /* ── Signature ───────────────────────────────────────── */
        .signature {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .signature-item {
            text-align: center;
            flex: 1;
        }

        .signature-title {
            font-size: 11px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }

        .signature-line {
            width: 150px;
            height: 1px;
            background: #2c3e50;
            margin: 50px auto 8px;
        }

        .signature-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 11px;
        }

        /* ── Footer ──────────────────────────────────────────── */
        .print-footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ecf0f1;
            font-size: 10px;
            color: #7f8c8d;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* ── Watermark ───────────────────────────────────────── */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: .03;
            font-size: 80px;
            font-weight: bold;
            white-space: nowrap;
            pointer-events: none;
            z-index: 999;
            color: #2c3e50;
        }

        /* ── Print Media ─────────────────────────────────────── */
        @media print {
            body {
                background: white;
            }

            .print-container {
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }

            .print-content {
                padding: 20px;
            }

            th {
                background: #f0f0f0 !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .group-row td,
            .subtotal-row td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: A4 portrait;
                margin: .5cm;
            }
        }
    </style>
</head>

<body>

    <!-- Toolbar -->
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print / Save as PDF</button>
        <button onclick="window.close()" class="btn btn-secondary">✕ Tutup</button>
    </div>

    <div class="print-container">
        <div class="print-content">

            <!-- Header -->
            <div class="print-header">
                <div class="logo-container">
                    <div class="logo-left">
                        <?php if (function_exists('getCompanyLogo')): ?>
                            <img src="<?= getCompanyLogo('left') ?>" alt="Logo" onerror="this.style.display='none'">
                        <?php endif; ?>
                    </div>
                    <div class="company-info">
                        <div class="company-name"><?= esc((string) $company['name']) ?></div>
                        <?php if (!empty($company['address'])): ?>
                            <div class="company-address"><?= nl2br(esc((string) $company['address'])) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="logo-right"></div>
                </div>
                <div class="document-title">
                    <h1>Laporan Data Karyawan</h1>
                    <h4>Divisi Dyeing &amp; Finishing</h4>
                    <div class="document-subtitle">Periode: <?= date('F Y') ?></div>
                </div>
            </div>

            <!-- Filter Info -->
            <div class="filter-info">
                <table>
                    <tr>
                        <td width="20%"><strong>📅 Dicetak:</strong></td>
                        <td width="30%"><?= esc((string) $print_date) ?></td>
                        <td width="20%"><strong>👤 User:</strong></td>
                        <td width="30%"><?= esc((string) $currentUser) ?></td>
                    </tr>
                    <?php if (!empty($filters['filter_department'])): ?>
                        <tr>
                            <td><strong>🏢 Departemen:</strong></td>
                            <td colspan="3"><?= esc((string) $filters['filter_department']) ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($filters['filter_position'])): ?>
                        <tr>
                            <td><strong>💼 Posisi:</strong></td>
                            <td colspan="3"><?= esc((string) $filters['filter_position']) ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($filters['filter_shift'])): ?>
                        <tr>
                            <td><strong>🕐 Shift:</strong></td>
                            <td colspan="3"><?= esc((string) $filters['filter_shift']) ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($groupBy && $groupBy !== 'none'): ?>
                        <tr>
                            <td><strong>📊 Group by:</strong></td>
                            <td colspan="3"><?= ucfirst(esc((string) $groupBy)) ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>📋 Total:</strong></td>
                        <td colspan="3"><strong><?= count($employees) ?> Karyawan</strong></td>
                    </tr>
                </table>
            </div>

            <!-- Tabel -->
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>JK</th>
                        <th>Posisi</th>
                        <th>Departemen</th>
                        <th>Shift</th>
                        <th>Status Kerja</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no        = 1;
                    $lastGroup = null;
                    $groupCounts = [];

                    foreach ($employees as $emp):
                        // Tentukan nilai group saat ini
                        $currentGroup = '';
                        if ($groupBy && $groupBy !== 'none') {
                            $currentGroup = match ($groupBy) {
                                'position'   => $emp['position_name']   ?? 'Tidak Ada',
                                'department' => $emp['department_name'] ?? 'Tidak Ada',
                                'shift'      => $emp['shift']            ?? 'Tidak Ada',
                                'gender'     => ($emp['gender'] ?? '') === 'L' ? 'Laki-Laki' : 'Perempuan',
                                default      => '',
                            };
                        }

                        // Ganti group: tampilkan subtotal group sebelumnya + header group baru
                        if ($currentGroup !== '' && $lastGroup !== $currentGroup):
                            if ($lastGroup !== null): ?>
                                <tr class="subtotal-row">
                                    <td colspan="9">
                                        📊 Subtotal <strong><?= esc((string) $lastGroup) ?></strong>:
                                        <?= $groupCounts[$lastGroup] ?? 0 ?> Karyawan
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr class="group-row">
                                <td colspan="9">📁 <?= esc((string) $currentGroup) ?></td>
                            </tr>
                        <?php
                            $lastGroup = $currentGroup;
                            $no = 1; // reset nomor urut per group
                            $groupCounts[$currentGroup] = 0;
                        endif;

                        // Hitung anggota group
                        if ($currentGroup !== '') {
                            $groupCounts[$currentGroup] = ($groupCounts[$currentGroup] ?? 0) + 1;
                        }
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc((string) ($emp['nik'] ?? '')) ?></td>
                            <td><?= esc((string) ($emp['fullname'] ?? '')) ?></td>
                            <td><?= ($emp['gender'] ?? '') === 'L' ? 'Laki-Laki' : 'Perempuan' ?></td>
                            <td><?= esc((string) ($emp['position_name']     ?? '-')) ?></td>
                            <td><?= esc((string) ($emp['department_name']   ?? '-')) ?></td>
                            <td><?= esc((string) ($emp['shift']             ?? '-')) ?></td>
                            <td><?= ucfirst(esc((string) ($emp['employment_status'] ?? '-'))) ?></td>
                            <td><?= ucfirst(esc((string) ($emp['status']            ?? '-'))) ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if ($lastGroup !== null && $groupBy && $groupBy !== 'none'): ?>
                        <tr class="subtotal-row">
                            <td colspan="9">
                                📊 Subtotal <strong><?= esc((string) $lastGroup) ?></strong>:
                                <?= $groupCounts[$lastGroup] ?? 0 ?> Karyawan
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr style="background:#34495e">
                        <td colspan="8" style="text-align:right;color:white;font-weight:bold">Total Keseluruhan:</td>
                        <td style="color:white;font-weight:bold"><?= count($employees) ?> Karyawan</td>
                    </tr>
                </tfoot>
            </table>

            <!-- Tanda Tangan -->
            <div class="signature">
                <div class="signature-item">
                    <div class="signature-title">Mengetahui,</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">DIV Manager</div>
                </div>
                <div class="signature-item">
                    <div class="signature-title">Petugas,</div>
                    <div class="signature-line"></div>
                    <div class="signature-name"><?= esc((string) $currentUser) ?></div>
                </div>
            </div>

            <!-- Footer -->
            <div class="print-footer">
                <div class="footer-content">
                    <span>📄 Dicetak oleh: <?= esc((string) $currentUser) ?></span>
                    <span>📅 <?= date('d-m-Y H:i:s') ?></span>
                    <span>🔒 Dokumen Rahasia</span>
                </div>
            </div>

        </div><!-- /.print-content -->
    </div><!-- /.print-container -->

    <div class="watermark">ERP TEXTILE</div>

</body>

</html>