<?php

namespace App\Controllers\Backend;

use CodeIgniter\Controller;
use Google\Authenticator\GoogleAuthenticator;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class TwoFactorController extends Controller
{
    use ResponseTrait;

    protected $session;
    protected $auth;
    protected $userModel;

    public function __construct()
    {
        $this->session = service('session');
        $this->auth = service('authentication');
        $this->userModel = new UserModel();
    }

    // Show 2FA Setup Page (QR Code)
    public function setup()
    {
        // Only for logged in users
        if (!$this->auth->check()) {
            return redirect()->to('/back-end/login');
        }

        $user = $this->auth->user();

        // If already set up, maybe show "Reset" or "Disable" option? 
        // For now, let's just generate a new secret for setup.
        
        $g = new GoogleAuthenticator();
        
        // Generate secret if not exists or user requested a reset
        // For security, don't overwrite immediately in DB, only on "Enable" click.
        // But for simplicity in this flow, we will generate a secret to show.
        
        $secret = $this->session->get('temp_2fa_secret');
        if (!$secret) {
            $secret = $g->generateSecret();
            $this->session->set('temp_2fa_secret', $secret);
        }

        // Generate QR Code URL
        // Username used in label
        $qrCodeUrl = $g->getURL($user->username, 'JarikLurikAdmin', $secret);

        return view('Backend/2fa/setup', [
            'qrCodeUrl' => $qrCodeUrl,
            'secret' => $secret
        ]);
    }

    // Reset 2FA Setup (Clear temp secret to generate new one)
    public function resetSetup()
    {
        if (!$this->auth->check()) {
             return redirect()->to('/back-end/login');
        }

        $this->session->remove('temp_2fa_secret');
        return redirect()->to('/back-end/2fa/setup')->with('message', 'QR Code refreshed. Please scan the new code.');
    }

    // Process Setup (Verify code and save secret)
    public function enable()
    {
        if (!$this->auth->check()) return $this->failUnauthorized();

        $code = $this->request->getPost('code');
        $secret = $this->session->get('temp_2fa_secret');

        if (!$code || !$secret) {
           return redirect()->to('/back-end/2fa/setup')->with('error', 'Silakan scan QR code ulang.');
        }

        $g = new GoogleAuthenticator();
        if ($g->checkCode($secret, $code)) {
            // Success! Save secret to DB
            $user = $this->auth->user();
            $user->google2fa_secret = $secret;
            $this->userModel->save($user);

            // Clear temp session
            $this->session->remove('temp_2fa_secret');
            // Mark session as verified so they don't get asked again immediately
            $this->session->set('2fa_verified', true);

            return redirect()->to('/back-end/dashboard')->with('message', '2FA Berhasil Diaktifkan!');
        } else {
            return redirect()->to('/back-end/2fa/setup')->with('error', 'Kode salah. Coba lagi.');
        }
    }

    // Show 2FA Login Form
    public function login()
    {
        $config = config('Auth');
        return view('Auth/login_2fa', ['config' => $config]);
    }

    // Verify 2FA on Login
    public function verify()
    {
        if (!$this->auth->check()) {
             return redirect()->to('/back-end/login');
        }

        $user = $this->auth->user();
        if (empty($user->google2fa_secret)) {
            // Should not happen if filter logic is correct, but fail-safe:
            $this->session->set('2fa_verified', true); 
            return redirect()->to('/back-end/dashboard');
        }

        $code = $this->request->getPost('code');
        $g = new GoogleAuthenticator();

        if ($g->checkCode($user->google2fa_secret, $code)) {
            $this->session->set('2fa_verified', true);
            
            $redirectUrl = $this->session->get('2fa_redirect_url');
            $this->session->remove('2fa_redirect_url');
            
            return redirect()->to($redirectUrl ?? '/back-end/dashboard');
        } else {
            return redirect()->back()->with('error', 'Kode OTP Salah!');
        }
    }
}
