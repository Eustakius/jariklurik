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
        $allowedOrigins = [
            rtrim(env('app.baseURL'), '/'),
            rtrim(env('app.baseBackendURL'), '/')
        ];
        
        $origin = $request->getServer('HTTP_ORIGIN');
        if (!$origin) {            
            $origin = (string)$request->getUri()->getScheme() . '://' . $request->getServer('HTTP_HOST');
        }

        if (!$origin || !in_array($origin, $allowedOrigins)) {
            http_response_code(403); // Forbidden
            die('CORS: Origin not allowed');
        }

        if ($request->getMethod(true) === 'OPTIONS') {
            die('OPTIONS');
        }

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

        if ($origin === env('app.baseBackendURL')) {
            if ($payloadToken->user != $payload->username) {
                return Services::response()->setJSON(['status' => false, 'message' => 'Invalid token'])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
        }


        if ($payloadToken === 'expired') {
            return Services::response()->setJSON(['status' => false, 'message' => 'Token expired'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        if ($origin === env('app.baseBackendURL')) {
            $auth = service('authentication');
            $user = $auth->user();
            
            if ($user->username != $payload->username) {
                return Services::response()->setJSON(['status' => false, 'message' => 'Invalid token'])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
            // simpan user id ke request untuk controller
            $request->username = $payload->username;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
