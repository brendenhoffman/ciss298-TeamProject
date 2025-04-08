<?php
session_start();
require_once __DIR__ . '/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$sql = "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "<div class='m-5 alert alert-success'>Message sent!.</div>";
} else {
    echo "<div class='m-5 alert alert-warning'>.Error sending message</div>";
}

exit();
?>