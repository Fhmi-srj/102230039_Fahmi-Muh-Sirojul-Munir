<?php
// Test WA dari hosting - hapus file ini setelah selesai
$url    = 'https://wa.groovy-media.com/send-message';
$apiKey = 'KZez7npqA0G2OEQjJSSLrxQU6NZV8A';
$sender = '6285190820190';

echo "<pre>";
echo "=== Konfigurasi Manual ===\n";
echo "URL   : $url\n";
echo "Sender: $sender\n";
echo "Key   : " . substr($apiKey, 0, 12) . "...\n\n";

// Test 1: form-urlencoded (cara Laravel Http::post)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'api_key' => $apiKey,
    'sender'  => $sender,
    'number'  => $sender,
    'message' => 'Test koneksi - abaikan',
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "=== Test 1: form-urlencoded ===\n";
echo "HTTP Status: $httpCode\n";
echo "CURL Error : " . ($curlError ?: 'none') . "\n";
echo "Response   : $response\n\n";

// Test 2: JSON (cara alternatif)
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $url);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode([
    'api_key' => $apiKey,
    'sender'  => $sender,
    'number'  => $sender,
    'message' => 'Test koneksi - abaikan',
]));
curl_setopt($ch2, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_TIMEOUT, 20);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);

$response2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
$curlError2 = curl_error($ch2);
curl_close($ch2);

echo "=== Test 2: JSON ===\n";
echo "HTTP Status: $httpCode2\n";
echo "CURL Error : " . ($curlError2 ?: 'none') . "\n";
echo "Response   : $response2\n\n";

// Laravel config check
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Laravel Config (dari .env / cache) ===\n";
echo "MPWA_URL   : " . config('services.mpwa.url') . "\n";
echo "MPWA_SENDER: " . config('services.mpwa.sender') . "\n";
echo "MPWA_KEY   : " . substr(config('services.mpwa.api_key'), 0, 12) . "...\n";
echo "</pre>";
