<!-- navbar.php -->
<header>
    <nav class="navbar">
        <div class="logo">FoodOrder</div>
        <div class="menu-toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <ul class="nav-list">
            <li><a href="index.php">Home</a></li>
            
            <?php if (isset($_SESSION['delivery_boy_id'])): ?>
                <li><a href="delivery_dashboard.php">Dashboard</a></li>
                <li><a href="delivery_history.php">Delivery History</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="pnr_form.php">Pnr</a></li>
                <li><a href="order_history.php">History</a></li>
                <li><a href="manage_food.php">Admin</a></li>
            <?php endif; ?>

            <li><a href="about.php">About</a></li>
        </ul>
    </nav>
</header>
