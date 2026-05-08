<?php
// Test WA dari hosting - hapus file ini setelah selesai
$url    = 'https://wa.groovy-media.com/send-message';
$apiKey = 'KZez7npqA0G2OEQjJSSLrxQU6NZV8A';

// Coba berbagai format sender
$senderVariants = [
    '6285190820190',
    '6285190820190:1',
    '085190820190',
];

echo "<pre>";

foreach ($senderVariants as $sender) {
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
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "Sender: $sender\n";
    echo "Status: $httpCode | Response: $response\n\n";
}

echo "</pre>";
