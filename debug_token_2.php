<?php

$token = 'EAAxdPVo72WkBQQNGZBx6YoRIjh5gCp017yX9RdFuOwCfgzEiuMlAZC0VZA2DGxjRWFMuxod344wvmMAd9wb4Npb0cfScZAdtgUBPh0fULILQsIR1qZCvdTq20tPWMwTIBpc667i0AQ1BFnqr1keTinaJ0P1JTvGXW1YxupvEdHdGj6YqrjssBb470j3TJJAZDZD';
$id = '122098971861212935';

// Try 1: Treat ID as WABA and get phone_numbers
$url1 = "https://graph.facebook.com/v20.0/$id/phone_numbers?access_token=" . $token;

// Try 2: Treat as User and get businesses
$url2 = "https://graph.facebook.com/v20.0/$id/businesses?access_token=" . $token;

// Try 3: Get 'me' phone numbers directly
$url3 = "https://graph.facebook.com/v20.0/me/phone_numbers?access_token=" . $token;

function checkUrl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

$res1 = checkUrl($url1);
$res2 = checkUrl($url2);
$res3 = checkUrl($url3);

file_put_contents('debug_output_2.txt', "Try 1 (WABA Phones): $res1\n\nTry 2 (User Businesses): $res2\n\nTry 3 (Me Phones): $res3");
