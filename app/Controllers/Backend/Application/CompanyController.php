<?php

namespace App\Controllers\Backend\Application;

use App\Controllers\BaseController;
use App\Models\CompanyModel;
use App\Models\GroupModel;
use App\Models\UserModel;
use Myth\Auth\Password;

class CompanyController extends BaseController
{
    protected $auth;
    protected $config;
    protected $configApp;
    protected $model;
    protected $modelUser;
    protected $modelGroup;

    public function __construct()
    {
        $this->config       = config('Backend');
        $this->configApp    = config('App');
        $this->auth         = service('authentication');
        $this->model        = new CompanyModel();
        $this->modelUser    = new UserModel();
        $this->modelGroup    = new GroupModel();
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
            ['title' => 'Logo', 'data' => 'logo'],
            ['title' => 'Name', 'data' => 'name'],
            ['title' => 'SIUP', 'data' => 'npwp'],
            ['title' => 'Phone', 'data' => 'phone'],
            ['title' => 'Business Sector', 'data' => 'business_sector'],
            ['title' => 'Address', 'data' => 'address'],
            ['title' => 'Status', 'data' => 'status', 'name' => 'status', 'className' => 'col-status'],
            ['title' => 'Code', 'data' => 'code'],
        ];
        $images = [
            ['column' => 1]
        ];
        return view('Backend/Application/company', [
            'config'     => $this->config,
            'datatable'  => [
                'key' => 'company',
                'api' => $this->configApp->baseBackendURL . '/api/company/data-table',
                'fixedcolumns' => 0,
                'token' => $token,
                'images' => $images,
                'columns' => datatableColumns($columns),
                'filters' => [
                    ['label' => 'Business Sector', 'id' => 'business_sector', 'input' => 'text'],
                    ['label' => 'Address', 'id' => 'address', 'input' => 'text'],
                    ['label' => 'Active', 'id' => 'status', 'input' => 'select', 'data' => [
                        ["value" => 0, "label" => "Inactive"],
                        ["value" => 1, "label" => "Aactive"]
                    ]],
                ],
                'page' => $this->request->getPath(),
                'permission' => $permission
            ],
            'loading'    => false,
            'error-backend'      => null,
        ]);
    }

    public function show($id = null)
    {
        $company = $this->model->find($id);

        if (!$company) {
            return redirect()->to(pathBack($this->request))->with('error-backend', "Company with ID $id not found");
        }

        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/company-form', [
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
        $data['status'] = 1;
        $logoPath = upload_file('logo', 'assets/images/company/logo', $this->request->getPost('name'));
        if ($logoPath) {
            $data['logo'] = $logoPath;
        }

        $data['status'] = 1;
        unset($data['user_id']);
        $id = $this->model->insert($data);
        if (!$id) {
            return redirect()->to(pathBack($this->request, "create"))->withInput()->with('errors-backend', $this->model->errors());
        }
        if (!empty($data['username'])) {

            $payloadUser = $data;
            $payloadUser['active'] = 1;
            $payloadUser['user_type'] = "company";
            $group = $this->modelGroup->where('name', 'company')->first();

            $payloadUser['email'] = null;
            $payloadUser['password_hash'] = Password::hash($payloadUser['password_hash']);
            $idUser = $this->modelUser->insert($payloadUser);
            if (!$idUser) {
                return redirect()->to(pathBack($this->request, "create"))->withInput()->with('errors-backend', $this->modelUser->errors());
            }
            $payloadUser['id'] = $this->modelUser->insertID;

            $this->modelUser->addGroup($payloadUser, $group->id);
            $data['user_id'] = $idUser;
            if (!$this->model->update($this->model->insertID, $data)) {
                return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
            }
        }
        return redirect()->to('/back-end/company')->with('message-backend', 'Company Create successfully');
    }

    /**
     * PUT/PATCH /company/(:num)
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        $logoPath = upload_file('logo', 'assets/images/company/logo', $this->request->getPost('name'));
        if ($logoPath) {
            $data['logo'] = $logoPath;
        }
        if (empty($data['user_id'])) {
            unset($data['user_id']);
        }
        if (!$this->model->update($id, $data)) {
            return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
        }

        if (!empty($data['username'])) {
            $payloadUser = $data;
            $user = $this->modelUser->where('username', $payloadUser['username'] ?? '')->first();
            $group = $this->modelGroup->where('name', 'company')->first();

            $payloadUser['email'] = null;
            $payloadUser['active'] = 1;
            $payloadUser['user_type'] = "company";
            if (empty($data['user_id'])) {
                $payloadUser['password_hash'] = Password::hash($payloadUser['password_hash']);
                $idUser = $this->modelUser->insert($payloadUser);
                if (!$idUser) {
                    return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->modelUser->errors());
                }
                $payloadUser['id'] = $this->modelUser->insertID;
                $this->modelUser->addGroup($payloadUser, $group->id);
                $data['user_id'] = $this->modelUser->insertID;

                if (!$this->model->update($id, $data)) {
                    return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
                }
            } else {
                $payloadUser['id'] = $data['user_id'];

                if (isset($payloadUser['password_hash'])) {
                    $payloadUser['password_hash'] = Password::hash($payloadUser['password_hash']);
                } else {
                    $payloadUser['password_hash'] = $user->password_hash;
                }
                if (! $this->modelUser->update((int)$data['user_id'], $payloadUser)) {
                    return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->modelUser->errors());
                }
                $this->modelUser->clearGroup($payloadUser);
                $this->modelUser->addGroup($payloadUser, $group->id);
            }
        }
        return redirect()->to('/back-end/company')->with('message-backend', 'Company updated successfully');
    }

    /**
     * DELETE /company/(:num)
     */
    public function delete($id = null)
    {
        $company = $this->model->find($id);

        if (!$company) {
            return redirect()->to(pathBack($this->request))->with('error-backend', "Company with ID $id not found");
        }

        if (!$this->model->delete($id)) {
            return redirect()->to(pathBack($this->request))->with('errors-backend', $this->model->errors());
        }

        if (!$this->modelUser->delete($company->user_id)) {
            return redirect()->to(pathBack($this->request))->with('errors-backend', $this->modelUser->errors());
        }

        return redirect()->to('/back-end/company')->with('message-backend', 'Company delete successfully');
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

        return view('Backend/Application/company-form', [
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
            return redirect()->to(pathBack($this->request))->with('error-backend', "Company with ID $id not found");
        }

        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/company-form', [
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
}
