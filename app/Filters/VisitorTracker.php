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
            
            $data = [
                'ip_address' => $request->getIPAddress(),
                'user_agent' => $request->getUserAgent()->getAgentString(),
                'page_url' => $uri ?: '/',
                'platform' => $request->getUserAgent()->getPlatform(),
                'referer' => $request->getHeaderLine('Referer') ?: null,
                'last_activity' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Insert or update visitor record
            $db->table('web_visitors')->insert($data);
            
        } catch (\Exception $e) {
            // Silently fail - don't break the page if tracking fails
            log_message('error', 'Visitor tracking error: ' . $e->getMessage());
        }
    }
}
