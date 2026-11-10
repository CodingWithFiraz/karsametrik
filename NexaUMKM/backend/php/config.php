<?php
define('DB_HOST', getEnvVar('DB_HOST', '127.0.0.1'));
define('DB_PORT', getEnvVar('DB_PORT', '4000'));
define('DB_NAME', getEnvVar('DB_NAME', 'db_sisehat'));
define('DB_USER', getEnvVar('DB_USER', 'root'));
define('DB_PASS', getEnvVar('DB_PASS', ''));
define('DB_CHARSET', getEnvVar('DB_CHARSET', 'utf8mb4'));
define('APP_ENV',    getEnvVar('APP_ENV', 'production'));
define('APP_DEBUG', true); // sementara
// define('APP_DEBUG', getEnvVar('APP_DEBUG', 'false') === 'true');
define('CORS_ORIGIN', getEnvVar('CORS_ORIGIN', '*'));
define('JWT_SECRET', getEnvVar('JWT_SECRET', 'NexaUMKM_JWT_Secret_Key_2026_XYZ'));
define('N8N_WEBHOOK_URL', getEnvVar('N8N_WEBHOOK_URL', 'http://localhost:5678/webhook-test/submit-sisehat'));
define('JWT_EXPIRE', 60 * 60 * 8);
define('PAGE_LIMIT_DEFAULT', 15);
define('PAGE_LIMIT_MAX',    100);



function getEnvVar($key, $default = '') {
    return getenv($key) ?: ($_ENV[$key] ?? $_SERVER[$key] ?? $default);
}

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