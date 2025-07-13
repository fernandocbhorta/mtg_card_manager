<?php
$database = 'class869_horta';
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>