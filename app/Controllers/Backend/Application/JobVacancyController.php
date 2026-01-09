<?php

namespace App\Controllers\Backend\Application;

use App\Controllers\BaseController;
use App\Entities\JobSeeker;
use App\Models\CompanyModel;
use App\Models\CountryModel;
use App\Models\JobVacancyModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class JobVacancyController extends BaseController
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
        $this->model        = new JobVacancyModel();
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

        if($this->auth->user()->user_type == "company"){
            $columns = [
                ['title' => 'Position', 'data' => 'position'],
                ['title' => 'Country', 'data' => 'country'],
                ['title' => 'Company', 'data' => 'company'],
                ['title' => 'Duration', 'data' => 'duration'],
                ['title' => 'Selection Date', 'data' => 'selection_date'],
                ['title' => 'Visitor', 'data' => 'visitor'],
                ['title' => 'Applicant', 'data' => 'applicant'],
                ['title' => 'Applicant Process', 'data' => 'applicant_process'],
                ['title' => 'Quota', 'data' => 'quota'],
                ['title' => 'Quota Used', 'data' => 'quota_used'],
                ['title' => 'Status', 'data' => 'status', 'name' => 'status', 'className' => 'col-status'],
                ['title' => 'Code', 'data' => 'code'],
            ];
        }
        else{
            
            $columns = [
                ['title' => 'Position', 'data' => 'position'],
                ['title' => 'Country', 'data' => 'country'],
                ['title' => 'Company', 'data' => 'company'],
                ['title' => 'Duration', 'data' => 'duration'],
                ['title' => 'Status', 'data' => 'status', 'name' => 'status', 'className' => 'col-status'],
                ['title' => 'Selection Date', 'data' => 'selection_date'],
                ['title' => 'Pinned', 'data' => 'is_pin'],
                ['title' => 'Visitor', 'data' => 'visitor'],
                ['title' => 'Applicant', 'data' => 'applicant'],
                ['title' => 'Applicant Process', 'data' => 'applicant_process'],
                ['title' => 'Quota', 'data' => 'quota'],
                ['title' => 'Quota Used', 'data' => 'quota_used'],
                ['title' => 'Code', 'data' => 'code'],
            ];
        }

        $filters = [
            ['label' => 'Country', 'id' => 'country', 'input' => 'select', 'api' => 'back-end/api/country/select'],
            ['label' => 'Company', 'id' => 'company', 'input' => 'select', 'api' => 'back-end/api/company/select'],
            ['label' => 'Selection From', 'id' => 'selectionfrom', 'input' => 'date', 'type' => 'date'],
            ['label' => 'Selection To', 'id' => 'selectionto', 'input' => 'date', 'type' => 'date'],
            ['label' => 'Duration', 'id' => 'duration', 'input' => 'textgroup', 'group' => ['id' => 'durationtype', 'input' => 'select', 'data' => [
                "",
                "Bulan",
                "Tahun"
            ]]],
            ['label' => 'Pinned', 'id' => 'pinned', 'input' => 'select', 'data' => [
                ["value" => 0, "label" => "Not Pinned"],
                ["value" => 1, "label" => "Pinned"]
            ]],
        ];

        return view('Backend/Application/job-vacancy', [
            'config'     => $this->config,
            'token' => $token,
            'tabs' => [
                [
                    'key' => 'active',
                    'label' => 'Active',
                    'icon' => 'mingcute:check-circle-line',
                    'datatable' => [
                        'key' => 'active',
                        'selectable' => true,
                        // status=1 for Active
                        'api' => $this->configApp->baseBackendURL . '/api/job-vacancy/data-table?status=1',
                        'fixedcolumns' => 0,
                        'token' => $token,
                        'columns' => datatableColumns($columns),
                        'page' => $this->request->getPath(),
                        'permission' => $permission,
                        'filters' => $filters,
                    ]
                ],
                [
                    'key' => 'inactive',
                    'label' => 'Inactive',
                    'icon' => 'mingcute:close-circle-line',
                    'datatable' => [
                        'key' => 'inactive',
                        'selectable' => true,
                        // status=0 for Inactive
                        'api' => $this->configApp->baseBackendURL . '/api/job-vacancy/data-table?status=0',
                        'fixedcolumns' => 0,
                        'token' => $token,
                        'columns' => datatableColumns($columns),
                        'page' => $this->request->getPath(),
                        'permission' => $permission,
                        'filters' => $filters,
                    ]
                ]
            ],
            'loading'    => false,
            'error-backend'      => null,
        ]);
    }

    public function show($id = null)
    {
        $jobVacancy = $this->model->find($id);

        if (!$jobVacancy) {
            return redirect()->to(pathBack($this->request))->with('error-backend', "Job Vacancy with ID $id not found");
        }
        if ($this->auth->user()->user_type == 'company') {
            $companyModel = new CompanyModel();

            $company = $companyModel->where('user_id', $this->auth->user()->id)->first();
            if (!empty($company)) {
                if($this->auth->user()->id != $company->user_id){
                    return redirect()->to(pathBack($this->request))->with('forbiden', "Job Vacancy with ID $id not found");
                }
            }
            else{
                return redirect()->to(pathBack($this->request))->with('forbiden', "Job Vacancy with ID $id not found");
            }
        }
        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/job-vacancy-form', [
            'config' => $this->config,
            'param' => [
                'id' => $id,
                'action' => 'detail',
            ],
            'data' => $jobVacancy,
            'token' => $token,
            'form' => [
                'route' => str_replace('/', '', $this->request->getPath()),
                'method' => 'PUT'
            ],
            'path' => $this->request->getPath(),
        ]);
    }

    /**
     * POST /jobVacancy
     */
    public function create()
    {
        $data = $this->request->getPost();

        if ($this->auth->user()->user_type == 'company') {
            $data['status'] = 9; // Pending
        } else {
            $data['status'] = 1; // Active (Admin)
        }
        
        $reqDocs = $this->request->getPost('required_documents');
        if (empty($reqDocs) || count($reqDocs) > 2) {
             return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', ['required_documents' => 'Please select at most 2 required documents (CV is mandatory).']);
        }
        $data['required_documents'] = json_encode($reqDocs);

        $id = $this->model->insert($data);
        if (! $id) {
            return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/job-vacancy')->with('message-backend', 'Job Vacancy Create successfully');
    }

    /**
     * PUT/PATCH /jobVacancy/(:num)
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        
        $reqDocs = $this->request->getPost('required_documents');
        if (empty($reqDocs) || count($reqDocs) > 2) {
             return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', ['required_documents' => 'Please select at most 2 required documents (CV is mandatory).']);
        }
        $data['required_documents'] = json_encode($reqDocs);

        $data['id'] = $id;

        // Security Check for Company
        if ($this->auth->user()->user_type == 'company') {
            $vacancy = $this->model->find($id);
            if (!$vacancy) {
                 return redirect()->to(pathBack($this->request))->with('error-backend', "Job Vacancy not found");
            }
            // Ensure the vacancy belongs to the company owned by this user
            $companyModel = new CompanyModel();
            $company = $companyModel->where('user_id', $this->auth->user()->id)->first();
            
            if (!$company || $vacancy->company_id != $company->id) {
                return redirect()->to(pathBack($this->request))->with('forbiden', "You do not have permission to update this vacancy.");
            }
        }

        if (!$this->model->update($id, $data)) {
            return redirect()->to(pathBack($this->request))->withInput()->with('errors-backend', $this->model->errors());
        }

        return redirect()->to('/back-end/job-vacancy')->with('message-backend', 'Job Vacancy updated successfully');
    }

    /**
     * DELETE /jobVacancy/(:num)
     */
    public function delete($id = null)
    {
        // Security Check for Company
        if ($this->auth->user()->user_type == 'company') {
            $vacancy = $this->model->find($id);
            if (!$vacancy) {
                 return redirect()->to(pathBack($this->request))->with('error-backend', "Job Vacancy not found");
            }
            $companyModel = new CompanyModel();
            $company = $companyModel->where('user_id', $this->auth->user()->id)->first();
            
            if (!$company || $vacancy->company_id != $company->id) {
                return redirect()->to(pathBack($this->request))->with('forbiden', "You do not have permission to delete this vacancy.");
            }
        }

        $this->model->delete($id);

        return redirect()->to('/back-end/job-vacancy')->with('message-backend', 'Job Vacancy delete successfully');
    }

    /**
     * GET /jobVacancy/new
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

        return view('Backend/Application/job-vacancy-form', [
            'config' => $this->config,
            'param' => [
                'id' => null,
                'action' => 'create',
            ],
            'data' => $this->model->getEmptyRecord(),
            'token' => $token,
            'auth' => $this->auth,
            'form' => [
                'route' => str_replace('/new', '', $this->request->getPath()),
                'method' => 'POST'
            ],
            'path' => $this->request->getPath(),
        ]);
    }

    /**
     * GET /jobVacancy/(:num)/edit
     * (Opsional, kalau butuh form HTML)
     */
    public function edit($id = null)
    {
        $jobVacancy = $this->model->find($id);

        if (!$jobVacancy) {
            return redirect()->to(pathBack($this->request))->with('error-backend', "Job Vacancy with ID $id not found");
        }
        if ($this->auth->user()->user_type == 'company') {
            $companyModel = new CompanyModel();

            $company = $companyModel->where('user_id', $this->auth->user()->id)->first();
            if (!empty($company)) {
                if($this->auth->user()->id != $company->user_id){
                    return redirect()->to(pathBack($this->request))->with('forbiden', "Job Vacancy with ID $id not found");
                }
            }
            else{
                return redirect()->to(pathBack($this->request))->with('forbiden', "Job Vacancy with ID $id not found");
            }
        }
        $jwt = new \App\Libraries\JWTService();

        $payload = [
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => (string) $this->request->getUserAgent(),
            'username'      => $this->auth->user()->username
        ];

        $token = $jwt->generateToken($payload);

        return view('Backend/Application/job-vacancy-form', [
            'config' => $this->config,
            'auth' => $this->auth,
            'param' => [
                'id' => $id,
                'action' => 'edit',
            ],
            'data' => $jobVacancy,
            'token' => $token,
            'form' => [
                'route' => str_replace('/edit', '', $this->request->getPath()),
                'method' => 'PUT'
            ],
            'path' => $this->request->getPath(),
        ]);
    }


    /**
     * GET /jobVacancy/(:num)/edit
     * (Opsional, kalau butuh form HTML)
     */
    public function templateImport($id = null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Isi header
        $sheet->setCellValue('A1', 'Position');
        $sheet->setCellValue('B1', 'Country');
        $sheet->setCellValue('C1', 'Male Quota');
        $sheet->setCellValue('D1', 'Female Quota');
        $sheet->setCellValue('E1', 'Unisex Quota');
        $sheet->setCellValue('F1', 'Duration');
        $sheet->setCellValue('G1', 'Duration Type');
        $sheet->setCellValue('H1', 'Selection Date');
        $sheet->setCellValue('I1', 'Email');
        $sheet->setCellValue('J1', 'Description');
        $sheet->setCellValue('K1', 'Requirement');
        $sheet->setCellValue('L1', 'by Company');
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setWidth(30);
        $sheet->getColumnDimension('J')->setWidth(80);
        $sheet->getColumnDimension('K')->setWidth(80);
        $sheet->getColumnDimension('L')->setWidth(30);

        $durationType = ['Bulan', 'Tahun'];
        $listString = '"' . implode(',', $durationType) . '"';

        $countryModel = new CountryModel();
        $query =  $countryModel->select('name')->findAll();
        $listCountries = array_column($query, 'name');
        $listCountriesVertical = array_map(fn($item) => [$item], $listCountries);

        $sheetCountryHidden = $spreadsheet->createSheet();
        $sheetCountryHidden->setTitle('Master_Country');
        $sheetCountryHidden->fromArray($listCountriesVertical, null, 'A1');
        // $sheetHidden->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
        $lastRow = count($listCountries);
        $formulaCountries = 'Master_Country!$A$1:$A$' . $lastRow;

        $companyModel = new CompanyModel();
        $queryCompany = $companyModel->asArray()->select('id, name')->findAll();
        if ($this->auth->user()->user_type == 'company') {
            $companyModel = new CompanyModel();
            $queryCompany = $companyModel->where('user_id', $this->auth->user()->id)->asArray()->select('id, name')->findAll();
        }
        $listCompanies = array_map(fn($row) => "{$row['name']}-{$row['id']}", $queryCompany);
        $listCompaniesVertical = array_map(fn($item) => [$item], $listCompanies);

        $sheetCountryHidden = $spreadsheet->createSheet();
        $sheetCountryHidden->setTitle('Master_Company');
        $sheetCountryHidden->fromArray($listCompaniesVertical, null, 'A1');
        // $sheetHidden->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
        $lastRow = count($listCompanies);
        $formulaCompanies = 'Master_Company!$A$1:$A$' . $lastRow;

        $sheet->getStyle('H1')->getNumberFormat()
            ->setFormatCode('yyyy-mm-dd');

        for ($row = 2; $row <= 20; $row++) {
            $validation = $sheet->getCell('H' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_DATE);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Input Salah');
            $validation->setError('Hanya tanggal yang diperbolehkan!');
            $validation->setPromptTitle('Masukkan Tanggal');
            $validation->setPrompt('Silakan masukkan tanggal dalam format YYYY-MM-DD');
            $validation->setFormula1('DATE(2000,1,1)');
            $validation->setFormula2('DATE(2100,12,31)');
            $validation->setOperator(DataValidation::OPERATOR_BETWEEN);

            $validationJ = $sheet->getCell('J' . $row)->getDataValidation();
            $validationJ->setType(DataValidation::TYPE_LIST);
            $validationJ->setErrorStyle(DataValidation::STYLE_STOP);
            $validationJ->setAllowBlank(false);
            $validationJ->setShowInputMessage(true);
            $validationJ->setPrompt('Gunakan baris baru (alt+enter) agar menjadi list');

            $validationK = $sheet->getCell('K' . $row)->getDataValidation();
            $validationK->setType(DataValidation::TYPE_LIST);
            $validationK->setErrorStyle(DataValidation::STYLE_STOP);
            $validationK->setAllowBlank(false);
            $validationK->setShowInputMessage(true);
            $validationK->setPrompt('Gunakan baris baru (alt+enter) agar menjadi list');

            $validation = $sheet->getCell('B' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input salah');
            $validation->setError('Pilih salah satu negara dari dropdown.');
            $validation->setPromptTitle('Country');
            $validation->setPrompt('Pilih salah satu negara yang tersedia dari sheet Master_Country');
            $validation->setFormula1($formulaCountries);

            $validation = $sheet->getCell('G' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input salah');
            $validation->setError('Pilih salah satu tipe durasi dari dropdown.');
            $validation->setPromptTitle('Duration Type');
            $validation->setPrompt('Pilih salah satu tipe durasi yang tersedia.');
            $validation->setFormula1($listString);

            $validation = $sheet->getCell('L' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input salah');
            $validation->setError('Pilih salah satu perusahaan dari dropdown.');
            $validation->setPromptTitle('Company');
            $validation->setPrompt('Pilih salah satu perusahaan yang tersedia.');
            $validation->setFormula1($formulaCompanies);
        }

        $fileName = 'template-job-vacancy-import.xlsx';
        $writer = new Xlsx($spreadsheet);

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment;filename="' . $fileName . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($this->saveToString($writer));
    }

    private function saveToString(Xlsx $writer): string
    {
        ob_start();
        $writer->save('php://output');
        return ob_get_clean();
    }

    public function import()
    {
        $file = $this->request->getFile('excel_file');

        if (! $file->isValid()) {
            return redirect()->to(pathBack($this->request))->with('error-backend', 'File tidak valid.');
        }

        // Simpan file sementara
        $filePath = WRITEPATH . 'uploads/' . $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', basename($filePath));


        $countryModel = new CountryModel();
        $query = $countryModel->asArray()->select('id, name')->findAll();
        $listCountries = array_column($query, 'name');

        $companyModel = new CompanyModel();
        $queryCompany = $companyModel->asArray()->select('id, name')->findAll();
        $listCompanies = array_map(fn($row) => "{$row['name']}-{$row['id']}", $queryCompany);

        try {
            // Load Excel
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            array_shift($rows);

            $validation = \Config\Services::validation();
            $dataInsert = [];
            $errors = [];

            foreach ($rows as $index => $row) {

                $countryValue = trim($row['B'] ?? '');
                $companyValue = trim($row['L'] ?? '');
                $companies = explode('-', $companyValue);
                $companyId = trim(end($companies));
                $excelDate = trim($row['H'] ?? '');

                if (is_numeric($excelDate)) {
                    $dateTime = Date::excelToDateTimeObject($excelDate);
                    $dateValue = $dateTime->format('Y-m-d');
                } elseif (!empty($excelDate)) {
                    $dateValue = date('Y-m-d', strtotime($excelDate));
                }
                if (!in_array($countryValue, $listCountries)) {
                    return redirect()->to(pathBack($this->request))->with('error-backend', "Baris " . ($index + 2) . " → Negara '$countryValue' tidak ditemukan.");
                }

                if (!in_array($companyValue, $listCompanies)) {
                    return redirect()->to(pathBack($this->request))->with('error-backend', "Baris " . ($index + 2) . " → Perusahaan '$companyValue' tidak ditemukan.");
                }

                $rowData = [
                    'position'    => trim($row['A'] ?? ''),
                    'company' => trim($row['B'] ?? ''),
                    'male_quota' => trim($row['C'] ?? ''),
                    'female_quota' => trim($row['D'] ?? ''),
                    'unisex_quota' => trim($row['E'] ?? ''),
                    'duration' => trim($row['F'] ?? ''),
                    'duration_type' => trim($row['G'] ?? ''),
                    'country_id' => array_values(array_filter($query, fn($row) => $row['name'] == $countryValue))[0]['id'] ?? null,
                    'company_id' => array_values(array_filter($queryCompany, fn($row) => $row['id'] == $companyId))[0]['id'] ?? null,
                    'email' => trim($row['I'] ?? ''),
                    'description' => convertTextToUlLi(trim($row['J'] ?? '')),
                    'requirement' => convertTextToUlLi(trim($row['K'] ?? '')),
                    'selection_date' => $dateValue,
                    'status' => 1,
                ];

                if (
                    $this->model->where('position', $rowData['position'])
                    ->where('country_id', $rowData['country_id'])
                    ->where('company_id', $rowData['company_id'])
                    ->countAllResults() > 0
                ) {
                    return redirect()->to(pathBack($this->request))->with('error-backend', "Data " . $rowData['position'] . " " . $companyValue . " " . $countryValue . " already exists");
                }

                $validation->setRules([
                    'position' => 'required|min_length[3]|max_length[255]',
                    'duration'  => 'required|numeric',
                    'male_quota'      => 'required|numeric|greater_than_equal_to[0]',
                    'female_quota'      => 'required|numeric|greater_than_equal_to[0]',
                    'unisex_quota'      => 'required|numeric|greater_than_equal_to[0]',
                    'duration_type' => 'required|in_list[Bulan,Tahun]',
                    'country_id'    => 'required|integer',
                    'company_id'    => 'required|integer',
                    'email'      => 'required|min_length[3]',
                    'description'      => 'required|min_length[3]',
                    'requirement'      => 'required|min_length[3]',
                    'selection_date' => 'required|valid_date[Y-m-d]',
                ]);

                if (!$validation->run($rowData)) {
                    $rowErrors = $validation->getErrors();
                    return redirect()->to(pathBack($this->request))->with('error-backend', "Baris " . ($index + 2) . " → " . implode(', ', $rowErrors));
                }

                $dataInsert[] = $rowData;
            }

            $dataInsert = array_map(function ($data) {
                return $this->model->generateCode(['data' => $data])['data'];
            }, $dataInsert);

            if (!empty($dataInsert)) {

                $result  = $this->model->insertBatch($dataInsert);
                if (!$result) {
                    return redirect()->to(pathBack($this->request))->with('errors-backend', $this->model->errors());
                }
            }

            unlink($filePath);
            return redirect()->to(pathBack($this->request))->with('message-backend', 'Job Vacancy import successfully');
        } catch (\Throwable $e) {
            unlink($filePath);
            return redirect()->to(pathBack($this->request))->with('error-backend', 'Read file failed: ' . $e->getMessage());
        }
    }
}
