<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\FormulationModel;
use CodeIgniter\HTTP\RedirectResponse;
use Hermawan\DataTables\DataTable;

class FormulationController extends BaseController
{
    protected FormulationModel $model;

    public function __construct()
    {
        $this->model = new FormulationModel();
    }

    // ============================================================
    // PAGES
    // ============================================================

    public function index(): string|RedirectResponse
    {
        if (!canDo('warehouse.formulations.view')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\formulations\index', [
            'title'            => 'Formulasi',
            'page_title'       => 'Daftar Formulasi',
            'page_description' => 'Kelola resep campuran bahan kimia untuk proses dyeing & finishing',
            'breadcrumbs'      => $this->breadcrumbs(),
        ]);
    }

    public function create(): string|RedirectResponse
    {
        if (!canDo('warehouse.formulations.manage')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\formulations\form', [
            'title'            => 'Tambah Formulasi',
            'page_title'       => 'Tambah Formulasi',
            'page_description' => 'Buat resep formulasi baru',
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Tambah', 'active' => true]]),
            'formulation'      => null,
        ]);
    }

    public function edit(int $id): string|RedirectResponse
    {
        if (!canDo('warehouse.formulations.manage')) return $this->forbidden();

        $result = $this->model->getData($id);
        if ($result['status'] !== 'success') {
            return redirect()->to(site_url('warehouse/formulations'))->with('error', $result['message']);
        }

        return view('App\Modules\Warehouse\Views\formulations\form', [
            'title'            => 'Edit Formulasi',
            'page_title'       => 'Edit Formulasi',
            'page_description' => 'Perbarui resep formulasi',
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Edit', 'active' => true]]),
            'formulation'      => $result['data'],
        ]);
    }

    public function show(int $id)
    {
        if (!canDo('warehouse.formulations.view')) return $this->jsonError('Akses ditolak', 403);

        $result = $this->model->getData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function trash(): string|RedirectResponse
    {
        if (!canDo('warehouse.formulations.manage')) return $this->forbidden();

        return view('App\Modules\Warehouse\Views\formulations\trash', [
            'title'            => 'Sampah — Formulasi',
            'page_title'       => 'Sampah Formulasi',
            'page_description' => 'Formulasi yang telah dihapus',
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Sampah', 'active' => true]]),
        ]);
    }

    // ============================================================
    // DATATABLE
    // ============================================================

    public function datatables()
    {
        if (!canDo('warehouse.formulations.view')) return $this->jsonError('Akses ditolak', 403);

        $db      = \Config\Database::connect();
        $builder = $db->table('formulations f')
            ->select([
                'f.id',
                'f.formulation_code',
                'f.formulation_name',
                'f.process_type',
                'f.output_quantity',
                'f.output_unit',
                'f.status',
                'f.created_at',
                'f.updated_at',
                'cu.username as created_by_name',
                'cu_emp.nickname as created_by_employee',
                '(SELECT COUNT(*) FROM formulation_items fi WHERE fi.formulation_id = f.id) as item_count',
            ])
            ->join('users cu', 'cu.id = f.created_by', 'left')
            ->join('employees cu_emp', 'cu_emp.id = cu.employee_id', 'left')
            ->where('f.deleted_at', null);

        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('f.formulation_name', $name)
                ->orLike('f.formulation_code', $name)
                ->groupEnd();
        }

        if ($processType = $this->request->getGet('filter_process_type')) {
            $builder->where('f.process_type', $processType);
        }

        if ($status = $this->request->getGet('filter_status')) {
            $builder->where('f.status', $status);
        }

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['f.formulation_name', 'f.formulation_code'])
            ->toJson(true);
    }

    public function trashDatatables()
    {
        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        $db      = \Config\Database::connect();
        $builder = $db->table('formulations f')
            ->select([
                'f.id',
                'f.formulation_code',
                'f.formulation_name',
                'f.process_type',
                'f.status',
                'f.deleted_at',
                'du.username as deleted_by_name',
                'du_emp.nickname as deleted_by_employee',
            ])
            ->join('users du', 'du.id = f.deleted_by', 'left')
            ->join('employees du_emp', 'du_emp.id = du.employee_id', 'left')
            ->where('f.deleted_at IS NOT NULL');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['f.formulation_name', 'f.formulation_code'])
            ->toJson(true);
    }

    // ============================================================
    // CRUD
    // ============================================================

    public function store()
    {
        $id       = (int) $this->request->getPost('id');
        $isUpdate = $id > 0;

        if ($isUpdate && !canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);
        if (!$isUpdate && !canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        try {
            $rules = [
                'formulation_code' => 'required|max_length[50]|alpha_numeric_punct',
                'formulation_name' => 'required|max_length[150]',
                'process_type'     => 'required|in_list[Dyeing,Finishing,Other]',
                'output_quantity'  => 'required|decimal|greater_than[0]',
                'output_unit'      => 'permit_empty|max_length[20]',
                'status'           => 'required|in_list[Active,Draft,Archived]',
                'description'      => 'permit_empty|max_length[500]',
            ];

            if (!$this->validate($rules)) {
                return $this->jsonResponse(['status' => 'error', 'errors' => $this->validator->getErrors()], 422);
            }

            $itemsRaw = $this->request->getPost('items');
            $items    = is_string($itemsRaw) ? json_decode($itemsRaw, true) : $itemsRaw;
            $items    = is_array($items) ? $items : [];

            $userId = auth()->id();
            $data = [
                'formulation_code' => strtoupper(trim($this->request->getPost('formulation_code'))),
                'formulation_name' => trim($this->request->getPost('formulation_name')),
                'process_type'     => $this->request->getPost('process_type'),
                'output_quantity'  => $this->request->getPost('output_quantity'),
                'output_unit'      => trim($this->request->getPost('output_unit') ?? '') ?: null,
                'description'      => trim($this->request->getPost('description') ?? '') ?: null,
                'status'           => $this->request->getPost('status'),
            ];

            if ($isUpdate) {
                $data['updated_by'] = $userId;
                $result = $this->model->updateData($id, $data, $items);
            } else {
                $data['created_by'] = $userId;
                $result = $this->model->createData($data, $items);
            }

            return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
        } catch (\Throwable $e) {
            log_message('error', 'FormulationController::store: ' . $e->getMessage());
            return $this->jsonError('Gagal menyimpan formulasi: ' . $e->getMessage(), 500);
        }
    }

    public function update(int $id)
    {
        // Reuse store() logic — form mengirim id via hidden field, tapi
        // sediakan endpoint /update/$1 sesuai definisi route.
        $this->request->setGlobal('post', array_merge($this->request->getPost() ?? [], ['id' => $id]));
        return $this->store();
    }

    public function delete(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        $result = $this->model->deleteData($id, auth()->id());
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        $result = $this->model->restoreData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function forceDelete(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        $result = $this->model->forceDeleteData($id);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function emptyTrash()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        $trashed = $this->model->onlyDeleted()->findAll();
        if (empty($trashed)) {
            return $this->jsonResponse(['status' => 'success', 'message' => 'Sampah sudah kosong']);
        }

        $deleted = 0;
        $errors  = [];
        foreach ($trashed as $row) {
            $result = $this->model->forceDeleteData($row['id']);
            if ($result['status'] === 'success') {
                $deleted++;
            } else {
                $errors[] = $row['formulation_name'] . ': ' . $result['message'];
            }
        }

        $message = "{$deleted} formulasi berhasil dihapus permanen";
        if (!empty($errors)) $message .= ". Gagal: " . implode('; ', $errors);

        return $this->jsonResponse(['status' => 'success', 'message' => $message]);
    }

    // ============================================================
    // STATS & SELECT2 & LOOKUPS
    // ============================================================

    public function stats()
    {
        if (!canDo('warehouse.formulations.view')) return $this->jsonError('Akses ditolak', 403);

        return $this->response->setJSON(['status' => 'success', 'data' => $this->model->getStats()]);
    }

    public function select2()
    {
        if (!canDo('warehouse.formulations.view')) return $this->jsonError('Akses ditolak', 403);

        $search = trim($this->request->getGet('search') ?? '');
        $db      = \Config\Database::connect();
        $builder = $db->table('formulations f')
            ->select('f.id, f.formulation_code AS code, f.formulation_name AS name, f.output_quantity, f.output_unit')
            ->where('f.status', 'Active')
            ->where('f.deleted_at', null)
            ->orderBy('f.formulation_name', 'ASC');

        if ($search !== '') {
            $builder->groupStart()
                ->like('f.formulation_name', $search)
                ->orLike('f.formulation_code', $search)
                ->groupEnd();
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $builder->limit(50)->get()->getResultArray()]);
    }

    /**
     * Daftar jenis proses (dipakai sebagai filter/kategori pada form & listing).
     */
    public function categories()
    {
        if (!canDo('warehouse.formulations.view')) return $this->jsonError('Akses ditolak', 403);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                ['value' => 'Dyeing',    'label' => 'Dyeing'],
                ['value' => 'Finishing', 'label' => 'Finishing'],
                ['value' => 'Other',     'label' => 'Lainnya'],
            ],
        ]);
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function breadcrumbs(array $extra = []): array
    {
        $base = [
            ['name' => 'Dashboard', 'url' => site_url('dashboard')],
            ['name' => 'Warehouse',  'url' => site_url('warehouse')],
            ['name' => 'Formulasi',  'url' => site_url('warehouse/formulations')],
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
