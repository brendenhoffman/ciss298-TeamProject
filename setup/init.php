<?php
require_once __DIR__ . '/../db.php';

$sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
if (!$conn->query($sql)) {
  die("Failed to create database '$dbname': " . $conn->error);
}

if (!$conn->select_db($dbname)) {
  die("Failed to select database '$dbname': " . $conn->error);
}

$schema = file_get_contents(__DIR__ . '/schema.sql');
if ($conn->multi_query($schema)) {
  do {
    $conn->store_result();
  } while ($conn->more_results() && $conn->next_result());

  echo "Schema installed successfully.";
} else {
  echo "Error installing schema: " . $conn->error;
}

$res = $conn->query("SELECT COUNT(*) AS count FROM room_type");
$count = $res->fetch_assoc()['count'];

if ($count == 0) {
  echo "Seeding default room types...\n";
  $conn->query("INSERT IGNORE INTO room_type (room_type_id, room_type_name) VALUES (1, 'Single')");
  $conn->query("INSERT IGNORE INTO room_type (room_type_id, room_type_name) VALUES (2, 'Double')");
  $conn->query("INSERT IGNORE INTO room_type (room_type_id, room_type_name) VALUES (3, 'Family')");
  echo "Room types inserted.\n";
} else {
  echo "Room types already exist, skipping.\n";
}

$res = $conn->query("SELECT COUNT(*) AS count FROM users");
$count = $res ? $res->fetch_assoc()['count'] : 0;

if ($count == 0) {
  echo "Seeding test users...<br>";
  $adminPass = password_hash('admin', PASSWORD_DEFAULT);
  $userPass  = password_hash('user', PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, ?)");

  $admin = ['admin', $adminPass, 1];
  $user  = ['user',  $userPass,  0];

  $stmt->bind_param("ssi", ...$admin);
  $stmt->execute();

  $stmt->bind_param("ssi", ...$user);
  $stmt->execute();

  $stmt->close();
  echo "Users inserted.<br>";
} else {
  echo "Users already exist, skipping.<br>";
}

$checkColumn = $conn->query("SHOW COLUMNS FROM reservation LIKE 'user_id'");
if ($checkColumn->num_rows === 0) {
  echo "Adding 'user_id' column to reservation table...\n";
  $alter = "ALTER TABLE reservation ADD COLUMN user_id INT DEFAULT NULL, 
            ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL";
  if (!$conn->query($alter)) {
    echo "Error adding user_id column: " . $conn->error . "\n";
  } else {
    echo "'user_id' column added.\n";
  }
} else {
  echo "'user_id' column already exists, skipping.\n";
}

$conn->close();
?>

