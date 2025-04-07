<?php
include 'connection.php';

$id = $_GET['id'] ?? '';

if (!$id) {
    header("Location: games.php");
    exit();
}

// Fetch the photo path to delete the file if it exists
$result = mysqli_query($conn, "SELECT photo_path FROM games WHERE id = '$id'");
$game = mysqli_fetch_assoc($result);

if ($game && file_exists($game['photo_path'])) {
    unlink($game['photo_path']); // Delete the image file
}

// Delete the game record from database
$query = "DELETE FROM games WHERE id = '$id'";
if (mysqli_query($conn, $query)) {
    header("Location: games.php");
    exit();
} else {
    echo "Error deleting game: " . mysqli_error($conn);
}

mysqli_close($conn);
?>