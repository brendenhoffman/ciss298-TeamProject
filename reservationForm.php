<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Access Denied');
}

$checkInDate = $_POST['checkin'];
$checkOutDate = $_POST['checkout'];
$roomTypeId = $_POST['roomType'];
$confirmNumber = uniqid();
$userId = $_SESSION['user']['id'] ?? null;

// Get total rooms of the type
$sql = "SELECT total_room FROM room_type WHERE room_type_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $roomTypeId);
$stmt->execute();
$stmt->bind_result($roomCount);
$stmt->fetch();
$stmt->close();

// Count overlapping reservations
$sql = "SELECT COUNT(*) FROM reservation
        WHERE room_type_id = ? AND (
          (begin_date <= ? AND end_date > ?) OR
          (begin_date < ? AND end_date >= ?) OR
          (begin_date >= ? AND end_date <= ?)
        )";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssss", $roomTypeId, $checkInDate, $checkInDate, $checkOutDate, $checkOutDate, $checkInDate, $checkOutDate);
$stmt->execute();
$stmt->bind_result($roomTaken);
$stmt->fetch();
$stmt->close();

$available = $roomCount - $roomTaken;

if ($available > 0) {
  // Insert reservation (with or without user ID)
  if ($userId) {
    $sql = "INSERT INTO reservation (room_type_id, begin_date, end_date, confirm_number, user_id)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $roomTypeId, $checkInDate, $checkOutDate, $confirmNumber, $userId);
  } else {
    $sql = "INSERT INTO reservation (room_type_id, begin_date, end_date, confirm_number)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $roomTypeId, $checkInDate, $checkOutDate, $confirmNumber);
  }

  if ($stmt->execute()) {
    $_SESSION['availability'] = "✅ Reservation successful! Confirmation number: <strong>$confirmNumber</strong>";
    $_SESSION['last_confirmation'] = $confirmNumber;
    echo "<div class='m-5 alert alert-success text-center' role='alert'>
            ✅ Reservation successful! Confirmation number: <strong>$confirmNumber</strong>
          </div>";
  } else {
    echo "<div class='m-5 alert alert-danger text-center' role='alert'>
            ❌ Reservation failed. Please try again.
          </div>";
  }
  $stmt->close();
} else {
  echo "<div class='m-5 alert alert-danger text-center' role='alert'>
          ❌ Sorry, no rooms available. Try another date or room type.
        </div>";
}

// Store dates in session (for convenience)
$_SESSION['checkin'] = $checkInDate;
$_SESSION['checkout'] = $checkOutDate;

$conn->close();
?>
