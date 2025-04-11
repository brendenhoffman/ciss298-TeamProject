<?php
session_start();
require_once __DIR__ . '/db.php';

// Process the uploaded file
$uploadDir = __DIR__ . '/uploads/';
$photo = $_FILES['myPhoto']['name'];
$uniqueFileName = uniqid('testimonial_', true) . '.' . pathinfo($photo, PATHINFO_EXTENSION);
$photoPath = $uploadDir . $uniqueFileName;

if (move_uploaded_file($_FILES['myPhoto']['tmp_name'], $photoPath)) {
    $name = $_POST['name'];
    $comment = $_POST['comment'];

    // Insert into the database with the unique filename
    $sql = "INSERT INTO testimonials (name, comment, photo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $comment, $uniqueFileName);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Testimonial submitted successfully!";
    } else {
        $_SESSION['message'] = "Error submitting testimonial: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "Failed to upload photo.";
}

header("Location: /pages/viewTestimonials.php");  // Redirect after form submission
exit();
?>

