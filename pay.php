<?php
session_start();
require('razorpay-php/Razorpay.php'); // adjust path

use Razorpay\Api\Api;

$keyId = "rzp_test_XXXXXX";
$keySecret = "XXXXXXX";

$api = new Api($keyId, $keySecret);

$amount = $_SESSION['total_amount'] * 100; // amount in paise
$receipt = 'order_rcptid_' . rand(1000,9999);

$order = $api->order->create([
    'receipt' => $receipt,
    'amount' => $amount,
    'currency' => 'INR'
]);

$_SESSION['razorpay_order_id'] = $order['id'];
?>

<form action="verify_payment.php" method="POST">
  <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?= $keyId ?>"
    data-amount="<?= $amount ?>"
    data-currency="INR"
    data-order_id="<?= $order['id'] ?>"
    data-buttontext="Pay with Razorpay"
    data-name="Train Food Order"
    data-description="Order Payment"
    data-prefill.name="Customer"
    data-theme.color="#F37254"
  ></script>
  <input type="hidden" custom="Hidden Element" name="hidden">
</form>
