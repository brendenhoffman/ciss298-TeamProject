<style>
  p.current-weather { min-height: 100px; }
  .forecast-list li { min-height: 100px; }
  .weather-page { min-height: 620px; }
  .weather-icon {
    width: 100px;
    height: 100px;
  }
</style>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$env = parse_ini_file(__DIR__ . "/../.env");
$apiKey = $env['WEATHER'];
$city = "Columbia";
$units = "imperial"; // or "metric"

$weatherApiUrl = "https://api.openweathermap.org/data/2.5";

// Coordinates for Columbia, Missouri
$lat = 38.9517;
$lon = -92.3341;

$currentUrl = "$weatherApiUrl/weather?lat=$lat&lon=$lon&appid=$apiKey&units=$units";
$forecastUrl = "$weatherApiUrl/forecast?lat=$lat&lon=$lon&appid=$apiKey&units=$units";

// Fetch current weather
$currentData = json_decode(file_get_contents($currentUrl), true);
if (!$currentData) {
    die("Error: Unable to fetch current weather.");
}

$currentTemp = $currentData['main']['temp'];
$currentDesc = ucfirst($currentData['weather'][0]['description']);
$currentIcon = $currentData['weather'][0]['icon'];
$currentHumidity = $currentData['main']['humidity'];
$currentWind = $currentData['wind']['speed'];
$iconUrl = "https://openweathermap.org/img/wn/{$currentIcon}@2x.png";

// Fetch forecast data
$forecastData = json_decode(file_get_contents($forecastUrl), true);
if (!$forecastData) {
    die("Error: Unable to fetch forecast data.");
}

// Extract one forecast per day (around midday)
$forecastList = $forecastData['list'];
$dailyForecasts = [];
foreach ($forecastList as $entry) {
    $timestamp = $entry['dt'];
    $hour = (int)date('H', $timestamp);
    if ($hour >= 11 && $hour <= 13 && count($dailyForecasts) < 3) {
        $dailyForecasts[] = $entry;
    }
}
?>

<!-- Display Weather -->
<div class="m-5 weather-page">
    <h2>Current Weather in <?= htmlspecialchars($city) ?></h2>
    <p class="current-weather">
        <img class="weather-icon" src="<?= $iconUrl ?>" style="vertical-align: middle;">
        <strong><?= $currentDesc ?></strong>
    </p>
    <div class="weather-data">
        <strong>Temperature:</strong> <?= $currentTemp ?>°
    </div>
    <div class="weather-data">
        <strong>Humidity:</strong> <?= $currentHumidity ?>%
    </div>
    <div class="weather-data">
        <strong>Wind:</strong> <?= $currentWind ?> mph
    </div>

    <h2 class="m-4">3-Day Forecast</h2>
    <ul class="forecast-list">
    <?php foreach ($dailyForecasts as $day):
        $date = date("l, M j", $day['dt']);
        $desc = ucfirst($day['weather'][0]['description']);
        $temp = $day['main']['temp'];
        $icon = $day['weather'][0]['icon'];
        $iconUrl = "https://openweathermap.org/img/wn/{$icon}@2x.png";
    ?>
        <li>
            <img class="weather-icon" src="<?= $iconUrl ?>" style="vertical-align: middle;">
            <strong><?= $date ?>:</strong> <?= $desc ?>, <?= $temp ?>°
        </li>
    <?php endforeach; ?>
    </ul>
</div>





