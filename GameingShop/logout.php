<?php
session_start(); // Start the session

// Unset session variables
session_unset(); // This clears all session variables

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header("Location: home.php");
exit(); // Don't forget to call exit to prevent further execution of the script
?>
