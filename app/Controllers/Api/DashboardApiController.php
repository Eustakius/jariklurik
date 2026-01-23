<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class DashboardApiController extends ResourceController
{
    protected $format = 'json';

    public function getVisitorStats()
    {
        try {
            $webVisitorModel = new \App\Models\WebVisitorModel();
            
            // 1. Total Unique Visitors (Lifetime)
            $totalVisitors = $webVisitorModel->db->table('web_visitors')
                ->select('COUNT(DISTINCT device_fingerprint) as unique_count')
                ->get()
                ->getRow()
                ->unique_count ?? 0;

            // 1b. Total Page Views (Hits)
            $totalPageViews = $webVisitorModel->db->table('web_visitors')
                ->countAllResults();

            // 2. Unique Visitor Growth (Monthly)
            $growthQuery = $webVisitorModel->db->table('web_visitors')
                ->select("DATE_FORMAT(visit_date, '%Y-%m') as ym, DATE_FORMAT(visit_date, '%b') as month, COUNT(DISTINCT CONCAT(ip_address, '-', device_fingerprint, '-', visit_date)) as count")
                ->where('visit_date IS NOT NULL')
                ->groupBy('ym')
                ->orderBy('ym', 'ASC')
                ->limit(12)
                ->get()
                ->getResultArray();
            
            $visitorGrowth = [
                'categories' => [],
                'data' => []
            ];
            
            foreach ($growthQuery as $row) {
                $visitorGrowth['categories'][] = $row['month'];
                $visitorGrowth['data'][] = (int)$row['count'];
            }
            
            if (empty($visitorGrowth['categories'])) {
                $visitorGrowth['categories'] = [date('M')];
                $visitorGrowth['data'] = [0];
            }

            // 3. Unique Visitors by Device Type
            $deviceQuery = $webVisitorModel->db->table('web_visitors')
                ->select("device_type, COUNT(DISTINCT CONCAT(ip_address, '-', device_fingerprint, '-', visit_date)) as count")
                ->where('device_type IS NOT NULL')
                ->groupBy('device_type')
                ->orderBy('count', 'DESC')
                ->get()
                ->getResultArray();
            
            $trafficSources = [
                'labels' => [],
                'series' => []
            ];
            
            foreach ($deviceQuery as $row) {
                $trafficSources['labels'][] = $row['device_type'] ?: 'Unknown';
                $trafficSources['series'][] = (int)$row['count'];
            }

            return $this->respond([
                'success' => true,
                'data' => [
                    'totalVisitors' => $totalVisitors,
                    'totalPageViews' => $totalPageViews,
                    'visitorGrowth' => $visitorGrowth,
                    'trafficSources' => $trafficSources,
                    'timestamp' => time()
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Dashboard API Error: ' . $e->getMessage());
            return $this->fail('Failed to fetch visitor statistics', 500);
        }
    }
}
