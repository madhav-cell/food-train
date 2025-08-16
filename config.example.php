<?php
// Copy this file to config.php and fill your real values.
// DO NOT commit config.php (it's in .gitignore).

$host = 'localhost';
$db   = 'food_ordering';
$user = 'your_db_user';     // e.g., root (local) or cPanel username (live)
$pass = 'your_db_password'; // e.g., Madhav@2109 (local)

// PDO connection example
try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
