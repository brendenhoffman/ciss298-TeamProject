<?php
session_start();
require_once __DIR__ . '/db.php';

if (!($_SESSION['user']['is_admin'] ?? false)) {
  http_response_code(403);
  echo "<div class='alert alert-danger m-5'>Unauthorized access</div>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rooms']) && is_array($_POST['rooms'])) {
  $successCount = 0;
  $stmt = $conn->prepare("UPDATE room_type SET total_room = ? WHERE room_type_id = ?");

  foreach ($_POST['rooms'] as $roomId => $totalRoom) {
    if (is_numeric($roomId) && is_numeric($totalRoom)) {
      $stmt->bind_param("ii", $totalRoom, $roomId);
      if ($stmt->execute()) {
        $successCount++;
      }
    }
  }

  $stmt->close();
  echo "<div class='alert alert-success m-5'>Updated $successCount room types successfully.</div>";
} else {
  echo "<div class='alert alert-warning m-5'>Invalid input.</div>";
}

$conn->close();
?>

