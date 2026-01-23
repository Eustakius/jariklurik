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


        // Initialize Default Chart Data
        $echartsData = [];

        try {
            // Chart Data: Applicants from Dec 2025 to Present (DAILY Granularity)
            $applicantModel = new \App\Models\ApplicantModel();
            
            $startDate = new \DateTime('2024-01-01');
            $endDate   = new \DateTime(); 
            
            $builder = $applicantModel->builder();
            $builder->select("
                DATE(applicant.created_at) as date_log, 
                SUM(CASE WHEN applicant.status = 1 THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN applicant.status = -1 THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN applicant.status = 2 OR applicant.status = 0 THEN 1 ELSE 0 END) as pending
            ");
            // Note: status 0 = New, 2 = Process. Grouping both as 'pending'/'in review' for the chart.

            $builder->join('job_vacancy', 'job_vacancy.id = applicant.job_vacancy_id', 'left');
            $builder->where('applicant.created_at >=', $startDate->format('Y-m-d 00:00:00'));
            
            if ($this->auth->user()->user_type === 'company' && !empty($company)) {
                 $builder->where('job_vacancy.company_id', $company->id);
            }
            // Ensure deleted vacancies are not counted if consistent with other views, 
            // but for historical trends maybe we keep them? 
            // ApplicantController doesn't filter deleted vacancies explicitly in lists unless joined.
            // keeping safe.
            
            $builder->groupBy('date_log'); // Alias from SELECT
            $builder->orderBy('date_log', 'ASC');
            
            $queryResults = $builder->get()->getResultArray();
            
            // Map results to date key
            $dataMap = [];
            foreach ($queryResults as $row) {
                $dataMap[$row['date_log']] = $row;
            }

            // Fill gaps 
            $period = new \DatePeriod(
                $startDate,
                new \DateInterval('P1D'),
                $endDate->modify('+1 day') 
            );

            foreach ($period as $dt) {
                $dateKey = $dt->format('Y-m-d');
                $timestamp = $dt->getTimestamp() * 1000; // JS ms
                
                if (isset($dataMap[$dateKey])) {
                    $echartsData[] = [
                        'date' => $timestamp, 
                        'approved' => (int)$dataMap[$dateKey]['approved'],
                        'rejected' => (int)$dataMap[$dateKey]['rejected'],
                        'pending'  => (int)$dataMap[$dateKey]['pending']
                    ];
                } else {
                    $echartsData[] = [
                        'date' => $timestamp,
                        'approved' => 0,
                        'rejected' => 0,
                        'pending'  => 0
                    ];
                }
            }

            // --- Visitor Statistics (Unique Visitors) ---
            
            // 1. Total Unique Visitors (count distinct IP + device + date combinations)
            $webVisitorModel = new \App\Models\WebVisitorModel();
            $totalVisitors = $webVisitorModel->db->table('web_visitors')
                ->select('COUNT(DISTINCT CONCAT(COALESCE(ip_address, ""), "-", COALESCE(device_fingerprint, ""), "-", COALESCE(visit_date, ""))) as unique_count')
                ->get()
                ->getRow()
                ->unique_count ?? 0;

            // 2. Unique Visitor Growth (Monthly)
            $growthQuery = $webVisitorModel->db->table('web_visitors')
                ->select("DATE_FORMAT(visit_date, '%Y-%m') as ym, DATE_FORMAT(visit_date, '%b') as month, COUNT(DISTINCT CONCAT(ip_address, '-', device_fingerprint, '-', visit_date)) as count")
                ->where('visit_date IS NOT NULL')
                ->groupBy('ym')
                ->orderBy('ym', 'ASC')
                ->limit(12)
                ->get()
                ->getResultArray();
            
            $visitorGrowth['categories'] = [];
            $visitorGrowth['data'] = [];
            foreach ($growthQuery as $row) {
                $visitorGrowth['categories'][] = $row['month'];
                $visitorGrowth['data'][] = (int)$row['count'];
            }
            if (empty($visitorGrowth['categories'])) {
                 // Fallback if empty
                 $visitorGrowth['categories'] = [date('M')];
                 $visitorGrowth['data'] = [0];
            }

            // 3. Unique Visitors by Device Type (Traffic Sources)
            $deviceQuery = $webVisitorModel->db->table('web_visitors')
                ->select("device_type, COUNT(DISTINCT CONCAT(ip_address, '-', device_fingerprint, '-', visit_date)) as count")
                ->where('device_type IS NOT NULL')
                ->groupBy('device_type')
                ->orderBy('count', 'DESC')
                ->get()
                ->getResultArray();
            
            $trafficSources['labels'] = [];
            $trafficSources['series'] = [];
            foreach ($deviceQuery as $row) {
                 $trafficSources['labels'][] = $row['device_type'] ?: 'Unknown';
                 $trafficSources['series'][] = (int)$row['count'];
            }

        } catch (\Exception $e) {
            log_message('error', 'Dashboard Chart Error: ' . $e->getMessage());
            $totalVisitors = 0;
            $visitorGrowth = ['categories' => [], 'data' => []];
            $trafficSources = ['labels' => [], 'series' => []];
        }

        return view('Backend/dashboard', [
            'config' => $this->config,
            'jobVacancyCount' => $jobVacancyCount,
            'jobVacancyExpiredCount' => $jobVacancyExpiredCount,
            'jobVacancyActiveCount' => $jobVacancyActiveCount,
            'jobVacancyNotActiveCount' => $jobVacancyNotActiveCount,
            'echartsData' => $echartsData,
            'totalVisitors' => $totalVisitors,
            'visitorGrowth' => $visitorGrowth,
            'trafficSources' => $trafficSources
        ]);
    }
}
