<?php $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : ''; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="feedback.css">
</head>
<body>
    <h2 style="text-align:center;">Rate Your Food and Delivery Experience</h2>

    <form action="index.php" method="POST">
        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">

        <h3>🍱 Food Feedback</h3>
        <label for="food_rating">Food Rating (1 to 5):</label>
        <select name="food_rating" id="food_rating" required>
            <option value="">--Select--</option>
            <option value="1">⭐</option>
            <option value="2">⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="5">⭐⭐⭐⭐⭐</option>
        </select>

        <label for="food_feedback">Comments on Food:</label>
        <textarea name="food_feedback" id="food_feedback" rows="4" placeholder="Your thoughts on the food..." required></textarea>

        <h3>🚚 Delivery Feedback</h3>
        <label for="delivery_rating">Delivery Rating (1 to 5):</label>
        <select name="delivery_rating" id="delivery_rating" required>
            <option value="">--Select--</option>
            <option value="1">⭐</option>
            <option value="2">⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="5">⭐⭐⭐⭐⭐</option>
        </select>

        <label for="delivery_feedback">Comments on Delivery:</label>
        <textarea name="delivery_feedback" id="delivery_feedback" rows="4" placeholder="Your thoughts on delivery service..." required></textarea>

        <button type="submit">Submit Feedback</button>
    </form>
</body>
</html>
