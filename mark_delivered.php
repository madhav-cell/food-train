<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = 'Delivered' WHERE id = ?");
    $stmt->execute([$_POST['order_id']]);
}

header("Location: delivery_dashboard.php");
exit();
?>
