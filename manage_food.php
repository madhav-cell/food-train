<?php
session_start();
include 'db_connect.php';

// Handle Add, Edit, Delete
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO food_items (restaurant_id, item_name, price, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['restaurant_id'], $_POST['item_name'], $_POST['price'], $_POST['description']]);
} elseif (isset($_POST['edit'])) {
    $stmt = $pdo->prepare("UPDATE food_items SET item_name=?, price=?, description=? WHERE id=?");
    $stmt->execute([$_POST['item_name'], $_POST['price'], $_POST['description'], $_POST['food_id']]);
} elseif (isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM food_items WHERE id=?");
    $stmt->execute([$_POST['food_id']]);
}

// Fetch items
$stmt = $pdo->query("SELECT food_items.*, restaurants.name as restaurant FROM food_items JOIN restaurants ON food_items.restaurant_id = restaurants.id");
$items = $stmt->fetchAll();
?>

<h2>Manage Food Items</h2>
<table border="1">
    <tr>
        <th>ID</th><th>Restaurant</th><th>Name</th><th>Price</th><th>Description</th><th>Actions</th>
    </tr>
    <?php foreach ($items as $item): ?>
        <tr>
            <form method="post">
                <input type="hidden" name="food_id" value="<?= $item['id'] ?>">
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['restaurant']) ?></td>
                <td><input name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>"></td>
                <td><input name="price" type="number" value="<?= $item['price'] ?>"></td>
                <td><input name="description" value="<?= htmlspecialchars($item['description']) ?>"></td>
                <td>
                    <button name="edit">Update</button>
                    <button name="delete" onclick="return confirm('Delete this item?')">Delete</button>
                </td>
            </form>
        </tr>
    <?php endforeach; ?>
</table>

<h3>Add New Item</h3>
<form method="post">
    Restaurant ID: <input name="restaurant_id" required>
    Name: <input name="item_name" required>
    Price: <input name="price" type="number" required>
    Description: <input name="description">
    <button name="add">Add</button>
</form>
