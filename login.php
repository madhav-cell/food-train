<?php
session_start();
include 'db.php'; // PDO connection as $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($username && $password && $role) {
        // Query the users table for the username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Check if the role matches the one in the database
            if ($user['role'] === $role) {
                // Set session and redirect based on role
                if ($role === 'user') {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header('Location: index.php');
                    exit();
                } elseif ($role === 'admin') {
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_username'] = $user['username'];
                    header('Location: manage_food.php');
                    exit();
                } else {
                    $error = "Invalid role selected.";
                }
            } else {
                $error = "Role does not match.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please fill in all fields and select a role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="login.css" />
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="register.php">Register</a>
    <a href="pnr_form.php">Book</a>
    <a href="contact.html">About</a>
</div>

<div class="wrapper">
    <h1>Login</h1>
    <form method="POST" action="">
        <div class="input-box">
            <input type="text" name="username" placeholder="Username" required />
        </div>
        <div class="input-box">
            <input type="password" name="password" placeholder="Password" required />
        </div>

        <div style="margin-top: 10px;">
            <label><input type="radio" name="role" value="user" required> User</label>
            <label><input type="radio" name="role" value="admin" required> Admin</label>
        </div>

        <button type="submit" class="btn">Login</button>
        <?php if (isset($error)): ?>
            <p class="error" style="color:red; margin-top:10px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </form>
    <div class="register-link" style="margin-top:15px;">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>
</body>
</html>
