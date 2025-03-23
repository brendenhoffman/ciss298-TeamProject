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

$conn->close();
?>

