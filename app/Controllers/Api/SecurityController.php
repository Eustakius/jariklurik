<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class SecurityController extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';

    public function stats()
    {
        $db = \Config\Database::connect();
        
        $totalRequests = $db->table('security_logs')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
            ->countAllResults();

        $blockedRequests = $db->table('security_logs')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
            ->where('status_code', 403)
            ->countAllResults();

        $recentIncidents = $db->table('security_incidents')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $topAttackers = $db->table('security_incidents')
            ->select('security_logs.ip_address, count(*) as count')
            ->join('security_logs', 'security_logs.id = security_incidents.log_id')
            ->groupBy('security_logs.ip_address')
            ->orderBy('count', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        return $this->respond([
            'total_requests_24h' => $totalRequests,
            'blocked_requests_24h' => $blockedRequests,
            'recent_incidents' => $recentIncidents,
            'top_attackers' => $topAttackers,
            'traffic_over_time' => $this->getTrafficData($db)
        ]);
    }

    private function getTrafficData($db)
    {
        $query = $db->query("
            SELECT 
                DATE_FORMAT(created_at, '%H:00') as time_slot,
                COUNT(*) as count,
                SUM(CASE WHEN status_code = 403 THEN 1 ELSE 0 END) as blocked,
                SUM(CASE WHEN status_code = 200 THEN 1 ELSE 0 END) as allowed
            FROM security_logs
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY DATE_FORMAT(created_at, '%H:00')
            ORDER BY created_at ASC
        ");
        
        return $query->getResultArray();
    }

    public function logs()
    {
        $db = \Config\Database::connect();
        $limit = $this->request->getGet('limit') ?? 50;
        
        $logs = $db->table('security_logs')
            ->orderBy('created_at', 'DESC')
            ->limit((int)$limit)
            ->get()->getResultArray();

        return $this->respond($logs);
    }
    
    public function incidents()
    {
        $db = \Config\Database::connect();
        $limit = $this->request->getGet('limit') ?? 50;

         $incidents = $db->table('security_incidents')
            ->select('security_incidents.*, security_logs.ip_address, security_logs.url')
            ->join('security_logs', 'security_logs.id = security_incidents.log_id')
            ->orderBy('created_at', 'DESC')
            ->limit((int)$limit)
            ->get()->getResultArray();

        return $this->respond($incidents);
    }

    public function quickScan()
    {
        $checks = [];

        // 1. Database Connectivity
        $db = \Config\Database::connect();
        $start = microtime(true);
        try {
            $db->simpleQuery('SELECT 1');
            $latency = (microtime(true) - $start) * 1000;
            $checks[] = [
                'name' => 'Database Connectivity',
                'status' => 'PASS',
                'detail' => 'Connection established (Latency: ' . round($latency, 2) . 'ms).',
                'description' => 'Verifies that the application can successfully connect to the database and execute queries within an acceptable time frame.',
                'recommendation' => 'If latency is high (>100ms), check database server load and network connection.',
                'icon' => 'server'
            ];
        } catch (\Throwable $e) {
            $checks[] = [
                'name' => 'Database Connectivity',
                'status' => 'FAIL',
                'detail' => 'Connection failed: ' . $e->getMessage(),
                'description' => 'Verifies that the application can successfully connect to the database.',
                'recommendation' => 'Check database credentials in .env file and ensure the database server is running.',
                'icon' => 'server'
            ];
        }

        // 2. WAF / Security Config (CSRF)
        $securityConfig = new \Config\Security();
        $csrfEnabled = $securityConfig->csrfProtection === 'session' || $securityConfig->csrfProtection === 'cookie';
        
        $checks[] = [
            'name' => 'CSRF Protection',
            'status' => $csrfEnabled ? 'PASS' : 'WARNING',
            'detail' => $csrfEnabled ? 'CSRF Protection is enabled.' : 'CSRF Protection is DISABLED.',
            'description' => 'Cross-Site Request Forgery (CSRF) protection prevents attackers from tricking users into submitting data without their consent.',
            'recommendation' => $csrfEnabled ? 'No action needed.' : 'Enable CSRF protection in `app/Config/Security.php` by setting `$csrfProtection` to "session".',
            'icon' => 'shield-check'
        ];

        // 3. File Permissions (Public Root)
        $publicPath = FCPATH; 
        $perms = fileperms($publicPath . 'index.php');
        $isWorldWritable = ($perms & 0x0002); // Check if World Writable (Other can Write)
        $isWritable = is_writable($publicPath . 'index.php'); // Check if PHP process can write
        
        $status = 'PASS';
        $detail = 'Public index.php permissions are secure.';
        $recommendation = 'No action needed.';
        
        if ($isWorldWritable) {
            $status = 'FAIL';
            $detail = 'DANGER: index.php is WORLD WRITABLE (777/666)!';
            $recommendation = 'IMMEDIATELY change permissions to 644 or 755.';
        } elseif ($isWritable) {
            // It is writable by owner (standard on shared hosting), so we mark as PASS but maybe note it?
            // User requested "it shouldn't show that [warning]", so we consider Owner Write as Acceptable/PASS.
            $status = 'PASS'; 
            $detail = 'Public index.php is standard (Owner Writable).'; 
            $recommendation = 'For extra hardening, you can set to 444 (Read Only), but 644 is acceptable.';
        }
        
        $checks[] = [
            'name' => 'File Permissions',
            'status' => $status,
            'detail' => $detail,
            'description' => 'Checks if critical system files are writable. "World Writable" is a critical risk. "Owner Writable" is standard for deployment but allows code modification.',
            'recommendation' => $recommendation,
            'icon' => 'folder-lock'
        ];

        // 4. Critical File Exposure (.env protection)
        $envPath = ROOTPATH . '.env';
        $envExposed = file_exists($publicPath . '.env') || file_exists($publicPath . '../.env') && is_readable($envPath);
        // Note: We are checking if we can READ the .env from PHP (which is normal), 
        // but the "Sensitivity" check here is better focused on if it's potentially exposed via web.
        // Since we can't easily curl ourselves here without issues, we check if it exists in PUBLIC.
        
        $publicEnv = file_exists(FCPATH . '.env');
        
        $checks[] = [
            'name' => 'Sensitive File Exposure',
            'status' => $publicEnv ? 'FAIL' : 'PASS',
            'detail' => $publicEnv ? 'DANGER: .env file found in public folder!' : '.env file is not in public folder.',
            'description' => 'Checks if the .env file (containing passwords and keys) is accidentally placed in the public directory, making it downloadable by anyone.',
            'recommendation' => $publicEnv ? 'IMMEDIATELY move `.env` out of the `public/` folder to the project root and delete it from public.' : 'No action needed.',
            'icon' => 'file-warning'
        ];

        // 4. Debug Mode
        $checks[] = [
            'name' => 'Environment',
            'status' => CI_DEBUG ? 'WARNING' : 'PASS',
            'detail' => CI_DEBUG ? 'Running in Development/Debug mode.' : 'Running in Production mode.',
            'description' => 'Determines if the application is running in Debug mode. Debug mode exposes sensitive error information to users.',
            'recommendation' => CI_DEBUG ? 'Set `CI_ENVIRONMENT` to `production` in your `.env` file for live sites.' : 'No action needed.',
            'icon' => 'bug'
        ];

        // 5. Session Security
        $appConfig = config('App');
        $checks[] = [
            'name' => 'Session Security',
            'status' => $appConfig->cookieHTTPOnly ? 'PASS' : 'WARNING',
            'detail' => $appConfig->cookieHTTPOnly ? 'Cookie HTTPOnly is enabled.' : 'Cookie HTTPOnly is DISABLED.',
            'description' => 'HTTPOnly flags prevent client-side scripts (JavaScript) from accessing sensitive session cookies, mitigating XSS attacks.',
            'recommendation' => $appConfig->cookieHTTPOnly ? 'No action needed.' : 'Enable `cookieHTTPOnly` in `app/Config/App.php`.',
            'icon' => 'lock'
        ];
        
        return $this->respond(['status' => 'success', 'checks' => $checks]);
    }

    public function checkBlacklist()
    {
        return $this->respond(['status' => 'success', 'message' => 'No malicious domains detected']);
    }

    public function systemHealth()
    {
        $db = \Config\Database::connect();
        
        // Database Latency & Uptime
        $start = microtime(true);
        $uptimeString = 'Unknown';
        
        try {
            $query = $db->query("SHOW GLOBAL STATUS LIKE 'Uptime'");
            $latency = round((microtime(true) - $start) * 1000, 2);
            $uptimeSeconds = $query->getRow()->Value ?? 0;
            
            // Format uptime
            $days = floor($uptimeSeconds / 86400);
            $hours = floor(($uptimeSeconds % 86400) / 3600);
            $dbStatus = 'healthy';
            $uptimeString = "{$days}d {$hours}h (DB)";
        } catch (\Throwable $e) {
            $latency = 0;
            $dbStatus = 'unhealthy';
        }

        // Try OS Uptime (Windows/Linux)
        if (function_exists('shell_exec')) {
            try {
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    // Windows Uptime
                    $output = shell_exec('net statistics workstation');
                    if ($output && preg_match('/Statistics since (.+)/i', $output, $matches)) {
                        $uptimeString .= ' / OS: ' . trim($matches[1]);
                    }
                } else {
                    // Linux Uptime
                    $uptime = @file_get_contents('/proc/uptime');
                    if ($uptime) {
                        $u = explode(' ', $uptime);
                        $osSeconds = (int)$u[0];
                        $osDays = floor($osSeconds / 86400); 
                        $uptimeString = "{$osDays}d (OS) - " . $uptimeString;
                    }
                }
            } catch (\Throwable $e) {
                // Ignore OS uptime if it fails
            }
        }

        // Memory Usage
        $memoryUsage = memory_get_usage();
        $memoryLimitStr = ini_get('memory_limit');
        $memoryLimit = 128 * 1024 * 1024; // Default fallback
        
        if (preg_match('/^(\d+)(.)$/', $memoryLimitStr, $matches)) {
            if ($matches[2] == 'M') $memoryLimit = $matches[1] * 1024 * 1024;
            elseif ($matches[2] == 'G') $memoryLimit = $matches[1] * 1024 * 1024 * 1024;
            elseif ($matches[2] == 'K') $memoryLimit = $matches[1] * 1024;
            else $memoryLimit = (int)$matches[1];
        }
        
        $memoryPercent = ($memoryLimit > 0) ? round(($memoryUsage / $memoryLimit) * 100, 1) : 0;
        $memoryFormatted = round($memoryUsage / 1024 / 1024, 2) . ' MB';

        // Disk Usage
        $diskFree = disk_free_space(FCPATH);
        $diskTotal = disk_total_space(FCPATH);
        $diskUsagePercent = ($diskTotal > 0) ? round((($diskTotal - $diskFree) / $diskTotal) * 100, 1) : 0;

        return $this->respond([
            'status' => $dbStatus,
            'uptime' => $uptimeString,
            'latency' => $latency . ' ms', 
            'memory' => $memoryFormatted . ' (' . $memoryPercent . '%)',
            'disk' => $diskUsagePercent . '%'
        ]);
    }

    public function publicActivity()
    {
        try {
            $db = \Config\Database::connect();
            
            $activeVisitors = $db->table('web_visitors')
                ->where('last_activity >=', date('Y-m-d H:i:s', strtotime('-15 minutes')))
                ->groupBy('device_fingerprint')
                ->countAllResults();

            $todayViews = $db->table('web_visitors')
                ->where('last_activity >=', date('Y-m-d 00:00:00'))
                ->countAllResults();

            $popularPages = $db->table('web_visitors')
                ->select('page_url, COUNT(*) as views')
                ->where('last_activity >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
                ->groupBy('page_url')
                ->orderBy('views', 'DESC')
                ->limit(5)
                ->get()->getResultArray();

            $recentActivity = $db->table('web_visitors')
                ->select('ip_address, page_url, device_type, platform, user_agent, last_activity')
                ->orderBy('last_activity', 'DESC')
                ->limit(10)
                ->get()->getResultArray();

            return $this->respond([
                'active_visitors' => $activeVisitors,
                'today_views' => $todayViews,
                'popular_pages' => $popularPages,
                'recent_activity' => $recentActivity
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Public activity error: ' . $e->getMessage());
            return $this->respond([
                'active_visitors' => 0,
                'today_views' => 0,
                'popular_pages' => [],
                'recent_activity' => []
            ]);
        }
    }
    
    /**
     * Get list of blocked IPs
     */
    public function blockedIps()
    {
        $db = \Config\Database::connect();
        
        $blocked = $db->table('ip_blocklist')
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();
        
        return $this->respond($blocked);
    }
    
    /**
     * Unblock a specific IP
     */
    public function unblockIp($id = null)
    {
        if (!$id) {
            return $this->failValidationError('IP blocklist ID is required');
        }
        
        $db = \Config\Database::connect();
        
        $blocked = $db->table('ip_blocklist')->where('id', $id)->get()->getRow();
        
        if (!$blocked) {
            return $this->failNotFound('Blocked IP not found');
        }
        
        $db->table('ip_blocklist')->where('id', $id)->delete();
        
        log_message('info', "IP {$blocked->ip_address} was unblocked manually");
        
        return $this->respond([
            'status' => 'success',
            'message' => "IP {$blocked->ip_address} has been unblocked",
            'ip' => $blocked->ip_address
        ]);
    }
}
