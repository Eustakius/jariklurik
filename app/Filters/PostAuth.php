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
        $allowedOrigins = [
            env('app.baseURL'),
            env('app.baseBackendURL')
        ];

        $origin = $request->getServer('HTTP_ORIGIN');
        if (!$origin) {
            if (!$origin) {
                $origin = (string)$request->getUri()->getScheme() . '://' . $request->getServer('HTTP_HOST');
            }
        }

        if (!$origin || !in_array($origin, $allowedOrigins)) {
            http_response_code(403); // Forbidden
            die('CORS: Origin not allowed');
        }

        if ($request->getMethod(true) === 'OPTIONS') {
            die();
        }

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

        if ($origin === env('app.baseBackendURL')) {
            if ($payloadToken->user != $payload->username) {
                return Services::response()->setJSON(['status' => false, 'message' => 'Invalid token'])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
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
