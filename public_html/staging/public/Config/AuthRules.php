<?php

namespace App\Config;

class AuthRules
{
    public function strong_password(string $str, string &$error = null): bool
    {
        $uppercase    = preg_match('@[A-Z]@', $str);
        $lowercase    = preg_match('@[a-z]@', $str);
        $number       = preg_match('@[0-9]@', $str);
        $specialChars = preg_match('@[^\w]@', $str);

        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($str) < 8) {
            $error = 'Password must be at least 8 characters long and contain an uppercase letter, a lowercase letter, a number, and a symbol.';
            return false;
        }

        return true;
    }
}
