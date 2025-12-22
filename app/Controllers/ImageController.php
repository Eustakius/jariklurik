<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ImageController extends Controller
{
    public function show($filename)
    {
        $path = FCPATH . 'assets/images/front-end' . $filename;

        if (!file_exists($path)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Image not found',
            ]);
        }

        // Deteksi mime type otomatis
        $mimeType = mime_content_type($path);

        return $this->response
            ->setContentType($mimeType)
            ->setBody(file_get_contents($path));
    }
}
