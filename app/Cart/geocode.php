<?php
header('Content-Type: application/json; charset=utf-8');

// Disable error reporting to prevent HTML errors breaking JSON
error_reporting(0);

$lat = $_GET['lat'] ?? '';
$lon = $_GET['lon'] ?? '';

if (!$lat || !$lon) {
    echo json_encode(['error' => 'Missing coordinates']);
    exit;
}

$url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}&addressdetails=1";

$response = null;
$error = null;

// Method 1: Try cURL (Preferred)
if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Nominatim requires a User-Agent
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    // Ignore SSL issues for local dev
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    
    if ($response === false) {
        $error = 'cURL Error: ' . curl_error($ch);
    }
    curl_close($ch);
}

// Method 2: Fallback to file_get_contents if cURL failed or is missing
if (!$response && ini_get('allow_url_fopen')) {
    $options = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n",
            "timeout" => 10
        ],
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false
        ]
    ];
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
}

if ($response) {
    echo $response;
} else {
    // If both failed, return error JSON
    echo json_encode(['error' => $error ?? 'Unknown error connecting to map service']);
}
?>
