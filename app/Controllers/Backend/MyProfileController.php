<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\CompanyModel;
use App\Models\UserModel;
use Myth\Auth\Password;

class MyProfileController extends BaseController
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
        $this->model = new UserModel();
        $this->modelCompany = new CompanyModel();
    }
    public function index()
    {
        $user = $this->model->find($this->auth->user()->id);

        if (!$user) {
            return redirect()->back()->with('error', "User not found");
        }

        $user->password_hash = '';

        if ($this->auth->user()->user_type == "admin") {
            return view('Backend/my-profile-admin', [
                'config' => $this->config,
                'param' => [
                    'id' => $this->auth->user()->id,
                    'action' => 'edit',
                    'back' => false,
                ],
                'title' => 'My Profile',
                'data' => $user,
                'form' => [
                    'route' => $this->request->getPath() . "/" . $this->auth->user()->id,
                    'method' => 'PUT'
                ],
                'path' => $this->request->getPath(),
            ]);
        } else if ($this->auth->user()->user_type == "company") {
            $company = $this->modelCompany->where('user_id', $this->auth->user()->id)->first();

            if (!$company) {
                return redirect()->back()->with('error', "Company  not found");
            }
            return view('Backend/my-profile-company', [
                'config' => $this->config,
                'param' => [
                    'id' => $company->id,
                    'action' => 'edit',
                    'back' => false,
                ],
                'title' => 'My Profile',
                'data' => $company,
                'form' => [
                    'route' => $this->request->getPath() . "/" . $company->id,
                    'method' => 'PUT'
                ],
                'path' => $this->request->getPath(),
            ]);
        }
        return null;
    }

    /**
     * PUT/PATCH /user/(:num)
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        $logoPath = upload_file('logo', $this->auth->user()->user_type == "company" ? 'assets/images/company/logo' : 'assets/images/user', $this->request->getPost('name'));
        if ($logoPath) {
            $data['logo'] = $logoPath;
        }


        if ($this->auth->user()->user_type == "admin") {

            $user = $this->model->find($id);
            if (isset($data['password_hash'])) {
                $data['password_hash'] = Password::hash($this->request->getPost('password_hash'));
            } else {
                $data['password_hash'] = $user->password_hash;
            }
            if (!$this->model->update($id, $data)) {
                dd($this->model->errors());
                return redirect()->back()->with('errors-backend', $this->model->errors());
            }
        } else if ($this->auth->user()->user_type == "company") {
            if (!$this->modelCompany->update($id, $data)) {
                return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->modelCompany->errors());
            }

            if (!empty($data['username'])) {
                $payloadUser = $data;
                $user = $this->model->where('username', $payloadUser['username'] ?? '')->first();

                $payloadUser['email'] = null;
                $payloadUser['active'] = 1;
                $payloadUser['user_type'] = "company";
                $payloadUser['id'] = $data['user_id'];

                if (isset($payloadUser['password_hash'])) {
                    $payloadUser['password_hash'] = Password::hash($payloadUser['password_hash']);
                } else {
                    $payloadUser['password_hash'] = $user->password_hash;
                }
                if (! $this->model->update((int)$data['user_id'], $payloadUser)) {
                    redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
                }
                
            }
        }
        return redirect()->to('/back-end/my-profile')->with('message-backend', 'Profil updated successfully');
    }
}
