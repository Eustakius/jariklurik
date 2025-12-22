<?php

namespace App\Controllers\Api;

use App\Models\UserModel;

class UserController extends BaseController
{
    protected $config;

    public function __construct()
    {
        $this->config = config('Backend');
    }
    public function dataTable()
    {
        $model = new UserModel();
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');
        $filter = [
           'user_type' => $request->getVar('type'),
           'active =' => $request->getVar('status'),
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
    
}
