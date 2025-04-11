<?php
session_start();
require_once __DIR__ . '/../db.php';
?>

<div class="container py-4">
  <h2 class="mb-4">Upcoming Events</h2>

  <?php if (isset($_SESSION['user']) && $_SESSION['user']['is_admin']): ?>
    <form id="eventForm" class="loadForm mb-5">
      <div class="mb-3">
        <label for="title" class="form-label">Event Title:</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description:</label>
        <textarea name="description" class="form-control" rows="4" required></textarea>
      </div>
      <div class="mb-3">
        <label for="location" class="form-label">Location:</label>
        <input type="text" name="location" class="form-control" required>
      </div>
      <div class="row mb-3">
        <div class="col">
          <label for="start_date" class="form-label">Start Date:</label>
          <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="col">
          <label for="end_date" class="form-label">End Date (Optional):</label>
          <input type="date" name="end_date" class="form-control">
        </div>
      </div>
      <div class="row mb-3">
        <div class="col">
          <label for="start_time" class="form-label">Start Time:</label>
          <input type="time" name="start_time" class="form-control" required>
        </div>
        <div class="col">
          <label for="end_time" class="form-label">End Time:</label>
          <input type="time" name="end_time" class="form-control" required>
        </div>
      </div>
      <button type="submit" name="submit" class="btn btn-success">Post Event</button>
    </form>
  <?php endif; ?>

  <?php
  $result = $conn->query("SELECT * FROM events ORDER BY start_date DESC, start_time DESC");
  while ($row = $result->fetch_assoc()):
  ?>
<div class="card mb-4 shadow-sm">
  <div class="card-body bg-light border rounded">
    <h5 class="card-title text-dark"><?= htmlspecialchars($row['title']) ?></h5>
    <p class="card-text text-dark"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
    <hr>
    <p class="card-text text-muted mb-1">
      <strong>📍 Location:</strong> <?= htmlspecialchars($row['location']) ?>
    </p>
    <p class="card-text text-muted mb-1">
      <strong>📅 Date:</strong>
      <?= htmlspecialchars($row['start_date']) ?>
      <?= $row['end_date'] ? ' to ' . htmlspecialchars($row['end_date']) : '' ?>
    </p>
    <p class="card-text text-muted">
      <strong>⏰ Time:</strong>
      <?= htmlspecialchars(substr($row['start_time'], 0, 5)) ?> - <?= htmlspecialchars(substr($row['end_time'], 0, 5)) ?>
    </p>
  </div>
</div>
  <?php endwhile; ?>
</div>
