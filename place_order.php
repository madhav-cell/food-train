<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
    exit();
}

$pnr = $_POST['pnr'];
$mobile = $_POST['mobile'];
$station_code = $_POST['station_code'];
$items = $_SESSION['cart'];
$customer_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("INSERT INTO orders (pnr, mobile, station_code, order_date, customer_id) VALUES (?, ?, ?, NOW(), ?)");
$stmt->execute([$pnr, $mobile, $station_code, $customer_id]);
$order_id = $pdo->lastInsertId();

$detail_stmt = $pdo->prepare("INSERT INTO order_items (order_id, food_name, quantity, price) VALUES (?, ?, ?, ?)");

foreach ($items as $item) {
    $detail_stmt->execute([$order_id, $item['item_name'], $item['quantity'], $item['item_price']]);
}

unset($_SESSION['cart']);

echo "<h2>Order Placed Successfully!</h2>";
echo "<p>Your Order ID: $order_id</p>";
?>
