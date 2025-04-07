<?php
include 'connection.php';

$id = $_GET['id'] ?? '';
$error = '';

if (!$id) {
    header("Location: games.php");
    exit();
}

// Fetch existing game data
$result = mysqli_query($conn, "SELECT * FROM games WHERE id = '$id'");
$game = mysqli_fetch_assoc($result);

if (!$game) {
    header("Location: games.php");
    exit();
}

// Fetch existing top-ups for this game
$topups_query = "SELECT * FROM game_topups WHERE game_id = '$id'";
$topups_result = mysqli_query($conn, $topups_query);
$topups = [];
while ($topup = mysqli_fetch_assoc($topups_result)) {
    $topups[] = $topup;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle file upload
    $photo_path = $game['photo_path'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_name = $_FILES['photo']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($file_ext, $allowed_ext)) {
            $photo_path = "uploads/" . time() . "_" . $file_name;
            move_uploaded_file($file_tmp, $photo_path);
        } else {
            $error = "Invalid file format. Only JPG, PNG, and GIF are allowed.";
        }
    }

    // Update the game details
    if (!$error) {
        $query = "UPDATE games SET name='$name', description='$description', photo_path='$photo_path' WHERE id='$id'";
        if (mysqli_query($conn, $query)) {
            // Update the top-ups
            foreach ($_POST['credit_type'] as $index => $credit_type) {
                $credit_type = mysqli_real_escape_string($conn, $credit_type);
                $top_up_amount = mysqli_real_escape_string($conn, $_POST['top_up_amount'][$index]);
                $top_up_id = $topups[$index]['id']; // Existing top-up ID

                // Update the existing top-up
                $topup_query = "UPDATE game_topups SET credit_type='$credit_type', amount='$top_up_amount' WHERE id='$top_up_id'";
                mysqli_query($conn, $topup_query);
            }
            header("Location: games.php");
            exit();
        } else {
            $error = "Error updating game: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Game</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; display: flex; }
        .container { width: 100%; margin: 20px auto; background-color: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .sidebar { width: 200px; background-color: #333; height: 100vh; padding: 20px; position: sticky; top: 0; }
        .sidebar h2 { color: white; margin-bottom: 20px; }
        .sidebar a { display: block; color: white; text-decoration: none; margin: 10px 0; padding: 10px; border-radius: 5px; }
        .sidebar a:hover { background-color: #555; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { opacity: 0.8; }
        .error { color: red; margin-bottom: 15px; }
        img { max-width: 200px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="admin.php">Add New Game</a>
        <a href="games.php">Show Games</a>
        <a href="login.php">Logout</a>
    </div>
    <div class="container">
        <h1>Update Game</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($game['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required><?php echo htmlspecialchars($game['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Current Photo:</label>
                <img src="<?php echo htmlspecialchars($game['photo_path']); ?>" alt="Current photo">
            </div>
            <div class="form-group">
                <label>New Photo (optional):</label>
                <input type="file" name="photo" accept="image/*">
            </div>
            
            <h3>Top-Up Details</h3>
            <?php foreach ($topups as $topup): ?>
                <div class="form-group">
                    <label>Credit Type:</label>
                    <input type="text" name="credit_type[]" value="<?php echo htmlspecialchars($topup['credit_type']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Top-Up Amount:</label>
                    <input type="number" name="top_up_amount[]" value="<?php echo htmlspecialchars($topup['amount']); ?>" required>
                </div>
            <?php endforeach; ?>

            <button type="submit">Update Game</button>
        </form>
    </div>
</body>
</html>
