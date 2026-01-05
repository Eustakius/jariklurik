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

class JWTAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Manual CORS check removed to allow global Cors filter to handle permissions.
        
        $authHeader = $request->getHeaderLine('Authorization');

        if (! $authHeader || ! str_starts_with($authHeader, 'Bearer ')) {
            return Services::response()->setJSON(['status' => false, 'message' => 'Missing token'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $jwt = new JWTService();
        $payload = $jwt->verifyToken($token);

        if (! $payload) {
            return Services::response()->setJSON(['status' => false, 'message' => 'Invalid token'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $authActivationAttemptModel = model(AuthActivationAttemptModel::class);

        $authActivationAttempt = $authActivationAttemptModel->findToken($payload);
        
        if (!$authActivationAttempt) {
            
            return Services::response()->setJSON(['status' => false, 'message' => 'Token revoked or expired'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $payloadToken = $jwt->verifyToken($authActivationAttempt->token);

        if ($payloadToken->user != $payload->username) {
            return Services::response()->setJSON(['status' => false, 'message' => 'Invalid token'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        if ($payloadToken === 'expired') {
            return Services::response()->setJSON(['status' => false, 'message' => 'Token expired'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $auth = service('authentication');
        if ($auth->check()) {
            $user = $auth->user();
            if ($user && $user->username == $payload->username) {
                 $request->username = $payload->username;
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
