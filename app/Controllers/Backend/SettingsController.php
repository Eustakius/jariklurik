<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\CompanyModel;
use App\Models\SettingModel;
use App\Models\UserModel;
use Myth\Auth\Password;

class SettingsController extends BaseController
{
    protected $auth;
    protected $config;
    protected $configApp;
    protected $model;
    protected $modelCompany;

    public function __construct()
    {
        $this->config = config('Backend');
        $this->configApp = config('App');
        $this->auth = service('authentication');
        $this->model = new SettingModel();
        $this->modelCompany = new CompanyModel();
    }
    public function index()
    {
        $this->model->getSettingSet();
        $settings = $this->model->findAll();
        
        return view('Backend/setting', [
            'config' => $this->config,
            'param' => [
                'action' => 'edit',
                'back' => false,
            ],
            'data' => json_decode(json_encode(array_column($settings, 'values', 'key'))),
            'form' => [
                'route' => $this->request->getPath(). "/0" ,
                'method' => 'PUT'
            ],
            'path' => $this->request->getPath(),
        ]);
    }

    /**
     * PUT/PATCH /user/(:num)
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;
        
        $logoPath = upload_file('file_statement_letter', 'file', 'file_statement_letter');
        if ($logoPath) {
            $this->model->where('key', 'file_statement_letter')->set(['values' => $logoPath])->update();
        }

        return redirect()->to('/back-end/administrator/setting')->with('message-backend', 'Setting updated successfully');
    }
}
