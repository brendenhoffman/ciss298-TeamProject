<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit("Access Denied");
}

if (!isset($_SESSION['user'])) {
  echo "<div class='m-5 alert alert-warning'>You must be logged in to claim a reservation.</div>";
  exit;
}

$confirm = $_POST['confirm_number'] ?? '';
$confirm = trim($confirm);
$userId = $_SESSION['user']['id'];

if (!$confirm) {
  echo "<div class='m-5 alert alert-warning'>Please enter a confirmation number.</div>";
  exit;
}

// Try to attach the reservation if it's not already claimed
$sql = "UPDATE reservation SET user_id = ? WHERE confirm_number = ? AND user_id IS NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $userId, $confirm);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo "<div class='m-5 alert alert-success'>Reservation claimed successfully!</div>";
} else {
  echo "<div class='m-5 alert alert-danger'>Reservation not found or already claimed.</div>";
}

$stmt->close();
$conn->close();
?>

