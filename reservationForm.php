<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    exit('Access Denied');
}

$checkInDate = $_POST['checkin'];
$checkOutDate = $_POST['checkout'];
$roomTypeId = $_POST['roomType'];
$confirmNumber = uniqid();

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

if ($available > 0) {
    // Insert reservation
    $sql = "INSERT INTO reservation (room_type_id, begin_date, end_date, confirm_number)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $roomTypeId, $checkInDate, $checkOutDate, $confirmNumber);
    if ($stmt->execute()) {
        $_SESSION['availability'] = "✅ Reservation successful! Confirmation number: <strong>$confirmNumber</strong>";
        echo "<div class='m-5 alert alert-success text-center' role='alert'>
        ✅ Reservation successful! Confirmation number: <strong>$confirmNumber</strong>
      </div>";

    } else {
        echo "❌ Reservation failed.";
    }
    $stmt->close();
} else {
echo "<div class='m-5 alert alert-danger text-center' role='alert'>
        ❌ Sorry, no rooms available. Try another date or room type.
      </div>";
}

$_SESSION['checkin'] = $checkInDate;
$_SESSION['checkout'] = $checkOutDate;
$conn->close();
?>
