<?php require_once __DIR__ . '/../db.php'; ?>

<h1>Test Page: DB Connection and Table Output</h1>

<?php
// Create a test table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS test_table (
  id INT AUTO_INCREMENT PRIMARY KEY,
  data VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($sql)) {
  echo "<p><strong>Error creating table:</strong> " . $conn->error . "</p>";
  return;
}

// Insert a random row
$randomData = "TestData_" . rand(1000, 9999);
$sql = "INSERT INTO test_table (data) VALUES ('$randomData')";
if (!$conn->query($sql)) {
  echo "<p><strong>Error inserting data:</strong> " . $conn->error . "</p>";
  return;
}

// Fetch and display rows
$result = $conn->query("SELECT * FROM test_table");

if ($result->num_rows > 0) {
  echo "<h2>Stored Data:</h2><ul>";
  while ($row = $result->fetch_assoc()) {
    echo "<li>ID: {$row['id']} - Data: {$row['data']} - Created At: {$row['created_at']}</li>";
  }
  echo "</ul>";
} else {
  echo "<p>No data found.</p>";
}

$conn->close();
?>

