<?php

namespace App\Controllers\Api;

use App\Models\ApplicantModel;
use App\Models\EmailQueueModel;
use App\Models\JobVacancyModel;
use CodeIgniter\CLI\CLI;
use Config\Services;

class ApplicantController extends BaseController
{
    protected $config;
    protected $model;
    protected $modelJobVacancy;
    protected $modelEmailQueue;
    protected $urlBackFe = '/daftar-kepelatihan';

    public function __construct()
    {
        $this->config = config('Backend');
        $this->model  = new ApplicantModel();
        $this->modelJobVacancy  = new JobVacancyModel();
        $this->modelEmailQueue  = new EmailQueueModel();
    }

    public function dataTableNew()
    {
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');
        $searchBuilder = $request->getVar('searchBuilder');
        $filter = [
            'job_vacancy_id =' => $request->getVar('jobvacancynew'),
            'country_id =' => $request->getVar('countrynew'),
            'company_id =' => $request->getVar('companynew'),
            'gender =' => $request->getVar('gendernew'),
            'education_level =' => $request->getVar('educationlevelnew'),
            'created_at >=' => $request->getVar('submitfromnew'),
            'created_at <=' => $request->getVar('submittonew'),
        ];

        $data            = $this->model->where('applicant.status', 0)->getData($search, $order, $columns, $start, $length,  $filter);
        $recordsFiltered = $this->model->where('applicant.status', 0)->countFiltered($search);
        $recordsTotal    = $this->model->where('applicant.status', 0)->countAll();
        $formatted = array_map(fn($item) => $item->formatDataTableModel(), $data);

        return $this->response->setJSON([
            'draw'            => intval($draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $formatted,
        ]);
    }

    public function dataTableProcessed()
    {
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');
        $searchBuilder = $request->getVar('searchBuilder');
        $filter = [
            'job_vacancy_id =' => $request->getVar('jobvacancyprocess'),
            'country_id =' => $request->getVar('countryprocess'),
            'company_id =' => $request->getVar('companyprocess'),
            'gender =' => $request->getVar('genderprocess'),
            'education_level =' => $request->getVar('educationlevelprocess'),
            'created_at >=' => $request->getVar('submitfromprocess'),
            'created_at <=' => $request->getVar('submittoprocess'),
        ];

        $data            = $this->model->where('applicant.status', 2)->getData($search, $order, $columns, $start, $length,  $filter);
        $recordsFiltered = $this->model->where('applicant.status', 2)->countFiltered($search);
        $recordsTotal    = $this->model->where('applicant.status', 2)->countAll();
        $formatted = array_map(fn($item) => $item->formatDataTableModel(), $data);

        return $this->response->setJSON([
            'draw'            => intval($draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $formatted,
        ]);
    }

    public function dataTableApproved()
    {
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');
        $searchBuilder = $request->getVar('searchBuilder');
        $filter = [
            'job_vacancy_id =' => $request->getVar('jobvacancyapproved'),
            'country_id =' => $request->getVar('countryapproved'),
            'company_id =' => $request->getVar('companyapproved'),
            'gender =' => $request->getVar('genderapproved'),
            'education_level =' => $request->getVar('educationlevelapproved'),
            'created_at >=' => $request->getVar('submitfromapproved'),
            'created_at <=' => $request->getVar('submittoapproved'),
        ];

        $data            = $this->model->where('applicant.status', 1)->getData($search, $order, $columns, $start, $length,  $filter);
        $recordsFiltered = $this->model->where('applicant.status', 1)->countFiltered($search);
        $recordsTotal    = $this->model->where('applicant.status', 1)->countAll();
        $formatted = array_map(fn($item) => $item->formatDataTableModel(), $data);

        return $this->response->setJSON([
            'draw'            => intval($draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $formatted,
        ]);
    }

    public function dataTableRejected()
    {
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');
        $filter = [
            'job_vacancy_id =' => $request->getVar('jobvacancyrejected'),
            'country_id =' => $request->getVar('countryrejected'),
            'company_id =' => $request->getVar('companyrejected'),
            'gender =' => $request->getVar('genderrejected'),
            'education_level =' => $request->getVar('educationlevelrejected'),
            'created_at >=' => $request->getVar('submitfromrejected'),
            'created_at <=' => $request->getVar('submittorejected'),
        ];

        $data            = $this->model->where('applicant.status', -1)->getData($search, $order, $columns, $start, $length,  $filter);
        $recordsFiltered = $this->model->where('applicant.status', -1)->countFiltered($search);
        $recordsTotal    = $this->model->where('applicant.status', -1)->countAll();
        $formatted = array_map(fn($item) => $item->formatDataTableModel(), $data);

        return $this->response->setJSON([
            'draw'            => intval($draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $formatted,
        ]);
    }

    public function create()
    {
        file_put_contents(WRITEPATH . 'logs/debug_custom.log', "[CONTROLLER] ApplicantController::create triggered via AJAX\n", FILE_APPEND);
        helper(['form', 'url', 'filesystem', 'text', 'session']);
        $request = service('request');
        $data = $request->getPost();
        helper('session');

        $slugs = explode('-', $data['slug']);
        $id = end($slugs);
        
        if (!is_numeric((int)shortDecrypt($id))) {
             return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid ID or Slug.'
            ]);
        }

        $dataJobVacancy = $this->modelJobVacancy->where('id', (int)shortDecrypt($id))->first();

        if (empty($dataJobVacancy)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Lowongan tidak ditemukan.'
            ]);
        }

        // CAPTCHA CHECK
        $captchaInput = strtolower(trim($request->getPost('captcha')));
        $captchaSession = session()->get('captcha_text');
        $created = session()->get('captcha_time') ?? 0;

        // Clean up session immediately
        session()->remove(['captcha_text', 'captcha_time']);

        if (time() - $created > 120) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Captcha sudah kadaluarsa, silakan refresh.'
            ]);
        }

        if (empty($captchaInput) || $captchaInput !== $captchaSession) {
             return $this->response->setJSON([
                'success' => false,
                'message' => 'Captcha salah.'
            ]);
        }

        // FILE UPLOAD LOGIC
        $uploadedDocs = [];
        $allowedKeys = ['cv', 'language_cert', 'skill_cert', 'other'];
        $requiredDocs = !empty($dataJobVacancy->required_documents) ? $dataJobVacancy->getNormalizedRequiredDocuments() : ['cv']; // Default to CV if empty
        
        foreach ($allowedKeys as $key) {
            $file = $request->getFile($key);
            if ($file && $file->isValid()) {
                // Limit 2MB = 2097152 bytes
                $filePath = upload_file_confidential($key, 'storage/file/applicant/' . $key, $request->getPost('first_name') . '-' . slugify($request->getPost('email')) . '-' . $key, 2097152);
                
                if (!$filePath['success']) {
                     return $this->response->setJSON([
                        'success' => false,
                        'message' => "Gagal mengunggah $key: " . $filePath['error']
                    ]);
                }
                
                $uploadedDocs[$key] = str_replace('storage/file/applicant/', '', $filePath['path']);
                
                if ($key === 'cv') {
                    $data['file_cv'] = $uploadedDocs[$key];
                }
            }
        }
        
        // Validation: Check if all required docs are present
        foreach ($requiredDocs as $req) {
            if (!isset($uploadedDocs[$req])) {
                 return $this->response->setJSON([
                    'success' => false,
                    'message' => "Dokumen wajib belum diunggah: " . strtoupper($req)
                ]);
            }
        }
        
        $data['documents'] = json_encode($uploadedDocs);
        $data['job_vacancy_id'] = (int)shortDecrypt($id);
        
        // INSERT APPLICANT
        $insertId = $this->model->insert($data);
        if (!$insertId) {
            $errors = $this->model->errors();
            $msg = is_array($errors) ? implode(', ', $errors) : $errors;
            
            if (stripos($msg, 'Duplicate entry') !== false) {
                $msg = 'Email sudah digunakan, silakan pakai yang lain.';
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => $msg
            ]);
        }

        // UPDATE VACANCY COUNT
        $dataJobVacancy->applicant = $dataJobVacancy->applicant + 1;
        $this->modelJobVacancy->update($dataJobVacancy->id, $dataJobVacancy);

        // QUEUE EMAIL
        $name = $data['first_name'] . ' ' . $data['last_name'];
        $position = $dataJobVacancy->position . ' ' . $dataJobVacancy->country->name;
        $subject = 'Applicant ' . $dataJobVacancy->position . ' ' . $dataJobVacancy->country->name;
        $body = "<p>Dengan hormat PT  <strong>{$dataJobVacancy->company->name}</strong>,</p>
            <p>Saya <strong>{$name}</strong></p>
            <p>Melamar pada jabatan <strong>{$position}</strong>.<br/>Detail lamaran ada pada list lowongan website jariklurik, <a href=" . env('app.baseBackendURL') . "/job-vacancy/" . $dataJobVacancy->id . "/edit" . ">klik disini</a></p>
            <p>Terima kasih,<br>Jariklurik</p>";

        $insertEmail = [
            'to_email' => $dataJobVacancy->company->email,
            'from_email' => env('email.fromEmail'),
            'subject' => $subject,
            'body' => $body,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->modelEmailQueue->insert($insertEmail);

        // TRIGGER EMAIL SEND (Background logic kept simple for now)
        // Note: In a real async setup, we wouldn't wait for CLI logic here, 
        // but keeping it as-is for now minus the direct CLI writes if not needed.
        // For standard HTTP request, we return success immediately.
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Terima kasih sudah Melamar!'
        ]);
    }
}
