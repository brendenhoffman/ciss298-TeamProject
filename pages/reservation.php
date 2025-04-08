<?php
session_start();
require_once __DIR__ . '/../db.php';
?>
<div class="p-4">
<h2>Make a Reservation</h2>

<?php
if (isset($_SESSION['availability'])) {
  echo "<p>" . $_SESSION['availability'] . "</p>";
  unset($_SESSION['availability']); // Clear after showing
}
?>

  <form id="reservationForm" class="loadForm">
    <div class="mb-3">
      <label for="checkin" class="form-label">Check In Date:</label>
      <input type="date" class="form-control" id="checkin" name="checkin" required
        value="<?= $_SESSION['checkin'] ?? '' ?>">
    </div>
    <div class="mb-3">
      <label for="checkout" class="form-label">Check Out Date:</label>
      <input type="date" class="form-control" id="checkout" name="checkout" required
        value="<?= $_SESSION['checkout'] ?? '' ?>">
    </div>
    <div class="mb-3">
      <label for="roomtype" class="form-label">Room Type:</label>
        <select name="roomType" id="roomType" class="form-select" required>
          <?php
            require_once __DIR__ . '/../db.php';
            $result = $conn->query("SELECT * FROM room_type");

            if ($result) {
              while ($row = $result->fetch_assoc()) {
                $selected = ($_SESSION['roomtype'] ?? '') == $row['room_type_id'] ? 'selected' : '';
                echo "<option value='{$row['room_type_id']}' $selected>{$row['room_type_name']}</option>";
              }
            }
          ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Make Reservation</button>
  </form>
</div>
