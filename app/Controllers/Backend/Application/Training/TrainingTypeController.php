<?php

namespace App\Controllers\Backend\Application\Training;

use App\Controllers\BaseController;
use App\Models\TrainingTypeModel;

class TrainingTypeController extends BaseController
{
    protected $auth;
    protected $config;
    protected $configApp;
    protected $model;

    public function __construct()
    {
        $this->config       = config('Backend');
        $this->configApp    = config('App');
        $this->auth         = service('authentication');
        $this->model        = new TrainingTypeModel();
    }
    public function index(): string
    {
        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);
        $permission = getPermissionDirect($this->request->getPath());

        $columns = [
            ['title' => 'Name', 'data' => 'name'],
            ['title' => 'Quota', 'data' => 'quota'],
            ['title' => 'Quota Used', 'data' => 'quotaused'],
            ['title' => 'Group', 'data' => 'group'],
            ['title' => 'Status', 'data' => 'status', 'name' => 'status', 'className' => 'col-status'],
        ];

        return view('Backend/Application/Training/training-type', [
            'config'     => $this->config,
            'datatable'  => [
                'key' => 'trainingtype',
                'api' => $this->configApp->baseBackendURL . '/api/training-type/data-table',
                'fixedcolumns' => 0,
                'token' => $token,
                'selectable' => true,
                'columns' => datatableColumns($columns),
                'permission' => $permission,
                'filters' => [
                    // ['label' => 'Group', 'id' => 'group', 'input' => 'select', 'data' => [
                    //     ["value" => 'JS', "label" => "Job Seekers"],
                    //     ["value" => 'PP', "label" => "Purna PMI"]
                    // ]],
                    ['label' => 'Active', 'id' => 'status', 'input' => 'select', 'data' => [
                        ["value" => 0, "label" => "Inactive"],
                        ["value" => 1, "label" => "Aactive"]
                    ]],
                ]
            ],
            'loading'    => false,
            'error-backend'      => null,
        ]);
    }

    public function show($id = null)
    {
        $company = $this->model->find($id);

        if (!$company) {
            return redirect()->to(pathBack($this->request))->with('error-backend', "Training Type with ID $id not found");
        }

        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/Training/training-type-form', [
            'config' => $this->config,
            'param' => [
                'id' => $id,
                'action' => 'detail',
            ],
            'data' => $company,
            'token' => $token,
            'form' => [
                'route' => str_replace('/', '', $this->request->getPath()),
                'method' => 'PUT'
            ],
            'path' => $this->request->getPath(),
        ]);
    }

    /**
     * POST /company
     */
    public function create()
    {
        $data = $this->request->getPost();

        $id = $this->model->insert($data);
        if (! $id) {
            return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
        }
        
        return redirect()->to('/back-end/training/training-type')->with('message-backend', 'Training Type Create successfully');
    }

    /**
     * PUT/PATCH /company/(:num)
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        if (!$this->model->update($id, $data)) {
            dd($this->model->errors());
            return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/training/training-type')->with('message-backend', 'Training Type updated successfully');
    }

    /**
     * DELETE /company/(:num)
     */
    public function delete($id = null) {
        $this->model->delete($id);

        return redirect()->to('/back-end/training/training-type')->with('message-backend', 'Training Type delete successfully');
    }

    /**
     * GET /company/new
     * (Opsional, kalau butuh form HTML)
     */
    public function new()
    {
        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/Training/training-type-form', [
            'config' => $this->config,
            'param' => [
                'id' => null,
                'action' => 'create',
            ],
            'data' => $this->model->getEmptyRecord(),
            'token' => $token,
            'form' => [
                'route' => str_replace('/new', '', $this->request->getPath()),
                'method' => 'POST'
            ],
            'path' => $this->request->getPath(),
        ]);
    }

    /**
     * GET /company/(:num)/edit
     * (Opsional, kalau butuh form HTML)
     */
    public function edit($id = null)
    {
        $company = $this->model->find($id);

        if (!$company) {
            return redirect()->to(pathBack($this->request))->with('error-backend', "Training Type with ID $id not found");
        }

        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/Training/training-type-form', [
            'config' => $this->config,
            'param' => [
                'id' => $id,
                'action' => 'edit',
            ],
            'data' => $company,
            'token' => $token,
            'form' => [
                'route' => str_replace('/edit', '', $this->request->getPath()),
                'method' => 'PUT'
            ],
            'path' => $this->request->getPath(),
        ]);
    }
    public function massDelete()
    {
        $ids = $this->request->getVar('ids');
        $key = $this->request->getVar('key');

        if (empty($ids) || !is_array($ids)) {
            return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'No items selected.'
            ])->setStatusCode(400);
        }

        $successCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            try {
                $item = $this->model->find($id);
                if (!$item) {
                     continue;
                }

                if ($item->quota_used > 0) {
                     $errors[] = "Item ID $id: Cannot delete because Quota Used > 0 (Used: " . $item->quota_used . ")";
                     continue;
                }

                $this->model->delete($id);
                $successCount++;
                
            } catch (\Exception $e) {
                $errors[] = "Item ID $id: " . $e->getMessage();
            }
        }

        if ($successCount > 0) {
            $msg = "$successCount items deleted successfully.";
            if (count($errors) > 0) {
                 $msg .= " " . count($errors) . " failed. Details: " . implode(', ', $errors);
            }
            return $this->response->setJSON([
                'status' => 'Success',
                'message' => $msg
            ]); 
        } else {
             return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'Failed to delete items. ' . implode(', ', $errors)
            ]);
        }
    }
}
