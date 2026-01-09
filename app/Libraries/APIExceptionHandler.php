<?php

namespace App\Libraries;

use CodeIgniter\Debug\ExceptionHandlerInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;
use CodeIgniter\API\ResponseTrait;

class APIExceptionHandler implements ExceptionHandlerInterface
{
    use ResponseTrait;

    public function handle(
        Throwable $exception,
        RequestInterface $request,
        ResponseInterface $response,
        int $statusCode,
        int $exitCode
    ): void {
        $data = [
            'success'   => false,
            'message'   => $exception->getMessage(),
            'error_code' => $exception->getCode(),
        ];

        // Add debug info if CI_DEBUG is true
        if (CI_DEBUG) {
            $data['trace'] = $exception->getTrace();
            $data['file'] = $exception->getFile();
            $data['line'] = $exception->getLine();
        }

        $response->setJSON($data)
                 ->setStatusCode($statusCode)
                 ->send();
        
        exit($exitCode);
    }
}
