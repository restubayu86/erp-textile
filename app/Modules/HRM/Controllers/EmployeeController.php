<?php

namespace App\Modules\HRM\Controllers;

use App\Controllers\BaseController;
use App\Modules\HRM\Models\EmployeeModel;
use App\Modules\HRM\Models\PositionModel;
use App\Modules\HRM\Models\DepartmentModel;
use Hermawan\DataTables\DataTable;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class EmployeeController extends BaseController
{
    protected EmployeeModel   $model;
    protected PositionModel   $positionModel;
    protected DepartmentModel $departmentModel;

    public function __construct()
    {
        $this->model           = new EmployeeModel();
        $this->positionModel   = new PositionModel();
        $this->departmentModel = new DepartmentModel();
    }

    // ============================================================
    // PAGES
    // ============================================================

    public function index(): ResponseInterface|string
    {
        if (!canDo('hrm.employees.view')) return $this->forbidden();

        return view('App\Modules\HRM\Views\employees\index', [
            'title'            => 'Karyawan',
            'page_title'       => 'Daftar Karyawan',
            'page_description' => 'Kelola data karyawan perusahaan',
            'breadcrumbs'      => $this->breadcrumbs(),
            'departments'      => $this->departmentModel->getAllActive(),
            'positions'        => $this->positionModel->getAllActive(),
            'work_areas'       => $this->model->getDistinctWorkAreas(),
            'shifts'           => ['NS', 'A', 'B', 'C', 'D', 'E'],
        ]);
    }

    public function trash(): ResponseInterface|string
    {
        if (!canDo('hrm.employees.delete')) return $this->forbidden();

        return view('App\Modules\HRM\Views\employees\trash', [
            'title'            => 'Sampah — Karyawan',
            'page_title'       => 'Sampah Karyawan',
            'page_description' => 'Karyawan yang telah dihapus',
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Sampah', 'active' => true]]),
        ]);
    }

    public function create(): ResponseInterface|string
    {
        if (!canDo('hrm.employees.create')) return $this->forbidden();

        return view('App\Modules\HRM\Views\employees\form', [
            'title'            => 'Tambah Karyawan',
            'page_title'       => 'Tambah Karyawan',
            'page_description' => 'Tambah data karyawan baru',
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Tambah', 'active' => true]]),
            'employee'         => null,
            'departments'      => $this->departmentModel->getAllActive(),
        ]);
    }

    public function edit(int $id): ResponseInterface|string
    {
        if (!canDo('hrm.employees.edit')) return $this->forbidden();

        $result = $this->model->getData($id);
        if ($result['status'] !== 'success') {
            return redirect()->to(site_url('hrm/employees'))->with('error', 'Data tidak ditemukan');
        }

        $employee = $result['data'];

        // Resolve department_name, department_id, position_name via JOIN ke positions + departments
        if (!empty($employee['position_id'])) {
            $db  = \Config\Database::connect();
            $row = $db->table('positions p')
                ->select([
                    'p.position_name',
                    'd.id   AS department_id',
                    'd.department AS department_name',
                ])
                ->join('departments d', 'd.id = p.department_id', 'left')
                ->where('p.id', (int) $employee['position_id'])
                ->get()
                ->getRowArray();

            $employee['position_name']   = $row['position_name']   ?? '';
            $employee['department_id']   = $row['department_id']   ?? null;
            $employee['department_name'] = $row['department_name'] ?? '';
        } else {
            $employee['position_name']   = '';
            $employee['department_id']   = null;
            $employee['department_name'] = '';
        }

        return view('App\Modules\HRM\Views\employees\form', [
            'title'            => 'Edit Karyawan',
            'page_title'       => 'Edit Karyawan',
            'page_description' => 'Perbarui data karyawan',
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Edit', 'active' => true]]),
            'employee'         => $employee,
            'departments'      => $this->departmentModel->getAllActive(),
        ]);
    }

    public function show(int $id): ResponseInterface|string
    {
        if (!canDo('hrm.employees.view')) return $this->forbidden();

        $result = $this->model->getData($id);
        if ($result['status'] !== 'success') {
            return redirect()->to(site_url('hrm/employees'))->with('error', 'Data tidak ditemukan');
        }

        return view('App\Modules\HRM\Views\employees\show', [
            'title'            => 'Detail Karyawan',
            'page_title'       => 'Detail Karyawan',
            'page_description' => $result['data']['fullname'],
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Detail', 'active' => true]]),
            'employee'         => $result['data'],
        ]);
    }

    public function print(): ResponseInterface|string
    {
        if (!canDo('hrm.employees.view')) return $this->forbidden();

        $data    = $this->getExportData();
        $groupBy = $this->request->getGet('group_by') ?? '';

        return view('App\Modules\HRM\Views\employees\print', [
            'title'      => 'Print Karyawan',
            'employees'  => $data,
            'groupBy'    => $groupBy,
            'filters'    => $this->request->getGet(),
            'print_date' => date('d-m-Y H:i:s'),
        ]);
    }

    // ============================================================
    // DATATABLE ENDPOINTS
    // ============================================================

    public function datatables()
    {
        if (!canDo('hrm.employees.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $groupBy = $this->request->getGet('group_by') ?? '';

        $builder = $db->table('employees')
            ->select([
                'employees.id',
                'employees.nik',
                'employees.fullname',
                'employees.nickname',
                'employees.gender',
                'employees.phone',
                'employees.photo',
                'employees.work_area',
                'employees.shift',
                'employees.employment_status',
                'employees.status',
                'employees.join_date',
                'employees.created_at',
                'employees.updated_at',
                'employees.position_id',
                'positions.position_name',
                'positions.position_level',
                'positions.position_code',
                "COALESCE(departments.department, '—') as department_name",
                'cu.username as created_by_name',
                'cu_emp.nickname as created_by_employee',
                'uu.username as updated_by_name',
                'uu_emp.nickname as updated_by_employee',
            ])
            ->join('positions',    'positions.id = employees.position_id',         'left')
            ->join('departments',  'departments.id = positions.department_id',      'left')
            ->join('users cu',     'cu.id = employees.created_by',                  'left')
            ->join('employees cu_emp', 'cu_emp.id = cu.employee_id', 'left')
            ->join('users uu',     'uu.id = employees.updated_by',                  'left')
            ->join('employees uu_emp', 'uu_emp.id = uu.employee_id', 'left')
            ->where('employees.deleted_at', null);

        $this->applyDatatableFilters($builder);

        $orderMap = [
            'position'   => 'positions.position_level',
            'department' => 'departments.department',
            'shift'      => 'employees.shift',
            'gender'     => 'employees.gender',
        ];
        if ($groupBy && isset($orderMap[$groupBy])) {
            $builder->orderBy($orderMap[$groupBy], 'ASC');
        }
        $builder->orderBy('positions.position_level', 'ASC');
        $builder->orderBy('employees.fullname', 'ASC');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'employees.fullname',
                'employees.nik',
                'employees.nickname',
                'employees.phone',
                'positions.position_name',
                'departments.department',
            ])
            ->toJson(true);
    }

    public function trashDatatables()
    {
        if (!canDo('hrm.employees.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('employees')
            ->select([
                'employees.id',
                'employees.nik',
                'employees.fullname',
                'employees.nickname',
                'employees.gender',
                'employees.shift',
                'employees.employment_status',
                'employees.deleted_at',
                'positions.position_name',
                'departments.department as department_name',
                'cu.username as created_by_name',
                'cu_emp.nickname as created_by_employee',
                'du.username as deleted_by_name',
                'du_emp.nickname as deleted_by_employee',
            ])
            ->join('positions',   'positions.id = employees.position_id',     'left')
            ->join('departments', 'departments.id = positions.department_id',  'left')
            ->join('users cu',    'cu.id = employees.created_by',              'left')
            ->join('employees cu_emp', 'cu_emp.id = cu.employee_id', 'left')
            ->join('users du',    'du.id = employees.deleted_by',              'left')
            ->join('employees du_emp', 'du_emp.id = du.employee_id', 'left')
            ->where('employees.deleted_at IS NOT NULL');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['employees.fullname', 'employees.nik'])
            ->toJson(true);
    }

    private function applyDatatableFilters($builder): void
    {
        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('employees.fullname', $name)
                ->orLike('employees.nik', $name)
                ->orLike('employees.nickname', $name)
                ->groupEnd();
        }

        if ($department = trim($this->request->getGet('filter_department') ?? '')) {
            $builder->where('departments.id', $department);
        }

        if ($position = trim($this->request->getGet('filter_position') ?? '')) {
            $builder->where('employees.position_id', $position);
        }

        if ($shift = trim($this->request->getGet('filter_shift') ?? '')) {
            $builder->where('employees.shift', $shift);
        }

        if ($workArea = trim($this->request->getGet('filter_work_area') ?? '')) {
            $builder->where('employees.work_area', $workArea);
        }

        if ($empStatus = trim($this->request->getGet('filter_employment_status') ?? '')) {
            $builder->where('employees.employment_status', $empStatus);
        }

        if ($status = trim($this->request->getGet('filter_status') ?? '')) {
            $builder->where('employees.status', $status);
        }
    }

    // ============================================================
    // EXPORT
    // ============================================================

    public function export()
    {
        if (!canDo('hrm.employees.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $format = $this->request->getGet('format');
        $data   = $this->getExportData();

        if ($format === 'excel') return $this->exportExcel($data);
        if ($format === 'pdf')   return $this->exportPdf($data);

        return $this->jsonError('Format tidak didukung', 400);
    }

    private function getExportData(): array
    {
        $db      = \Config\Database::connect();
        $groupBy = $this->request->getGet('group_by') ?? '';

        $builder = $db->table('employees')
            ->select([
                'employees.nik',
                'employees.fullname',
                'employees.nickname',
                'employees.gender',
                'employees.phone',
                'employees.work_area',
                'employees.shift',
                'employees.employment_status',
                'employees.status',
                'employees.join_date',
                'positions.position_name',
                'positions.position_level',
                "COALESCE(departments.department, '—') as department_name",
            ])
            ->join('positions',   'positions.id = employees.position_id',    'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->where('employees.deleted_at', null);

        $this->applyDatatableFilters($builder);

        $orderMap = [
            'position'   => 'positions.position_name',
            'department' => 'departments.department',
            'shift'      => 'employees.shift',
            'gender'     => 'employees.gender',
        ];
        if ($groupBy && isset($orderMap[$groupBy])) {
            $builder->orderBy($orderMap[$groupBy], 'ASC');
        }
        $builder->orderBy('positions.position_level', 'ASC');
        $builder->orderBy('employees.fullname', 'ASC');

        return $builder->get()->getResultArray();
    }

    private function exportExcel(array $data): ResponseInterface
    {
        $groupBy     = $this->request->getGet('group_by') ?? '';
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Karyawan');

        $headers = [
            'No',
            'NIK',
            'Nama Lengkap',
            'Nama Panggilan',
            'JK',
            'Telepon',
            'Area Kerja',
            'Shift',
            'Posisi',
            'Departemen',
            'Status Kerja',
            'Status',
            'Tgl Bergabung',
        ];

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        $row       = 2;
        $no        = 1;
        $lastGroup = null;

        foreach ($data as $item) {
            if ($groupBy && $groupBy !== 'none') {
                $groupValue = match ($groupBy) {
                    'department' => $item['department_name'] ?? '—',
                    'position'   => $item['position_name']   ?? '—',
                    'shift'      => $item['shift']            ?? '—',
                    'gender'     => $item['gender'] === 'L' ? 'Laki-laki' : 'Perempuan',
                    default      => '',
                };
                if ($lastGroup !== $groupValue) {
                    $lastGroup = $groupValue;
                    $sheet->setCellValue('A' . $row, '=== ' . strtoupper($groupValue) . ' ===');
                    $sheet->mergeCells("A{$row}:M{$row}");
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$row}")->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFE2E8F0');
                    $row++;
                    $no = 1;
                }
            }

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['nik']               ?? '');
            $sheet->setCellValue('C' . $row, $item['fullname']          ?? '');
            $sheet->setCellValue('D' . $row, $item['nickname']          ?? '');
            $sheet->setCellValue('E' . $row, ($item['gender'] ?? '') === 'L' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValue('F' . $row, $item['phone']             ?? '');
            $sheet->setCellValue('G' . $row, $item['work_area']         ?? '');
            $sheet->setCellValue('H' . $row, $item['shift']             ?? '');
            $sheet->setCellValue('I' . $row, $item['position_name']     ?? '');
            $sheet->setCellValue('J' . $row, $item['department_name']   ?? '');
            $sheet->setCellValue('K' . $row, $item['employment_status'] ?? '');
            $sheet->setCellValue('L' . $row, $item['status']            ?? '');
            $sheet->setCellValue('M' . $row, $item['join_date']         ?? '');
            $row++;
        }

        $dir = WRITEPATH . 'exports/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $filename = 'employees_' . date('Ymd_His') . '.xlsx';
        $filepath = $dir . $filename;

        (new Xlsx($spreadsheet))->save($filepath);

        return $this->response->download($filepath, null)->setFileName($filename);
    }

    private function exportPdf(array $data): ResponseInterface
    {
        helper('company');
        $company = getCompanyProfile();

        $groupBy     = $this->request->getGet('group_by') ?? 'none';
        $printDate   = date('d F Y, H:i:s');
        $filters     = $this->request->getGet();
        $currentUser = auth()->user()->username ?? (session()->get('username') ?? 'System');

        // Logo — encode base64 (dompdf tidak support path lokal)
        $logoPath = FCPATH . 'assets/img/logo-left.png';
        $logoTag  = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoTag  = '<img src="data:image/png;base64,' . $logoData . '" alt="Logo" style="max-width:70px;max-height:70px;">';
        }

        // Build rows
        $no          = 1;
        $lastGroup   = null;
        $groupCounts = [];
        $rows        = '';

        foreach ($data as $emp) {
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

            if ($currentGroup !== '' && $lastGroup !== $currentGroup) {
                if ($lastGroup !== null) {
                    $rows .= '<tr class="subtotal-row">
                        <td colspan="9">Subtotal <strong>' . esc((string) $lastGroup) . '</strong>: '
                        . ($groupCounts[$lastGroup] ?? 0) . ' Karyawan</td>
                    </tr>';
                }
                $rows .= '<tr class="group-row">
                    <td colspan="9">' . esc((string) $currentGroup) . '</td>
                </tr>';
                $lastGroup                  = $currentGroup;
                $groupCounts[$currentGroup] = 0;
                $no                         = 1;
            }

            if ($currentGroup !== '') {
                $groupCounts[$currentGroup] = ($groupCounts[$currentGroup] ?? 0) + 1;
            }

            $gender = ($emp['gender'] ?? '') === 'L' ? 'Laki-Laki' : 'Perempuan';
            $rows  .= '<tr>
                <td>' . $no++ . '</td>
                <td>' . esc((string) ($emp['nik']               ?? '')) . '</td>
                <td>' . esc((string) ($emp['fullname']          ?? '')) . '</td>
                <td>' . $gender . '</td>
                <td>' . esc((string) ($emp['position_name']     ?? '-')) . '</td>
                <td>' . esc((string) ($emp['department_name']   ?? '-')) . '</td>
                <td>' . esc((string) ($emp['shift']             ?? '-')) . '</td>
                <td>' . ucfirst(esc((string) ($emp['employment_status'] ?? '-'))) . '</td>
                <td>' . ucfirst(esc((string) ($emp['status']            ?? '-'))) . '</td>
            </tr>';
        }

        if ($lastGroup !== null && $groupBy && $groupBy !== 'none') {
            $rows .= '<tr class="subtotal-row">
                <td colspan="9">Subtotal <strong>' . esc((string) $lastGroup) . '</strong>: '
                . ($groupCounts[$lastGroup] ?? 0) . ' Karyawan</td>
            </tr>';
        }

        // Filter info
        $filterRows  = '<tr>';
        $filterRows .= '<td width="15%"><strong>Dicetak:</strong></td>';
        $filterRows .= '<td width="35%">' . $printDate . '</td>';
        $filterRows .= '<td width="15%"><strong>User:</strong></td>';
        $filterRows .= '<td width="35%">' . esc((string) $currentUser) . '</td>';
        $filterRows .= '</tr>';

        if (!empty($filters['filter_department'])) {
            $filterRows .= '<tr><td><strong>Departemen:</strong></td><td colspan="3">' . esc((string) $filters['filter_department']) . '</td></tr>';
        }
        if (!empty($filters['filter_position'])) {
            $filterRows .= '<tr><td><strong>Posisi:</strong></td><td colspan="3">' . esc((string) $filters['filter_position']) . '</td></tr>';
        }
        if (!empty($filters['filter_shift'])) {
            $filterRows .= '<tr><td><strong>Shift:</strong></td><td colspan="3">' . esc((string) $filters['filter_shift']) . '</td></tr>';
        }
        if ($groupBy && $groupBy !== 'none') {
            $filterRows .= '<tr><td><strong>Group by:</strong></td><td colspan="3">' . ucfirst(esc((string) $groupBy)) . '</td></tr>';
        }
        $filterRows .= '<tr><td><strong>Total:</strong></td><td colspan="3"><strong>' . count($data) . ' Karyawan</strong></td></tr>';

        // HTML template
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Laporan Data Karyawan</title>
            <style>
                * { margin:0; padding:0; box-sizing:border-box; }
                body { font-family: Arial, sans-serif; font-size: 11px; color: #2c3e50; margin: 28px; }
                .print-header { text-align: center; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 3px solid #2c3e50; }
                .logo-container { width: 100%; margin-bottom: 10px; }
                .logo-cell-left  { width: 80px; vertical-align: middle; }
                .logo-cell-right { width: 80px; vertical-align: middle; }
                .logo-cell-center { text-align: center; vertical-align: middle; }
                .company-name { font-size: 17px; font-weight: bold; text-transform: uppercase; color: #2c3e50; letter-spacing: 1px; margin-bottom: 4px; }
                .company-address { font-size: 10px; color: #7f8c8d; line-height: 1.4; }
                .doc-title { font-size: 14px; font-weight: bold; text-transform: uppercase; color: #2c3e50; margin: 8px 0 2px; }
                .doc-subtitle-sm { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #2c3e50; margin-bottom: 2px; }
                .doc-periode { font-size: 10px; color: #7f8c8d; }
                .filter-box { background: #ecf0f1; padding: 8px 12px; margin-bottom: 14px; border-radius: 5px; font-size: 10px; }
                .filter-box table { width: 100%; border-collapse: collapse; }
                .filter-box td    { padding: 3px 6px; border: none; color: #2c3e50; background: transparent; }
                .main-table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 10px; }
                .main-table th { background: #34495e; color: white; padding: 6px 5px; font-weight: bold; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: .3px; border: 1px solid #2c3e50; }
                .main-table td { border: 1px solid #dde0e3; padding: 5px; color: #2c3e50; }
                .main-table tr.even td { background: #f7f8fa; }
                .group-row td { background: #d6eaf8; font-weight: bold; color: #1a5276; border-left: 3px solid #2980b9; padding-left: 8px; }
                .subtotal-row td { background: #fef9e7; font-weight: bold; color: #b7770d; }
                .total-row td { background: #34495e; color: white; font-weight: bold; }
                .sig-table { width: 100%; margin-top: 22px; border-collapse: collapse; }
                .sig-table td { border: none; text-align: center; width: 33%; padding: 0 10px; color: #2c3e50; font-size: 10px; }
                .sig-title { color: #7f8c8d; margin-bottom: 2px; }
                .sig-space { height: 42px; }
                .sig-line  { width: 120px; height: 1px; background: #2c3e50; margin: 0 auto 5px; }
                .sig-name  { font-weight: bold; }
                .footer-table { width: 100%; border-collapse: collapse; margin-top: 12px; padding-top: 8px; border-top: 1px solid #d5d8dc; font-size: 9px; color: #7f8c8d; }
                .footer-table td { border: none; padding: 0; }
                @page { size: A4 portrait; margin: 0; }
            </style>
        </head>
        <body>
            <div class="print-header">
                <table class="logo-container" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="logo-cell-left">' . $logoTag . '</td>
                        <td class="logo-cell-center">
                            <div class="company-name">' . esc((string) $company['name']) . '</div>
                            ' . (!empty($company['address']) ? '<div class="company-address">' . nl2br(esc((string) $company['address'])) . '</div>' : '') . '
                        </td>
                        <td class="logo-cell-right"></td>
                    </tr>
                </table>
                <div class="doc-title">Laporan Data Karyawan</div>
                <div class="doc-subtitle-sm">Divisi Dyeing &amp; Finishing</div>
                <div class="doc-periode">Periode: ' . date('F Y') . '</div>
            </div>
            <div class="filter-box">
                <table>' . $filterRows . '</table>
            </div>
            <table class="main-table">
                <thead>
                    <tr>
                        <th style="width:28px">No</th>
                        <th style="width:80px">NIK</th>
                        <th>Nama Lengkap</th>
                        <th style="width:52px">JK</th>
                        <th>Posisi</th>
                        <th>Departemen</th>
                        <th style="width:32px">Shift</th>
                        <th style="width:70px">Status Kerja</th>
                        <th style="width:52px">Status</th>
                    </tr>
                </thead>
                <tbody>' . $rows . '</tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="8" style="text-align:right;padding-right:8px">Total Keseluruhan:</td>
                        <td>' . count($data) . ' Karyawan</td>
                    </tr>
                </tfoot>
            </table>
            <table class="sig-table">
                <tr>
                    <td>
                        <div class="sig-title">Mengetahui,</div>
                        <div class="sig-space"></div>
                        <div class="sig-line"></div>
                        <div class="sig-name">DIV Manager</div>
                    </td>
                    <td></td>
                    <td>
                        <div class="sig-title">Petugas,</div>
                        <div class="sig-space"></div>
                        <div class="sig-line"></div>
                        <div class="sig-name">' . esc((string) $currentUser) . '</div>
                    </td>
                </tr>
            </table>
            <table class="footer-table">
                <tr>
                    <td style="text-align:left">Dicetak oleh: ' . esc((string) $currentUser) . '</td>
                    <td style="text-align:center">' . date('d-m-Y H:i:s') . '</td>
                    <td style="text-align:right">Dokumen Rahasia</td>
                </tr>
            </table>
        </body>
        </html>';

        // Render via dompdf, kembalikan sebagai ResponseInterface (tidak pakai exit())
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Ambil output PDF sebagai string lalu kirim via CI4 response — tidak pakai exit()
        $pdfContent = $dompdf->output();
        $filename   = 'employees_' . date('Ymd_His') . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Content-Length', (string) strlen($pdfContent))
            ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->setBody($pdfContent);
    }

    // ============================================================
    // AJAX — CRUD
    // ============================================================

    public function get(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        $result = $this->model->getData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);

        $id       = (int) $this->request->getPost('id');
        $isUpdate = $id > 0;

        if ($isUpdate  && !canDo('hrm.employees.edit'))   return $this->jsonError('Akses ditolak', 403);
        if (!$isUpdate && !canDo('hrm.employees.create')) return $this->jsonError('Akses ditolak', 403);

        $rules = [
            'nik'               => "required|max_length[20]|is_unique[employees.nik,id,{$id}]",
            'fullname'          => 'required|max_length[100]',
            'nickname'          => 'permit_empty|max_length[50]',
            'gender'            => 'required|in_list[L,P]',
            'phone'             => 'permit_empty|max_length[20]',
            'position_id'       => 'required|is_natural_no_zero',
            'work_area'         => 'permit_empty|max_length[100]',
            'shift'             => 'required|in_list[NS,A,B,C,D,E]',
            'employment_status' => 'required|in_list[tetap,kontrak,magang]',
            'join_date'         => 'permit_empty|valid_date[Y-m-d]',
            'status'            => 'required|in_list[active,inactive]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse(['status' => 'error', 'errors' => $this->validator->getErrors()], 422);
        }

        $userId = auth()->id();
        $data   = [
            'nik'               => strtoupper(trim($this->request->getPost('nik'))),
            'fullname'          => trim($this->request->getPost('fullname')),
            'nickname'          => trim($this->request->getPost('nickname') ?? '') ?: null,
            'gender'            => $this->request->getPost('gender'),
            'phone'             => trim($this->request->getPost('phone') ?? '') ?: null,
            'position_id'       => (int) $this->request->getPost('position_id'),
            'work_area'         => trim($this->request->getPost('work_area') ?? '') ?: null,
            'shift'             => $this->request->getPost('shift'),
            'employment_status' => $this->request->getPost('employment_status'),
            'join_date'         => $this->request->getPost('join_date') ?: null,
            'status'            => $this->request->getPost('status'),
        ];

        if ($isUpdate) {
            $data['updated_by'] = $userId;
            $result = $this->model->updateData($id, $data);
        } else {
            $data['created_by'] = $userId;
            $result = $this->model->createData($data);
        }

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function delete(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('hrm.employees.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->deleteData($id, auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('hrm.employees.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->restoreData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function forceDelete(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('hrm.employees.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->forceDeleteData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    /**
     * FIX: gunakan transaksi DB agar tidak ada data yang terhapus sebagian
     * jika salah satu forceDelete gagal di tengah loop.
     */
    public function emptyTrash()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('hrm.employees.delete')) return $this->jsonError('Akses ditolak', 403);

        $trashed = $this->model->onlyDeleted()->findAll();
        if (empty($trashed)) {
            return $this->jsonResponse(['status' => 'success', 'message' => 'Sampah sudah kosong']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $deleted = $skipped = 0;
        foreach ($trashed as $row) {
            if ($this->model->forceDeleteData($row['id'])['status'] === 'success') {
                $deleted++;
            } else {
                $skipped++;
            }
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return $this->jsonError('Gagal mengosongkan sampah, silakan coba lagi', 500);
        }

        $msg = "{$deleted} karyawan berhasil dihapus permanen";
        if ($skipped) $msg .= ", {$skipped} gagal dihapus";

        return $this->jsonResponse(['status' => 'success', 'message' => $msg]);
    }

    // ============================================================
    // AJAX — PHOTO
    // ============================================================

    public function uploadPhoto(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.employees.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $result = $this->model->getData($id);
        if ($result['status'] !== 'success') {
            return $this->jsonError('Karyawan tidak ditemukan', 404);
        }

        $file = $this->request->getFile('photo');

        if (!$file) {
            log_message('error', 'uploadPhoto: no file object from getFile()');
            return $this->jsonError('Tidak ada file yang diupload', 422);
        }

        if (!$file->isValid()) {
            $errorCode    = $file->getError();
            $errorMessage = $this->getUploadErrorMessage($errorCode);
            log_message('error', "uploadPhoto: file invalid — code {$errorCode}: {$errorMessage}");
            return $this->jsonError('File tidak valid: ' . $errorMessage, 422);
        }

        // Validasi server-side (pertahanan kedua setelah validasi JS di frontend)
        $validationRules = [
            'photo' => [
                'uploaded[photo]',
                'is_image[photo]',
                'mime_in[photo,image/jpeg,image/png,image/webp]',
                'max_size[photo,2048]',
                'max_dims[photo,1024,1024]',
            ],
        ];

        if (!$this->validate($validationRules)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ], 422);
        }

        // Siapkan direktori upload
        $uploadPath = FCPATH . 'uploads/employees/';
        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0755, true)) {
                log_message('error', 'uploadPhoto: gagal membuat direktori ' . $uploadPath);
                return $this->jsonError('Gagal membuat direktori upload', 500);
            }
        }

        if (!is_writable($uploadPath)) {
            log_message('error', 'uploadPhoto: direktori tidak writable ' . $uploadPath);
            return $this->jsonError('Direktori upload tidak dapat ditulis', 500);
        }

        // Hapus foto lama sebelum simpan yang baru
        $employee = $result['data'];
        if (!empty($employee['photo'])) {
            $oldPhoto = $uploadPath . $employee['photo'];
            if (file_exists($oldPhoto) && !@unlink($oldPhoto)) {
                log_message('warning', 'uploadPhoto: gagal hapus foto lama — ' . $oldPhoto);
            }
        }

        // Generate nama file baru
        $extension = strtolower($file->getExtension());
        $nik       = strtoupper($employee['nik'] ?? 'UNKNOWN');
        $newName   = 'EMP_' . $nik . '_' . time() . '.' . $extension;

        try {
            // FIX: pindahkan file ke disk TERLEBIH DAHULU, baru resize
            if (!$file->move($uploadPath, $newName)) {
                log_message('error', 'uploadPhoto: gagal memindahkan file ke disk');
                return $this->jsonError('Gagal menyimpan file', 500);
            }

            $uploadedFilePath = $uploadPath . $newName;

            if (!file_exists($uploadedFilePath)) {
                log_message('error', 'uploadPhoto: file tidak ditemukan setelah move — ' . $uploadedFilePath);
                return $this->jsonError('File tidak ditemukan setelah upload', 500);
            }

            // FIX: resize dipanggil SETELAH file ada di disk
            if (!$this->resizeImage($uploadedFilePath, $uploadedFilePath, 400, 400, 80)) {
                // Resize gagal tidak fatal — file original tetap tersimpan
                log_message('warning', 'uploadPhoto: resize gagal untuk ' . $newName . ', file original dipakai');
            }

            // FIX: cek return value array dari updateData(), bukan bool
            $updateResult = $this->model->updateData($id, [
                'photo'      => $newName,
                'updated_by' => auth()->id(),
            ]);

            if ($updateResult['status'] !== 'success') {
                // Rollback: hapus file yang sudah diupload jika DB gagal
                if (file_exists($uploadedFilePath)) {
                    @unlink($uploadedFilePath);
                }
                log_message('error', 'uploadPhoto: gagal update DB — ' . ($updateResult['message'] ?? ''));
                return $this->jsonError('Gagal memperbarui data karyawan', 500);
            }

            return $this->jsonResponse([
                'status'  => 'success',
                'message' => 'Foto berhasil diperbarui',
                'data'    => [
                    'photo'     => $newName,
                    'photo_url' => base_url('uploads/employees/' . $newName),
                ],
            ]);
        } catch (\Exception $e) {
            log_message('error', 'uploadPhoto exception: ' . $e->getMessage());
            return $this->jsonError('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    private function getUploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE   => 'File melebihi upload_max_filesize di php.ini',
            UPLOAD_ERR_FORM_SIZE  => 'File melebihi MAX_FILE_SIZE yang diatur di form',
            UPLOAD_ERR_PARTIAL    => 'File hanya terupload sebagian',
            UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang diupload',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder tmp tidak ditemukan',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
            UPLOAD_ERR_EXTENSION  => 'Upload dihentikan oleh ekstensi PHP',
            default               => 'Unknown error code: ' . $errorCode,
        };
    }

    /**
     * Resize image ke dimensi maksimal dengan mempertahankan aspect ratio.
     * File sumber dan tujuan boleh sama (in-place resize).
     */
    private function resizeImage(
        string $sourcePath,
        string $destPath,
        int $maxWidth  = 400,
        int $maxHeight = 400,
        int $quality   = 80
    ): bool {
        try {
            $imageInfo = @getimagesize($sourcePath);
            if (!$imageInfo) return false;

            [$width, $height, $type] = $imageInfo;

            // Tidak perlu resize jika sudah lebih kecil dari batas
            if ($width <= $maxWidth && $height <= $maxHeight) return true;

            $ratio     = min($maxWidth / $width, $maxHeight / $height);
            $newWidth  = (int) ($width  * $ratio);
            $newHeight = (int) ($height * $ratio);

            $src = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
                IMAGETYPE_PNG  => @imagecreatefrompng($sourcePath),
                IMAGETYPE_WEBP => @imagecreatefromwebp($sourcePath),
                default        => false,
            };
            if (!$src) return false;

            $dest = imagecreatetruecolor($newWidth, $newHeight);
            if (!$dest) {
                imagedestroy($src);
                return false;
            }

            // Pertahankan transparansi PNG
            if ($type === IMAGETYPE_PNG) {
                imagealphablending($dest, false);
                imagesavealpha($dest, true);
                $transparent = imagecolorallocatealpha($dest, 255, 255, 255, 127);
                imagefilledrectangle($dest, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($dest, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            $saved = match ($type) {
                IMAGETYPE_JPEG => imagejpeg($dest, $destPath, $quality),
                IMAGETYPE_PNG  => imagepng($dest, $destPath, 8),
                IMAGETYPE_WEBP => imagewebp($dest, $destPath, $quality),
                default        => false,
            };

            imagedestroy($src);
            imagedestroy($dest);

            return (bool) $saved;
        } catch (\Exception $e) {
            log_message('error', 'resizeImage exception: ' . $e->getMessage());
            return false;
        }
    }

    public function deletePhoto(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('hrm.employees.edit')) return $this->jsonError('Akses ditolak', 403);

        $result = $this->model->getData($id);
        if ($result['status'] !== 'success') return $this->jsonError('Karyawan tidak ditemukan', 404);

        $employee  = $result['data'];
        $photoPath = FCPATH . 'uploads/employees/' . ($employee['photo'] ?? '');
        if (!empty($employee['photo']) && file_exists($photoPath)) {
            @unlink($photoPath);
        }

        $this->model->updateData($id, ['photo' => null, 'updated_by' => auth()->id()]);
        return $this->jsonResponse(['status' => 'success', 'message' => 'Foto berhasil dihapus']);
    }

    // ============================================================
    // AJAX — STATS, SELECT2, LOOKUPS
    // ============================================================

    public function stats()
    {
        if (!canDo('hrm.employees.view')) return $this->jsonError('Akses ditolak', 403);
        return $this->response->setJSON(['status' => 'success', 'data' => $this->model->getStats()]);
    }

    public function select2()
    {
        $search       = trim($this->request->getGet('search') ?? '');
        $positionId   = $this->request->getGet('position_id');
        $departmentId = $this->request->getGet('department_id');
        $shift        = $this->request->getGet('shift');
        $workArea     = $this->request->getGet('work_area');

        $builder = $this->model->db->table('employees')
            ->select([
                'employees.id',
                'employees.nik',
                'employees.fullname',
                'employees.nickname',
                'employees.shift',
                'employees.work_area',
                'positions.position_name',
                'departments.department as department_name',
            ])
            ->join('positions',   'positions.id = employees.position_id',    'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->where('employees.status', 'active')
            ->where('employees.deleted_at', null)
            ->orderBy('employees.fullname', 'ASC');

        if ($positionId)   $builder->where('employees.position_id', $positionId);
        if ($departmentId) $builder->where('positions.department_id', $departmentId);
        if ($shift)        $builder->where('employees.shift', $shift);
        if ($workArea)     $builder->where('employees.work_area', $workArea);

        if ($search !== '') {
            $builder->groupStart()
                ->like('employees.fullname', $search)
                ->orLike('employees.nik', $search)
                ->orLike('employees.nickname', $search)
                ->groupEnd();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $builder->limit(50)->get()->getResultArray(),
        ]);
    }

    public function checkUnique()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);

        $field = $this->request->getPost('field');
        $value = trim($this->request->getPost('value') ?? '');
        $id    = (int) $this->request->getPost('id');

        if ($field !== 'nik') return $this->jsonError('Field tidak valid', 422);

        $q = $this->model->where("UPPER({$field})", strtoupper($value))->where('deleted_at', null);
        if ($id > 0) $q->where('id !=', $id);

        return $this->jsonResponse(['status' => 'success', 'available' => !$q->first()]);
    }

    public function getByPosition(int $positionId)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getByPosition($positionId),
        ]);
    }

    public function getByDepartment(int $departmentId)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getByDepartment($departmentId),
        ]);
    }

    public function getByShift(string $shift)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        $valid = ['NS', 'A', 'B', 'C', 'D', 'E'];
        if (!in_array(strtoupper($shift), $valid)) return $this->jsonError('Shift tidak valid', 422);
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getByShift(strtoupper($shift)),
        ]);
    }

    public function getByWorkArea()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        $workArea = trim($this->request->getGet('work_area') ?? '');
        if ($workArea === '') return $this->jsonError('Work area tidak boleh kosong', 422);
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getByWorkArea($workArea),
        ]);
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function breadcrumbs(array $extra = []): array
    {
        $base = [
            ['name' => 'Dashboard', 'url' => site_url('dashboard')],
            ['name' => 'HRM',       'url' => site_url('hrm')],
            ['name' => 'Karyawan',  'url' => site_url('hrm/employees')],
        ];

        if (empty($extra)) {
            $base[2]['active'] = true;
            unset($base[2]['url']);
            return $base;
        }

        return array_merge($base, $extra);
    }

    private function forbidden(): ResponseInterface
    {
        return redirect()->to(site_url('errors/403'));
    }

    private function jsonResponse(array $result, int $code = 200): ResponseInterface
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON(array_merge($result, ['csrfHash' => csrf_hash()]));
    }

    private function jsonError(string $message, int $code = 500): ResponseInterface
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON(['status' => 'error', 'message' => $message, 'csrfHash' => csrf_hash()]);
    }
}
