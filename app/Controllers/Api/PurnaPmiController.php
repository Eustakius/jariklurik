<?php

namespace App\Controllers\Api;

use App\Models\PurnaPmiModel;
use App\Models\TrainingTypeModel;

class PurnaPmiController extends BaseController
{
    protected $config;
    protected $model;
    protected $urlBackFe = '/daftar-kepelatihan-purna-pmi';
    protected $modelTriningType;

    public function __construct()
    {
        $this->config = config('Backend');
        $this->model  = new PurnaPmiModel();
        $this->modelTriningType  = new TrainingTypeModel();
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
        $filter = [
            'training_type_id =' => $request->getVar('trainingnew'),
            'gender =' => $request->getVar('gendernew'),
            'education_level =' => $request->getVar('educationlevelnew'),
            'created_at >=' => $request->getVar('submitfromnew'),
            'created_at <=' => $request->getVar('submittonew'),
        ];

        $data            = $this->model->where('purna_pmi.status', 0)->getData($search, $order, $columns, $start, $length, $filter);
        $recordsFiltered = $this->model->where('purna_pmi.status', 0)->countFiltered($search);
        $recordsTotal    = $this->model->where('purna_pmi.status', 0)->countAll();
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
        $filter = [
            'training_type_id =' => $request->getVar('trainingapproved'),
            'gender =' => $request->getVar('genderapproved'),
            'education_level =' => $request->getVar('educationlevelapproved'),
            'created_at >=' => $request->getVar('submitfromapproved'),
            'created_at <=' => $request->getVar('submittoapproved'),
        ];
        $data            = $this->model->where('purna_pmi.status', 1)->getData($search, $order, $columns, $start, $length, $filter);
        $recordsFiltered = $this->model->where('purna_pmi.status', 1)->countFiltered($search);
        $recordsTotal    = $this->model->where('purna_pmi.status', 1)->countAll();
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
            'training_type_id =' => $request->getVar('trainingrejected'),
            'gender =' => $request->getVar('genderrejected'),
            'education_level =' => $request->getVar('educationlevelrejected'),
            'created_at >=' => $request->getVar('submitfromrejected'),
            'created_at <=' => $request->getVar('submittorejected'),
        ];
        $data            = $this->model->where('purna_pmi.status', -1)->getData($search, $order, $columns, $start, $length, $filter);
        $recordsFiltered = $this->model->where('purna_pmi.status', -1)->countFiltered($search);
        $recordsTotal    = $this->model->where('purna_pmi.status', -1)->countAll();
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
        $filePath = upload_file_confidential('file', 'storage/file/job-seeker/stamp-passport-imigrasi', $request->getPost('name') . '-' . slugify($request->getPost('email')) . '-stamp-passport-imigrasi');
        if (!$filePath['success']) {
            return redirect()->to($this->urlBackFe)
                ->withInput()
                ->with('error', $filePath['error']);
        }

        $data['file'] = str_replace('storage/file/job-seeker/', '', $filePath['path']);

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
            else{
                return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('error-backend', 'Quota Full !!');
            }           

            $this->modelTriningType->update($item->training_type_id, $dataTriningType);
        } 
        else {
            return redirect()->to('/back-end/training/purna-pmi')->with('key', $data['key'])->with('error-backend', 'Training Type Not Found !!');
        }
        return redirect()->to('/thank-you-registered')->with('message', 'Terima kasih sudah Mendaftar!');
    }
}
