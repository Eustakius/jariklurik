<?php namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class BaseController extends ResourceController
{
    protected $format = 'json';

    protected function respondSuccess($data = null, $message = 'OK', $code = 200)
    {
        $payload = [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];
        return $this->respond($payload, $code);
    }

    protected function respondError($message = 'Error', $code = 400, $errors = null)
    {
        $payload = [
            'status' => 'error',
            'message' => $message,
        ];
        if ($errors !== null) $payload['errors'] = $errors;
        return $this->respond($payload, $code);
    }
}
