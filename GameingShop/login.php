<?php
session_start(); // Start session at the top
require 'connection.php';

if(isset($_POST['submit'])) { // User Login
    $usernameemail = $_POST['username'];
    $password = $_POST['password'];
    
    // Check in regular users table
    $result = mysqli_query($conn, "SELECT * FROM signup WHERE (username='$usernameemail' OR email='$usernameemail')");
    $row = mysqli_fetch_assoc($result);
    
    if(mysqli_num_rows($result) > 0) {
        if($password === $row['password']) { // Direct comparison with plain text password
            $_SESSION['login'] = true;
            $_SESSION['id'] = $row["id"];
            $_SESSION['role'] = 'user';
            
            // Redirect to the page they were trying to access after login
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit();
            } else {
                header("Location: home.php"); // Default redirect
                exit();
            }
        } else {
            echo "<script>alert('Wrong password');</script>";
        }
    } else {
        echo "<script>alert('User not registered');</script>";
    }
}

if(isset($_POST['admin-submit'])) { // Admin Login
    $usernameemail = $_POST['username'];
    $password = $_POST['password'];
    
    // Assuming you have an admins table or a role column
    $result = mysqli_query($conn, "SELECT * FROM signup WHERE (username='$usernameemail' OR email='$usernameemail')");
    $row = mysqli_fetch_assoc($result);
    
    if(mysqli_num_rows($result) > 0) {
        if($usernameemail === "admin" && $password === $row['password']) { // Direct comparison with plain text password
            $_SESSION['login'] = true;
            $_SESSION['id'] = $row["id"];
            $_SESSION['role'] = 'admin';
            header("Location: admin.php"); // Redirect to admin dashboard
            exit();
        } else {
            echo "<script>alert('Wrong password');</script>";
        }
    } else {
        echo "<script>alert('Admin not found');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Shop</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .login-form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
        }
        .login-form input[type="submit"] {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .login-form input[type="submit"]:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <h3>Gaming Shop</h3>
        </div>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="contact.php">Contact Us</a>
        </div>
        <div class="nav-buttons">
    <input type="text" placeholder="Search..." class="search-input">
    <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
        <form action="logout.php" method="post" style="display:inline;">
            <button type="submit" class="login-btn">Logout</button>
        </form>
    <?php else: ?>
        <button class="login-btn"><a href="login.php">Login</a></button>
        <button class="register-btn"><a href="signup.php">SignUp</a></button>
    <?php endif; ?>
</div>

    </nav>
    
    <div class="login-form">
        <h2>Login</h2>
        <form action="" method="post">
            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <div style="display:flex; gap:10px; margin-top: 15px;">
                <input type="submit" name="submit" value="User Login">
                <input type="submit" name="admin-submit" value="Admin Login">
            </div>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>
</body>
</html>