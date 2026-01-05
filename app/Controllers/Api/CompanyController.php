<?php

namespace App\Controllers\Api;

use App\Models\CompanyModel;

class CompanyController extends BaseController
{
    protected $config;
    protected $auth;

    public function __construct()
    {
        $this->config = config('Backend');
        $this->auth         = service('authentication');
    }
    public function dataTable()
    {
        $model = new CompanyModel();
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');
        $filter = [
            'name' => $request->getVar('name'),
            'business_sector' => $request->getVar('business_sector'),
            'address' => $request->getVar('address'),
            'status =' => $request->getVar('status'),
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

    public function select2()
    {
        $request = service('request');

        $id   = $request->getVar('id');
        $term  = $request->getVar('term');
        $page = $request->getVar('page') ?? 1;
        $perPage = 10;

        $model = new CompanyModel();
        if ($this->auth->user()->user_type == 'company') {
            $companyModel = new CompanyModel();

            $company = $companyModel->where('user_id', $this->auth->user()->id)->first();
            if (!empty($company)) {
                $id = $company->id;
            }
        }
        if (isset($id)) {
            $companies = $model->where('id', $id)
                ->paginate($perPage, 'default', $page);
            $total = $model->where('id', $id)->countAllResults();
        } else {
            $companies = $model->like('name', $term ?? "")
                ->paginate($perPage, 'default', $page);
            $total = $model->like('name', $term??"")->countAllResults();
        }

        $results = array_map(function ($company) {
            $text = $company->name;
            // Add dynamic status indicator
            if ($company->status == 1) {
                $text .= ' [✓ Active]';
            } else {
                $text .= ' [✕ Inactive]';
            }
            return [
                'id'   => $company->id,
                'text' => $text,
            ];
        }, $companies);


        return $this->respond([
            'results' => $results,
            'pagination' => ['more' => ($page * $perPage) < $total]
        ]);
    }

    public function autocomplate()
    {
        $request = service('request');

        $term = $request->getVar('q'); 
        $page = (int) ($request->getVar('page') ?? 1);
        $perPage = 10;

        $model = new CompanyModel();
        $model->join('job_vacancy', 'companies.id = job_vacancy.company_id', 'inner');

        $model->select('companies.*')->distinct();

        // Query data berdasarkan nama
        $builder = $model->like('name', $term ?? '')
            ->orderBy('name', 'ASC');

        $total = $builder->countAllResults(false); 
        $results = $builder->paginate($perPage, 'default', $page);

        $data = array_map(function ($company) {
            return $company->name;
        }, $results);

        return $this->respond([
            'data' => $data,
            'has_more' => ($page * $perPage) < $total
        ]);
    }
}
