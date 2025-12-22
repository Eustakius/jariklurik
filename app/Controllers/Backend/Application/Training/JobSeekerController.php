<?php

namespace App\Controllers\Backend\Application\Training;

use App\Controllers\BaseController;
use App\Models\JobSeekerModel;
use App\Models\TrainingTypeModel;

class JobSeekerController extends BaseController
{
    protected $auth;
    protected $config;
    protected $configApp;
    protected $model;
    protected $modelTriningType;

    public function __construct()
    {
        $this->config       = config('Backend');
        $this->configApp    = config('App');
        $this->auth         = service('authentication');
        $this->model        = new JobSeekerModel();
        $this->modelTriningType  = new TrainingTypeModel();
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
            ['title' => 'Submited', 'data' => 'created_at'],
            ['title' => 'Gender', 'data' => 'gender'],
            ['title' => 'Birth of Day', 'data' => 'bod'],
            ['title' => 'Education Level', 'data' => 'educationlevel'],
            ['title' => 'Training Type', 'data' => 'training_type'],
            ['title' => 'Email', 'data' => 'email'],
            ['title' => 'Address', 'data' => 'address'],
            ['title' => 'Phone', 'data' => 'phone'],
            ['title' => 'Attachment', 'data' => 'file_statement'],
            ['title' => 'Code', 'data' => 'code'],
        ];

        $files = [
            ['column' => 10]
        ];
        return view('Backend/Application/Training/job-seeker', [
            'config'    => $this->config,
            'token' => $token,
            'tabs'      => [
                [
                    'key' => 'new',
                    'label' => 'New',
                    'datatable'  => [
                        'key' => 'new',
                        'api' => $this->configApp->baseBackendURL . '/api/job-seeker/data-table-new',
                        'fixedcolumns' => 0,
                        'token' => $token,
                        'files' => $files,
                        'columns' => datatableColumns($columns),
                        'page' => $this->request->getPath(),
                        'permission' => array_filter($permission, fn($p) => str_ends_with($p['permission'], '.approve') || str_ends_with($p['permission'], '.reject')),
                        'filters' => [
                            ['label' => 'Gender', 'id' => 'gendernew', 'input' => 'select', 'data' => [
                                ["value" => 'M', "label" => "Male"],
                                ["value" => 'F', "label" => "Female"]
                            ]],
                            ['label' => 'Education Level', 'id' => 'educationlevelnew', 'input' => 'select', 'data' => config('Backend')->educationLevel],
                            ['label' => 'Training Type', 'id' => 'trainingnew', 'input' => 'select', 'api' => 'back-end/api/training-type/select'],
                            ['label' => 'Submit From', 'id' => 'submitfromnew', 'input' => 'date', 'type' => 'date'],
                            ['label' => 'Submit To', 'id' => 'submittonew', 'input' => 'date', 'type' => 'date'],
                        ],
                    ],
                ],
                [
                    'key' => 'approved',
                    'label' => 'Approved',
                    'datatable'  => [
                        'key' => 'approved',
                        'api' => $this->configApp->baseBackendURL . '/api/job-seeker/data-table-approved',
                        'fixedcolumns' => 0,
                        'token' => $token,
                        'files' => $files,
                        'columns' => datatableColumns($columns),
                        'page' => $this->request->getPath(),
                        'permission' => array_filter($permission, fn($p) => str_ends_with($p['permission'], '.revert')),
                        'filters' => [
                            ['label' => 'Gender', 'id' => 'genderapproved', 'input' => 'select', 'data' => [
                                ["value" => 'M', "label" => "Male"],
                                ["value" => 'F', "label" => "Female"]
                            ]],
                            ['label' => 'Education Level', 'id' => 'educationlevelapproved', 'input' => 'select', 'data' => config('Backend')->educationLevel],
                            ['label' => 'Training Type', 'id' => 'trainingapproved', 'input' => 'select', 'api' => 'back-end/api/training-type/select'],
                            ['label' => 'Submit From', 'id' => 'submitfromapproved', 'input' => 'date', 'type' => 'date'],
                            ['label' => 'Submit To', 'id' => 'submittoapproved', 'input' => 'date', 'type' => 'date'],
                        ],
                    ],
                ],
                [
                    'key' => 'rejected',
                    'label' => 'Rejected',
                    'datatable'  => [
                        'key' => 'rejected',
                        'api' => $this->configApp->baseBackendURL . '/api/job-seeker/data-table-rejected',
                        'fixedcolumns' => 0,
                        'token' => $token,
                        'files' => $files,
                        'columns' => datatableColumns($columns),
                        'page' => $this->request->getPath(),
                        'permission' => array_filter($permission, fn($p) => str_ends_with($p['permission'], '.revert')),
                        'filters' => [
                            ['label' => 'Gender', 'id' => 'genderrejected', 'input' => 'select', 'data' => [
                                ["value" => 'M', "label" => "Male"],
                                ["value" => 'F', "label" => "Female"]
                            ]],
                            ['label' => 'Education Level', 'id' => 'educationlevelrejected', 'input' => 'select', 'data' => config('Backend')->educationLevel],
                            ['label' => 'Training Type', 'id' => 'trainingrejected', 'input' => 'select', 'api' => 'back-end/api/training-type/select'],
                            ['label' => 'Submit From', 'id' => 'submitfromrejected', 'input' => 'date', 'type' => 'date'],
                            ['label' => 'Submit To', 'id' => 'submittorejected', 'input' => 'date', 'type' => 'date'],
                        ],
                    ],
                ],
            ],
            'loading'    => false,
            'error-backend'      => null,
        ]);
    }

    public function show($id = null)
    {
        $company = $this->model->find($id);

        if (!$company) {
            return redirect()->to('/back-end/training/job-seekers')->with('error-backend', "Job Seeker with ID $id not found");
        }

        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/job-seeker-form', [
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

        $logoPath = upload_file('logo', 'assets/images/company/logo', $this->request->getPost('name'));
        if ($logoPath) {
            $data['logo'] = $logoPath;
        }

        $id = $this->model->insert($data);
        if (! $id) {
            return redirect()->to('/back-end/training/job-seekers')->with('errors-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/company')->with('message-backend', 'Job Seeker Create successfully');
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

        if (!$this->model->update($id, $data)) {
            dd($this->model->errors());
            return redirect()->to('/back-end/training/job-seekers')->with('errors-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/company')->with('message-backend', 'Job Seeker updated successfully');
    }

    /**
     * DELETE /company/(:num)
     */
    public function delete($id = null) {}

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

        return view('Backend/Application/job-seeker-form', [
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
            return redirect()->to('/back-end/training/job-seekers')->with('error-backend', "Job Seeker with ID $id not found");
        }

        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/job-seeker-form', [
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

    /**
     * GET /company/(:num)/edit
     * (Opsional, kalau butuh form HTML)
     */
    public function approve($id = null)
    {
        $data = $this->request->getPost();
        $data['status'] = 1;
        $data['id'] = $id;
        
        if (!$this->model->update($id, $data)) {
            return redirect()->to('/back-end/training/job-seekers')->with('key', $data['key'])->with('errors-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/training/job-seekers')->with('key', $data['key'])->with('message-backend', 'Job Seeker Approve successfully');
    }

    public function reject($id = null)
    {
        $data = $this->request->getPost();
        $data['status'] = -1;
        $data['id'] = $id;
        
        $item = $this->model->find($id);
        $dataTriningType = $this->modelTriningType->where('id', $item->training_type_id)->first();
        if (!empty($dataTriningType)) {
            if($dataTriningType->quota_used > 0)
            {
                $dataTriningType->quota_used = $dataTriningType->quota_used - 1;
            }
            else{
                return redirect()->to('/back-end/training/job-seekers')->with('key', $data['key'])->with('error-backend', 'Quota Used Not Found !!');
            }            
            if (!$this->modelTriningType->update($item->training_type_id, $dataTriningType)) {
                return redirect()->to('/back-end/training/job-seekers')->with('key', $data['key'])->with('error-backend', $this->modelTriningType->errors());
            }
        }

        if (!$this->model->update($id, $data)) {
            return redirect()->to('/back-end/training/job-seekers')->with('key', $data['key'])->with('error-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/training/job-seekers')->with('key', $data['key'])->with('message-backend', 'Job Seeker Reject successfully');
    }

    public function revert($id = null)
    {
        $data = $this->request->getPost();
        $data['status'] = 0;
        $data['id'] = $id;
        
        $item = $this->model->find($id);
        $dataTriningType = $this->modelTriningType->where('id', $item->training_type_id)->first();
        if (!empty($dataTriningType)) {
            if($dataTriningType->quota_used < $dataTriningType->quota && $dataTriningType->quota > 0)
            {
                $dataTriningType->quota_used = $dataTriningType->quota_used + 1;
            }
            else{
                return redirect()->to('/back-end/training/job-seekers')->with('key', $data['key'])->with('error-backend', 'Quota Full !!');
            }            
            if (!$this->modelTriningType->update($item->training_type_id, $dataTriningType)) {
                return redirect()->to('/back-end/training/job-seekers')->with('key', $data['key'])->with('error-backend', $this->modelTriningType->errors());
            }
        }     
        else {
            return redirect()->to('/back-end/training/job-seekers')->with('key', $data['key'])->with('error-backend', 'Training Type Not Found !!');
        }

        if (!$this->model->update($id, $data)) {
            return redirect()->to('/back-end/training/job-seekers')->with('key', $data['key'])->with('error-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/training/job-seekers')->with('key', $data['key'])->with('message-backend', 'Job Seeker Revert successfully');
    }
}
