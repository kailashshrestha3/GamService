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
        // Print the username and password for debugging
        echo "Username: " . $row['username'] . "<br>";
        echo "Password: " . $row['password'] . "<br>";

        // Use password_verify() for hashed passwords
        if(password_verify($password, $row['password'])) { // Assuming password is hashed
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
?>
