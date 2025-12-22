<?php

namespace App\Controllers\Backend\Administrator;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Myth\Auth\Password;

class UserController extends BaseController
{
    protected $auth;
    protected $config;
    protected $configApp;
    protected $model;

    public function __construct()
    {
        $this->config = config('Backend');
        $this->configApp = config('App');
        $this->auth = service('authentication');
        $this->model = new UserModel();
    }
    public function index(): string
    {
        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'        => $this->request->getIPAddress(),
            'user_agent' => (string) $this->request->getUserAgent(),
            'username'  => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);
        $permission = getPermissionDirect($this->request->getPath());

        $columns = [
            ['title' => 'Name', 'data' => 'name'],
            ['title' => 'User Name', 'data' => 'username'],
            ['title' => 'email', 'data' => 'email'],
            ['title' => 'Type', 'data' => 'user_type'],
            ['title' => 'Status', 'data' => 'active', 'name' => 'active', 'className' => 'col-status'],
        ];

        return view('Backend/Administrator/user', [
            'config'     => $this->config,
            'datatable'  => [
                'key' => 'user',
                'api' => $this->configApp->baseBackendURL . '/api/user/data-table',
                'fixedcolumns' => 0,
                'token' => $token,
                'columns' => datatableColumns($columns),
                'permission' => $permission,
                'filters' => [
                    ['label' => 'Type', 'id' => 'type', 'input' => 'select', 'data' => [
                        ["value" => 'admin', "label" => "Admin"],
                        ["value" => 'company', "label" => "Company"]
                    ]],
                    ['label' => 'Active', 'id' => 'status', 'input' => 'select', 'data' => [
                        ["value" => 0, "label" => "Inactive"],
                        ["value" => 1, "label" => "Aactive"]
                    ]],
                ],
            ],
            'loading'    => false,
            'error-backend'      => null,
        ]);
    }

    public function show($id = null)
    {
        $user = $this->model->find($id);

        if (!$user) {
            return redirect()->to(pathBack($this->request))->with('error-backend', "User with ID $id not found");
        }

        $user->password_hash = '';
        return view('Backend/Administrator/user-form', [
            'config' => $this->config,
            'param' => [
                'id' => $id,
                'action' => 'detail',
            ],
            'data' => $user,
            'form' => [
                'route' => str_replace('/', '', $this->request->getPath()),
                'method' => 'PUT'
            ],
            'path' => $this->request->getPath(),
        ]);
    }

    /**
     * POST /user
     */
    public function create()
    {
        $data = $this->request->getPost();

        $data['user_type'] = 'admin';
        $data['active'] = 1;
        $data['password_hash'] = Password::hash($this->request->getPost('password_hash'));
        $id = $this->model->insert($data);

        if (! $id) {
            return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
        }

        $data['id'] = $id;
        if (!empty($data['roles'])) {
            foreach ($data['roles'] as $role) {
                $this->model->addGroup($data, $role);
            }
        }
        return redirect()->to('/back-end/administrator/user')->with('message-backend', 'User Create successfully');
    }

    /**
     * PUT/PATCH /user/(:num)
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        $user = $this->model->find($id);
        if (isset($data['password_hash'])) {
            $data['password_hash'] = Password::hash($this->request->getPost('password_hash'));
        } else {
            $data['password_hash'] = $user->password_hash;
        }
        if (!$this->model->update($id, $data)) {
            return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
        }

        $this->model->clearGroup($data);

        if (!empty($data['roles'])) {
            foreach ($data['roles'] as $role) {
                $this->model->addGroup($data, $role);
            }
        }
        return redirect()->to('/back-end/administrator/user')->with('message-backend', 'User updated successfully');
    }

    /**
     * DELETE /user/(:num)
     */
    public function delete($id = null)
    {
        if (!$this->model->delete($id)) {
            return redirect()->to(pathBack($this->request))->with('errors-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/administrator/user')->with('message-backend', 'User deleted successfully');
    }

    /**
     * GET /user/new
     * (Opsional, kalau butuh form HTML)
     */
    public function new()
    {
        return view('Backend/Administrator/user-form', [
            'config' => $this->config,
            'param' => [
                'id' => null,
                'action' => 'create',
            ],
            'data' => $this->model->getEmptyRecord(),
            'form' => [
                'route' => str_replace('/new', '', $this->request->getPath()),
                'method' => 'POST'
            ],
            'path' => $this->request->getPath(),
        ]);
    }

    /**
     * GET /user/(:num)/edit
     * (Opsional, kalau butuh form HTML)
     */
    public function edit($id = null)
    {
        $user = $this->model->find($id);

        if (!$user) {
            return redirect()->to(pathBack($this->request))->with('error-backend', "User with ID $id not found");
        }
        $user->password_hash = '';
        return view('Backend/Administrator/user-form', [
            'config' => $this->config,
            'param' => [
                'id' => $id,
                'action' => 'edit',
            ],
            'data' => $user,
            'form' => [
                'route' => str_replace('/edit', '', $this->request->getPath()),
                'method' => 'PUT'
            ],
            'path' => $this->request->getPath(),
        ]);
    }
}
