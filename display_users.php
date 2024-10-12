<?php
require_once 'Database.php';
require_once 'User.php';

// Database connection
$database = new Database();
$db = $database->getConnection();

// Create user object and fetch data
$user = new User($db);
$stmt = $user->read();

echo "<h2>Registered Users</h2>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    echo "Username: {$username}, Email: {$email}, Created At: {$created_at}<br>";
}
?>
