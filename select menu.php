<?php
$res_id = $_GET['res_id'];
$conn = new PDO("mysql:host=localhost;dbname=food_ordering", "root", "Madhav@2109");

$stmt = $conn->prepare("SELECT * FROM restaurants WHERE id = ?");
$stmt->execute([$res_id]);
$res = $stmt->fetch();

$menuStmt = $conn->prepare("SELECT * FROM menu_items WHERE restaurant_id = ?");
$menuStmt->execute([$res_id]);
$menu = $menuStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Restaurant: <?= $res['name'] ?> (<?= $res['station_id'] ?>)</h3>
<p>Min Order: ₹<?= $res['min_order'] ?> | Time: <?= $res['delivery_time'] ?> mins</p>

<?php foreach ($menu as $item): ?>
<div>
    <h4><?= $item['item_name'] ?> - ₹<?= $item['price'] ?></h4>
    <p><?= $item['description'] ?></p>
    <form method="POST" action="cart.php">
        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
        <button type="submit">Add</button>
    </form>
</div>
<?php endforeach; ?>
