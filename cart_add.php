<?php
session_start();

if (isset($_POST['add_to_cart'])) {
    $food_ids = $_POST['food_id'];
    $item_names = $_POST['item_name'];
    $item_prices = $_POST['item_price'];
    $quantities = $_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    for ($i = 0; $i < count($food_ids); $i++) {
        $quantity = (int)$quantities[$i];
        if ($quantity <= 0) continue; // Skip if quantity is 0

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['food_id'] == $food_ids[$i]) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            $_SESSION['cart'][] = [
                'food_id' => $food_ids[$i],
                'item_name' => $item_names[$i],
                'item_price' => (float)$item_prices[$i],
                'quantity' => $quantity
            ];
        }
    }

    echo "<script>
        alert('Selected items added to cart successfully!');
        window.location.href = 'upi_qr.php';
    </script>";
    exit();
}
?>
