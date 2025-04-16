<?php
session_start();
require_once __DIR__ . '/db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm'] ?? '';

// Alphanumeric check
if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
    echo "<div class='alert alert-danger m-5'>Username can only contain letters and numbers.</div>";
    exit;
}

// Confirm passwords match
if ($password !== $confirm) {
    echo "<div class='alert alert-danger m-5'>Passwords do not match.</div>";
    exit;
}

// Password validation
function validatePassword($password, $username) {
    if (strlen($password) < 8) return "Password must be at least 8 characters long.";
    if (!preg_match('/[A-Z]/', $password)) return "Password must include at least one uppercase letter.";
    if (!preg_match('/[a-z]/', $password)) return "Password must include at least one lowercase letter.";
    if (!preg_match('/[0-9]/', $password)) return "Password must include at least one number.";
    if (!preg_match('/[\W_]/', $password)) return "Password must include at least one symbol.";
    if (stripos($password, $username) !== false) return "Password must not include your username.";
    return true;
}

$valid = validatePassword($password, $username);
if ($valid !== true) {
    echo "<div class='alert alert-danger m-5'>$valid</div>";
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

// Hash and insert password
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

