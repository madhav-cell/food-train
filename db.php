<?php
$host = 'localhost';
$db = 'food_ordering';
$user = 'root'; // Change this to your database username
$pass = 'Madhav@2109'; // Change this to your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>