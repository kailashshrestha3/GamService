<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $player_id = mysqli_real_escape_string($conn, $_POST['player_id']);

    // Check if player already exists
    $check_query = "SELECT * FROM players WHERE user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error_message = "You already have a registered Player ID.";
    } else {
        $insert_query = "INSERT INTO players (user_id, player_id) VALUES ('$user_id', '$player_id')";
        if (mysqli_query($conn, $insert_query)) {
            $success_message = "Player ID saved successfully!";
        } else {
            $error_message = "Error saving Player ID: " . mysqli_error($conn);
        }
    }
}

// Show current player ID if already set
$current_id = "";
$query = "SELECT player_id FROM players WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $current_id = $row['player_id'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Player ID</title>
</head>
<body>
    <h2>Register Your Player ID</h2>

    <?php if ($success_message): ?>
        <p style="color:green;"><?php echo $success_message; ?></p>
    <?php elseif ($error_message): ?>
        <p style="color:red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if ($current_id): ?>
        <p>Your registered Player ID: <strong><?php echo $current_id; ?></strong></p>
    <?php else: ?>
        <form method="POST">
            <label>Enter Your Player ID:</label>
            <input type="text" name="player_id" required>
            <button type="submit">Save</button>
        </form>
    <?php endif; ?>

    <p><a href="shop.php">Back to Shop</a></p>
</body>
</html>
