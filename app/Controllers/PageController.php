<?php

namespace App\Controllers;

use App\Libraries\ModelService;
use App\Models\AuthActivationAttemptModel;
use App\Models\JobVacancyModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class PageController extends BaseController
{
    protected $config;

    public function __construct()
    {
        $this->config = config('Frontend');
    }

    public function index($slug = null): string
    {
        $page = $this->request->getPath();
        $page = $page === "" ? "lowongan-kerja" : $page;
        $data = null;
        $meta = (object)[   
            'title' => 'Jariklurik ' . ($page === "" ? "" : " - ". ucfirst(str_replace("-"," ",$page))),
            'description' => 'Jariklurik - Job info menarik luar negeri sekali klik' .($page === "" ? "" : ", ". str_replace("/","",$page)),
            'keywords' => 'Jariklurik - Job info menarik luar negeri sekali klik'. ($page === "" ? "" : ", ". str_replace("/","",$page))
        ];
        if (isset($slug)) {
            $slugs = explode('-', $slug);
            $id = end($slugs);
            if (is_numeric((int)shortDecrypt($id))) {
                // dd((int)shortDecrypt($id));
                $jobVacancyModel = new JobVacancyModel();
                $data = $jobVacancyModel->where('status', 1)->where('id', (int)shortDecrypt($id))->first();
                if (!empty($data)) {
                    $data->visitor = $data->visitor + 1;
                    $data->status = $data->status;
                    // dd($data);
                    $jobVacancyModel->update($data->id, $data);
                    $data = (object)$data->formatDataFrontendDetailModel();
                    $page = 'detail-job-vacancy';
                    $meta = (object)[   
                        'title' => 'Jariklurik - ' . $data->position . ' ' . $data->country,
                        'description' => 'Jariklurik - Job info menarik luar negeri sekali klik ' . $data->position . ' ' . $data->country . ' ' . $data->company->name . ' Durasi Kontrak '. $data->duration,
                        'keywords' => 'Jariklurik - Job info menarik luar negeri sekali klik, '.$data->position . ' ' . $data->country.', '.$data->company->name
                    ];
                }
            }
        }
        $jwt = new \App\Libraries\JWTService();
        $payload = (object)[
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => 'frontend-' . (string) $this->request->getUserAgent(),
        ];
        $token = $jwt->generateToken([
            'ip_address' => $payload->ip_address,
            'user_agent' => $payload->user_agent,
            'time' => date('Y-m-d H:i:s')
        ]);
        $authActivationAttemptModel = model(AuthActivationAttemptModel::class);
        $authActivationAttempt = $authActivationAttemptModel->findToken($payload);
        if (!empty($authActivationAttempt)) {
            $authActivationAttemptModel->update(
                $authActivationAttempt->id,
                ['token' => $token]
            );
        } else {
            $authActivationAttemptModel->insert(
                [
                    'token' => $token,
                    'ip_address' => $payload->ip_address,
                    'user_agent' => $payload->user_agent
                ]
            );
        }

        // ---------------------------------
        
        $sections = array_filter($this->config->sections, function ($key) use ($page) {
            return $key === $page;
        }, ARRAY_FILTER_USE_KEY);
        $sectionList = reset($sections);
        if (empty($sectionList)) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        usort($sectionList, function ($a, $b) {
            return $a['id'] <=> $b['id'];
        });
        $dataMaster = null;
        foreach ($sectionList as $section) {
            if (!empty($section['master'])) {
                foreach ($section['master'] as $master) {
                    $dataMaster[$master['key']] = (new ModelService())->dataMaster($master['key'], $slug);
                }
            }
        }

        return view('page', [
            'token' => $token,
            'config' => $this->config,
            'page' => $page,
            'meta' => $meta,
            'data' => $data,
            'sections' => $sectionList,
            'data_master' => $dataMaster,
            'loading' => view('Sections/spinner')
        ]);
    }
    public function thankYou()
    {
        // session()->setFlashdata('message', 'Terima kasih, lamaran Anda telah kami terima.');
        
        // Render the page using the 'thank-you-registered' section from configuration
        $page = 'thank-you-registered';
        
        $meta = (object)[   
            'title' => 'Jariklurik - Terima Kasih',
            'description' => 'Terima kasih telah mendaftar di Jariklurik.',
            'keywords' => 'jariklurik, terima kasih, lamaran'
        ];

        // Basic Token Logic (reused from index) - simplified
        $jwt = new \App\Libraries\JWTService();
        $payload = (object)[
            'ip_address'    => $this->request->getIPAddress(),
            'user_agent'    => 'frontend-' . (string) $this->request->getUserAgent(),
        ];
        $token = $jwt->generateToken([
            'ip_address' => $payload->ip_address,
            'user_agent' => $payload->user_agent,
            'time' => date('Y-m-d H:i:s')
        ]);
        
        // Ideally we should reuse the auth tracking logic or extract it to a private method, 
        // but for now keeping it inline to avoid breaking changes in other methods.
        
        $sections = array_filter($this->config->sections, function ($key) use ($page) {
            return $key === $page;
        }, ARRAY_FILTER_USE_KEY);
        $sectionList = reset($sections);
        
        if (empty($sectionList)) {
            // Should not happen if config is correct
            throw PageNotFoundException::forPageNotFound();
        }
        
         // Add the message manually to the data passed to the view, 
         // since sending it via Flashdata on a separate redirect request might be tricky if session is lost 
         // But notification.php uses session()->getFlashdata('message').
         // So we MUST set it here.
         session()->setFlashdata('message', 'Terima kasih, formulir Anda berhasil dikirim! Tim kami akan segera meninjau lamaran Anda.');

        return view('page', [
            'token' => $token,
            'config' => $this->config,
            'page' => $page,
            'meta' => $meta,
            'data' => null, // No specific data needed for static thank you
            'sections' => $sectionList,
            'data_master' => null,
            'loading' => view('Sections/spinner')
        ]);
    }


}
