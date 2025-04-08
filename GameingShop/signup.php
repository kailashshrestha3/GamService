<?php
include 'connection.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Check for duplicate username or email
    $duplicate = mysqli_query($conn, "SELECT * FROM signup WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($duplicate) > 0) {
        echo "<script>alert('Username or email has already been taken');</script>";
    } else {
        // Check if passwords match
        if ($password == $confirmpassword) {
            // Hash the password before storing it
           // Skip hashing, store plain text password (NOT RECOMMENDED for real projects)
$plainPassword = $password;

$stmt = mysqli_prepare($conn, "INSERT INTO signup (name, username, email, password) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $name, $username, $email, $plainPassword);
            $execute = mysqli_stmt_execute($stmt);

            // Execute the query and check for success
            if ($execute) {
                echo "<script>alert('Registration Successful');</script>";
                // Optionally, redirect the user to login page after registration
                // echo "<script>window.location.href='login.php';</script>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Passwords do not match');</script>";
        }
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
            <button class="login-btn"><a href="login.php">Login</a></button>
            <button class="register-btn"><a href="signup.php">SignUp</a></button>
        </div>
    </nav>

    <div class="signup-form">
        <h2>Sign Up</h2>
        <form action="signup.php" method="post">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Your Password</label>
            <input type="password" id="password" name="password" required>

            <label for="confirmpassword">Confirm Password</label>
            <input type="password" id="confirmpassword" name="confirmpassword" required>

            <input type="submit" value="Register" name="submit"> 
        </form>
        <p>Already have an account? <a href="login.php">Sign in</a></p>
    </div>
</body>
</html>
