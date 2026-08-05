<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\PeriodModel;
use CodeIgniter\HTTP\RedirectResponse;
use Hermawan\DataTables\DataTable;

class PeriodController extends BaseController
{
    protected PeriodModel $model;

    public function __construct()
    {
        $this->model = new PeriodModel();
    }

    public function index(): string|RedirectResponse
    {
        if (!canDo('warehouse.periods.view')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\periods\index', [
            'title'            => 'Periode',
            'page_title'       => 'Master Periode',
            'page_description' => 'Kelola periode untuk pencatatan stok',
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    public function trash(): string|RedirectResponse
    {
        if (!canDo('warehouse.periods.delete')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\periods\trash', [
            'title'            => 'Sampah — Periode',
            'page_title'       => 'Sampah Periode',
            'page_description' => 'Periode yang telah dihapus',
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Sampah', 'active' => true]]),
        ]);
    }

    public function datatables()
    {
        if (!canDo('warehouse.periods.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('periods p')
            ->select([
                'p.id',
                'p.period_code',
                'p.period_name',
                'p.start_date',
                'p.end_date',
                'p.status',
                'p.is_current',
                'p.created_at',
                'p.updated_at',
                'cu.username as created_by_name',
                'cu_emp.nickname as created_by_employee',
                'uu.username as updated_by_name',
                'uu_emp.nickname as updated_by_employee',
            ])
            ->join('users cu', 'cu.id = p.created_by', 'left')
            ->join('employees cu_emp', 'cu_emp.id = cu.employee_id', 'left')
            ->join('users uu', 'uu.id = p.updated_by', 'left')
            ->join('employees uu_emp', 'uu_emp.id = uu.employee_id', 'left')
            ->where('p.deleted_at', null);

        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('p.period_name', $name)
                ->orLike('p.period_code', $name)
                ->groupEnd();
        }

        if ($status = trim($this->request->getGet('filter_status') ?? '')) {
            $builder->where('p.status', $status);
        }

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['p.period_name', 'p.period_code'])
            ->toJson(true);
    }

    public function trashDatatables()
    {
        if (!canDo('warehouse.periods.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('periods p')
            ->select([
                'p.id',
                'p.period_code',
                'p.period_name',
                'p.status',
                'p.deleted_at',
                'cu.username as created_by_name',
                'cu_emp.nickname as created_by_employee',
                'du.username as deleted_by_name',
                'du_emp.nickname as deleted_by_employee',
            ])
            ->join('users cu', 'cu.id = p.created_by', 'left')
            ->join('employees cu_emp', 'cu_emp.id = cu.employee_id', 'left')
            ->join('users du', 'du.id = p.deleted_by', 'left')
            ->join('employees du_emp', 'du_emp.id = du.employee_id', 'left')
            ->where('p.deleted_at IS NOT NULL');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['p.period_name', 'p.period_code'])
            ->toJson(true);
    }

    public function get(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        $result = $this->model->getData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);

        $id = (int) $this->request->getPost('id');
        $isUpdate = $id > 0;

        if ($isUpdate && !canDo('warehouse.periods.edit'))   return $this->jsonError('Akses ditolak', 403);
        if (!$isUpdate && !canDo('warehouse.periods.create')) return $this->jsonError('Akses ditolak', 403);

        $rules = [
            'period_code' => 'required|max_length[20]|alpha_numeric_punct',
            'period_name' => 'required|max_length[50]',
            'start_date'  => 'required|valid_date',
            'end_date'    => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse(['status' => 'error', 'errors' => $this->validator->getErrors()], 422);
        }

        $userId = auth()->id();
        $data = [
            'period_code' => strtoupper(trim($this->request->getPost('period_code'))),
            'period_name' => trim($this->request->getPost('period_name')),
            'start_date'  => $this->request->getPost('start_date'),
            'end_date'    => $this->request->getPost('end_date'),
            'notes'       => trim($this->request->getPost('notes') ?? '') ?: null,
            'is_current'  => (int) ($this->request->getPost('is_current') ?? 0),
        ];

        if ($isUpdate) {
            $data['updated_by'] = $userId;
            $result = $this->model->updateData($id, $data);
        } else {
            $data['created_by'] = $userId;
            $data['status']     = 'Open';
            $result = $this->model->createData($data);
        }

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function delete(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.periods.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->deleteData($id, auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.periods.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->restoreData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function forceDelete(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.periods.delete')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->forceDeleteData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function emptyTrash()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.periods.delete')) return $this->jsonError('Akses ditolak', 403);

        $trashed = $this->model->onlyDeleted()->findAll();
        if (empty($trashed)) return $this->jsonResponse(['status' => 'success', 'message' => 'Sampah sudah kosong']);

        $deleted = 0;
        foreach ($trashed as $row) {
            if ($this->model->forceDeleteData($row['id'])['status'] === 'success') $deleted++;
        }
        return $this->jsonResponse(['status' => 'success', 'message' => "{$deleted} periode berhasil dihapus permanen"]);
    }

    // ============================================================
    // BUSINESS ACTIONS
    // ============================================================

    public function setCurrent(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.periods.edit')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->setCurrent($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function closePeriod(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.periods.edit')) return $this->jsonError('Akses ditolak', 403);
        $result = $this->model->closePeriod($id, auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    /**
     * Resolve bulan kalender (YYYY-MM) -> data periode.
     * GET ?month=2026-08
     */
    public function byMonth()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);

        $month = trim((string) $this->request->getGet('month'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            return $this->jsonError('Format bulan tidak valid', 422);
        }

        $period = $this->model->findByMonth($month);
        if (!$period) {
            return $this->jsonError('Periode tidak ditemukan untuk bulan ini', 404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'id'         => $period['id'],
                'name'       => $period['period_name'] ?? $period['name'] ?? null,
                'code'       => $period['period_code'] ?? $period['code'] ?? null,
                'status'     => $period['status'],
                'start_date' => $period['start_date'] ?? null,
                'end_date'   => $period['end_date'] ?? null,
            ],
        ]);
    }

    public function stats()
    {
        if (!canDo('warehouse.periods.view')) return $this->jsonError('Akses ditolak', 403);
        return $this->response->setJSON(['status' => 'success', 'data' => $this->model->getStats()]);
    }

    public function select2()
    {
        $search  = trim($this->request->getGet('search') ?? '');
        $builder = $this->model->db->table('periods')
            ->select('id, period_code AS code, period_name AS name, status')
            ->where('deleted_at', null)->orderBy('start_date', 'DESC');
        if ($search !== '') $builder->groupStart()->like('period_name', $search)->orLike('period_code', $search)->groupEnd();
        return $this->response->setJSON(['status' => 'success', 'data' => $builder->limit(50)->get()->getResultArray()]);
    }

    private function breadcrumbs(array $extra = []): array
    {
        $base = [
            ['name' => 'Dashboard', 'url' => site_url('dashboard')],
            ['name' => 'Warehouse',  'url' => site_url('warehouse')],
            ['name' => 'Periode',    'url' => site_url('warehouse/master/periods')],
        ];
        if (empty($extra)) {
            $base[2]['active'] = true;
            unset($base[2]['url']);
            return $base;
        }
        return array_merge($base, $extra);
    }

    private function forbidden(): RedirectResponse
    {
        return redirect()->to(site_url('errors/403'));
    }
    private function jsonResponse(array $result, int $code = 200)
    {
        return $this->response->setStatusCode($code)->setJSON(array_merge($result, ['csrfHash' => csrf_hash()]));
    }
    private function jsonError(string $message, int $code = 500)
    {
        return $this->response->setStatusCode($code)->setJSON(['status' => 'error', 'message' => $message, 'csrfHash' => csrf_hash()]);
    }
}
