<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    echo "User not logged in or cart is empty.";
    exit();
}

// Process cart to DB
$user_id = $_SESSION['user_id'];
$pnr = $_SESSION['pnr'] ?? 'N/A'; // Or get from form/session
$mobile = $_SESSION['mobile'] ?? '0000000000';
$station_code = $_SESSION['station_code'] ?? 'STN';
$order_date = date('Y-m-d H:i:s');

// Insert into orders table
$stmt = $pdo->prepare("INSERT INTO orders (customer_id, pnr, mobile, station_code, order_date) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $pnr, $mobile, $station_code, $order_date]);
$order_id = $pdo->lastInsertId();

// Insert order items
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, food_name, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->execute([$order_id, $item['item_name'], $item['quantity'], $item['item_price']]);
    $total_amount += $item['quantity'] * $item['item_price'];
}

// Clear cart
unset($_SESSION['cart']);

// Generate UPI link and QR
$upi_id = "madhavdeshpande123@ibl";
$payee_name = "Madhav Deshpande";
$amount = $total_amount;

$upi_link = "upi://pay?pa=$upi_id&pn=" . urlencode($payee_name) . "&am=$amount&cu=INR";
$qr_code_url = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . urlencode($upi_link);
$phonepe_qr_path = "images/payment.jpg"; // Static backup image
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pay via UPI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 40px;
        }
        .qr-container {
            border: 2px solid #444;
            padding: 20px;
            display: inline-block;
            max-width: 700px;
        }
        .qr-images {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .qr-images img {
            width: 280px;
            height: auto;
        }
        .upi-text {
            font-size: 18px;
            margin-top: 10px;
        }
        .upi-id {
            font-weight: bold;
            color: #2c3e50;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>Scan to Pay</h1>
    <div class="qr-container">
        <div class="qr-images">
            <img src="<?= $qr_code_url ?>" alt="UPI QR Code">
            <img src="<?= $phonepe_qr_path ?>" alt="PhonePe QR">
        </div>
        <div class="upi-text">Pay to <span class="upi-id"><?= $upi_id ?></span></div>
        <div class="upi-text">Name: <b><?= $payee_name ?></b></div>
        <?php if ($amount && $amount > 0): ?>
            <div class="upi-text">Amount: ₹<b><?= $amount ?></b></div>
        <?php endif; ?>
    </div>

    <a href="order_history.php">→ View Order History</a>
</body>
</html>
