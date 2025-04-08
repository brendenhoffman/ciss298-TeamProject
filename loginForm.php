<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username && $password) {
  $stmt = $conn->prepare("SELECT id, username, password, is_admin FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $hashed_password = $user['password'];

    if (password_verify($password, $hashed_password)) {
      // Login successful
      $_SESSION['user'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'is_admin' => (bool)$user['is_admin']
      ];
      header('Content-Type: application/json');
      echo json_encode([
        'status' => 'success',
        'message' => "<div class='m-5 alert alert-success'>Welcome, <strong>{$user['username']}</strong>!</div>",
        'refreshHeader' => true
      ]);
    } else {
      echo "<div class='m-5 alert alert-danger'>Incorrect password.</div>";
    }
  } else {
    echo "<div class='m-5 alert alert-danger'>User not found.</div>";
  }
  $stmt->close();
} else {
  echo "<div class='m-5 alert alert-warning'>All fields are required.</div>";
}
$conn->close();
?>

