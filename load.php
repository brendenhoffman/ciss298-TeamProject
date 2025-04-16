<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

session_start();
header("Content-Type: text/html");

// --- USER STORY E: Auto-logout after 10 minutes of inactivity ---
if (isset($_SESSION['user'])) {
  $now = time();
  $lastActivity = $_SESSION['last_activity'] ?? $now;
  $inactiveTime = $now - $lastActivity;

  if ($inactiveTime > 300) {
    session_unset();
    session_destroy();
    echo "<div class='m-5 alert alert-warning text-center'>You were logged out due to inactivity.</div>";
    exit;
  } else {
    $_SESSION['last_activity'] = $now; // Update activity timestamp
  }
}

if ($_SERVER["REQUEST_METHOD"] !== "GET" || empty($_GET['page'])) {
  http_response_code(403);
  exit("403 Forbidden");
}

$page = preg_replace("/[^a-zA-Z0-9_-]/", "", $_GET['page']);
$htmlFile = __DIR__ . "/pages/{$page}.html";
$phpFile = __DIR__ . "/pages/{$page}.php";

// Serve PHP if it exists, else fallback to HTML
if (file_exists($phpFile)) {
  include $phpFile;
} elseif (file_exists($htmlFile)) {
  readfile($htmlFile);
} else {
  http_response_code(404);
  echo "<h1>404 - Page Not Found</h1>";
}?>

