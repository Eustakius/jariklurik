<?php

namespace App\Filters;

use App\Libraries\JWTService;
use App\Models\AuthActivationAttemptModel;
use App\Models\AuthTokenModel;
use App\Models\UserModel;
use CodeIgniter\Config\Services;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PostAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        file_put_contents(WRITEPATH . 'logs/debug_custom.log', "[POST-AUTH] Request received: " . $request->getUri()->getPath() . "\n", FILE_APPEND);
        // Manual CORS check removed to allow global Cors filter to handle permissions.
        
        $token = $request->getPost('token');

        if (! $token) {
            return Services::response()->setJSON(['status' => false, 'message' => 'Missing token'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $jwt = new JWTService();
        $payload = $jwt->verifyToken($token);

        if (! $payload) {
            return Services::response()->setJSON(['status' => false, 'message' => 'Invalid token'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $authActivationAttemptModel = model(AuthActivationAttemptModel::class);

        $authActivationAttempt = $authActivationAttemptModel->findToken($payload);
        $payloadToken = $jwt->verifyToken($authActivationAttempt->token);

        if ($payloadToken->user != $payload->username) {
            return Services::response()->setJSON(['status' => false, 'message' => 'Invalid token'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        if (!$authActivationAttempt) {
            return Services::response()->setJSON(['status' => false, 'message' => 'Token revoked or expired'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        if ($payloadToken === 'expired') {
            return Services::response()->setJSON(['status' => false, 'message' => 'Token expired'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
