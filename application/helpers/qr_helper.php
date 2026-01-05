<?php defined('BASEPATH') OR exit('No direct script access allowed');

function encryptData($data, $secretKey = '@rioG4nt3nK') {
    $key = hash('sha256', $secretKey, true); // 32 bytes
    $iv = openssl_random_pseudo_bytes(16); // 16 bytes IV

    $ciphertext = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext, $key, true);
    $result = base64_encode($iv . $hmac . $ciphertext);

    return $result;
}

function decryptData($encrypted, $secretKey = '@rioG4nt3nK') {
    $key = hash('sha256', $secretKey, true);
    $data = base64_decode($encrypted);

    $iv = substr($data, 0, 16);
    $hmac = substr($data, 16, 32);
    $ciphertext = substr($data, 48);

    $calculatedHmac = hash_hmac('sha256', $ciphertext, $key, true);
    if (!hash_equals($hmac, $calculatedHmac)) {
        return false;
    }

    $plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return $plaintext;
}