<?php
session_start();
require_once __DIR__ . '/../db.php';

// Make sure user is logged in
if (!isset($_SESSION['user'])) {
  echo "<div class='alert alert-warning m-5'>Please log in to view your reservations.</div>";
  exit;
}

$userId = $_SESSION['user']['id'];

// Get user's reservations
$sql = "SELECT confirm_number, begin_date, end_date, room_type_name
        FROM reservation r
        JOIN room_type rt ON r.room_type_id = rt.room_type_id
        WHERE r.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>

<div class="container py-4">
  <h2>Your Reservations</h2>

  <?php if ($result->num_rows > 0): ?>
    <ul class="list-group my-4">
      <?php while ($row = $result->fetch_assoc()): ?>
        <li class="list-group-item">
          <strong><?= htmlspecialchars($row['room_type_name']) ?></strong><br>
          From: <?= htmlspecialchars($row['begin_date']) ?><br>
          To: <?= htmlspecialchars($row['end_date']) ?><br>
          Confirmation #: <?= htmlspecialchars($row['confirm_number']) ?>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p class="text-muted">You have no reservations.</p>
  <?php endif; ?>

  <hr>

  <h4>Claim a Reservation</h4>
  <form id="claimForm" class="loadForm">
    <div class="mb-3">
      <label for="confirm_number" class="form-label">Enter Confirmation Number</label>
      <input type="text" id="confirm_number" name="confirm_number" class="form-control" required>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Claim</button>
  </form>

  <div id="claimResult" class="mt-3"></div>
</div>

