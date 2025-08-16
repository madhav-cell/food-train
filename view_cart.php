<?php
session_start();

echo "<h2>Your Cart</h2>";
$total = 0;

if (!empty($_SESSION['cart'])) {
    echo "<form action='place_order.php' method='POST'>";
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['item_price'] * $item['quantity'];
        $total += $subtotal;
        echo "<div>
            <h4>{$item['item_name']} ({$item['quantity']} × ₹{$item['item_price']}) = ₹$subtotal</h4>
        </div><hr>";
    }

    echo "<h3>Total: ₹$total</h3>";

    // Add Customer Info
    echo "<label>Enter PNR: </label><input type='text' name='pnr' required><br>";
    echo "<label>Mobile: </label><input type='text' name='mobile' required><br>";
    echo "<label>Delivery Station Code: </label><input type='text' name='station_code' required><br>";
    echo "<button type='submit'>Place Order</button>";
    echo "</form>";
} else {
    echo "<p>Your cart is empty!</p>";
}
?>
