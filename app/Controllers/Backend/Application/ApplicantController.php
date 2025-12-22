<?php

namespace App\Controllers\Backend\Application;

use App\Controllers\BaseController;
use App\Models\ApplicantModel;
use App\Models\JobVacancyModel;

class ApplicantController extends BaseController
{
    protected $auth;
    protected $config;
    protected $configApp;
    protected $model;
    protected $modelJobVacancy;

    public function __construct()
    {
        $this->config       = config('Backend');
        $this->configApp    = config('App');
        $this->auth         = service('authentication');
        $this->model        = new ApplicantModel();
        $this->modelJobVacancy  = new JobVacancyModel();
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
            ['title' => 'Job Vacancy', 'data' => 'job_vacancy'],
            ['title' => 'Country', 'data' => 'country'],
            ['title' => 'Company', 'data' => 'company'],
            ['title' => 'Email', 'data' => 'email'],
            ['title' => 'Phone', 'data' => 'phone'],
            ['title' => 'Attachment', 'data' => 'file'],
            ['title' => 'Code', 'data' => 'code'],
        ];

        $files = [
            ['column' => 11]
        ];
        return view('Backend/Application/applicant', [
            'config'    => $this->config,
            'token' => $token,
            'tabs'      => [
                [
                    'key' => 'new',
                    'label' => 'New',
                    'datatable'  => [
                        'key' => 'new',
                        'api' => $this->configApp->baseBackendURL . '/api/applicant/data-table-new',
                        'fixedcolumns' => 0,
                        'token' => $token,
                        'files' => $files,
                        'columns' => datatableColumns($columns),
                        'page' => $this->request->getPath(),
                        'permission' => array_filter($permission, fn($p) => str_ends_with($p['permission'], '.process') || str_ends_with($p['permission'], '.reject')),
                        'filters' => [
                            ['label' => 'Gender', 'id' => 'gendernew', 'input' => 'select', 'data' => [
                                ["value" => 'M', "label" => "Male"],
                                ["value" => 'F', "label" => "Female"]
                            ]],
                            ['label' => 'Education Level', 'id' => 'educationlevelnew', 'input' => 'select', 'data' => config('Backend')->educationLevel],
                            ['label' => 'Job Vacancy', 'id' => 'jobvacancynew', 'input' => 'select', 'api' => 'back-end/api/job-vacancy/select'],
                            ['label' => 'Country', 'id' => 'countrynew', 'input' => 'select', 'api' => 'back-end/api/country/select'],
                            ['label' => 'Company', 'id' => 'companynew', 'input' => 'select', 'api' => 'back-end/api/company/select'],
                            ['label' => 'Submit From', 'id' => 'submitfromnew', 'input' => 'date', 'type' => 'date'],
                            ['label' => 'Submit To', 'id' => 'submittonew', 'input' => 'date', 'type' => 'date'],
                        ],
                    ],
                ],
                [
                    'key' => 'process',
                    'label' => 'Process',
                    'datatable'  => [
                        'key' => 'process',
                        'api' => $this->configApp->baseBackendURL . '/api/applicant/data-table-processed',
                        'fixedcolumns' => 0,
                        'token' => $token,
                        'files' => $files,
                        'columns' => datatableColumns($columns),
                        'page' => $this->request->getPath(),
                        'permission' => array_filter($permission, fn($p) =>  str_ends_with($p['permission'], '.revert') || str_ends_with($p['permission'], '.approve') || str_ends_with($p['permission'], '.reject')),
                        'filters' => [
                            ['label' => 'Gender', 'id' => 'genderprocess', 'input' => 'select', 'data' => [
                                ["value" => 'M', "label" => "Male"],
                                ["value" => 'F', "label" => "Female"]
                            ]],
                            ['label' => 'Education Level', 'id' => 'educationlevelprocess', 'input' => 'select', 'data' => config('Backend')->educationLevel],
                            ['label' => 'Job Vacancy', 'id' => 'jobvacancyprocess', 'input' => 'select', 'api' => 'back-end/api/job-vacancy/select'],
                            ['label' => 'Country', 'id' => 'countryprocess', 'input' => 'select', 'api' => 'back-end/api/country/select'],
                            ['label' => 'Company', 'id' => 'companyprocess', 'input' => 'select', 'api' => 'back-end/api/company/select'],
                            ['label' => 'Submit From', 'id' => 'submitfromprocess', 'input' => 'date', 'type' => 'date'],
                            ['label' => 'Submit To', 'id' => 'submittoprocess', 'input' => 'date', 'type' => 'date'],
                        ],
                    ],
                ],
                [
                    'key' => 'approved',
                    'label' => 'Approved',
                    'datatable'  => [
                        'key' => 'approved',
                        'api' => $this->configApp->baseBackendURL . '/api/applicant/data-table-approved',
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
                            ['label' => 'Job Vacancy', 'id' => 'jobvacancyapproved', 'input' => 'select', 'api' => 'back-end/api/job-vacancy/select'],
                            ['label' => 'Country', 'id' => 'countryapproved', 'input' => 'select', 'api' => 'back-end/api/country/select'],
                            ['label' => 'Company', 'id' => 'companyapproved', 'input' => 'select', 'api' => 'back-end/api/company/select'],
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
                        'api' => $this->configApp->baseBackendURL . '/api/applicant/data-table-rejected',
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
                            ['label' => 'Job Vacancy', 'id' => 'jobvacancyrejected', 'input' => 'select', 'api' => 'back-end/api/job-vacancy/select'],
                            ['label' => 'Country', 'id' => 'countryrejected', 'input' => 'select', 'api' => 'back-end/api/country/select'],
                            ['label' => 'Company', 'id' => 'companyrejected', 'input' => 'select', 'api' => 'back-end/api/company/select'],
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
            return redirect()->to('/back-end/applicant')->with('error-backend', "Applicant with ID $id not found");
        }

        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/applicant', [
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
            return redirect()->to('/back-end/applicant')->with('errors-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/applicant')->with('message-backend', 'Applicant Create successfully');
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
            return redirect()->to('/back-end/applicant')->with('errors-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/company')->with('message-backend', 'Applicant updated successfully');
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

        return view('Backend/Application/applicant-form', [
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
            return redirect()->to('/back-end/applicant')->with('error-backend', "Applicant with ID $id not found");
        }

        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/applicant-form', [
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
    public function process($id = null)
    {
        $data = $this->request->getPost();
        $data['status'] = 2;
        $data['id'] = $id;
        
        $item = $this->model->find($id);

        if (!$item) {
            return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', "Applicant with ID $id not found");
        }

        if (!$this->model->update($id, $data)) {
            return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', $this->model->errors());
        }

        $dataJobVacancy = $this->modelJobVacancy->where('id', $item->job_vacancy_id)->first();

        if (!empty($dataJobVacancy)) {
            $dataJobVacancy->applicant_process = $dataJobVacancy->applicant_process + 1;
            
            if (!$this->modelJobVacancy->update($item->job_vacancy_id, $dataJobVacancy)) {
                return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', $this->modelJobVacancy->errors());
            }
        }
        return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('message-backend', 'Applicant Process successfully');
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
        $item = $this->model->find($id);

        $dataJobVacancy = $this->modelJobVacancy->where('id', $item->job_vacancy_id)->first();

        if (!empty($dataJobVacancy)) {
            $dataJobVacancy->applicant_process = $dataJobVacancy->applicant_process - 1;

            if($item->gender == 'M'){
                if($dataJobVacancy->male_quota_used < $dataJobVacancy->male_quota && $dataJobVacancy->male_quota > 0)
                {
                    $dataJobVacancy->male_quota_used = $dataJobVacancy->male_quota_used + 1;
                }
                else if($dataJobVacancy->unisex_quota_used < $dataJobVacancy->unisex_quota_used && $dataJobVacancy->unisex_quota_used > 0)
                {
                    $dataJobVacancy->unisex_quota_used = $dataJobVacancy->unisex_quota_used + 1;
                }
                else{
                    return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', 'Quota Full !!');
                }
            }
            else if($item->gender == 'F'){
                if($dataJobVacancy->female_quota_used < $dataJobVacancy->female_quota && $dataJobVacancy->female_quota_used > 0)
                {
                    $dataJobVacancy->female_quota_used = $dataJobVacancy->female_quota_used + 1;
                }
                else if($dataJobVacancy->unisex_quota_used < $dataJobVacancy->unisex_quota_used && $dataJobVacancy->unisex_quota_used > 0)
                {
                    $dataJobVacancy->unisex_quota_used = $dataJobVacancy->unisex_quota_used + 1;
                }
                else{
                    return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', 'Quota Full !!');
                }
            }
            
            if (!$this->modelJobVacancy->update($item->job_vacancy_id, $dataJobVacancy)) {
                return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', $this->modelJobVacancy->errors());
            }
        }
        
        if (!$this->model->update($id, $data)) {
            return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('message-backend', 'Applicant Approve successfully');
    }

    public function reject($id = null)
    {
        $data = $this->request->getPost();
        $data['status'] = -1;
        $data['id'] = $id;
        $item = $this->model->find($id);

        $dataJobVacancy = $this->modelJobVacancy->where('id', $item->job_vacancy_id)->first();

        if (!empty($dataJobVacancy)) {
            if($item->status == 2){
                $dataJobVacancy->applicant_process = $dataJobVacancy->applicant_process - 1;
            }
            else if($item->status == 1){
                $dataJobVacancy->applicant_process = $dataJobVacancy->applicant_process - 1;
            }      

            if($item->status > 0)
            {
                if (!$this->modelJobVacancy->update($item->job_vacancy_id, $dataJobVacancy)) {
                    return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', $this->modelJobVacancy->errors());
                }
            }
        }
        
        if (!$this->model->update($id, $data)) {
            return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('message-backend', 'Applicant Reject successfully');
    }

    public function revert($id = null)
    {
        $data = $this->request->getPost();
        $data['status'] = 0;
        $data['id'] = $id;
        $item = $this->model->find($id);

        if (!$this->model->update($id, $data)) {
            return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', $this->model->errors());
        }

        $dataJobVacancy = $this->modelJobVacancy->where('id', $item->job_vacancy_id)->first();

        if (!empty($dataJobVacancy)) {
            if($item->status == 2){
                $dataJobVacancy->applicant_process = $dataJobVacancy->applicant_process - 1;
            }
            else if($item->status == 1){
                if($item->gender == 'M'){
                    if($dataJobVacancy->male_quota_used > 0)
                    {
                        $dataJobVacancy->male_quota_used = $dataJobVacancy->male_quota_used - 1;
                    }
                    else if($dataJobVacancy->unisex_quota_used > 0)
                    {
                        $dataJobVacancy->unisex_quota_used = $dataJobVacancy->unisex_quota_used - 1;
                    }
                    else{
                        return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', 'Quota Used Not Found !!');
                    }
                }
                else if($item->gender == 'F'){
                    if($dataJobVacancy->female_quota_used > 0)
                    {
                        $dataJobVacancy->female_quota_used = $dataJobVacancy->female_quota_used - 1;
                    }
                    else if($dataJobVacancy->unisex_quota_used > 0)
                    {
                        $dataJobVacancy->unisex_quota_used = $dataJobVacancy->unisex_quota_used - 1;
                    }
                    else{
                        return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', 'Quota Used Not Found !!');
                    }
                }
            }
            
            if (!$this->modelJobVacancy->update($item->job_vacancy_id, $dataJobVacancy)) {
                return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', $this->modelJobVacancy->errors());
            }
        }
        return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('message-backend', 'Applicant Revert successfully');
    }
}
