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
            'purna_pmi.status =' => 0,
        ];

        $data            = $this->model->getData($search, $order, $columns, $start, $length, $filter);
        $recordsFiltered = $this->model->countFiltered($search, $filter);
        $recordsTotal    = $this->model->getDataTableQuery(null, null, null, null, null, ['purna_pmi.status =' => 0])->countAllResults();
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
            'purna_pmi.status =' => 1,
        ];
        $data            = $this->model->getData($search, $order, $columns, $start, $length, $filter);
        $recordsFiltered = $this->model->countFiltered($search, $filter);
        $recordsTotal    = $this->model->getDataTableQuery(null, null, null, null, null, ['purna_pmi.status =' => 1])->countAllResults();
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
            'purna_pmi.status =' => -1,
        ];
        $data            = $this->model->getData($search, $order, $columns, $start, $length, $filter);
        $recordsFiltered = $this->model->countFiltered($search, $filter);
        $recordsTotal    = $this->model->getDataTableQuery(null, null, null, null, null, ['purna_pmi.status =' => -1])->countAllResults();
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

    public function massRevert()
    {
        $request = service('request');
        // Handle both JSON and Form Data
        $data = $request->getJSON(true);
        $ids = $data['ids'] ?? $request->getVar('ids');
        
        // Ensure ids is an array (Handle comma separated string if any)
        if (!empty($ids) && !is_array($ids)) {
            // Should be array from getVar('ids') if submitted as ids[]
            // But if submitted as ids=1,2,3
            $ids = explode(',', $ids);
        }
        
        $ids = $ids ?? [];

        if (empty($ids)) {
            return $this->response->setJSON([
                'status' => 'error',
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
                     // Only increment if coming from Rejected
                     if($dataTriningType->quota_used < $dataTriningType->quota && $dataTriningType->quota > 0)
                     {
                         $dataTriningType->quota_used = $dataTriningType->quota_used + 1;
                         $this->modelTriningType->update($item->training_type_id, $dataTriningType);
                     }
                     else{
                         // Quota Full
                         $errors[] = "Item ID $id: Training Quota Full.";
                         continue;
                     } 
                }
                
                $this->model->update($id, ['status' => 0]);
                $successCount++;
                
            } catch (\Exception $e) {
                $errors[] = "Item ID $id: " . $e->getMessage();
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "$successCount items reverted.",
            'errors' => $errors
        ]);
    }

    public function massProcess()
    {
        return $this->massRevert();
    }

    public function massApprove()
    {
        $request = service('request');
        $data = $request->getJSON(true);
        $ids = $data['ids'] ?? $request->getVar('ids');
        $ids = $ids ?? [];

        if (empty($ids)) {
            return $this->response->setJSON([
                'status' => 'error',
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

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "$successCount items approved.",
            'errors' => $errors
        ]);
    }

    public function massReject()
    {
        $request = service('request');
        $data = $request->getJSON(true);
        $ids = $data['ids'] ?? $request->getVar('ids');
        $ids = $ids ?? [];

        if (empty($ids)) {
            return $this->response->setJSON([
                'status' => 'error',
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

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "$successCount items rejected.",
            'errors' => $errors
        ]);
    }
}
