<?php
session_start();
require_once __DIR__ . '/db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm'] ?? '';

// Basic validation
if ($password !== $confirm) {
  echo "<div class='alert alert-danger m-5'>Passwords do not match.</div>";
  exit;
}

// Check if username exists
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
  echo "<div class='alert alert-danger m-5'>Username already exists.</div>";
  exit;
}

// Hash password and insert
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 0)");
$stmt->bind_param("ss", $username, $hashed);

if ($stmt->execute()) {
  $_SESSION['user'] = [
    'id' => $stmt->insert_id,
    'username' => $username,
    'is_admin' => false
  ];
  echo "<div class='alert alert-success m-5'>Account created. You may now log in.</div>";
} else {
  echo "<div class='alert alert-danger m-5'>Error creating account: {$stmt->error}</div>";
}
$stmt->close();
$conn->close();
?>

