<?php

declare(strict_types=1);

namespace Hb\AccuWeather;

class AccuWeatherClient
{
    private string $apiKey;
    private string $baseUrl = 'https://dataservice.accuweather.com';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getLocationKey(string $city, string $countryCode = 'US'): ?array
    {
        $url = "{$this->baseUrl}/locations/v1/cities/search";
        $params = [
            'apikey' => $this->apiKey,
            'q' => $city,
        ];

        $response = $this->makeRequest($url, $params);

        if (empty($response)) {
            return null;
        }

        return [
            'key' => $response[0]['Key'],
            'name' => $response[0]['LocalizedName'],
            'country' => $response[0]['Country']['LocalizedName'],
        ];
    }

    public function getCurrentWeather(string $locationKey): ?array
    {
        $url = "{$this->baseUrl}/currentconditions/v1/{$locationKey}";
        $params = [
            'apikey' => $this->apiKey,
            'details' => 'true',
        ];

        $response = $this->makeRequest($url, $params);

        if (empty($response)) {
            return null;
        }

        $data = $response[0];

        return [
            'temperature' => $data['Temperature']['Metric']['Value'],
            'temperatureUnit' => 'C',
            'feelsLike' => $data['TemperatureSummary']['Metric']['RealitiveTendency']['Phrase'],
            'condition' => $data['WeatherText'],
            'icon' => $data['WeatherIcon'],
            'humidity' => $data['RelativeHumidity'],
            'wind' => $data['Wind']['Speed']['Metric']['Value'],
            'windUnit' => $data['Wind']['Speed']['Metric']['Unit'],
            'pressure' => $data['Pressure']['Metric']['Value'],
            'pressureUnit' => $data['Pressure']['Metric']['Unit'],
            'visibility' => $data['Visibility']['Metric']['Value'],
            'visibilityUnit' => $data['Visibility']['Metric']['Unit'],
            'uvIndex' => $data['UVIndex'],
            'lastUpdated' => $data['LocalObservationDateTime'],
        ];
    }

    public function getWeatherByCity(string $city, string $countryCode = 'US'): ?array
    {
        $location = $this->getLocationKey($city, $countryCode);

        if (!$location) {
            return null;
        }

        $weather = $this->getCurrentWeather($location['key']);

        if (!$weather) {
            return null;
        }

        return [
            'location' => $location,
            'weather' => $weather,
        ];
    }

    private function makeRequest(string $url, array $params): ?array
    {
        $url .= '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return null;
        }

        return json_decode($response, true);
    }
}