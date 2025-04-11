<?php
session_start();
if (!($_SESSION['user']['is_admin'] ?? false)) {
  echo "<div class='alert alert-danger m-5'>Access Denied.</div>";
  exit;
}

require_once __DIR__ . '/../db.php';

$result = $conn->query("SELECT * FROM room_type");
?>

<div class="container py-4">
  <h2 class="mb-4">Manage Room Types</h2>
  <form id="roomTypeForm" class="loadForm">
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Room Type</th>
            <th>Total Rooms</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['room_type_name']) ?></td>
            <td>
              <input type="number" name="rooms[<?= $row['room_type_id'] ?>]" class="form-control" value="<?= $row['total_room'] ?>" required>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="mt-3 text-end">
      <button type="submit" class="btn btn-success">Save Changes</button>
    </div>
  </form>
</div>

