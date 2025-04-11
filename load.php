<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

header("Content-Type: text/html");

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

