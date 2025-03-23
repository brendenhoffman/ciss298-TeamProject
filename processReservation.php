<?php
session_start();
require_once __DIR__ . '/../db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
  $checkInDate = $_POST['checkin'];
  $checkOutDate = $_POST['checkout'];
  $roomTypeId = $_POST['roomtype'];
  $confirmNumber = uniqid();

  // Get total number of rooms
  $sql = "SELECT total_room FROM room_type WHERE room_type_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $roomTypeId);
  $stmt->execute();
  $stmt->bind_result($totalRooms);
  $stmt->fetch();
  $stmt->close();

  // Get number of rooms already reserved
  $sql = "SELECT COUNT(*) FROM reservation 
          WHERE room_type_id = ?
            AND (
              (begin_date <= ? AND end_date > ?)
              OR (begin_date < ? AND end_date >= ?)
              OR (begin_date >= ? AND end_date <= ?)
            )";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("issssss", $roomTypeId,
    $checkInDate, $checkInDate,
    $checkOutDate, $checkOutDate,
    $checkInDate, $checkOutDate);
  $stmt->execute();
  $stmt->bind_result($roomsTaken);
  $stmt->fetch();
  $stmt->close();

  $available = $totalRooms - $roomsTaken;

  if ($available > 0) {
    // Insert reservation
    $sql = "INSERT INTO reservation (room_type_id, begin_date, end_date, confirm_number)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $roomTypeId, $checkInDate, $checkOutDate, $confirmNumber);
    if ($stmt->execute()) {
      $_SESSION['availability'] = "✅ Reservation successful! Confirmation number: <strong>$confirmNumber</strong>";
    } else {
      $_SESSION['availability'] = "❌ Reservation failed.";
    }
    $stmt->close();
  } else {
    $_SESSION['availability'] = "❌ Sorry, no rooms available. Try another date or room type.";
  }

  $_SESSION['checkin'] = $checkInDate;
  $_SESSION['checkout'] = $checkOutDate;
  $conn->close();

  header("Location: /?page=reservation.php");
  exit();
}
?>

