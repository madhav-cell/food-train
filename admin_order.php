<?php
include 'db_connect.php';

// Fetch all orders with delivery info
$stmt = $pdo->query("SELECT o.*, db.name AS delivery_boy
                     FROM orders o
                     LEFT JOIN delivery_boys db ON o.delivery_boy_id = db.id
                     ORDER BY o.id DESC");
$orders = $stmt->fetchAll();
?>

<h2>All Orders</h2>
<table border='1'>
<tr>
  <th>Order ID</th><th>PNR</th><th>Mobile</th><th>Station</th><th>Status</th><th>Delivery Boy</th><th>Action</th>
</tr>
<?php foreach ($orders as $order): ?>
<tr>
  <td><?= $order['id'] ?></td>
  <td><?= $order['pnr'] ?></td>
  <td><?= $order['mobile'] ?></td>
  <td><?= $order['station_code'] ?></td>
  <td><?= $order['status'] ?></td>
  <td><?= $order['delivery_boy'] ?: 'Unassigned' ?></td>
  <td>
    <?php if ($order['status'] != 'Delivered'): ?>
      <form action="assign_delivery.php" method="POST">
        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
        <select name="delivery_boy_id">
          <?php
            $dbs = $pdo->query("SELECT id, name FROM delivery_boys")->fetchAll();
            foreach ($dbs as $db) {
              echo "<option value='{$db['id']}'>{$db['name']}</option>";
            }
          ?>
        </select>
        <button type="submit">Assign</button>
      </form>
    <?php else: ?>
      Completed
    <?php endif; ?>
  </td>
</tr>
<?php endforeach; ?>
</table>
