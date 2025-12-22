<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class FilePreviewController extends ResourceController
{
    public function statementLetter($filename)
    {
        $filePath = ROOTPATH . 'storage/file/job-seeker/statement-letter/' . $filename;

        if (!file_exists($filePath)) {
            return $this->failNotFound('File tidak ditemukan');
        }

        // Ambil mime type (pdf, jpg, png, dll)
        $mimeType = mime_content_type($filePath);

        // Kirim file sebagai response (inline)
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"')
            ->setBody(file_get_contents($filePath));
    }
    
    public function stampPassportImigrasi($filename)
    {
        $filePath = ROOTPATH . 'storage/file/job-seeker/stamp-passport-imigrasi/' . $filename;

        if (!file_exists($filePath)) {
            return $this->failNotFound('File tidak ditemukan');
        }

        // Ambil mime type (pdf, jpg, png, dll)
        $mimeType = mime_content_type($filePath);

        // Kirim file sebagai response (inline)
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"')
            ->setBody(file_get_contents($filePath));
    }

    
    public function CV($filename)
    {
        $filePath = ROOTPATH . 'storage/file/applicant/cv/' . $filename;

        if (!file_exists($filePath)) {
            return $this->failNotFound('File tidak ditemukan');
        }

        // Ambil mime type (pdf, jpg, png, dll)
        $mimeType = mime_content_type($filePath);

        // Kirim file sebagai response (inline)
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"')
            ->setBody(file_get_contents($filePath));
    }
}
