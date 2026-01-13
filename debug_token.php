<?php

$token = 'EAAxdPVo72WkBQQNGZBx6YoRIjh5gCp017yX9RdFuOwCfgzEiuMlAZC0VZA2DGxjRWFMuxod344wvmMAd9wb4Npb0cfScZAdtgUBPh0fULILQsIR1qZCvdTq20tPWMwTIBpc667i0AQ1BFnqr1keTinaJ0P1JTvGXW1YxupvEdHdGj6YqrjssBb470j3TJJAZDZD';
$url = "https://graph.facebook.com/v20.0/me?fields=id,name,accounts{id,name,phone_numbers}&access_token=" . $token;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For dev env

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    file_put_contents('debug_output.txt', "Error: " . $error);
} else {
    file_put_contents('debug_output.txt', "Response: " . $response);
}

// Try to fallback to fetch all businesses helper
$url2 = "https://graph.facebook.com/v20.0/me/associated_business_accounts?access_token=" . $token;
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $url2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
$response2 = curl_exec($ch2);
file_put_contents('debug_output.txt', "\n\nAssociated Businesses: " . $response2, FILE_APPEND);
