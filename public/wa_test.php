<?php
// Test WA dari hosting - hapus file ini setelah selesai
$url    = 'https://wa.groovy-media.com/send-message';
$apiKey = 'KZez7npqA0G2OEQjJSSLrxQU6NZV8A';
$sender = '6285190820190';

echo "<pre>";
echo "URL   : $url\n";
echo "Sender: $sender\n";
echo "Key   : " . substr($apiKey, 0, 12) . "...\n\n";

// Test dengan curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'api_key' => $apiKey,
    'sender'  => $sender,
    'number'  => $sender,
    'message' => 'Test koneksi - abaikan',
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error    = curl_error($ch);
curl_close($ch);

echo "HTTP Status: $httpCode\n";
echo "CURL Error : " . ($error ?: 'none') . "\n";
echo "Response   : " . ($response ?: 'empty') . "\n";

// Also test env dari Laravel
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n=== Laravel .env ===" . PHP_EOL;
echo "MPWA_URL   : " . config('services.mpwa.url') . PHP_EOL;
echo "MPWA_SENDER: " . config('services.mpwa.sender') . PHP_EOL;
echo "MPWA_KEY   : " . substr(config('services.mpwa.api_key'), 0, 12) . "..." . PHP_EOL;
echo "</pre>";
