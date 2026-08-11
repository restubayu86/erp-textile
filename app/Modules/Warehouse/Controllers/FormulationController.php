<?php

namespace App\Modules\Warehouse\Controllers;

use App\Controllers\BaseController;
use App\Modules\Warehouse\Models\FormulationModel;
use App\Modules\Warehouse\Models\FormulationGroupModel;
use CodeIgniter\HTTP\RedirectResponse;
use Hermawan\DataTables\DataTable;

class FormulationController extends BaseController
{
    protected FormulationModel $model;
    protected FormulationGroupModel $groupModel;

    public function __construct()
    {
        $this->model      = new FormulationModel();
        $this->groupModel = new FormulationGroupModel();
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

        $suggestedCode = $this->model->generateNextCode();

        return view('App\Modules\Warehouse\Views\formulations\form', [
            'title'            => 'Tambah Formulasi',
            'page_title'       => 'Tambah Formulasi',
            'page_description' => 'Buat resep formulasi baru',
            'breadcrumbs'      => $this->breadcrumbs([['name' => 'Tambah', 'active' => true]]),
            'formulation'      => null,
            'suggested_code'   => $suggestedCode,
            'process_sub_types' => $this->getProcessSubTypes(),
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
            'suggested_code'   => null,
            'process_sub_types' => $this->getProcessSubTypes(),
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

        $db     = \Config\Database::connect();
        $status = $this->request->getGet('filter_status');
        $status = $status !== null ? trim((string) $status) : '';

        // ------------------------------------------------------------
        // "Versi representatif" per formulasi dihitung via correlated
        // subquery (bukan JOIN ke f.current_version_id yang gampang basi,
        // dan bukan pula JOIN ke derived table raw string yang ternyata
        // rawan gagal di query builder). Semua nilai versi (status,
        // output_percentage, version_no, item_count) diturunkan dari
        // version_no target yang sama di bawah ini, supaya konsisten.
        //
        // - Filter status eksplisit -> target = version_no terbesar YANG
        //   BERSTATUS SESUAI FILTER tsb (formulasi tanpa versi berstatus
        //   itu dikecualikan lewat EXISTS di bawah).
        // - Tanpa filter ("Semua") -> prioritaskan version_no terbesar
        //   yang Active; kalau tidak ada versi Active sama sekali,
        //   fallback ke version_no terbesar apa pun statusnya (termasuk
        //   yang semua versinya sudah Archived).
        // ------------------------------------------------------------
        if ($status !== '') {
            $targetVersionNoExpr = "(SELECT MAX(vn.version_no) FROM formulation_versions vn "
                . "WHERE vn.formulation_id = f.id AND vn.status = " . $db->escape($status) . ")";
        } else {
            $targetVersionNoExpr = "COALESCE("
                . "(SELECT MAX(vn.version_no) FROM formulation_versions vn WHERE vn.formulation_id = f.id AND vn.status = 'Active'),"
                . "(SELECT MAX(vn.version_no) FROM formulation_versions vn WHERE vn.formulation_id = f.id)"
                . ")";
        }

        $versionIdExpr = "(SELECT vv.id FROM formulation_versions vv "
            . "WHERE vv.formulation_id = f.id AND vv.version_no = {$targetVersionNoExpr})";

        $builder = $db->table('formulations f')
            ->select([
                'f.id',
                'f.formulation_code',
                'f.formulation_name',
                'f.process_type',
                'f.process_sub_type',
                'f.process_sub_type_label',
                'f.last_used_at',
                'g.group_name',
                "{$targetVersionNoExpr} as version_no",
                "(SELECT vv.status FROM formulation_versions vv WHERE vv.id = {$versionIdExpr}) as status",
                "(SELECT vv.output_percentage FROM formulation_versions vv WHERE vv.id = {$versionIdExpr}) as output_percentage",
                "(SELECT COUNT(*) FROM formulation_items fi WHERE fi.formulation_version_id = {$versionIdExpr}) as item_count",
                'f.created_at',
                'f.updated_at',
                'cu.username as created_by_name',
                'cu_emp.nickname as created_by_employee',
            ])
            ->join('formulation_groups g', 'g.id = f.group_id', 'left')
            ->join('users cu', 'cu.id = f.created_by', 'left')
            ->join('employees cu_emp', 'cu_emp.id = cu.employee_id', 'left')
            ->where('f.deleted_at', null);

        // Kecualikan formulasi yang tidak punya versi berstatus sesuai filter
        if ($status !== '') {
            $builder->where(
                "EXISTS (SELECT 1 FROM formulation_versions fv3 WHERE fv3.formulation_id = f.id AND fv3.status = " . $db->escape($status) . ")",
                null,
                false
            );
        }

        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('f.formulation_name', $name)
                ->orLike('f.formulation_code', $name)
                ->groupEnd();
        }

        if ($processType = $this->request->getGet('filter_process_type')) {
            $builder->where('f.process_type', $processType);
        }

        if ($subProcessType = $this->request->getGet('filter_sub_process_type')) {
            $builder->where('f.process_sub_type', $subProcessType);
        }

        if ($groupId = $this->request->getGet('filter_group_id')) {
            $builder->where('f.group_id', $groupId);
        }

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns(['f.formulation_name', 'f.formulation_code', 'g.group_name'])
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
                'f.process_sub_type',
                'f.current_version_id',  // ← KOLOM INI PENTING!
                'g.group_name',
                'v.status',
                'v.version_no',
                'f.deleted_at',
                'du.username as deleted_by_name',
                'du_emp.nickname as deleted_by_employee',
            ])
            ->join('formulation_groups g', 'g.id = f.group_id', 'left')
            ->join('formulation_versions v', 'v.id = f.current_version_id', 'left')
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

        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        try {
            $rules = [
                'formulation_code'  => 'permit_empty|max_length[50]|alpha_numeric_punct',
                'formulation_name'  => 'required|max_length[150]',
                'process_type'      => 'required|in_list[Dyeing,Finishing,Other]',
                'process_sub_type'  => 'required|in_list[Dyeing,Dipping,Dipping 1,Dipping 2,Coating,Spray,Coating_Foam,Finishing,Other]',
                'process_sub_type_label' => 'permit_empty|max_length[50]',
                'output_percentage' => 'permit_empty|decimal|greater_than_equal_to[0]',
                'version_status'    => 'required|in_list[Active,Draft,Archived]',
                'description'       => 'permit_empty|max_length[500]',
                'group_id'          => 'permit_empty|integer',
                'group_name'        => 'permit_empty|max_length[100]',
                'create_new_version' => 'permit_empty|in_list[0,1]',
            ];

            if (!$this->validate($rules)) {
                return $this->jsonResponse(['status' => 'error', 'errors' => $this->validator->getErrors()], 422);
            }

            $itemsRaw = $this->request->getPost('items');
            $items    = is_string($itemsRaw) ? json_decode($itemsRaw, true) : $itemsRaw;
            $items    = is_array($items) ? $items : [];

            // Debug: log items yang diterima
            log_message('debug', 'Items received in controller: ' . json_encode($items));

            $userId = auth()->id();

            $groupId = (int) ($this->request->getPost('group_id') ?: 0) ?: null;
            $groupName = trim((string) $this->request->getPost('group_name'));
            if (!$groupId && $groupName !== '') {
                $groupId = $this->groupModel->findOrCreateByName($groupName, $userId);
            }

            $data = [
                'formulation_code'   => strtoupper(trim($this->request->getPost('formulation_code') ?? '')),
                'formulation_name'   => trim($this->request->getPost('formulation_name')),
                'process_type'       => $this->request->getPost('process_type'),
                'process_sub_type'   => $this->request->getPost('process_sub_type'),
                'process_sub_type_label' => trim($this->request->getPost('process_sub_type_label') ?? '') ?: null,
                'group_id'           => $groupId,
                'description'        => trim($this->request->getPost('description') ?? '') ?: null,
            ];

            $outputPercentage = $this->request->getPost('output_percentage');
            $outputPercentage = ($outputPercentage !== '' && $outputPercentage !== null) ? $outputPercentage : null;

            $versionMeta = [
                'status'            => $this->request->getPost('version_status'),
                'output_percentage' => $outputPercentage,
                'notes'             => trim($this->request->getPost('version_notes') ?? '') ?: null,
            ];

            $createNewVersion = (bool) $this->request->getPost('create_new_version');

            if ($isUpdate) {
                $data['updated_by'] = $userId;
                $versionMeta['created_by'] = $userId;

                if ($createNewVersion) {
                    $result = $this->model->updateData($id, $data, $items, $versionMeta);
                } else {
                    $result = $this->model->updateDataWithoutVersion($id, $data, $items, $versionMeta);
                }
            } else {
                $data['created_by'] = $userId;
                $versionMeta['created_by'] = $userId;
                $result = $this->model->createData($data, $items, $versionMeta);
            }

            return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
        } catch (\Throwable $e) {
            log_message('error', 'FormulationController::store: ' . $e->getMessage());
            return $this->jsonError('Gagal menyimpan formulasi: ' . $e->getMessage(), 500);
        }
    }

    public function update(int $id)
    {
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
    // VERSIONING
    // ============================================================

    public function versions(int $id)
    {
        if (!canDo('warehouse.formulations.view')) return $this->jsonError('Akses ditolak', 403);

        return $this->response->setJSON(['status' => 'success', 'data' => $this->model->getVersions($id)]);
    }

    public function activateVersion(int $id, int $versionId)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        $result = $this->model->activateVersion($id, $versionId);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    // Toggle status aktif per versi (independen, bisa lebih dari 1 versi aktif)
    public function toggleVersionActive(int $id, int $versionId)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        $active = $this->request->getPost('active') === '1';
        $result = $this->model->toggleVersionActive($id, $versionId, $active);
        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    // ============================================================
    // VERSION DETAIL & PREVIEW
    // ============================================================

    public function getVersionDetail(int $formulationId, int $versionId)
    {
        if (!canDo('warehouse.formulations.view')) return $this->jsonError('Akses ditolak', 403);

        // Gunakan query builder langsung agar bisa mengambil data yang sudah di-soft-delete
        $db = \Config\Database::connect();

        // Ambil data formulasi (termasuk yang sudah dihapus/trash)
        $formulation = $db->table('formulations f')
            ->select('f.*, g.group_name')
            ->join('formulation_groups g', 'g.id = f.group_id', 'left')
            ->where('f.id', $formulationId)
            ->get()->getRowArray();

        if (!$formulation) {
            return $this->jsonError('Formulasi tidak ditemukan', 404);
        }

        // Ambil data versi
        $version = $db->table('formulation_versions v')
            ->select('v.*, u.username as created_by_name')
            ->join('users u', 'u.id = v.created_by', 'left')
            ->where('v.id', $versionId)
            ->where('v.formulation_id', $formulationId)
            ->get()->getRowArray();

        if (!$version) {
            return $this->jsonError('Versi tidak ditemukan', 404);
        }

        // Ambil items menggunakan model (tidak masalah karena items tidak di-soft-delete)
        $items = $this->model->getItems($versionId);

        // Format items
        $formattedItems = [];
        foreach ($items as $item) {
            $formattedItems[] = [
                'id' => $item['id'],
                'composition_type' => $item['composition_type'],
                'chemical_id' => $item['chemical_id'],
                'chemical_name' => $item['chemical_name'] ?? null,
                'chemical_code' => $item['chemical_code'] ?? null,
                'custom_label' => $item['custom_label'] ?? null,
                'percentage' => $item['percentage'] ? (float) $item['percentage'] : 0,
                'unit' => $item['unit'] ?? null,
                'notes' => $item['notes'] ?? null,
                'sort_order' => $item['sort_order'] ?? 0,
            ];
        }

        return $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'formulation' => [
                    'id' => $formulation['id'],
                    'formulation_code' => $formulation['formulation_code'],
                    'formulation_name' => $formulation['formulation_name'],
                    'process_type' => $formulation['process_type'],
                    'process_sub_type' => $formulation['process_sub_type'],
                    'process_sub_type_label' => $formulation['process_sub_type_label'] ?? null,
                    'group_name' => $formulation['group_name'] ?? null,
                    'description' => $formulation['description'] ?? null,
                ],
                'version' => [
                    'id' => $version['id'],
                    'version_no' => $version['version_no'],
                    'status' => $version['status'],
                    'output_percentage' => $version['output_percentage'] ? (float) $version['output_percentage'] : null,
                    'notes' => $version['notes'] ?? null,
                    'created_at' => $version['created_at'],
                    'created_by_name' => $version['created_by_name'] ?? null,
                ],
                'items' => $formattedItems,
            ]
        ]);
    }

    // ============================================================
    // COMPARISON
    // ============================================================

    public function compareVersions()
    {
        if (!canDo('warehouse.formulations.view')) return $this->jsonError('Akses ditolak', 403);

        $formulationId = (int) $this->request->getGet('formulation_id');
        $versionIds = $this->request->getGet('version_ids');

        if (empty($versionIds) || !is_array($versionIds) || count($versionIds) < 2) {
            return $this->jsonError('Pilih minimal 2 versi untuk komparasi', 422);
        }

        $formulation = $this->model->find($formulationId);
        if (!$formulation) {
            return $this->jsonError('Formulasi tidak ditemukan', 404);
        }

        $db = \Config\Database::connect();
        $comparison = [];
        foreach ($versionIds as $vid) {
            $version = $db->table('formulation_versions v')
                ->select('v.*, u.username as created_by_name')
                ->join('users u', 'u.id = v.created_by', 'left')
                ->where('v.id', $vid)
                ->where('v.formulation_id', $formulationId)
                ->get()->getRowArray();

            if ($version) {
                $version['items'] = $this->model->getItems((int) $vid);
                $comparison[] = $version;
            }
        }

        if (count($comparison) < 2) {
            return $this->jsonError('Versi yang dipilih tidak valid', 422);
        }

        return $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'formulation' => $formulation,
                'versions' => $comparison,
            ]
        ]);
    }

    public function compareFormulations()
    {
        if (!canDo('warehouse.formulations.view')) return $this->jsonError('Akses ditolak', 403);

        $formulationIds = $this->request->getGet('formulation_ids');

        if (empty($formulationIds) || !is_array($formulationIds) || count($formulationIds) < 2) {
            return $this->jsonError('Pilih minimal 2 formulasi untuk komparasi', 422);
        }

        $comparison = [];
        foreach ($formulationIds as $fid) {
            $result = $this->model->getData((int) $fid);
            if ($result['status'] === 'success') {
                $comparison[] = $result['data'];
            }
        }

        if (count($comparison) < 2) {
            return $this->jsonError('Formulasi yang dipilih tidak valid', 422);
        }

        return $this->jsonResponse([
            'status' => 'success',
            'data' => $comparison,
        ]);
    }

    public function markUsed(int $id)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        $result = $this->model->updateLastUsed($id);

        return $this->jsonResponse([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Berhasil menandai penggunaan' : 'Gagal menandai penggunaan'
        ]);
    }

    // ============================================================
    // CODE GENERATION
    // ============================================================

    public function generateCodeSuggestion()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        $code = $this->model->generateNextCode();

        return $this->jsonResponse(['status' => 'success', 'code' => $code]);
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
            ->select('f.id, f.formulation_code AS code, f.formulation_name AS name, f.current_version_id, v.output_percentage')
            ->join('formulation_versions v', 'v.id = f.current_version_id', 'left')
            ->where('v.status', 'Active')
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

    public function groupsSelect2()
    {
        if (!canDo('warehouse.formulations.view')) return $this->jsonError('Akses ditolak', 403);

        $search = trim($this->request->getGet('search') ?? '');
        $db      = \Config\Database::connect();
        $builder = $db->table('formulation_groups')
            ->select('id, group_name AS name')
            ->where('status', 'Active')
            ->orderBy('group_name', 'ASC');

        if ($search !== '') {
            $builder->like('group_name', $search);
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $builder->limit(50)->get()->getResultArray()]);
    }

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

    public function subProcessTypes()
    {
        if (!canDo('warehouse.formulations.view')) return $this->jsonError('Akses ditolak', 403);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->getProcessSubTypes(),
        ]);
    }

    public function checkName()
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        $name = trim($this->request->getPost('name') ?? '');
        $excludeId = (int) $this->request->getPost('exclude_id') ?: null;

        if (empty($name)) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Nama tidak boleh kosong'], 422);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('formulations')
            ->where('LOWER(formulation_name)', strtolower($name))
            ->where('deleted_at', null);

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        $exists = $builder->countAllResults() > 0;

        return $this->jsonResponse([
            'status' => 'success',
            'available' => !$exists,
            'message' => $exists ? 'Nama formulasi sudah digunakan' : 'Nama tersedia'
        ]);
    }

    public function deactivateVersion(int $id, int $versionId)
    {
        if (!$this->request->isAJAX()) return $this->jsonError('Method not allowed', 405);
        if (!canDo('warehouse.formulations.manage')) return $this->jsonError('Akses ditolak', 403);

        return $this->toggleVersionActive($id, $versionId, false);
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

    private function getProcessSubTypes(): array
    {
        return [
            ['value' => 'Dyeing',       'label' => 'Dyeing',       'process' => 'Dyeing'],
            ['value' => 'Dipping',      'label' => 'Dipping',      'process' => 'Dyeing'],
            ['value' => 'Dipping 1',    'label' => 'Dipping 1',    'process' => 'Dyeing'],
            ['value' => 'Dipping 2',    'label' => 'Dipping 2',    'process' => 'Dyeing'],
            ['value' => 'Coating',      'label' => 'Coating',      'process' => 'Dyeing'],
            ['value' => 'Spray',        'label' => 'Spray',        'process' => 'Dyeing'],
            ['value' => 'Coating_Foam', 'label' => 'Coating Foam', 'process' => 'Dyeing'],
            ['value' => 'Finishing',    'label' => 'Finishing',    'process' => 'Finishing'],
            ['value' => 'Other',        'label' => 'Lainnya',      'process' => 'Other'],
        ];
    }
}
