<?php session_start(); ?>
<div class="container py-4">
<h2 class="mb-4">Gallery</h2>

<?php if (isset($_SESSION['user']) && $_SESSION['user']['is_admin']): ?>
    <form id="galleryForm" class="loadForm mb-4" enctype="multipart/form-data" method="post">
      <div class="input-group">
    <input type="file" name="galleryPhoto" accept="image/*" class="form-control" required>
    <button type="submit" class="btn btn-primary">Upload Image</button>
    </div>
    </form>
    <?php endif; ?>

    <div class="row g-3">
    <?php
    require_once __DIR__ . '/../db.php';
    $result = $conn->query("SELECT filename FROM gallery ORDER BY uploaded_at DESC");
    $uploadDir = "/gallery/";

    while ($row = $result->fetch_assoc()):
    $src = $uploadDir . htmlspecialchars($row['filename']);
    ?>
    <div class="col-6 col-md-4 col-lg-3">
      <img src="<?= $src ?>" class="img-fluid rounded shadow-sm" alt="Gallery image">
      </div>
     <?php endwhile; ?>
    </div>
</div>