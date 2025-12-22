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
        $length = 6; // jumlah karakter

        // Generate random string (huruf + angka, tanpa karakter ambig)
        $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $text = '';
        for ($i = 0; $i < $length; $i++) {
            $text .= $chars[rand(0, strlen($chars) - 1)];
        }

        // Simpan di session (bisa lowercase untuk case-insensitive)
        session()->set('captcha_text', strtolower($text));
        session()->set('captcha_time', time());

        // Buat image
        $im = imagecreatetruecolor($width, $height);

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
        $fontFile = WRITEPATH . 'fonts/Roboto-Regular.ttf'; // letakkan font di writable/fonts
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
        header('Content-Type: image/png');
        imagepng($im);
        imagedestroy($im);
        exit;
    }
}
