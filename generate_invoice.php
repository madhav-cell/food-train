<?php
session_start();
include 'db_connect.php';
require('fpdf.php');  // Make sure the path is correct

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first to download invoice.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? null;

if (!$order_id || !is_numeric($order_id)) {
    die('Invalid order ID');
}

// Fetch order info
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND customer_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die('Order not found or access denied.');
}

// Fetch order items
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Order ID: " . $order['id'], 0, 1);
$pdf->Cell(0, 10, "PNR: " . $order['pnr'], 0, 1);
$pdf->Cell(0, 10, "Customer ID: " . $order['customer_id'], 0, 1);
$pdf->Cell(0, 10, "Order Date: " . $order['order_date'], 0, 1);

$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 10, "Item", 1);
$pdf->Cell(30, 10, "Quantity", 1, 0, 'C');
$pdf->Cell(40, 10, "Price (₹)", 1, 0, 'R');
$pdf->Cell(30, 10, "Total (₹)", 1, 1, 'R');

$pdf->SetFont('Arial', '', 12);

$total_amount = 0;

foreach ($items as $item) {
    $item_total = $item['quantity'] * $item['price'];
    $total_amount += $item_total;

    $pdf->Cell(90, 10, $item['food_name'], 1);
    $pdf->Cell(30, 10, $item['quantity'], 1, 0, 'C');
    $pdf->Cell(40, 10, number_format($item['price'], 2), 1, 0, 'R');
    $pdf->Cell(30, 10, number_format($item_total, 2), 1, 1, 'R');
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(160, 10, "Grand Total", 1);
$pdf->Cell(30, 10, number_format($total_amount, 2), 1, 1, 'R');

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 7, "Thank you for your order! If you have any questions, please contact support.");

// Output PDF to browser for download
$pdf->Output('I', "invoice_order_{$order_id}.pdf");
exit();
?>
