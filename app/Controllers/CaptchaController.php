<?php namespace App\Controllers;

use CodeIgniter\Controller;

class CaptchaController extends Controller
{
    public function index()
    {
        helper('session');

        // Settings
        $width  = 180;
        $height = 50;
        $length = 6; 

        // Generate random string
        $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $text = '';
        for ($i = 0; $i < $length; $i++) {
            $text .= $chars[rand(0, strlen($chars) - 1)];
        }

        // Log Session Data
        session()->set('captcha_text', strtolower($text));
        session()->set('captcha_time', time());

        // CHECK GD EXTENSION
        if (!extension_loaded('gd') || !function_exists('imagecreatetruecolor')) {
            exit;
        }

        try {
            // Buat image
            $im = imagecreatetruecolor($width, $height);
        } catch (\Throwable $e) {
             exit;
        }
        if (!$im) {
             // Handle error silently or log to proper log file
        }

        // Colors
        $bg = imagecolorallocate($im, 240, 240, 240);
        $textcol = imagecolorallocate($im, 30, 30, 30);
        $linecol = imagecolorallocate($im, 100, 100, 100);

        // Fill background
        imagefilledrectangle($im, 0, 0, $width, $height, $bg);

        // Add some random lines for noise
        for ($i = 0; $i < 6; $i++) {
            imageline($im,
                rand(0, $width), rand(0, $height),
                rand(0, $width), rand(0, $height),
                $linecol
            );
        }

        // Add the text — prefer menggunakan font TTF (lebih rapi)
        $fontFile = WRITEPATH . 'fonts/Roboto-Regular.ttf'; 
        
        if (!file_exists($fontFile)) {
            // fallback ke built-in font jika ttf tidak tersedia
            imagestring($im, 5, 15, 12, $text, $textcol);
        } else {
            // tambahkan tiap karakter dengan sedikit rotasi
            $fontSize = 20;
            $x = 12;
            for ($i = 0; $i < strlen($text); $i++) {
                $angle = rand(-20, 20);
                $y = rand($height - 10, $height - 8);
                imagettftext($im, $fontSize, $angle, $x, $y, $textcol, $fontFile, $text[$i]);
                $x += $fontSize - 2;
            }
        }

        // Output image
        if (ob_get_length()) {
            ob_clean();
        }

        // Disable compression to avoid double-compression issues
        ini_set('zlib.output_compression', 'Off');

        ob_start(); // Start new buffer for image
        imagepng($im);
        $imageData = ob_get_clean(); // Capture image data
        imagedestroy($im);

        $size = strlen($imageData);

        if ($size > 0) {
            header('Content-Type: image/png');
            header('Content-Length: ' . $size);
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
            
            echo $imageData;
        }
        exit;
    }
}
