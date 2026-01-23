<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class VisitorTracker implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do nothing before request
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        try {
            // Only track successful GET requests to public pages
            if (strtolower($request->getMethod()) !== 'get' || $response->getStatusCode() !== 200) {
                return;
            }

            // Skip API endpoints and admin pages
            $uri = $request->getUri()->getPath();
            if (strpos($uri, '/api/') !== false || strpos($uri, '/back-end/') !== false) {
                return;
            }

            $db = \Config\Database::connect();
            
            // Generate device fingerprint
            $userAgent = $request->getUserAgent();
            $fingerprintData = [
                'user_agent' => $userAgent->getAgentString(),
                'platform' => $userAgent->getPlatform(),
                'browser' => $userAgent->getBrowser(),
                'accept_language' => $request->getHeaderLine('Accept-Language'),
            ];
            $deviceFingerprint = md5(json_encode($fingerprintData));
            
            // Detect device type
            $agentString = strtolower($userAgent->getAgentString());
            if (strpos($agentString, 'tablet') !== false || strpos($agentString, 'ipad') !== false) {
                $deviceType = 'Tablet';
            } elseif ($userAgent->isMobile()) {
                $deviceType = 'Mobile';
            } elseif ($userAgent->isRobot()) {
                $deviceType = 'Unknown'; // Track bots but mark as Unknown
            } else {
                $deviceType = 'Desktop';
            }
            
            $ipAddress = $request->getIPAddress();
            $visitDate = date('Y-m-d');
            
            // Always insert hit directly (Real-Time Feed Log Mode)
            $data = [
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent->getAgentString(),
                'device_fingerprint' => $deviceFingerprint,
                'device_type' => $deviceType,
                'page_url' => $uri ?: '/',
                'platform' => $userAgent->getPlatform(),
                'referer' => $request->getHeaderLine('Referer') ?: null,
                'visit_date' => $visitDate,
                'last_activity' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            $db->table('web_visitors')->insert($data);
            
        } catch (\Throwable $e) {
            // Silently fail - don't break the page if tracking fails
            log_message('error', 'Visitor tracking error: ' . $e->getMessage());
        }
    }
}
