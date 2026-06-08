<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        if (! auth()->loggedIn()) {
            return redirect()->to('login');
        }

        $data = [
            'title'       => 'Dashboard',
            'user'        => auth()->user(),

            // ── Production ─────────────────────────────────────────────
            // Ganti dengan query nyata ketika modul Production sudah siap
            'totalWoActive'     => 0, // WorkOrderModel->where('status','active')->countAllResults()
            'totalWoDraft'      => 0, // WorkOrderModel->where('status','draft')->countAllResults()
            'woCompleted7d'     => 0, // WO selesai 7 hari terakhir
            'woCompletedPct'    => 0, // % completed dari total
            'woActivePct'       => 0, // % active dari total
            'woMonthlyData'     => array_fill(0, 12, 0), // [jan..des] — isi dari query GROUP BY MONTH
            'recentWorkOrders'  => [], // 10 WO terbaru

            // ── HRM ────────────────────────────────────────────────────
            'totalEmployees'    => 0, // EmployeeModel->where('is_active',1)->countAllResults()
            'employeesByDept'   => [], // [['name'=>'Dyeing','total'=>12], ...]

            // ── Warehouse ──────────────────────────────────────────────
            'totalLowStock'         => 0, // bahan dengan stok < min_stock
            'chemicalCategoryData'  => [0, 0, 0],   // qty per kategori
            'chemicalCategoryLabels' => ['Pewarna', 'Kimia Pembantu', 'Finishing Agent'],

            // ── Machines ───────────────────────────────────────────────
            'machineActivePct'  => 0, // % mesin status active
            'machineIdlePct'    => 0, // % mesin status idle/maintenance
        ];

        // ── Contoh query nyata (uncomment setelah modul siap) ──────────
        //
        // $woModel = new \App\Modules\Production\Models\WorkOrderModel();
        // $data['totalWoActive'] = $woModel->where('status', 'active')->countAllResults();
        // $data['totalWoDraft']  = $woModel->where('status', 'draft')->countAllResults();
        //
        // $data['recentWorkOrders'] = $woModel
        //     ->select('work_orders.*, machines.name as machine_name')
        //     ->join('machines', 'machines.id = work_orders.machine_id', 'left')
        //     ->orderBy('work_orders.created_at', 'DESC')
        //     ->limit(10)
        //     ->findAll();
        //
        // // WO per bulan (tahun berjalan)
        // $woMonthly = $woModel
        //     ->select('MONTH(created_at) as month, COUNT(*) as total')
        //     ->where('YEAR(created_at)', date('Y'))
        //     ->groupBy('MONTH(created_at)')
        //     ->findAll();
        // $monthlyArr = array_fill(0, 12, 0);
        // foreach ($woMonthly as $row) {
        //     $monthlyArr[(int)$row['month'] - 1] = (int)$row['total'];
        // }
        // $data['woMonthlyData'] = $monthlyArr;
        //
        // $empModel = new \App\Modules\HRM\Models\EmployeeModel();
        // $data['totalEmployees']  = $empModel->where('is_active', 1)->countAllResults();
        // $data['employeesByDept'] = $empModel
        //     ->select('departments.name, COUNT(employees.id) as total')
        //     ->join('departments', 'departments.id = employees.department_id')
        //     ->where('employees.is_active', 1)
        //     ->groupBy('departments.id')
        //     ->findAll();

        return view('dashboard/index', $data);
    }
}
