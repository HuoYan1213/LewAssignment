<?php
header('Content-Type: application/json');

// Capture input from GET parameters
$address = urlencode($_GET['q'] ?? '');
$lat = $_GET['lat'] ?? '';
$lon = $_GET['lon'] ?? '';

// If we are searching by address (q), use the Nominatim Search API
if ($address) {
    $url = "https://nominatim.openstreetmap.org/search?format=json&q={$address}";
}
// If we are searching by latitude and longitude (reverse geocode)
elseif ($lat && $lon) {
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}";
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Initialize cURL to fetch data from Nominatim
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'MyFoodApp/1.0'); // Nominatim requires a User-Agent header
$response = curl_exec($ch);
curl_close($ch);

// Return the JSON response from Nominatim
echo $response;
?>
