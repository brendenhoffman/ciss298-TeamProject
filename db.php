<?php
//header("Content-Type: application/json");

// Check if running on Windows (XAMPP)
if (strtoupper(substr(PHP_OS, 0, 3)) === "WIN") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel";
} else {
    // Load environment variables for Linux/Production
    $env = parse_ini_file(__DIR__ . "/.env");
    $servername = $env['DB_HOST'];
    $username = $env['DB_USER'];
    $password = isset($env['DB_PASS']) ? $env['DB_PASS'] : "";
    $dbname = $env['DB_NAME'];
}

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
?>

