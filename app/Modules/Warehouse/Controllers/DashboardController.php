<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\ChemicalModel;
use App\Modules\Warehouse\Models\FormulationModel;
use App\Modules\Warehouse\Models\PeriodModel;
use App\Modules\Warehouse\Models\WarehouseModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if (!auth()->loggedIn()) return redirect()->to('login');

        $db = \Config\Database::connect();

        $data = [
            'title'            => 'Dashboard Warehouse',
            'page_title'       => 'Dashboard Warehouse',
            'page_description' => 'Ringkasan gudang kimia produksi dyeing & finishing',
            'breadcrumbs'      => [
                ['name' => 'Dashboard', 'url' => site_url('dashboard')],
                ['name' => 'Warehouse', 'active' => true],
            ],
            'totalChemicals'    => 0,
            'totalFormulations' => 0,
            'totalWarehouses'   => 0,
            'currentPeriod'     => null,
        ];

        try {
            $data['totalChemicals'] = (new ChemicalModel())->where('deleted_at', null)->countAllResults();
        } catch (\Throwable $e) {}

        try {
            $data['totalFormulations'] = (new FormulationModel())->where('deleted_at', null)->countAllResults();
        } catch (\Throwable $e) {}

        try {
            $data['totalWarehouses'] = (new WarehouseModel())->where('deleted_at', null)->countAllResults();
        } catch (\Throwable $e) {}

        try {
            $data['currentPeriod'] = $db->table('periods')->where('is_current', 1)->get()->getRowArray();
        } catch (\Throwable $e) {}

        return view('App\Modules\Warehouse\Views\dashboard\index', $data);
    }
}
