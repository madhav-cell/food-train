<?php
include 'db_connect.php';

$order_id = $_POST['order_id'];
$delivery_boy_id = $_POST['delivery_boy_id'];

$stmt = $pdo->prepare("UPDATE orders SET delivery_boy_id = ?, status = 'Assigned' WHERE id = ?");
$stmt->execute([$delivery_boy_id, $order_id]);

header("Location: admin_orders.php");
exit();
?>
