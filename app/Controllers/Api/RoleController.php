<?php

namespace App\Controllers\Api;

use App\Models\GroupModel;

class RoleController extends BaseController
{
    protected $config;

    public function __construct()
    {
        $this->config = config('Backend');
    }
    public function dataTable()
    {        
        $model = new GroupModel();
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');
        $data            = $model->getData($search, $order, $columns, $start, $length);
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
