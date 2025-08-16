<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $food_rating = $_POST['food_rating'];
    $food_feedback = $_POST['food_feedback'];
    $delivery_rating = $_POST['delivery_rating'];
    $delivery_feedback = $_POST['delivery_feedback'];

    try {
        $stmt = $pdo->prepare("INSERT INTO feedback (order_id, food_rating, food_feedback, delivery_rating, delivery_feedback)
                               VALUES (:order_id, :food_rating, :food_feedback, :delivery_rating, :delivery_feedback)");
        $stmt->execute([
            'order_id' => $order_id,
            'food_rating' => $food_rating,
            'food_feedback' => $food_feedback,
            'delivery_rating' => $delivery_rating,
            'delivery_feedback' => $delivery_feedback
        ]);

        // Redirect to home page after successful feedback
        echo "<script>alert('Thank you for your feedback!'); window.location.href='index.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='index.php';</script>";
}
?>
