<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\CompanyModel;
use App\Models\JobVacancyModel;

class DashboardController extends BaseController
{
    protected $config;
    protected $auth;

    public function __construct()
    {
        $this->config = config('Backend');
        $this->auth         = service('authentication');
    }
    public function index(): string
    {
        $jobVacancyModel = new JobVacancyModel();
        $baseQuery = $jobVacancyModel->where('deleted_at', null);
        
        if ($this->auth->user()->user_type === 'company') {
            $company = (new CompanyModel())
                ->where('user_id', $this->auth->user()->id)
                ->first();

            if (!empty($company)) {                
                $jobVacancyCount = (clone $baseQuery)->where('company_id', $company->id)->countAllResults();
                $jobVacancyActiveCount = (clone $baseQuery)
                    ->where('selection_date >', date('Y-m-d'))
                    ->where('status', 1)
                    ->where('company_id', $company->id)
                    ->countAllResults();
                $jobVacancyNotActiveCount = (clone $baseQuery)
                    ->where('status', 0)
                    ->where('company_id', $company->id)
                    ->countAllResults();
                $jobVacancyExpiredCount = (clone $baseQuery)
                    ->where('company_id', $company->id)
                    ->where('selection_date <', date('Y-m-d'))
                    ->countAllResults();

            }
            else{
                $jobVacancyCount = 0;
                $jobVacancyActiveCount = 0;
                $jobVacancyNotActiveCount = 0;
                $jobVacancyExpiredCount = 0;
            }
        }
        else{

            $jobVacancyCount = (clone $baseQuery)->countAllResults();
            $jobVacancyActiveCount = (clone $baseQuery)
                ->where('selection_date >', date('Y-m-d'))
                ->where('status', 1)
                ->countAllResults();
            $jobVacancyNotActiveCount = (clone $baseQuery)
                ->where('status', 0)
                ->countAllResults();
            $jobVacancyExpiredCount = (clone $baseQuery)
                ->where('selection_date <', date('Y-m-d'))
                ->countAllResults();

        }


        return view('Backend/dashboard', [
            'config' => $this->config,
            'jobVacancyCount' => $jobVacancyCount,
            'jobVacancyExpiredCount' => $jobVacancyExpiredCount,
            'jobVacancyActiveCount' => $jobVacancyActiveCount,
            'jobVacancyNotActiveCount' => $jobVacancyNotActiveCount,
        ]);
    }
}
