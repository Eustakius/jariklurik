<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TwoFactorFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('authentication');
        $session = service('session');

        if (!$auth->check()) {
            return redirect()->to('/back-end/login');
        }

        $user = $auth->user();
        
        // Force reload user from DB to bypass session cache
        $userModel = new \App\Models\UserModel();
        $freshUser = $userModel->find($user->id);

        // If user has enabled 2FA (has secret)
        if (!empty($freshUser->google2fa_secret)) {
            // Check if verified in session
            if (!$session->has('2fa_verified') || $session->get('2fa_verified') !== true) {
                // Save current URL for redirect back
                $session->set('2fa_redirect_url', current_url());
                return redirect()->to('/back-end/2fa/login');
            }
        }
        
        // Optional: Force 2FA Setup
        // if (empty($user->google2fa_secret)) {
        //     return redirect()->to('/back-end/2fa/setup');
        // }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
