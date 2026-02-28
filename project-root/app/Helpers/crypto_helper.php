<?php

function encryptMessage($text)
{
    $key = getenv('APP_KEY');
    $iv = random_bytes(16);

    $cipher = openssl_encrypt(
        $text,
        'AES-256-CBC',
        $key,
        0,
        $iv
    );

    return base64_encode($iv . $cipher);
}

function decryptMessage($encrypted)
{
    $key = getenv('APP_KEY');

    $data = base64_decode($encrypted);

    $iv = substr($data, 0, 16);
    $cipher = substr($data, 16);

    return openssl_decrypt(
        $cipher,
        'AES-256-CBC',
        $key,
        0,
        $iv
    );
}