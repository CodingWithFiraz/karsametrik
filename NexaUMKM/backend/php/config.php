<?php
define('DB_HOST',    getenv('DB_HOST')    ?: 'localhost');
define('DB_PORT',    getenv('DB_PORT')    ?: '3306');
define('DB_NAME',    getenv('DB_NAME')    ?: 'nexaumkm');
define('DB_USER',    getenv('DB_USER')    ?: 'root');
define('DB_PASS',    getenv('DB_PASS')    ?: '');
define('DB_CHARSET', 'utf8mb4');
define('APP_ENV',    getenv('APP_ENV')    ?: 'development');
define('APP_DEBUG',  APP_ENV === 'development');
define('CORS_ORIGIN', getenv('CORS_ORIGIN') ?: '*');
define('JWT_SECRET', getenv('JWT_SECRET')   ?: 'NexaUMKM_JWT_Secret_Key_2026_XYZ');
define('N8N_WEBHOOK_URL', getenv('N8N_WEBHOOK_URL') ?: 'http://localhost:5678/webhook-test/submit-sisehat');
define('JWT_EXPIRE', 60 * 60 * 8);
define('PAGE_LIMIT_DEFAULT', 15);
define('PAGE_LIMIT_MAX',    100);


function sendWebhookToN8n($payload) {
    $webhookUrl = N8N_WEBHOOK_URL;

    if (!$webhookUrl) {
        return false;
    }

    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}