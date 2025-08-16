<?php
include 'db.php'; // PDO connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize user input
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $mobile_no = trim($_POST['mobile_no']);
    $dob = $_POST['dob'];
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Get role from POST, default to 'user' if not set or invalid
    $allowed_roles = ['user', 'admin'];
    $role = in_array($_POST['role'] ?? 'user', $allowed_roles) ? $_POST['role'] : 'user';

    try {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, mobile_no, dob, username, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $mobile_no, $dob, $username, $password, $role]);

        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            $error = "Username already exists. Please choose another.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="register.css" />
    <title>Register</title>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="pnr_form.php">Book</a>
        <a href="contact.html">About</a>
    </div>

    <div class="main-div-for-blur-img">
        <div class="wrapper">
            <h1>Register</h1>
            <form method="POST">
                <div class="input-box">
                    <input type="text" name="first_name" placeholder="First Name" required />
                </div>
                <div class="input-box">
                    <input type="text" name="last_name" placeholder="Last Name" required />
                </div>
                <div class="input-box">
                    <input type="text" name="mobile_no" placeholder="Mobile Number" required />
                </div>
                <div class="input-box">
                    <input type="date" name="dob" placeholder="Date of Birth" required />
                </div>
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required />
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required />
                </div>
                <div class="input-box">
                    <label for="role">Register as:</label>
                    <select name="role" id="role" required>
                        <option value="user" selected>User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn">Register</button>
                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            </form>
            <div class="register-link">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
