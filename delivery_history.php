<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['delivery_boy_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='delivery_login.php';</script>";
    exit();
}

$delivery_boy_id = $_SESSION['delivery_boy_id'];

// Show delivery history (Delivered orders assigned to this delivery boy)
$stmt = $pdo->prepare("SELECT * FROM orders WHERE delivery_boy_id = ? AND status = 'Delivered' ORDER BY order_date DESC");
$stmt->execute([$delivery_boy_id]);
$orders = $stmt->fetchAll();
?>

<h2>Your Delivery History</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <th>Order ID</th><th>PNR</th><th>Station</th><th>Mobile</th><th>Delivered On</th>
    </tr>
    <?php if ($orders): ?>
        <?php foreach ($orders as $order): ?>
        <tr>
          <td><?= htmlspecialchars($order['id']) ?></td>
          <td><?= htmlspecialchars($order['pnr']) ?></td>
          <td><?= htmlspecialchars($order['station_code']) ?></td>
          <td><?= htmlspecialchars($order['mobile']) ?></td>
          <td><?= htmlspecialchars($order['order_date']) ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">No delivered orders found.</td></tr>
    <?php endif; ?>
</table>

<hr>

<?php
// Now show the menu for a restaurant if ?rid= is set
$rid = $_GET['rid'] ?? null;

if ($rid !== null) {

    if (!is_numeric($rid)) {
        echo "<p>Invalid restaurant ID.</p>";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM food_items WHERE restaurant_id = ?");
        $stmt->execute([$rid]);
        $foods = $stmt->fetchAll();

        if (!$foods) {
            echo "<p>No food items found for this restaurant.</p>";
        } else {
            echo "<h2>Menu for Restaurant ID: " . htmlspecialchars($rid) . "</h2>";
            echo "<form action='cart_add.php' method='POST'>";
            foreach ($foods as $food) {
                echo "<div style='margin-bottom: 15px; padding:10px; border:1px solid #ccc;'>";
                echo "<h4>" . htmlspecialchars($food['item_name']) . " - ₹" . htmlspecialchars($food['price']) . "</h4>";
                echo "<p>" . htmlspecialchars($food['description']) . "</p>";
                echo "<input type='hidden' name='item_name' value='" . htmlspecialchars($food['item_name']) . "'>";
                echo "<input type='hidden' name='item_price' value='" . htmlspecialchars($food['price']) . "'>";
                echo "<input type='number' name='quantity' value='1' min='1' style='width:50px;'> ";
                echo "<input type='hidden' name='food_id' value='" . htmlspecialchars($food['id']) . "'>";
                echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
                echo "</div>";
            }
            echo "</form>";
        }
    }
} else {
    echo "<p>Select a restaurant to view its menu by adding <code>?rid=RESTAURANT_ID</code> in the URL.</p>";
}
?>
