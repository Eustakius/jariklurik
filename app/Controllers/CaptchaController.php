<?php namespace App\Controllers;

use CodeIgniter\Controller;

class CaptchaController extends Controller
{
    public function index()
    {
        // 1. Log Entry
        file_put_contents('php://stderr', "\n[CaptchaDebug] Controller Accessed at " . date('H:i:s') . "\n");

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

        // 2. Log Session Data
        session()->set('captcha_text', strtolower($text));
        session()->set('captcha_time', time());
        file_put_contents('php://stderr', "[CaptchaDebug] Generated Text: " . $text . "\n");

        // CHECK GD EXTENSION
        if (!extension_loaded('gd')) {
            file_put_contents('php://stderr', "\n[CaptchaDebug] !!! CRITICAL ERROR !!!\n[CaptchaDebug] The 'gd' extension is NOT loaded in your PHP configuration.\n[CaptchaDebug] You must enable extension=gd in your php.ini file.\n");
            exit;
        }

        if (!function_exists('imagecreatetruecolor')) {
            file_put_contents('php://stderr', "\n[CaptchaDebug] !!! CRITICAL ERROR !!!\n[CaptchaDebug] function 'imagecreatetruecolor' does not exist despite GD being loaded?!\n");
            exit;
        }

        try {
            // Buat image
            $im = imagecreatetruecolor($width, $height);
        } catch (\Throwable $e) {
             file_put_contents('php://stderr', "[CaptchaDebug] EXCEPTION: " . $e->getMessage() . "\n");
             exit;
        }
        if (!$im) {
            file_put_contents('php://stderr', "[CaptchaDebug] CRITICAL: imagecreatetruecolor failed!\n");
        } else {
            file_put_contents('php://stderr', "[CaptchaDebug] Image resource created successfully.\n");
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
        
        // 3. Log Font Check
        if (!file_exists($fontFile)) {
            file_put_contents('php://stderr', "[CaptchaDebug] Font Check: FAILED. File not found at: " . $fontFile . "\n");
            file_put_contents('php://stderr', "[CaptchaDebug] Fallback: Using imagestring (built-in font).\n");
            
            // fallback ke built-in font jika ttf tidak tersedia
            imagestring($im, 5, 15, 12, $text, $textcol);
        } else {
            file_put_contents('php://stderr', "[CaptchaDebug] Font Check: SUCCESS. Using TTF font.\n");
            
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
        // NUCLEAR OPTION: Capture, Measure, Send.
        if (ob_get_length()) {
            $buffer = ob_get_clean();
            file_put_contents('php://stderr', "[CaptchaDebug] WARNING: Output buffer was not empty! Cleared " . strlen($buffer) . " bytes of garbage data.\n");
        }

        // Disable compression to avoid double-compression issues
        ini_set('zlib.output_compression', 'Off');

        ob_start(); // Start new buffer for image
        imagepng($im);
        $imageData = ob_get_clean(); // Capture image data
        imagedestroy($im);

        $size = strlen($imageData);
        file_put_contents('php://stderr', "[CaptchaDebug] Image Generated. Size: " . $size . " bytes.\n");

        if ($size === 0) {
            file_put_contents('php://stderr', "[CaptchaDebug] CRITICAL: Generated image size is 0 bytes!\n");
        }

        header('Content-Type: image/png');
        header('Content-Length: ' . $size);
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        
        echo $imageData;
        file_put_contents('php://stderr', "[CaptchaDebug] Done. Headers and Data Sent.\n");
        exit;
    }
}
