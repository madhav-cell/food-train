<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: feedback_form.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo "Invalid order.";
    exit();
}

// Check if order belongs to logged in user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND customer_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "Order not found or access denied.";
    exit();
}

// Check if feedback already exists for this order by this user
$stmt = $pdo->prepare("SELECT * FROM feedback WHERE order_id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);
$existingFeedback = $stmt->fetch();

if ($existingFeedback) {
    echo "<p>You have already submitted feedback for this order.</p>";
    echo "<p>Rating: " . intval($existingFeedback['rating']) . "/5</p>";
    echo "<p>Comments: " . nl2br(htmlspecialchars($existingFeedback['comments'])) . "</p>";
    echo "<p><a href='order_history.php'>Back to Order History</a></p>";
    exit();
}

// Handle feedback form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $comments = trim($_POST['comments']);

    if ($rating < 1 || $rating > 5) {
        echo "Please select a valid rating between 1 and 5.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO feedback (order_id, user_id, rating, comments) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $user_id, $rating, $comments]);

        echo "Thank you for your feedback!";
        echo "<br><a href='order_history.php'>Back to Order History</a>";
        exit();
    }
}
?>

<h2>Feedback for Order #<?= htmlspecialchars($order_id) ?></h2>

<form method="POST">
    <label>Rating (1-5):</label><br>
    <select name="rating" required>
        <option value="">Select rating</option>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
    </select><br><br>

    <label>Comments:</label><br>
    <textarea name="comments" rows="4" cols="50" placeholder="Your feedback..."></textarea><br><br>

    <button type="submit">Submit Feedback</button>
</form>
