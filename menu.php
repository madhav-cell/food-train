<?php
session_start();
include 'db_connect.php';

$rid = $_GET['rid'] ?? null;

if (!$rid || !is_numeric($rid)) {
    echo "Invalid or missing restaurant ID.";
    exit();
}

// Fetch food items
$stmt = $pdo->prepare("SELECT * FROM food_items WHERE restaurant_id = ?");
$stmt->execute([$rid]);
$foods = $stmt->fetchAll();

if (!$foods) {
    echo "No food items found for this restaurant.";
    exit();
}

// Fetch restaurant name & optional location (for map)
$stmtRes = $pdo->prepare("SELECT name, latitude, longitude FROM restaurants WHERE id = ?");
$stmtRes->execute([$rid]);
$restaurant = $stmtRes->fetch();
$restaurantName = $restaurant ? $restaurant['name'] : 'Restaurant';
$latitude = $restaurant['latitude'] ?? 19.1790608;
$longitude = $restaurant['longitude'] ?? 77.3300495;

// Restaurant images map
$restaurantImages = [
    'Tandoori Express' => 'tandoori_express.jpg',
    'Shree Pure Veg' => 'shree_pure_veg.jpg',
    'Royal Kitchen' => 'royal_kitchen.jpg',
    'Biryani House' => 'biryani_house.jpg',
    'Food Junction' => 'food_junction.jpg',
    'Prayagraj Delight' => 'prayagraj_delight.jpg'
];

$imageFile = $restaurantImages[$restaurantName] ?? 'default_restaurant.png';
$imagePath = "images/restaurants/$imageFile";
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($restaurantName); ?> Menu</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .restaurant-header img { width: 180px; border-radius: 10px; margin-bottom: 20px; }
        .menu-item { border: 1px solid #ccc; border-radius: 10px; padding: 10px; margin-bottom: 15px; }
        .map-container iframe { width: 100%; height: 300px; border: none; border-radius: 8px; }
        .status-box { background: #f5f5f5; border-left: 4px solid #007bff; padding: 10px; margin: 20px 0; }
    </style>
</head>
<body>

    <div class="restaurant-header">
        <h2><?php echo htmlspecialchars($restaurantName); ?> Menu</h2>
        <img src="<?php echo $imagePath; ?>" alt="<?php echo $restaurantName; ?>">
    </div>

    <!-- Live Order Status (Feature 6) -->
    <div class="status-box">
        <strong>🕒 Live Order Status:</strong><br>
        Your current order status: <span id="order-status">Pending</span> 
        <a href="#" onclick="location.reload()">🔄 Refresh</a>
        <!-- You can replace this with AJAX later to show dynamic updates -->
    </div>

    <!-- Menu Items -->
    <form action="cart_add.php" method="POST">
        <?php foreach ($foods as $food): ?>
            <div class="menu-item">
                <h4><?php echo htmlspecialchars($food['item_name']); ?> - ₹<?php echo htmlspecialchars($food['price']); ?></h4>
                <p><?php echo htmlspecialchars($food['description']); ?></p>
                <input type="hidden" name="food_id[]" value="<?php echo htmlspecialchars($food['id']); ?>">
                <input type="hidden" name="item_name[]" value="<?php echo htmlspecialchars($food['item_name']); ?>">
                <input type="hidden" name="item_price[]" value="<?php echo htmlspecialchars($food['price']); ?>">
                Quantity: <input type="number" name="quantity[]" value="0" min="0">
            </div>
        <?php endforeach; ?>
        <button type="submit" name="add_to_cart">Add Selected Items to Cart</button>
    </form>

    <!-- Google Map (Feature 13) -->
    <h3>📍 Restaurant Location:</h3>
    <div class="map-container">
        <iframe 
            src="https://www.google.com/maps?q=<?php echo $latitude; ?>,<?php echo $longitude; ?>&output=embed"
            allowfullscreen loading="lazy">
        </iframe>
    </div>

</body>
</html>
