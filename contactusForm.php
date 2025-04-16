<?php
session_start();
require_once __DIR__ . '/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

if (empty($name) || empty($email) || empty($message)) {
    echo "<div class='m-5 alert alert-warning'>All fields are required.</div>";
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<div class='m-5 alert alert-warning'>Invalid email address.</div>";
    exit();
}
$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    if ($stmt === false) {
    echo "<div class='m-5 alert alert-warning'>Error preparing the SQL statement.</div>";
    exit();}

$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    echo "<div class='m-5 alert alert-success'>Message sent!</div>";
} else {
    echo "<div class='m-5 alert alert-warning'>Error sending message</div>";
}

$stmt->close();
$conn->close();
exit();
?>
