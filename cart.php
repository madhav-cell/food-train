<?php
session_start();

if (isset($_POST['add_to_cart'])) {
    $food_id = $_POST['food_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $quantity = $_POST['quantity'];

    // Initialize cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if item already exists in cart
    $item_found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['food_id'] == $food_id) {
            $item['quantity'] += $quantity;
            $item_found = true;
            break;
        }
    }
    unset($item); // break reference

    // If item not found, add new item
    if (!$item_found) {
        $_SESSION['cart'][] = [
            'food_id' => $food_id,
            'item_name' => $item_name,
            'item_price' => $item_price,
            'quantity' => $quantity
        ];
    }

    // Show popup and redirect using JS
    echo "<script>
        alert('Item added to cart successfully!');
        window.location.href = 'pay.php';
    </script>";
    exit();
}
?>
