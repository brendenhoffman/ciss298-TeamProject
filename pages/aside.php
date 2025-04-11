<aside class="border rounded p-3 asideForm">
  <h4>Check Availability</h4>
  <form id="availability-form">
    <label for="checkin">Check In:</label>
    <input type="date" id="checkin" name="checkin" required>

    <label for="checkout">Check Out:</label>
    <input type="date" id="checkout" name="checkout" required>

    <label for="roomtype">Room Type:</label>
    <select id="roomtype" name="roomtype" required>
      <?php
      require_once __DIR__ . '/../db.php';
      $res = $conn->query("SELECT room_type_id, room_type_name FROM room_type ORDER BY room_type_id");
      while ($row = $res->fetch_assoc()) {
        echo "<option value='{$row['room_type_id']}'>{$row['room_type_name']}</option>";
      }
      ?>
    </select>

    <button type="submit" class="btn btn-primary mt-2">Check Availability</button>
  </form>

  <div id="availability-result" class="mt-2"></div>

</aside>
