<?php
session_start();
require_once __DIR__ . '/db.php';

// Only accept POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  exit;
}

// Sanitize input
$checkInDate = $_POST['checkin'] ?? '';
$checkOutDate = $_POST['checkout'] ?? '';
$roomTypeId = intval($_POST['roomtype'] ?? 0);

// Get room count
$sql = "SELECT total_room FROM room_type WHERE room_type_id = $roomTypeId";
$res = $conn->query($sql);
$room = $res->fetch_object();
$roomCount = $room->total_room ?? 0;

// Count reserved
$sql = "SELECT COUNT(*) AS roomReserved FROM reservation
        WHERE room_type_id = $roomTypeId AND (
          (begin_date <= '$checkInDate' AND end_date > '$checkInDate') OR
          (begin_date < '$checkOutDate' AND end_date >= '$checkOutDate') OR
          (begin_date >= '$checkInDate' AND end_date <= '$checkOutDate')
        )";
$res = $conn->query($sql);
$row = $res->fetch_object();
$roomTaken = $row->roomReserved ?? 0;

$available = $roomCount - $roomTaken;

$response = [];

if ($available > 0) {
  $_SESSION['checkin'] = $checkInDate;
  $_SESSION['checkout'] = $checkOutDate;
  $_SESSION['roomtype'] = $roomTypeId;
  $response['available'] = true;
  $response['message'] = "✅ Room is available! You can now reserve.";
} else {
  $response['available'] = false;
  $response['message'] = "❌ Sorry, not available. Try different dates or room.";
}

header("Content-Type: application/json");
echo json_encode($response);
?>
