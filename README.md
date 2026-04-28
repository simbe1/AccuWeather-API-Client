# AccuWeather API Client for PHP

A simple PHP class to fetch current weather data from the AccuWeather API.

## Requirements

- PHP 7.4+ or higher
- cURL extension enabled
- AccuWeather API key (free at https://developer.accuweather.com/)

## Installation

### Via Composer

```bash
composer require hb/accuweather
```

### Manual

1. Download the package
2. Include `src/AccuWeatherClient.php`:

```php
require_once 'src/AccuWeatherClient.php';
```

## Usage

```php
use Hb\AccuWeather\AccuWeatherClient;

$weather = new AccuWeatherClient('YOUR_API_KEY');
$data = $weather->getWeatherByCity('New York', 'US');

if ($data) {
    echo $data['location']['name'];
    echo $data['weather']['temperature'];
    echo $data['weather']['condition'];
    echo $data['weather']['humidity'];
    echo $data['weather']['wind'];
    echo $data['weather']['pressure'];
    echo $data['weather']['icon'];
}
```

## Methods

### `__construct(string $apiKey)`

Initialize with your AccuWeather API key.

### `getLocationKey(string $city, string $countryCode = 'US'): ?array`

Search for a city and get its AccuWeather location key.

### `getCurrentWeather(string $locationKey): ?array`

Get current weather for a location key.

### `getWeatherByCity(string $city, string $countryCode = 'US'): ?array`

Convenience method to get weather directly by city name.

## Weather Data Returned

| Field | Description |
|-------|-------------|
| temperature | Temperature in Celsius |
| condition | Weather condition text |
| icon | Weather icon code (1-44) |
| humidity | Humidity percentage |
| wind | Wind speed |
| pressure | Pressure value |
| uvIndex | UV Index value |

## Example

See `examples/example.php` for a complete demo with HTML interface.

## License

MIT License - See LICENSE file
