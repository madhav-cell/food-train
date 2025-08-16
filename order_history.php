<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to view your order history.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Optional: Filter by date range (week/month)
$dateFilter = '';
if (isset($_GET['filter']) && $_GET['filter'] === 'week') {
    $dateFilter = "AND o.order_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif (isset($_GET['filter']) && $_GET['filter'] === 'month') {
    $dateFilter = "AND o.order_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
}

$stmt = $pdo->prepare("
    SELECT o.id AS order_id, o.pnr, o.mobile, o.station_code, o.order_date, o.status,
           oi.food_name, oi.quantity, oi.price,
           f.rating, f.comments, f.id AS feedback_id
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN feedback f ON o.id = f.order_id AND f.user_id = ?
    WHERE o.customer_id = ?
    $dateFilter
    ORDER BY o.order_date DESC
");
$stmt->execute([$user_id, $user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$orders) {
    echo "<link rel='stylesheet' href='history.css'>";
    echo "<div class='container'><p>No orders found.</p>";
    echo "<a class='home-button' href='index.php'>← Back to Home</a></div>";
    exit();
}

// Group items by order_id
$orderMap = [];
foreach ($orders as $row) {
    $oid = $row['order_id'];
    if (!isset($orderMap[$oid])) {
        $orderMap[$oid] = [
            'info' => $row,
            'items' => [],
            'total' => 0,
            'feedback' => [
                'id' => $row['feedback_id'],
                'rating' => $row['rating'],
                'comments' => $row['comments']
            ]
        ];
    }

    $orderMap[$oid]['items'][] = $row;
    $orderMap[$oid]['total'] += $row['quantity'] * $row['price'];
}

echo "<!DOCTYPE html><html><head>
    <meta charset='UTF-8'>
    <title>Order History</title>
    <link rel='stylesheet' href='history.css'>
</head><body>";

echo "<div class='container'>";
echo "<h2>Your Order History</h2>";

echo "<p class='filters'>Filter: 
    <a href='order_history.php'>All</a> | 
    <a href='order_history.php?filter=week'>Last 7 Days</a> | 
    <a href='order_history.php?filter=month'>Last Month</a>
</p><hr>";

foreach ($orderMap as $orderId => $data) {
    $info = $data['info'];
    $status = ucfirst($info['status'] ?? 'Pending');

    echo "<div class='order-block'>";
    echo "<h3>Order ID: {$orderId}</h3>";
    echo "<p><strong>PNR:</strong> " . htmlspecialchars($info['pnr']) . "</p>";
    echo "<p><strong>Mobile:</strong> " . htmlspecialchars($info['mobile']) . "</p>";
    echo "<p><strong>Station:</strong> " . htmlspecialchars($info['station_code']) . "</p>";
    echo "<p><strong>Date:</strong> " . htmlspecialchars($info['order_date']) . "</p>";
    echo "<p><strong>Status:</strong> $status</p>";

    echo "<ul>";
    foreach ($data['items'] as $item) {
        echo "<li>" . htmlspecialchars($item['food_name']) .
             " - " . intval($item['quantity']) . " x ₹" . htmlspecialchars($item['price']) . "</li>";
    }
    echo "</ul>";

    echo "<p><strong>Total:</strong> ₹" . number_format($data['total'], 2) . "</p>";

    if ($data['feedback']['id']) {
        echo "<p><strong>Your Feedback:</strong> Rating: " . intval($data['feedback']['rating']) . "/5<br>";
        echo "Comments: " . nl2br(htmlspecialchars($data['feedback']['comments'])) . "</p>";
    } else {
        echo "<p><a class='feedback-link' href='feedback_form.php?order_id={$orderId}'>Give Feedback</a></p>";
    }

}
echo "<a class='home-button' href='index.php'>← Back to Home</a>";
echo "</div></body></html>";
?>
