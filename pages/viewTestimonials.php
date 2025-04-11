<?php
require_once __DIR__ . '/../db.php';
?>

<div class="container py-5">
  <h1 class="text-center mb-4">Customer Testimonials</h1>

  <?php
  // Query the testimonials table to fetch all testimonials
  $sql = "SELECT name, comment, photo, date_added FROM testimonials ORDER BY date_added DESC";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      // Loop through the rows and display the testimonials
      while ($row = $result->fetch_assoc()) {
          echo "<div class='col'>";
          echo "<div class='card p-3 shadow-sm'>";

          // Display the photo
          echo "<div class='text-center mb-3'>";
          echo "<img src='/uploads/{$row['photo']}' alt='testimonial photo' class='img-fluid rounded' style='max-width: 500px; height: auto;'>";
          echo "</div>";

          // Display the name, comment, and date
          echo "<h5 class='mb-2'>{$row['name']}</h5>";
          echo "<p class='mb-2'>{$row['comment']}</p>";
          echo "<small>Submitted on: {$row['date_added']}</small>";

          echo "</div>";
          echo "</div>";
      }
      echo "</div>";
  } else {
      echo "<p class='alert alert-info'>No testimonials available.</p>";
  }

  $conn->close();
  ?>
</div>
