<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ambil langsung dari config Laravel (yang sudah diupdate dari .env)
$url    = config('services.mpwa.url');
$apiKey = config('services.mpwa.api_key');
$sender = config('services.mpwa.sender');

// Coba berbagai format sender
$senderVariants = [
    $sender,
    $sender . ':1',
    '0' . substr($sender, 2)
];

echo "<pre>";
echo "=== Config yang terbaca dari server ===\n";
echo "URL   : $url\n";
echo "Key   : " . substr($apiKey, 0, 5) . "..." . substr($apiKey, -5) . "\n\n";

foreach ($senderVariants as $s) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'api_key' => $apiKey,
        'sender'  => $s,
        'number'  => $s,
        'message' => 'Test koneksi dari server hosting - abaikan',
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "Sender: $s\n";
    echo "Status: $httpCode | Response: $response\n\n";
}

echo "</pre>";
