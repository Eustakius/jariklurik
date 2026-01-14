<?php

namespace App\Controllers\Backend\Administrator;

use App\Controllers\BaseController;
use App\Models\GroupModel;
use App\Models\UserModel;

class RoleController extends BaseController
{
    protected $auth;
    protected $config;
    protected $configApp;
    protected $model;

    public function __construct()
    {
        $this->config = config('Backend');
        $this->configApp = config('App');
        $this->auth   = service('authentication');
        $this->model     = new GroupModel();
    }

    /**
     * Check if the role is Administrator (system-critical role)
     * @param int $id Role ID
     * @return bool
     */
    private function isAdministratorRole($id)
    {
        $role = $this->model->find($id);
        return $role && strtolower($role->name) === 'administrator';
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
            ['title' => 'Description', 'data' => 'description'],
        ];

        return view('Backend/Administrator/role', [
            'config'     => $this->config,
            'datatable'  => [
                'key' => 'role',
                'api' => $this->configApp->baseBackendURL . '/api/role/data-table',
                'fixedcolumns' => 0,
                'token' => $token,
                'columns' => datatableColumns($columns),
                'permission' => $permission,
                'filters' => []
            ],
            'loading'    => false,
            'error-backend'      => null,
        ]);
    }

    public function show($id = null)
    {
        $role = $this->model->find($id);

        if (!$role) {
            return redirect()->to(pathBack($this->request))->with('error-backend', "Role with ID $id not found");
        }

        // Check if this is Administrator role (read-only)
        $isAdministrator = $this->isAdministratorRole($id);
        
        return view('Backend/Administrator/role-form', [
            'config' => $this->config,
            'param' => [
                'id' => $id,
                'action' => 'detail',
                'isAdministrator' => $isAdministrator, // Pass flag to view
            ],
            'data' => $role,
            'datarole' => $this->model->getPermissionsForGroupSet($id),
            'form' => [
                'route' => str_replace('/', '', $this->request->getPath()),
                'method' => 'PUT'
            ],
            'path' => $this->request->getPath(),
        ]);
    }

    /**
     * POST /role
     */
    public function create()
    {
        $data = $this->request->getPost();        
        $id = $this->model->insert($data); 
        if (! $id) {
            return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
        }
        if(!empty($data['permissions']))
        {
            foreach($data['permissions'] as $permissions){
                foreach($permissions as $permission){
                    $this->model->addPermissionToGroup((int)$permission,$id);
                }
            }
        }
        return redirect()->to('/back-end/administrator/role')->with('message-backend', 'Role Create successfully');
    }

    /**
     * PUT/PATCH /role/(:num)
     */
    public function update($id = null)
    {
        // PROTECTION: Prevent updating Administrator role
        if ($this->isAdministratorRole($id)) {
            return redirect()->to('/back-end/administrator/role')
                ->with('error-backend', 'Cannot modify Administrator role. This is a system-critical role.');
        }

        $data = $this->request->getPost();
        $data['id'] = $id;

        if (!$this->model->update($id, $data)) {
            return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
        }

        $this->model->removeAllPermissionFromGroup($id);

        if(!empty($data['permissions']))
        {
            foreach($data['permissions'] as $permissions){
                foreach($permissions as $permission){
                    $this->model->addPermissionToGroup((int)$permission,$id);
                }
            }
        }
        return redirect()->to('/back-end/administrator/role')->with('message-backend', 'Role updated successfully');
    }

    /**
     * DELETE /role/(:num)
     */
    public function delete($id = null)
    {
        // PROTECTION: Prevent deleting Administrator role
        if ($this->isAdministratorRole($id)) {
            return redirect()->to('/back-end/administrator/role')
                ->with('error-backend', 'Cannot delete Administrator role. This is a system-critical role.');
        }

        if (!$this->model->delete($id)) {
            return redirect()->to(pathBack($this->request))->with('errors-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/administrator/role')->with('message-backend', 'Role deleted successfully');
    }

    /**
     * GET /role/new
     * (Opsional, kalau butuh form HTML)
     */
    public function new()
    {
        return view('Backend/Administrator/role-form', [
            'config' => $this->config,
            'param' => [
                'id' => null,
                'action' => 'create',
            ],
            'data' => $this->model->getEmptyRecord(),
            'datarole' => $this->model->getPermissionsForGroupSet(null),
            'form' => [
                'route' => str_replace('/new', '', $this->request->getPath()),
                'method' => 'POST'
            ],
            'path' => $this->request->getPath(),
        ]);
    }

    /**
     * GET /role/(:num)/edit
     * (Opsional, kalau butuh form HTML)
     */
    public function edit($id = null)
    {
        // PROTECTION: Prevent editing Administrator role
        if ($this->isAdministratorRole($id)) {
            return redirect()->to('/back-end/administrator/role')
                ->with('error-backend', 'Cannot edit Administrator role. This is a system-critical role. You can only view it.');
        }

        $role = $this->model->find($id);

        if (!$role) {
            return redirect()->to(pathBack($this->request))->with('error-backend', "Role with ID $id not found");
        }
        
        return view('Backend/Administrator/role-form', [
            'config' => $this->config,
            'param' => [
                'id' => $id,
                'action' => 'edit',
            ],
            'data' => $role,
            'datarole' => $this->model->getPermissionsForGroupSet($id),
            'form' => [
                'route' => str_replace('/edit', '', $this->request->getPath()),
                'method' => 'PUT'
            ],
            'path' => $this->request->getPath(),
        ]);
    }
}
