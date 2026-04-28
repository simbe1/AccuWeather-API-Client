<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Hb\AccuWeather\AccuWeatherClient;

if ($argc < 2) {
    echo "Usage: php cli.php <city> [country_code]\n";
    echo "Example: php cli.php London GB\n";
    exit(1);
}

$apiKey = 'YOUR_API_KEY';
$city = $argv[1];
$countryCode = $argv[2] ?? 'US';

$weather = new AccuWeatherClient($apiKey);
$data = $weather->getWeatherByCity($city, $countryCode);

if ($data) {
    echo "=== Current Weather ===\n";
    echo "Location: " . $data['location']['name'] . ', ' . $data['location']['country'] . "\n";
    echo "Temperature: " . $data['weather']['temperature'] . "°C\n";
    echo "Condition: " . $data['weather']['condition'] . "\n";
    echo "Humidity: " . $data['weather']['humidity'] . "%\n";
    echo "Wind: " . $data['weather']['wind'] . ' ' . $data['weather']['windUnit'] . "\n";
    echo "Pressure: " . $data['weather']['pressure'] . ' ' . $data['weather']['pressureUnit'] . "\n";
    echo "UV Index: " . $data['weather']['uvIndex'] . "\n";
} else {
    echo "Failed to fetch weather data for: $city\n";
}