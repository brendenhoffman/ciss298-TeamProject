<?php
session_start();
require_once __DIR__ . '/db.php';

if (!($_SESSION['user']['is_admin'] ?? false)) {
  echo "<div class='alert alert-danger m-5'>Unauthorized access.</div>";
  exit;
}

$title = trim($_POST['title']);
$desc = trim($_POST['description']);
$loc = trim($_POST['location']);
$start = $_POST['start_date'];
$end = $_POST['end_date'] ?? null;
$startTime = $_POST['start_time'];
$endTime = $_POST['end_time'];

if ($title && $desc && $loc && $start && $startTime && $endTime) {
  $stmt = $conn->prepare("INSERT INTO events (title, description, location, start_date, end_date, start_time, end_time)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssss", $title, $desc, $loc, $start, $end, $startTime, $endTime);

  if ($stmt->execute()) {
    echo "<div class='alert alert-success m-5'>✅ Event created!</div>";
  } else {
    echo "<div class='alert alert-danger m-5'>Error: " . $stmt->error . "</div>";
  }

  $stmt->close();
} else {
  echo "<div class='alert alert-warning m-5'>Please fill all required fields.</div>";
}

$conn->close();
?>