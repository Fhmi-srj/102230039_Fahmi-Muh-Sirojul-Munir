<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Baca file .env langsung tanpa meload Laravel
$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
    die("File .env tidak ditemukan di $envPath");
}

$envVars = parse_ini_file($envPath);
$url = $envVars['MPWA_URL'] ?? 'https://wa.groovy-media.com/send-message';
$apiKey = $envVars['MPWA_API_KEY'] ?? '';
$sender = $envVars['MPWA_SENDER'] ?? '';

$senderVariants = [
    $sender,
    $sender . ':1',
    '0' . substr($sender, 2)
];

echo "<pre>";
echo "=== Config langsung dari .env ===\n";
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
        'message' => 'Test koneksi dari server hosting',
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
