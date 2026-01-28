<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MaintenanceFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response.If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Allow access to backend, API, login, and static assets
        $path = trim($request->getUri()->getPath(), '/');
        
        // Define excluded paths (Admin panel, API, Login, etc.)
        $excludedPaths = [
            'back-end',
            'api',
            'login',
            'assets',
            'uploads'
        ];

        foreach ($excludedPaths as $excluded) {
            if (str_starts_with($path, $excluded) || $path === $excluded) {
                return;
            }
        }
        
        // Check Maintenance Setting from Database
        $db = \Config\Database::connect();
        
        // Avoid error if table doesn't exist yet (during migrations)
        if (!$db->tableExists('settings')) {
            return;
        }

        $builder = $db->table('settings');
        $maintenanceMode = $builder->where('key', 'maintenance_mode')->get()->getRow();
        
        if ($maintenanceMode && $maintenanceMode->values == '1') {
            // Fetch optional message
            $messageRow = $builder->where('key', 'maintenance_message')->get()->getRow();
            $message = $messageRow ? $messageRow->values : '';
            
            // Return 503 Service Unavailable with the view
            $response = service('response');
            $response->setStatusCode(503);
            $response->setBody(view('maintenance', ['message' => $message]));
            return $response;
        }
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
        //
    }
}
