<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Hb\AccuWeather\AccuWeatherClient;

$apiKey = 'YOUR_API_KEY';
$city = $_GET['city'] ?? 'New York';
$countryCode = $_GET['country'] ?? 'US';

$cities = [
    'New York' => 'US',
    'London' => 'GB',
    'Tokyo' => 'JP',
    'Paris' => 'FR',
    'Sydney' => 'AU',
    'Berlin' => 'DE',
    'Madrid' => 'ES',
    'Rome' => 'IT',
    'Toronto' => 'CA',
    'Dubai' => 'AE',
];

$weather = new AccuWeatherClient($apiKey);
$data = $weather->getWeatherByCity($city, $countryCode);

$weatherIcons = [
    1 => '&#9925;', 2 => '&#127780;', 3 => '&#9925;', 4 => '&#9729;', 5 => '&#128167;',
    6 => '&#128167;', 7 => '&#9729;', 8 => '&#9729;', 11 => '&#128167;', 12 => '&#128167;',
    13 => '&#127783;', 14 => '&#127783;', 15 => '&#9928;', 16 => '&#9928;', 17 => '&#128167;',
    18 => '&#128167;', 19 => '&#127744;', 20 => '&#128167;', 21 => '&#128167;', 22 => '&#10052;',
    23 => '&#10052;', 24 => '&#127784;', 25 => '&#129398;', 26 => '&#128167;', 29 => '&#127784;',
    30 => '&#128287;', 31 => '&#128287;', 32 => '&#128075;', 33 => '&#127769;', 34 => '&#127769;',
    35 => '&#127783;', 36 => '&#127783;', 37 => '&#9928;', 38 => '&#127783;', 39 => '&#127783;',
    40 => '&#127783;', 41 => '&#9928;', 42 => '&#127783;', 43 => '&#10052;', 44 => '&#10052;',
];

$iconCode = $data['weather']['icon'] ?? 1;
$weatherIcon = $weatherIcons[$iconCode] ?? '&#127780;';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather - <?php echo $data ? $data['location']['name'] : 'Not Found'; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .weather-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        .weather-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .weather-header h1 { font-size: 2rem; margin-bottom: 5px; }
        .weather-header .country { font-size: 1rem; opacity: 0.9; }
        .weather-body { padding: 30px; }
        .temperature { text-align: center; margin-bottom: 30px; }
        .temperature .temp-value { font-size: 4rem; font-weight: bold; color: #333; }
        .temperature .temp-unit { font-size: 1.5rem; color: #666; }
        .temperature .condition { font-size: 1.2rem; color: #555; margin-top: 5px; }
        .weather-details { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .detail-item { background: #f8f9fa; padding: 15px; border-radius: 12px; text-align: center; }
        .detail-item .label { font-size: 0.85rem; color: #666; margin-bottom: 5px; }
        .detail-item .value { font-size: 1.1rem; font-weight: bold; color: #333; }
        .search-form { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .search-form form { display: flex; gap: 10px; }
        .search-form input { flex: 1; padding: 12px 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 1rem; }
        .search-form input:focus { outline: none; border-color: #667eea; }
        .search-form button { padding: 12px 20px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; }
        .search-form button:hover { background: #5a6fd6; }
        .error { text-align: center; padding: 50px; color: #dc3545; }
        .city-selector { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-bottom: 20px; }
        .city-selector a { padding: 8px 12px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 20px; font-size: 0.85rem; transition: all 0.3s; }
        .city-selector a:hover, .city-selector a.active { background: #667eea; color: white; }
        .weather-icon { font-size: 5rem; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php if ($data): ?>
    <div class="weather-card">
        <div class="weather-header">
            <h1><?php echo htmlspecialchars($data['location']['name']); ?></h1>
            <div class="country"><?php echo htmlspecialchars($data['location']['country']); ?></div>
        </div>
        <div class="weather-body">
            <div class="weather-icon"><?php echo $weatherIcon; ?></div>
            <div class="temperature">
                <div class="temp-value"><?php echo htmlspecialchars($data['weather']['temperature']); ?><span class="temp-unit">&#176;C</span></div>
                <div class="condition"><?php echo htmlspecialchars($data['weather']['condition']); ?></div>
            </div>
            <div class="weather-details">
                <div class="detail-item"><div class="label">Humidity</div><div class="value"><?php echo htmlspecialchars($data['weather']['humidity']); ?>%</div></div>
                <div class="detail-item"><div class="label">Wind</div><div class="value"><?php echo htmlspecialchars($data['weather']['wind']); ?> <?php echo htmlspecialchars($data['weather']['windUnit']); ?></div></div>
                <div class="detail-item"><div class="label">Pressure</div><div class="value"><?php echo htmlspecialchars($data['weather']['pressure']); ?> <?php echo htmlspecialchars($data['weather']['pressureUnit']); ?></div></div>
                <div class="detail-item"><div class="label">UV Index</div><div class="value"><?php echo htmlspecialchars($data['weather']['uvIndex']); ?></div></div>
            </div>
            <div class="city-selector">
                <?php foreach ($cities as $c => $cc): ?>
                <a href="?city=<?php echo urlencode($c); ?>&country=<?php echo $cc; ?>" class="<?php echo ($city === $c && $countryCode === $cc) ? 'active' : ''; ?>"><?php echo htmlspecialchars($c); ?></a>
                <?php endforeach; ?>
            </div>
            <div class="search-form">
                <form method="get"><input type="text" name="city" placeholder="Enter city name" required><button type="submit">Search</button></form>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="weather-card">
        <div class="error"><h2>Weather Not Found</h2><p>Could not find weather for "<?php echo htmlspecialchars($city); ?>"</p></div>
    </div>
    <?php endif; ?>
</body>
</html>