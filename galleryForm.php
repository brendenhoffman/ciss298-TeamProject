<?php
ini_set('display_errors' , 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/db.php';

$uploadDir = __DIR__ . '/gallery/';
$photo = $_FILES['galleryPhoto']['name'] ?? '';
$uniqueFileName = uniqid('gallery_', true) . '.' . pathinfo($photo, PATHINFO_EXTENSION);
$photoPath = $uploadDir . $uniqueFileName;

if (move_uploaded_file($_FILES['galleryPhoto']['tmp_name'], $photoPath)) {
  // Save filename in database
  $sql = "INSERT INTO gallery (filename) VALUES (?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $uniqueFileName);

  if ($stmt->execute()) {
    $_SESSION['message'] = "Image uploaded successfully!";
  } else {
    $_SESSION['message'] = "Error saving to database: " . $stmt->error;
  }

  $stmt->close();
} else {
  $_SESSION['message'] = "Failed to upload image.";
}

header("Location: /pages/gallery.php");
exit();
?>
