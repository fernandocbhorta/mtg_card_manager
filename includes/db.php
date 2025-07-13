<?php
$database = 'YOUR_DATABASE_NAME';
$host = 'YOUR_HOST';
$username = 'YOUR_USER';
$password = 'YOUR_PASSWORD';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
