<?php

namespace App\Controllers;

use App\Models\ShortUrlModel;

class ShortUrlController extends BaseController
{
    /**
     * Redirect short URL to full URL
     */
    public function redirect($shortCode)
    {
        $shortUrlModel = new ShortUrlModel();
        $fullUrl = $shortUrlModel->getFullUrl($shortCode);
        
        if ($fullUrl) {
            return redirect()->to($fullUrl);
        }
        
        // If short code not found, redirect to home
        return redirect()->to('/');
    }
}
