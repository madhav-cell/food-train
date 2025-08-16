<?php
include 'auth_check.php';  // Check login session
include 'db.php';          // Database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Food Ordering in Train</title>
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .restaurant-item {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            margin-bottom: 20px;
            padding: 20px;
            display: flex;
            gap: 20px;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .restaurant-item img {
            width: 160px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }

        .restaurant-info {
            flex: 1;
        }

        footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 40px 0;
        }

        footer a {
            color: #ffffff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        h1, h2 {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container my-4">
        <h1>Welcome to Train Food Delivery</h1>
        <h2>Order Delicious Meals at Your Seat</h2>

        <ul class="list-unstyled mt-4">
            <?php
            $stmt = $pdo->query("SELECT * FROM restaurants");
            while ($row = $stmt->fetch()) {
                echo "<li class='restaurant-item'>";
                echo "<img src='images/{$row['image']}' alt='" . htmlspecialchars($row['name']) . "'>";
                echo "<div class='restaurant-info'>";
                echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "<a href='menu.php?rid={$row['id']}' class='btn btn-primary'>Order Now</a>";
                echo "</div>";
                echo "</li>";
            }
            ?>
        </ul>
    </main>

    <!-- Footer Section -->
    <footer class="text-center">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Train Food Express. All Rights Reserved.</p>
            <p>
                <a href="contact.html">About Us</a> |
                <a href="contact.php">Contact</a> |
                <a href="term.php">Terms & Conditions</a>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="navbar.js"></script>
</body>
</html>
