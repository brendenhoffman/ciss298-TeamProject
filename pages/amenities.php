<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "hotel_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch amenities
$sql = "SELECT * FROM amenities";
$result = $conn->query($sql);
$amenities = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hotel Amenities</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Our Amenities</h2>

    <div id="amenitiesCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
        <div class="carousel-inner">
            <?php foreach($amenities as $index => $item): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                        <i class="<?= $item['icon'] ?> amenity-icon"></i>
                        <h4><?= htmlspecialchars($item['name']) ?></h4>
                        <p class="text-center w-75"><?= htmlspecialchars($item['description']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a class="carousel-control-prev" href="#amenitiesCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </a>
        <a class="carousel-control-next" href="#amenitiesCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </a>
    </div>
</div>


<div class="container my-5">
    <div class="card shadow text-center">
        <div class="card-body">
            <h4 class="card-title">Current Weather in <span id="city-name">...</span></h4>
            <div id="weather-content">
                <!-- Weather info loads here -->
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const apiKey = 'YOUR_API_KEY'; // Replace with your real API key
    const city = 'Dubai'; // Change this to your hotel city
    const url = https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const temp = data.main.temp;
            const desc = data.weather[0].description;
            const icon = data.weather[0].icon;
            const iconUrl = http://openweathermap.org/img/wn/${icon}@2x.png;

            document.getElementById('city-name').textContent = city;
            document.getElementById('weather-content').innerHTML = `
                <img src="${iconUrl}" alt="Weather Icon" class="mb-2" style="width: 80px;">
                <h5 class="card-text">${temp}&deg;C - ${desc}</h5>
            `;
        })
        .catch(error => {
            console.error('Weather API error:', error);
            document.getElementById('weather-content').innerHTML = '
                <div class="alert alert-warning">Weather data unavailable.</div>
            ';
        });
</script>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>