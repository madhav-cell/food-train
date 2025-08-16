<?php
session_start();
include 'db_connect.php';

$stmt = $pdo->query("SELECT id, restaurant_name FROM restaurants");
$restaurants = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Restaurants</title>
</head>
<body>
    <h1>Restaurants</h1>
    <ul>
        <?php foreach ($restaurants as $restaurant): ?>
            <li>
                <a href="menu.php?rid=<?= htmlspecialchars($restaurant['id']) ?>">
                    <?= htmlspecialchars($restaurant['restaurant_name']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
