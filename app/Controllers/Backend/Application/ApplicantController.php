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
                    'icon' => 'mingcute:new-folder-line',
                    'datatable'  => [
                        'key' => 'new',
                        'selectable' => true,
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
                    'icon' => 'mingcute:refresh-2-line',
                    'datatable'  => [
                        'key' => 'process',
                        'selectable' => true,
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
                    'icon' => 'mingcute:check-circle-line',
                    'datatable'  => [
                        'key' => 'approved',
                        'selectable' => true,
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
                    'icon' => 'mingcute:close-circle-line',
                    'datatable'  => [
                        'key' => 'rejected',
                        'selectable' => true,
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

        $documents = [];
        // Validate against Job Vacancy Requirements
        $jobVacancyId = $this->request->getPost('job_vacancy_id');
        $jobVacancy = $this->modelJobVacancy->find($jobVacancyId);
        
        $requiredDocs = [];
        if ($jobVacancy && !empty($jobVacancy->required_documents)) {
            $requiredDocs = $jobVacancy->required_documents; // Auto-cast to array
        }

        $documents = [];
        $docKeys = ['cv', 'language_cert', 'skill_cert', 'other'];
        
        // First pass: Upload files
        foreach ($docKeys as $key) {
            $path = upload_file($key, 'assets/documents/applicant', $this->request->getPost('name') . '_' . $key);
            if ($path) {
                $documents[$key] = $path;
                if ($key === 'cv') {
                    $data['file_cv'] = $path;
                }
            }
        }

        // Second pass: Check requirements
        foreach ($requiredDocs as $req) {
            if (empty($documents[$req])) {
                 return redirect()->to('/back-end/applicant')->withInput()->with('errors-backend', ['documents' => "Document '$req' is required."]);
            }
        }
        $data['documents'] = json_encode($documents);

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

        $documents = [];
        // Decode existing if needed, but usually we just merge or overwrite.
        // For simple update, let's just handle new files and merge if we can read existing.
        // But for now, just save what's uploaded. Ideally we should merge with existing.
        // Let's rely on entity/model to not overwrite if null? No, upload_file returns false if no file.
        // So we only update entries that are uploaded.
        
        $docKeys = ['cv', 'language_cert', 'skill_cert', 'other'];
        
        // Fetch existing data to merge documents
        $existing = $this->model->find($id);
        $existingDocs = !empty($existing->documents) ? $existing->documents : [];
        // Handle if existingDocs is object or string unexpectedly (though Cast should handle it, keeping it safe)
        if(is_string($existingDocs)) $existingDocs = json_decode($existingDocs, true) ?? [];
        if(!is_array($existingDocs)) $existingDocs = (array)$existingDocs;

        foreach ($docKeys as $key) {
            $path = upload_file($key, 'assets/documents/applicant', $this->request->getPost('name') . '_' . $key);
            if ($path) {
                $existingDocs[$key] = $path;
                if ($key === 'cv') {
                    $data['file_cv'] = $path;
                }
            }
        }

        // Validate Requirements
        $jobVacancyId = $this->request->getPost('job_vacancy_id');
        // If job_vacancy_id not in post, use existing
        if(empty($jobVacancyId)) $jobVacancyId = $existing->job_vacancy_id;

        $jobVacancy = $this->modelJobVacancy->find($jobVacancyId);
        $requiredDocs = [];
        if ($jobVacancy && !empty($jobVacancy->required_documents)) {
             $requiredDocs = $jobVacancy->required_documents;
        }

        foreach ($requiredDocs as $req) {
            if (empty($existingDocs[$req])) {
                 return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', ['documents' => "Document '$req' is required."]);
            }
        }
        $data['documents'] = json_encode($existingDocs);

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
        
        $result = $this->processItem($id, $data);

        if ($this->request->isAJAX()) {
             if ($result === true) {
                 return $this->response->setJSON(['status' => 'Success', 'message' => 'Applicant Process successfully']);
             }
             return $this->response->setJSON(['status' => 'Error', 'message' => (is_array($result) ? implode(', ', $result) : $result)]);
        }

        if ($result !== true) {
             return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', $result);
        }

        return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('message-backend', 'Applicant Process successfully');
    }

    public function massProcess()
    {
        // Handle both form-encoded and JSON data
        $ids = $this->request->getVar('ids');
        
        // If not found, try to get from JSON body
        if (empty($ids) && strpos($this->request->getHeaderLine('Content-Type'), 'application/json') !== false) {
            $json = $this->request->getJSON();
            $ids = $json->ids ?? null;
        }
        
        $key = $this->request->getVar('key');

        if (empty($ids) || !is_array($ids)) {
             return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'No items selected'
            ]);
        }

        $successCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            $data = ['key' => $key]; 
            $result = $this->processItem($id, $data);
            if ($result === true) {
                $successCount++;
            } else {
                $errors[] = "ID $id: " . (is_array($result) ? json_encode($result) : $result);
            }
        }

        if ($successCount > 0) {
            $msg = "$successCount items processed successfully.";
            if (count($errors) > 0) {
                $msg .= " " . count($errors) . " failed.";
            }
            return $this->response->setJSON([
                'status' => 'Success',
                'message' => $msg
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'Failed to process items. ' . implode(', ', $errors)
            ]);
        }
    }

    private function processItem($id, $contextData)
    {
        $data = $contextData;
        $data['status'] = 2;
        $data['id'] = $id;

        $item = $this->model->find($id);

        if (!$item) {
            return "Applicant with ID $id not found";
        }

        // Security Check: Ensure Applicant belongs to a vacancy owned by this company
        if ($this->auth->user()->user_type == 'company') {
            $companyModel = new \App\Models\CompanyModel();
            $company = $companyModel->where('user_id', $this->auth->user()->id)->first();
            
            // Applicant -> JobVacancy -> Company
            $vacancy = $this->modelJobVacancy->find($item->job_vacancy_id);
            
            if (!$company || !$vacancy || $vacancy->company_id != $company->id) {
                 return "You do not have permission to process this applicant.";
            }
        }

        if (!$this->model->update($id, $data)) {
            return $this->model->errors();
        }

        $dataJobVacancy = $this->modelJobVacancy->where('id', $item->job_vacancy_id)->first();

        if (!empty($dataJobVacancy)) {
            $dataJobVacancy->applicant_process = $dataJobVacancy->applicant_process + 1;
            
            if (!$this->modelJobVacancy->update($item->job_vacancy_id, $dataJobVacancy)) {
                return $this->modelJobVacancy->errors();
            }
        }
        return true;
    }

    /**
     * GET /company/(:num)/edit
     * (Opsional, kalau butuh form HTML)
     */
    public function approve($id = null)
    {
        $data = $this->request->getVar();
        $result = $this->approveItem($id, $data);

        if ($this->request->isAJAX()) {
             if ($result === true) {
                 return $this->response->setJSON(['status' => 'Success', 'message' => 'Applicant Approve successfully']);
             }
             return $this->response->setJSON(['status' => 'Error', 'message' => (is_array($result) ? implode(', ', $result) : $result)]);
        }

        if ($result !== true) {
             return redirect()->to('/back-end/applicant')->with('key', $data['key'] ?? 'process')->with('error-backend', $result);
        }

        return redirect()->to('/back-end/applicant')->with('key', $data['key'] ?? 'approved')->with('message-backend', 'Applicant Approve successfully');
    }

    public function massApprove()
    {
        // Handle both form-encoded and JSON data
        $ids = $this->request->getVar('ids');
        
        // If not found, try to get from JSON body
        if (empty($ids) && strpos($this->request->getHeaderLine('Content-Type'), 'application/json') !== false) {
            $json = $this->request->getJSON();
            $ids = $json->ids ?? null;
        }
        
        $key = $this->request->getVar('key');

        if (empty($ids) || !is_array($ids)) {
             return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'No items selected'
            ]);
        }

        $successCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            $data = ['key' => $key]; // Context data if needed
            $result = $this->approveItem($id, $data);
            if ($result === true) {
                $successCount++;
            } else {
                $errors[] = "ID $id: " . (is_array($result) ? json_encode($result) : $result);
            }
        }

        if ($successCount > 0) {
            $msg = "$successCount items approved successfully.";
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
                'message' => 'Failed to approve items. ' . implode(', ', $errors)
            ]);
        }
    }

    private function approveItem($id, $contextData) 
    {
        $data = $contextData; // Use context data from request if needed, or minimal
        // But the original code merged request->getPost() with status=1.
        // We should replicate that or just accept what we need.
        // The important part is logic inside.
        
        // We reconstruct $data as in the original method
        $data['status'] = 1;
        $data['id'] = $id;

        $item = $this->model->find($id);
        if (!$item) return "Item not found";

        $dataJobVacancy = $this->modelJobVacancy->where('id', $item->job_vacancy_id)->first();

        if (!empty($dataJobVacancy)) {
            $dataJobVacancy->applicant_process = $dataJobVacancy->applicant_process - 1;

            if($item->gender == 'M'){
                if($dataJobVacancy->male_quota_used < $dataJobVacancy->male_quota && $dataJobVacancy->male_quota > 0)
                {
                    $dataJobVacancy->male_quota_used = $dataJobVacancy->male_quota_used + 1;
                }
                else if($dataJobVacancy->unisex_quota_used < $dataJobVacancy->unisex_quota && $dataJobVacancy->unisex_quota > 0)
                {
                    $dataJobVacancy->unisex_quota_used = $dataJobVacancy->unisex_quota_used + 1;
                }
                else{
                    return 'Quota Full !!';
                }
            }
            else if($item->gender == 'F'){
                if($dataJobVacancy->female_quota_used < $dataJobVacancy->female_quota && $dataJobVacancy->female_quota > 0)
                {
                    $dataJobVacancy->female_quota_used = $dataJobVacancy->female_quota_used + 1;
                }
                else if($dataJobVacancy->unisex_quota_used < $dataJobVacancy->unisex_quota && $dataJobVacancy->unisex_quota > 0)
                {
                    $dataJobVacancy->unisex_quota_used = $dataJobVacancy->unisex_quota_used + 1;
                }
                else{
                    return 'Quota Full !!';
                }
            }
            
            if (!$this->modelJobVacancy->update($item->job_vacancy_id, $dataJobVacancy)) {
                return $this->modelJobVacancy->errors();
            }
        }
        
        if (!$this->model->update($id, $data)) {
            return $this->model->errors();
        }
        
        return true;
    }

    private function rejectItem($id, $contextData)
    {
        $data = $contextData;
        $data['status'] = -1;
        $data['id'] = $id;

        $item = $this->model->find($id);
        if (!$item) return "Item not found";

        // Security Check
        if ($this->auth->user()->user_type == 'company') {
            $companyModel = new \App\Models\CompanyModel();
            $company = $companyModel->where('user_id', $this->auth->user()->id)->first();
            $vacancy = $this->modelJobVacancy->find($item->job_vacancy_id);
            if (!$company || !$vacancy || $vacancy->company_id != $company->id) {
                 return "You do not have permission to reject this applicant.";
            }
        }

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
                    return $this->modelJobVacancy->errors();
                }
            }
        }
        
        if (!$this->model->update($id, $data)) {
            return $this->model->errors();
        }
        
        return true;
    }

    public function reject($id = null)
    {
        $data = $this->request->getPost();
        
        $result = $this->rejectItem($id, $data);

        if ($this->request->isAJAX()) {
             if ($result === true) {
                 return $this->response->setJSON(['status' => 'Success', 'message' => 'Applicant Reject successfully']);
             }
             return $this->response->setJSON(['status' => 'Error', 'message' => (is_array($result) ? implode(', ', $result) : $result)]);
        }

        if ($result === true) {
             return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('message-backend', 'Applicant Reject successfully');
        } else {
             return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', is_array($result) ? implode(', ', $result) : $result);
        }
    }

    public function massReject()
    {
        // Handle both form-encoded and JSON data
        $ids = $this->request->getVar('ids');
        
        // If not found, try to get from JSON body
        if (empty($ids) && strpos($this->request->getHeaderLine('Content-Type'), 'application/json') !== false) {
            $json = $this->request->getJSON();
            $ids = $json->ids ?? null;
        }
        
        $key = $this->request->getVar('key');

        if (empty($ids) || !is_array($ids)) {
             return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'No items selected'
            ]);
        }

        $successCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            $data = ['key' => $key];
            $result = $this->rejectItem($id, $data);
            if ($result === true) {
                $successCount++;
            } else {
                $errors[] = "ID $id: " . (is_array($result) ? json_encode($result) : $result);
            }
        }

        if ($successCount > 0) {
            $msg = "$successCount items rejected successfully.";
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
                'message' => 'Failed to reject items. ' . implode(', ', $errors)
            ]);
        }
    }

    private function revertItem($id, $contextData)
    {
        try {
            $data = $contextData;
            $data['status'] = 0;
            $data['id'] = $id;
            
            $item = $this->model->find($id);
            if (!$item) return "Applicant ID $id not found";

            // Security Check
            if ($this->auth->user()->user_type == 'company') {
                $companyModel = new \App\Models\CompanyModel();
                $company = $companyModel->where('user_id', $this->auth->user()->id)->first();
                $vacancy = $this->modelJobVacancy->find($item->job_vacancy_id);
                if (!$company || !$vacancy || $vacancy->company_id != $company->id) {
                    return "You do not have permission to revert this applicant.";
                }
            }

            if (!$this->model->update($id, $data)) {
                return $this->model->errors();
            }

            if (!empty($item->job_vacancy_id)) {
                $dataJobVacancy = $this->modelJobVacancy->where('id', $item->job_vacancy_id)->first();

                if (!empty($dataJobVacancy)) {
                    $needUpdate = false;

                    if($item->status == 2){
                        // Reverting from Process: Decrement process count
                        $dataJobVacancy->applicant_process = $dataJobVacancy->applicant_process - 1;
                        $needUpdate = true;
                    }
                    else if($item->status == 1){
                        // Reverting from Approved: Decrement quota and process count?
                        // Note: approveItem decrements process and increments quota.
                        // So revertItem should decrement quota and ... increment process?
                        // No, revertItem status=0 (New). New items don't count in process or quota.
                        // So we just decrement quota.
                        // Wait, approveItem decremented applicant_process.
                        // If we revert to 0, we imply it was never processed.
                        // So we just restore quota.
                        
                       if($item->gender == 'M'){
                           if($dataJobVacancy->male_quota_used > 0) {
                               $dataJobVacancy->male_quota_used = $dataJobVacancy->male_quota_used - 1;
                               $needUpdate = true;
                           } else if($dataJobVacancy->unisex_quota_used > 0) {
                               $dataJobVacancy->unisex_quota_used = $dataJobVacancy->unisex_quota_used - 1;
                               $needUpdate = true;
                           } else {
                               return 'Quota Used Not Found !!';
                           }
                       }
                       else if($item->gender == 'F'){
                          if($dataJobVacancy->female_quota_used > 0) {
                               $dataJobVacancy->female_quota_used = $dataJobVacancy->female_quota_used - 1;
                               $needUpdate = true;
                           } else if($dataJobVacancy->unisex_quota_used > 0) {
                               $dataJobVacancy->unisex_quota_used = $dataJobVacancy->unisex_quota_used - 1;
                               $needUpdate = true;
                           } else {
                               return 'Quota Used Not Found !!';
                           }
                       }
                    }
                    
                    if ($needUpdate) {
                        if (!$this->modelJobVacancy->update($item->job_vacancy_id, $dataJobVacancy)) {
                            return $this->modelJobVacancy->errors();
                        }
                    }
                }
            }
            return true;
        } catch (\Throwable $th) {
            return "Exception: " . $th->getMessage();
        }
    }

    public function revert($id = null)
    {
        $data = $this->request->getPost();
        
        $result = $this->revertItem($id, $data);

        if ($this->request->isAJAX()) {
             if ($result === true) {
                 return $this->response->setJSON(['status' => 'Success', 'message' => 'Applicant Revert successfully']);
             }
             return $this->response->setJSON(['status' => 'Error', 'message' => (is_array($result) ? implode(', ', $result) : $result)]);
        }

        if ($result === true) {
             return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('message-backend', 'Applicant Revert successfully');
        } else {
             return redirect()->to('/back-end/applicant')->with('key', $data['key'])->with('error-backend', is_array($result) ? implode(', ', $result) : $result);
        }
    }

    public function massRevert()
    {
        // Handle both form-encoded and JSON data
        $ids = $this->request->getVar('ids');
        
        // If not found, try to get from JSON body
        if (empty($ids) && strpos($this->request->getHeaderLine('Content-Type'), 'application/json') !== false) {
            $json = $this->request->getJSON();
            $ids = $json->ids ?? null;
        }
        
        $key = $this->request->getVar('key');

        if (empty($ids) || !is_array($ids)) {
             return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'No items selected'
            ]);
        }

        $successCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            $data = ['key' => $key];
            $result = $this->revertItem($id, $data);
            if ($result === true) {
                $successCount++;
            } else {
                $errors[] = "ID $id: " . (is_array($result) ? json_encode($result) : $result);
            }
        }

        if ($successCount > 0) {
            $msg = "$successCount items reverted successfully.";
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
                'message' => 'Failed to revert items. ' . implode(', ', $errors)
            ]);
        }
    }
}
