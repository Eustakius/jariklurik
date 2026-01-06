<?php

namespace App\Controllers\Api;

use App\Models\TrainingTypeModel;

class TrainingTypeController extends BaseController
{
    protected $config;

    public function __construct()
    {
        $this->config = config('Backend');
    }

    public function show($id = null)
    {
        $model = new TrainingTypeModel();
        $data = $model->find($id);

        if (!$data) {
            return $this->failNotFound('Training Type not found');
        }

        return $this->response->setJSON($data);
    }
    public function dataTable()
    {
        $model = new TrainingTypeModel();
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');
        $filter = [
           'name' => $request->getVar('name'),           
           'status =' => $request->getVar('status'),
        //    "case when is_jobseekers = 1 then 'js' when is_purna_pmi = 1 then 'pp' else '' end" => $request->getVar('group'),
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

        $model = new TrainingTypeModel();
        $model->where('quota > quota_used');
        
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

        $total = $model->like('name', $term??"")->countAllResults();

        return $this->respond([
            'results' => $results,
            'pagination' => ['more' => ($page * $perPage) < $total]
        ]);
    }

    public function massDelete()
    {
        $request = service('request');
        // Handle both JSON and Form Data
        $data = $request->getJSON(true);
        $ids = $data['ids'] ?? $request->getVar('ids');
        $ids = $ids ?? [];

        if (empty($ids)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No items selected.'
            ])->setStatusCode(400);
        }

        $successCount = 0;
        $errors = [];
        $model = new TrainingTypeModel();

        foreach ($ids as $id) {
            try {
                $item = $model->find($id);
                if (!$item) {
                    continue;
                }
                
                if ($item->quota_used > 0) {
                     $errors[] = "Item '{$item->name}': Cannot delete because it has {$item->quota_used} active applicants.";
                     continue;
                }

                $model->delete($id);
                $successCount++;
                
            } catch (\Exception $e) {
                $errors[] = "Item ID $id: " . $e->getMessage();
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "$successCount items deleted.",
            'errors' => $errors
        ]);
    }
}
