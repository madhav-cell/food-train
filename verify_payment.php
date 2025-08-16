<?php
session_start();
require('razorpay-php/Razorpay.php'); // adjust path
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$keyId = "rzp_test_XXXXXX";
$keySecret = "XXXXXXX";

$api = new Api($keyId, $keySecret);

try {
    $attributes = [
        'razorpay_order_id' => $_POST['razorpay_order_id'],
        'razorpay_payment_id' => $_POST['razorpay_payment_id'],
        'razorpay_signature' => $_POST['razorpay_signature']
    ];

    $api->utility->verifyPaymentSignature($attributes);

    // ✅ Payment successful
    echo "<h2>Payment Successful</h2>";
    // Save to DB: payment_id, order_id, amount, status, etc.
    // Clear cart if needed
    unset($_SESSION['cart']);

} catch (SignatureVerificationError $e) {
    echo "<h2>Payment Failed</h2>";
    echo "Error: " . $e->getMessage();
}
?>
