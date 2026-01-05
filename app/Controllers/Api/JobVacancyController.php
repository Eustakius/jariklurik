<?php

namespace App\Controllers\Api;

use App\Entities\JobVacancy;
use App\Models\GroupModel;
use App\Models\JobVacancyModel;

class JobVacancyController extends BaseController
{
    protected $config;
    protected $auth;

    public function __construct()
    {
        $this->config = config('Backend');
        $this->auth = service('authentication');
    }
    public function datatable()
    {
        $model   = new JobVacancyModel();
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');

        $filter = [
            'country_id =' => $request->getVar('country'),
            'company_id =' => $request->getVar('company'),
            'selection_date >=' => $request->getVar('selectionfrom'),
            'selection_date <=' => $request->getVar('selectionto'),
            'CONCAT(duration,duration_type)' => $request->getVar('duration'),
            'job_vacancy.status =' => $request->getVar('status'),
            'job_vacancy.is_pin =' => $request->getVar('pinned'),
        ];
        $data            = $model->getData($search, $order, $columns, $start, $length, $filter);
        $recordsFiltered = $model->countFiltered($search);
        $recordsTotal    = $model->countAll();
        $formatted = array_map(fn($item) => $item->formatDataTableModel(), $data);

        return $this->response->setJSON([
            'draw'            => intval($draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $formatted,
        ]);
    }
    public function dataListFrontend()
    {
        $request = service('request');
        $today = date('Y-m-d');

        $page     = $request->getVar('page') ?? 1;
        $order    = $request->getVar('order') ?? 'DESC';
        $company  = $request->getVar('company');
        $country  = $request->getVar('country');
        $perPage  = 10;

        $model = new JobVacancyModel();

        // 🔹 Tambahkan join untuk filter company
        $model->join('companies', 'companies.id = job_vacancy.company_id', 'left');
        $model->join('countries', 'countries.id = job_vacancy.country_id', 'left');

        // 🔹 Filter umum
        $model->where('job_vacancy.status', 1)
            ->where('job_vacancy.selection_date >=', $today);

        // 🔹 Jika ada filter nama perusahaan
        if (!empty($company)) {
            $model->like('companies.name', $company);
        }
        if (!empty($country)) {
            $model->like('countries.name', $country);
        }
        // 🔹 Hitung total data
        $total = $model->countAllResults(false);
        $model->select('job_vacancy.*');
        // 🔹 Ambil data (paginate tetap bisa digunakan)
        $items = $model->orderBy('job_vacancy.is_pin', 'desc')->orderBy('job_vacancy.id', $order)
            ->paginate($perPage, 'default', $page);

        // 🔹 Format hasil
        $formatted = array_map(function ($item) {
            return $item->formatDataFrontendModel();
        }, $items);

        $totalPages = (int) ceil($total / $perPage);

        $payload = [
            'pagination' => [
                'hasnext'   => ($page * $perPage) < $total,
                'hasprev'   => $page > 1,
                'page'      => (int) $page,
                'totalpage' => $totalPages,
            ],
            'data' => $formatted,
        ];

        return $this->respond($payload);
    }

    public function dataTableUpdate()
    {
        $request = service('request');
        $data = $request->getRawInput();
        helper('session');
        $model = new JobVacancyModel();
        $data['is_pin'] = $data['pinned'];

        /*
        $model->where('is_pin', 1);
        if ($data['pinned'] == 1) {
            $total = $model->countAllResults(false);
            if ($total >= 5) {
                $payload = [
                    'status' => 'Bad Request',
                    'message' => 'Limit Pinned only 5',
                ];
                return $this->respond($payload);
            }
        }
        */

        $model = new JobVacancyModel();
        if (!$model->update($data['id'], $data)) {
            return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
        }
        $payload = [
            'status' => 'Success',
        ];

        return $this->respond($payload);
    }

    public function select2()
    {
        $request = service('request');

        $id   = $request->getVar('id');
        $term = $request->getVar('term');
        $page = $request->getVar('page') ?? 1;
        $perPage = 10;

        $model = new JobVacancyModel();
        $table = $model->table; // pastikan nama tabel utama benar

        // Tambahkan kolom dari tabel relasi - Show ALL job vacancies with status indicator
        $model->select("{$table}.*, companies.name AS company_name, countries.name AS country_name")
            ->join('companies', "companies.id = {$table}.company_id", 'left')
            ->join('countries', "countries.id = {$table}.country_id", 'left');
            // Removed status filter - now showing both active and inactive

        if ($this->auth->user()->user_type == 'company') {
            $companyModel = new \App\Models\CompanyModel();
            $company = $companyModel->where('user_id', $this->auth->user()->id)->first();
            if ($company) {
                $model->where("{$table}.company_id", $company->id);
            } else {
                 // User is 'company' type but has no company record? Hide everything.
                 $model->where("{$table}.company_id", -1);
            }
        }

        // Jika ada ID spesifik
        if (!empty($id)) {
            $model->where("{$table}.id", $id);
        }

        // Filter pencarian
        if (!empty($term)) {
            $model->groupStart()
                ->like("{$table}.position", $term, 'both', null, true)
                ->orLike("countries.name", $term, 'both', null, true)
                ->orLike("companies.name", $term, 'both', null, true)
                ->groupEnd();
        }

        // Ambil data hasil paginasi
        $jobVacancys = $model->paginate($perPage, 'default', $page);

        // Ubah ke format Select2 dengan indikator status dinamis
        $results = array_map(function ($item) {
            $text = trim(($item->position ?? '') . ' - ' . ($item->company_name ?? '') . ' - ' . ($item->country_name ?? ''));
            // Add dynamic status indicator
            if ($item->status == 1) {
                $text .= ' [✓ Active]';
            } else {
                $text .= ' [✕ Inactive]';
            }
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        }, $jobVacancys);

        // Hitung total hasil - Count ALL job vacancies
        $countModel = new JobVacancyModel();
        $countModel->join('companies', "companies.id = {$table}.company_id", 'left')
            ->join('countries', "countries.id = {$table}.country_id", 'left');
            // Removed status filter

        if (!empty($term)) {
            $countModel->groupStart()
                ->like("{$table}.position", $term)
                ->orLike("countries.name", $term)
                ->orLike("companies.name", $term)
                ->groupEnd();
        }

        $total = $countModel->countAllResults();

        return $this->respond([
            'results' => $results,
            'pagination' => ['more' => ($page * $perPage) < $total]
        ]);
    }
    public function show($id = null)
    {
        $model = new JobVacancyModel();
        $data = $model->find($id);

        if (!$data) {
            return $this->failNotFound('Job Vacancy not found');
        }
        
        // Format response to include company_name and country_name for easier frontend consumption
        $response = [
            'id' => $data->id,
            'position' => $data->position,
            'company_name' => $data->company?->name ?? null,
            'country_name' => $data->country?->name ?? null,
            'company_id' => $data->company_id,
            'country_id' => $data->country_id,
            'duration' => $data->duration,
            'duration_type' => $data->duration_type,
            'selection_date' => $data->selection_date,
            'status' => $data->status,
            'required_documents' => $data->required_documents,
        ];
        
        return $this->respond($response);
    }
}
