<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['delivery_boy_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='delivery_login.php';</script>";
    exit();
}

$delivery_boy_id = $_SESSION['delivery_boy_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE delivery_boy_id = ? AND status != 'Delivered'");
$stmt->execute([$delivery_boy_id]);
$orders = $stmt->fetchAll();
?>

<h2>Assigned Orders</h2>
<table border="1">
<tr>
  <th>Order ID</th><th>PNR</th><th>Station</th><th>Mobile</th><th>Status</th><th>Action</th>
</tr>
<?php foreach ($orders as $order): ?>
<tr>
  <td><?= $order['id'] ?></td>
  <td><?= $order['pnr'] ?></td>
  <td><?= $order['station_code'] ?></td>
  <td><?= $order['mobile'] ?></td>
  <td><?= $order['status'] ?></td>
  <td>
    <form method="POST" action="mark_delivered.php">
      <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
      <button type="submit">Mark Delivered</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>
