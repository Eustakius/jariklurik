<?php

namespace App\Controllers\Backend\Application\Training;

use App\Controllers\BaseController;
use App\Models\PurnaPmiModel;
use App\Models\TrainingTypeModel;

class PurnaPmiController extends BaseController
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
        $this->model        = new PurnaPmiModel();
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
            ['title' => 'End Year', 'data' => 'end_year'],
            ['title' => 'Education Level', 'data' => 'educationlevel'],
            ['title' => 'Training Type', 'data' => 'training_type'],
            ['title' => 'Email', 'data' => 'email'],
            ['title' => 'Address', 'data' => 'address'],
            ['title' => 'Phone', 'data' => 'phone'],
            ['title' => 'Attachment', 'data' => 'file'],
            ['title' => 'Code', 'data' => 'code'],
        ];

        $files = [
            ['column' => 11]
        ];
        return view('Backend/Application/Training/purna-pmi', [
            'config'    => $this->config,
            'token' => $token,
            'tabs'      => [
                [
                    'key' => 'new',
                    'label' => 'New',
                    'datatable'  => [
                        'key' => 'new',
                        'api' => $this->configApp->baseBackendURL . '/api/purna-pmi/data-table-new',
                        'fixedcolumns' => 0,
                        'token' => $token,
                        'files' => $files,
                        'selectable' => true,
                        'columns' => datatableColumns($columns),
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
                        'api' => $this->configApp->baseBackendURL . '/api/purna-pmi/data-table-approved',
                        'fixedcolumns' => 0,
                        'token' => $token,
                        'files' => $files,
                        'selectable' => true,
                        'columns' => datatableColumns($columns),
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
                        'api' => $this->configApp->baseBackendURL . '/api/purna-pmi/data-table-rejected',
                        'fixedcolumns' => 0,
                        'token' => $token,
                        'files' => $files,
                        'selectable' => true,
                        'columns' => datatableColumns($columns),
                        'permission' => array_filter($permission, fn($p) => str_ends_with($p['permission'], '.revert')),'filters' => [
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
            return redirect()->to('/back-end/training/purna-pmi')->with('error-backend', "PurnaPmi with ID $id not found");
        }

        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/purna-pmi-form', [
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
            return redirect()->to('/back-end/training/purna-pmi')->with('errors-backend', $this->model->errors());
        }
        
        return redirect()->to('/back-end/company')->with('message-backend', 'PurnaPmi Create successfully');
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
            return redirect()->to('/back-end/training/purna-pmi')->with('errors-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/company')->with('message-backend', 'PurnaPmi updated successfully');
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

        return view('Backend/Application/purna-pmi-form', [
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
            return redirect()->to('/back-end/training/purna-pmi')->with('error-backend', "PurnaPmi with ID $id not found");
        }

        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/purna-pmi-form', [
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
            return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('error-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('message-backend', 'Job Seeker Approve successfully');
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
                return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('error-backend', 'Quota Used Not Found !!');
            }            
            if (!$this->modelTriningType->update($item->training_type_id, $dataTriningType)) {
                return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('error-backend', $this->modelTriningType->errors());
            }
        }
        
        if (!$this->model->update($id, $data)) {
            return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('error-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('message-backend', 'Job Seeker Reject successfully');
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
                return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('error-backend', 'Quota Full !!');
            }            
            if (!$this->modelTriningType->update($item->training_type_id, $dataTriningType)) {
                return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('error-backend', $this->modelTriningType->errors());
            }
        } 
        else {
            return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('error-backend', 'Training Type Not Found !!');
        }
        if (!$this->model->update($id, $data)) {
            return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('error-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('message-backend', 'Job Seeker Revert successfully');
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
                
                $currentStatus = (int)$item->status;
                if ($currentStatus == -1) {
                     $dataTriningType = $this->modelTriningType->where('id', $item->training_type_id)->first();
                     if (!empty($dataTriningType)) {
                         if($dataTriningType->quota_used < $dataTriningType->quota && $dataTriningType->quota > 0)
                         {
                             $dataTriningType->quota_used = $dataTriningType->quota_used + 1;
                             $this->modelTriningType->update($item->training_type_id, $dataTriningType);
                         } else {
                             $errors[] = "Item ID $id: Quota Full.";
                             continue;
                         }
                     }
                }

                $this->model->update($id, ['status' => 1]);
                $successCount++;
                
            } catch (\Exception $e) {
                $errors[] = "Item ID $id: " . $e->getMessage();
            }
        }

        if ($successCount > 0) {
            $msg = "$successCount items approved.";
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

                $currentStatus = (int)$item->status;
                if ($currentStatus == 0 || $currentStatus == 1) {
                     $dataTriningType = $this->modelTriningType->where('id', $item->training_type_id)->first();
                     if (!empty($dataTriningType) && $dataTriningType->quota_used > 0) {
                         $dataTriningType->quota_used = $dataTriningType->quota_used - 1;
                         $this->modelTriningType->update($item->training_type_id, $dataTriningType);
                     }
                }

                $this->model->update($id, ['status' => -1]);
                $successCount++;
                
            } catch (\Exception $e) {
                $errors[] = "Item ID $id: " . $e->getMessage();
            }
        }

        if ($successCount > 0) {
            $msg = "$successCount items rejected.";
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

                $currentStatus = (int)$item->status;
                $dataTriningType = $this->modelTriningType->where('id', $item->training_type_id)->first();

                if (!empty($dataTriningType) && $currentStatus == -1) {
                     if($dataTriningType->quota_used < $dataTriningType->quota && $dataTriningType->quota > 0)
                     {
                         $dataTriningType->quota_used = $dataTriningType->quota_used + 1;
                         $this->modelTriningType->update($item->training_type_id, $dataTriningType);
                     }
                     else{
                         $errors[] = "Item ID $id: Quota Full.";
                         continue;
                     } 
                }
                
                $this->model->update($id, ['status' => 0]);
                $successCount++;
                
            } catch (\Exception $e) {
                $errors[] = "Item ID $id: " . $e->getMessage();
            }
        }

        if ($successCount > 0) {
            $msg = "$successCount items reverted.";
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

    // massProcess is handled by the decision modal in the frontend
    // It shows Approve/Reject options based on the current tab
    public function massProcess()
    {
        // This method intentionally returns an error
        // The frontend decision modal should call massApprove or massReject directly
        return $this->response->setJSON([
            'status' => 'Error',
            'message' => 'Mass Process should use decision modal to call massApprove or massReject'
        ])->setStatusCode(400);
    }
}
