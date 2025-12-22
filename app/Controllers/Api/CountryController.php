<?php

namespace App\Controllers\Api;

use App\Models\CountryModel;

class CountryController extends BaseController
{
    protected $config;

    public function __construct()
    {
        $this->config = config('Backend');
    }
    public function select2()
    {
        $request = service('request');

        $id   = $request->getVar('id');
        $term  = $request->getVar('term');
        $page = $request->getVar('page') ?? 1;
        $perPage = 10;

        $model = new CountryModel();
        if(isset($id)){

            $countries = $model->where('id', $id)
                           ->paginate($perPage, 'default', $page);
            $total = $model->where('id', $id)->countAllResults();
        }
        else{
            $countries = $model->like('name', $term??"")
                           ->paginate($perPage, 'default', $page);

            $total = $model->like('name', $term??"")->countAllResults();
        }

        $results = array_map(function($country) {
            return [
                'id'   => $country->id,
                'text' => $country->name,
            ];
        }, $countries);
        
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

        $model = new CountryModel();
        $model->join('job_vacancy', 'countries.id = job_vacancy.country_id', 'inner');

        $model->select('countries.*')->distinct();
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
