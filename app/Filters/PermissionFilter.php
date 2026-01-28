<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Exceptions\PermissionException;
use Myth\Auth\Filters\BaseFilter;

class PermissionFilter extends BaseFilter implements FilterInterface
{
    /**
     * @param array|null $arguments
     *
     * @return RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! $this->authenticate->check()) {
            session()->set('redirect_url', current_url());
            return redirect($this->reservedRoutes['login']);
        }

        $uri = $request->getUri();
        $routePath = trim($uri->getPath(), '/');
        $httpMethod = strtolower($request->getMethod());
        $permissionKey = $this->mapRouteToPermission($routePath, $httpMethod);
        
        // DEBUG: Temporary dump to debug 404/403 issue
        // dd([
        //     'route' => $routePath,
        //     'method' => $httpMethod,
        //     'calculated_key' => $permissionKey,
        //     'has_permission' => $this->authorize->hasPermission($permissionKey, $this->authenticate->id()),
        //     'user_id' => $this->authenticate->id()
        // ]);
        
        if (! $this->authorize->hasPermission($permissionKey, $this->authenticate->id())) {
            // throw new PermissionException(lang('Auth.notEnoughPrivilege'));
            return redirect()->to(site_url('/back-end'))->with('forbiden', "Forbidden access");
        }
    }

    private function mapRouteToPermission(string $route, string $method): string
    {
        $cleanRoute = str_replace('back-end/', '', $route);
        $segments = explode('/', $cleanRoute);
        $resource = end($segments) ?? '';
        
        $prefix = str_replace('/', '.', $cleanRoute);

        if ($method === 'get') {
            if ($resource == 'edit') {
                // Route: .../controller/ID/edit
                // Segments: [..., controller, ID, edit]
                // We want: ...controller.update
                // array_slice(..., 0, -2) removes ID and edit.
                return implode('.', array_slice($segments, 0, -2)) . '.update';
            } else if (is_numeric($resource)) {
                // Route: .../controller/ID
                // Segments: [..., controller, ID]
                // We want: ...controller.detail
                // array_slice(..., 0, -1) removes ID.
                return implode('.', array_slice($segments, 0, -1)) . '.detail';
            } else if ($resource == 'new') {
                return implode('.', array_slice($segments, 0, -1)) . '.create';
            } else if ($resource == 'template-import') {
                return implode('.', array_slice($segments, 0, -1)) . '.import';
            }
            return $prefix . '.view';
        } else if ($method === 'post') { 
            // Handle mass actions via POST
            if (in_array($resource, ['mass-process', 'mass-approve'])) {
                return implode('.', array_slice($segments, 0, -1)) . '.approve';
            } else if ($resource === 'mass-reject') {
                return implode('.', array_slice($segments, 0, -1)) . '.reject';
            } else if ($resource === 'mass-revert') {
                return implode('.', array_slice($segments, 0, -1)) . '.revert';
            } else if ($resource === 'mass-delete') {
                return implode('.', array_slice($segments, 0, -1)) . '.delete';
            } else if ($resource == 'import') {
                return implode('.', array_slice($segments, 0, -1)) . '.import';
            }
            return $prefix . '.create';
        } else if (in_array($method, ['put', 'patch'])) {
             if (in_array($resource, ['approve', 'process', 'reject', 'revert'])) {
                // Route: .../controller/ID/action
                // Segments: [..., controller, ID, action]
                // We want: ...controller.action
                return implode('.', array_slice($segments, 0, -2)) . '.' . $resource;
            } else if (in_array($resource, ['mass-process', 'mass-approve'])) {
                return implode('.', array_slice($segments, 0, -1)) . '.approve';
            } else if ($resource === 'mass-reject') {
                return implode('.', array_slice($segments, 0, -1)) . '.reject';
            } else if ($resource === 'mass-revert') {
                return implode('.', array_slice($segments, 0, -1)) . '.revert';
            } else if ($resource === 'mass-delete') {
                return implode('.', array_slice($segments, 0, -1)) . '.delete';
            }
            // Route: .../controller/ID (PUT/PATCH)
            // Segments: [..., controller, ID]
            // We want: ...controller.update
            return implode('.', array_slice($segments, 0, -1)) . '.update';
        }
        if ($method === 'delete') {
            // Route: .../controller/ID
            // Segments: [..., controller, ID]
            // We want: ...controller.delete
            return implode('.', array_slice($segments, 0, -1)) . '.delete';
        }

        return str_replace('/', '.', $route);
    }



    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
