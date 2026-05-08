<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Baca file .env manual baris demi baris
$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
    die("File .env tidak ditemukan di $envPath");
}

$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$envVars = [];
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue;
    $parts = explode('=', $line, 2);
    if (count($parts) === 2) {
        $envVars[trim($parts[0])] = trim($parts[1], " \t\n\r\0\x0B\"'"); // Hilangkan tanda kutip
    }
}

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
echo "Sender: $sender\n";
echo "Key   : " . substr($apiKey, 0, 5) . "..." . substr($apiKey, -5) . "\n\n";

if (empty($apiKey) || empty($sender)) {
    die("ERROR: API Key atau Sender tidak terbaca dengan benar dari .env!");
}

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
