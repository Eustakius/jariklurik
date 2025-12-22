<?php

namespace App\Controllers\Api;

use App\Models\JobSeekerModel;
use App\Models\TrainingTypeModel;

class JobSeekerController extends BaseController
{
    protected $config;
    protected $model;
    protected $urlBackFe = '/daftar-kepelatihan';
    protected $modelTriningType;

    public function __construct()
    {
        $this->config = config('Backend');
        $this->model  = new JobSeekerModel();
        $this->modelTriningType  = new TrainingTypeModel();
    }
    
    public function dataTableNew()
    {
        $model = new JobSeekerModel();
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');
        $searchBuilder = $request->getVar('searchBuilder');
        $filter = [
           'training_type_id =' => $request->getVar('trainingnew'),
           'gender =' => $request->getVar('gendernew'),
           'education_level =' => $request->getVar('educationlevelnew'),
           'created_at >=' => $request->getVar('submitfromnew'),
           'created_at <=' => $request->getVar('submittonew'),
        ];

        $data            = $model->where('job_seekers.status', 0)->getData($search, $order, $columns, $start, $length,  $filter);
        $recordsFiltered = $model->where('job_seekers.status', 0)->countFiltered($search);
        $recordsTotal    = $model->where('job_seekers.status', 0)->countAll();
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
        $model = new JobSeekerModel();
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');
        $searchBuilder = $request->getVar('searchBuilder');
        $filter = [
           'training_type_id =' => $request->getVar('trainingapproved'),
           'gender =' => $request->getVar('genderapproved'),
           'education_level =' => $request->getVar('educationlevelapproved'),
           'created_at >=' => $request->getVar('submitfromapproved'),
           'created_at <=' => $request->getVar('submittoapproved'),
        ];
        
        $data            = $model->where('job_seekers.status', 1)->getData($search, $order, $columns, $start, $length,  $filter);
        $recordsFiltered = $model->where('job_seekers.status', 1)->countFiltered($search);
        $recordsTotal    = $model->where('job_seekers.status', 1)->countAll();
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
        $model = new JobSeekerModel();
        $request = service('request');

        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order  = $request->getVar('order');
        $columns = $request->getVar('columns');
        $filter = [
           'training_type_id =' => $request->getVar('trainingrejected'),
           'gender =' => $request->getVar('genderrejected'),
           'education_level =' => $request->getVar('educationlevelrejected'),
           'created_at >=' => $request->getVar('submitfromrejected'),
           'created_at <=' => $request->getVar('submittorejected'),
        ];

        $data            = $model->where('job_seekers.status', -1)->getData($search, $order, $columns, $start, $length,  $filter);
        $recordsFiltered = $model->where('job_seekers.status', -1)->countFiltered($search);
        $recordsTotal    = $model->where('job_seekers.status', -1)->countAll();
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
        $request = service('request');
        $data = $request->getPost();
        helper('session');

        $captchaInput = strtolower(trim($request->getPost('captcha')));
        $captchaSession = session()->get('captcha_text');

        // Optional: cek waktu expired (mis. 2 menit)
        $created = session()->get('captcha_time') ?? 0;
        if (time() - $created > 120) {
            session()->remove(['captcha_text', 'captcha_time']);
            return redirect()
                ->to($this->urlBackFe)
                ->withInput()
                ->with('error', 'Captcha sudah kadaluarsa, silakan refresh.');
        }

        if (empty($captchaInput) || $captchaInput !== $captchaSession) {
            session()->remove(['captcha_text', 'captcha_time']);

            return redirect()
                ->to($this->urlBackFe)
                ->withInput()
                ->with('error', 'Captcha salah.');;
        }

        session()->remove(['captcha_text', 'captcha_time']);
        $filePath = upload_file_confidential('file_statement', 'storage/file/job-seeker/statement-letter', $request->getPost('name') . '-' . slugify($request->getPost('email')) . '-statement-letter');
        if (!$filePath['success']) {
            return redirect()->to($this->urlBackFe)
                ->withInput()
                ->with('error', $filePath['error']);
        }
        
        $data['file_statement'] = str_replace('storage/file/job-seeker/','',$filePath['path']);

        $data['training_type_id'] = $data['training_type']; 
        
        $id = $this->model->insert($data);
        
        if (! $id) {
            if (stripos(implode(', ', $this->model->errors()), 'Duplicate entry') !== false) {
                return redirect()->to($this->urlBackFe)
                    ->withInput()
                    ->with('error', 'Email sudah digunakan, silakan pakai yang lain.');
            }

            return redirect()
                ->to($this->urlBackFe)
                ->withInput()
                ->with('error', $this->model->errors());
        }

        $item = $this->model->find($id);
        $dataTriningType = $this->modelTriningType->where('id', $item->training_type_id)->first();
        if (!empty($dataTriningType)) {
            if($dataTriningType->quota_used < $dataTriningType->quota && $dataTriningType->quota > 0)
            {
                $dataTriningType->quota_used = $dataTriningType->quota_used + 1;
            }

            $this->modelTriningType->update($item->training_type_id, $dataTriningType);
        }    

        return redirect()->to('/thank-you-registered')->with('message', 'Terima kasih sudah Mendaftar!');
    }
}
