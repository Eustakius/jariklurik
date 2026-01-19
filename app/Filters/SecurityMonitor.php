<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\SecurityEngine;
use Config\Services;

class SecurityMonitor implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $db = \Config\Database::connect();
        $ip = $request->getIPAddress();

        // Whitelist: Skip WAF for forensics collection (POST only)
        $uri = (string)$request->getUri();
        if (strpos($uri, '/api/security/forensics') !== false && $request->getMethod() === 'post') {
            $request->startTime = microtime(true);
            return null; // Allow through without analysis
        }

        // 1. Check IP Blocklist
        $builder = $db->table('ip_blocklist');
        $blocked = $builder->where('ip_address', $ip)
                           ->where('expires_at >', date('Y-m-d H:i:s'))
                           ->get()->getRow();

        if ($blocked) {
            return Services::response()
                ->setStatusCode(403)
                ->setBody('Access Denied: Your IP is blocked.');
        }

        // 2. Analyze Request using SecurityEngine
        $engine = new SecurityEngine();
        $threat = $engine->analyzeRequest($request);

        if ($threat) {
            // Log the incident
            $this->logIncident($db, $request, $threat);

            // Special handling for Rate Limiting (Low Severity)
            if ($threat['severity'] === 'low') {
                return Services::response()
                    ->setStatusCode(429)
                    ->setBody('Too Many Requests. Please slow down.');
            }

            // Return 403 Forbidden for actual threats
            return Services::response()
                ->setStatusCode(403)
                ->setBody('Security Alert: Malicious request detected.');
        }

        // Store start time for response time calculation
        $request->startTime = microtime(true);
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Log the request details
        $this->logRequest($request, $response);
    }

    private function logIncident($db, $request, $threat)
    {
        // First create a log entry (we can't call logRequest because response isn't ready, so manual insert)
        $logData = [
            'ip_address'    => $request->getIPAddress(),
            'user_agent'    => $request->getUserAgent()->getAgentString(),
            'url'           => (string)$request->getUri(),
            'method'        => $request->getMethod(),
            'status_code'   => ($threat['severity'] === 'low') ? 429 : 403,
            'response_time' => 0,
            'payload_hash'  => hash('sha256', json_encode($request->getGet()) . json_encode($request->getPost())),
            'created_at'    => date('Y-m-d H:i:s'),
        ];
        
        $db->table('security_logs')->insert($logData);
        $logId = $db->insertID();

        // Create Incident
        $incidentData = [
            'log_id'     => $logId,
            'type'       => $threat['type'],
            'severity'   => $threat['severity'],
            'details'    => $threat['details'],
            'status'     => 'open',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $db->table('security_incidents')->insert($incidentData);
        
        // Auto-block if critical (Example Logic)
        if ($threat['severity'] === 'critical') {
            $db->table('ip_blocklist')->insert([
                'ip_address' => $request->getIPAddress(),
                'reason'     => 'Auto-blocked: ' . $threat['type'],
                'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    private function logRequest($request, $response)
    {
        $db = \Config\Database::connect();
        
        // Calculate duration
        $duration = 0;
        if (isset($request->startTime)) {
            $duration = microtime(true) - $request->startTime;
        }

        // Only log 1% of benign traffic to avoid DB flooding, unless it's a 404/500/403
        $statusCode = $response->getStatusCode();
        
        // Always log everything for the Security Dashboard Live Feed
        // (In high-traffic production, you might want to switch back to sampling)
        $logData = [
            'ip_address'    => $request->getIPAddress(),
            'user_agent'    => $request->getUserAgent()->getAgentString(),
            'url'           => (string)$request->getUri(),
            'method'        => $request->getMethod(),
            'status_code'   => $statusCode,
            'response_time' => $duration,
            'payload_hash'  => hash('sha256', json_encode($request->getGet()) . json_encode($request->getPost())), 
            'created_at'    => date('Y-m-d H:i:s'),
        ];
        $db->table('security_logs')->insert($logData);
    }
}
