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
        // dd($permissionKey);
        if (! $this->authorize->hasPermission($permissionKey, $this->authenticate->id())) {
            // throw new PermissionException(lang('Auth.notEnoughPrivilege'));
            return redirect()->to(site_url('/back-end'))->with('forbiden', "Forbidden access");
        }
    }

    private function mapRouteToPermission(string $route, string $method): string
    {
        $segments = explode('/', str_replace('back-end/', '', $route));
        $resource = end($segments) ?? '';
        $prefix = str_replace('/', '.', str_replace('back-end/', '', $route));
        if ($method === 'get') {
            if ($resource == 'edit') {
                return implode('.', array_slice($segments, 0, -2)) . '.update';
            } else if (is_numeric($resource)) {
                return implode('.', array_slice($segments, 0, -1)) . '.detail';
            } else if ($resource == 'new') {
                return implode('.', array_slice($segments, 0, -1)) . '.create';
            } else if ($resource == 'template-import') {
                return implode('.', array_slice($segments, 0, -1)) . '.import';
            }
            return $prefix . '.view';
        } else if ($method === 'post') { 
            if ($resource == 'import') {
                return implode('.', array_slice($segments, 0, -1)) . '.import';
            }
            return $prefix . '.create';
        } else if (in_array($method, ['put', 'patch'])) {
             if ($resource == 'approve') {
                return implode('.', array_slice($segments, 0, -2)) . '.approve';
            } else if ($resource == 'process') {
                return implode('.', array_slice($segments, 0, -2)) . '.process';
            } else if ($resource == 'reject') {
                return implode('.', array_slice($segments, 0, -2)) . '.reject';
            } else if ($resource == 'revert') {
                return implode('.', array_slice($segments, 0, -2)) . '.revert';
            } else if ($resource === 'mass-process') {
                return implode('.', array_slice($segments, 0, -1)) . '.approve';
            } else if ($resource === 'mass-approve') {
                return implode('.', array_slice($segments, 0, -1)) . '.approve';
            } else if ($resource === 'mass-reject') {
                return implode('.', array_slice($segments, 0, -1)) . '.reject';
            } else if ($resource === 'mass-revert') {
                return implode('.', array_slice($segments, 0, -1)) . '.revert';
            }
            return implode('.', array_slice($segments, 0, -1)) . '.update';
        }
        if ($method === 'delete') {
            return implode('.', array_slice($segments, 0, -1)) . '.delete';
        }

        return str_replace('/', '.', $route);
    }



    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
