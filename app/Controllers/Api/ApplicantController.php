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
        $request = service('request');
        $data = $request->getPost();
        helper('session');

        $slugs = explode('-', $data['slug']);
        $id = end($slugs);
        if (is_numeric((int)shortDecrypt($id))) {
            $dataJobVacancy = $this->modelJobVacancy->where('id', (int)shortDecrypt($id))->first();

            if (!empty($dataJobVacancy)) {
                $captchaInput = strtolower(trim($request->getPost('captcha')));
                $captchaSession = session()->get('captcha_text');

                $created = session()->get('captcha_time') ?? 0;
                if (time() - $created > 120) {
                    session()->remove(['captcha_text', 'captcha_time']);
                    return redirect()
                        ->to($data['slug'])
                        ->withInput()
                        ->with('error', 'Captcha sudah kadaluarsa, silakan refresh.');
                }

                if (empty($captchaInput) || $captchaInput !== $captchaSession) {
                    session()->remove(['captcha_text', 'captcha_time']);

                    return redirect()
                        ->to($data['slug'])
                        ->withInput()
                        ->with('error', 'Captcha salah.');;
                }

                session()->remove(['captcha_text', 'captcha_time']);
                $filePath = upload_file_confidential('file_cv', 'storage/file/applicant/cv', $request->getPost('first_name') . '-' . slugify($request->getPost('email')) . '-cv', 5242880);
                if (!$filePath['success']) {
                    return redirect()->to($data['slug'])
                        ->withInput()
                        ->with('error', $filePath['error']);
                }

                $data['file_cv'] = str_replace('storage/file/applicant/', '', $filePath['path']);
                $data['job_vacancy_id'] = (int)shortDecrypt($id);
                $id = $this->model->insert($data);
                if (! $id) {
                    if (stripos(implode(', ', $this->model->errors()), 'Duplicate entry') !== false) {
                        return redirect()->to($data['slug'])
                            ->withInput()
                            ->with('error', 'Email sudah digunakan, silakan pakai yang lain.');
                    }

                    return redirect()
                        ->to($data['slug'])
                        ->withInput()
                        ->with('error', $this->model->errors());
                }


                $dataJobVacancy->applicant = $dataJobVacancy->applicant + 1;
                
                $this->modelJobVacancy->update($dataJobVacancy->id, $dataJobVacancy);

                $name = $data['first_name'] . ' ' . $data['last_name'];
                $position = $dataJobVacancy->position . ' ' . $dataJobVacancy->country->name;
                $subject = 'Applicant ' . $dataJobVacancy->position . ' ' . $dataJobVacancy->country->name;
                $body = "<p>Dengan hormat PT  <strong>{$dataJobVacancy->company->name}</strong>,</p>
                    <p>Saya <strong>{$name}</strong></p>
                    <p>Melamar pada jabatan <strong>{$position}</strong>.<br/>Detail lamaran ada pada list lowongan website jariklurik, <a href=" . env('app.baseBackendURL') . "/job-vacancy/" . $dataJobVacancy->id . "/edit" . ">klik disini</a></p>
                    <p>Terima kasih,<br>Jariklurik</p>";

                $insertData[] = [
                    'to_email' => $dataJobVacancy->company->email,
                    'from_email' => env('email.fromEmail'),
                    'subject' => $subject,
                    'body' => $body,
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if (!empty($insertData)) {
                    $this->modelEmailQueue->insertBatch($insertData);
                } else {
                }

                CLI::write('Starting send emails job: ' . date('Y-m-d H:i:s'), 'yellow');

                $emailModel = new EmailQueueModel();

                // Ambil misal 50 pesan yang belum dikirim
                $toSend = $emailModel->where('status', 'pending')
                    ->where('subject', $subject)
                    ->orderBy('created_at', 'ASC')
                    ->findAll(50);

                if (empty($toSend)) {
                    CLI::write('No emails to send.', 'green');
                    return;
                }

                $emailService = Services::email();

                foreach ($toSend as $row) {
                    try {
                        // Atur konfigurasi email dari env atau global config
                        $emailService->clear();
                        $emailService->setFrom($row['from_email'] ?? env('email.fromEmail'));
                        $emailService->setTo($row['to_email']);
                        $emailService->setSubject($row['subject']);
                        $emailService->setMessage($row['body']);
                        $emailService->setMailType('html');

                        // Kirim
                        if ($emailService->send()) {
                            $emailModel->update($row['id'], [
                                'status' => 'sent',
                                'sent_at' => date('Y-m-d H:i:s'),
                                'error' => null,
                                'id' => $row['id']
                            ]);
                            CLI::write("Sent: {$row['to_email']}", 'green');
                        } else {
                            $err = $emailService->printDebugger(['headers']);
                            $emailModel->update($row['id'], [
                                'status' => 'failed',
                                'error' => substr($err, 0, 1000),
                                'id' => $row['id']
                            ]);
                            CLI::write("Failed: {$row['to_email']} — " . trim($err), 'red');
                        }

                        sleep(1); // sesuaikan jika perlu
                    } catch (\Exception $e) {
                        $emailModel->update($row['id'], [
                            'status' => 'failed',
                            'error' => $e->getMessage()
                        ]);
                        CLI::write("Exception: {$e->getMessage()}", 'red');
                    }
                }

                CLI::write('Job finished: ' . date('Y-m-d H:i:s'), 'yellow');
            } else {
                return redirect()
                    ->to($data['slug'])
                    ->withInput()
                    ->with('error', 'Lowongan tidak ditemukan');
            }
        } else {
            return redirect()
                ->to($data['slug'])
                ->withInput();
        }
        return redirect()->to('/thank-you-registered')->with('message', 'Terima kasih sudah Melamar!');
    }
}
