<?php
include 'connection.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_name = mysqli_real_escape_string($conn, $_POST['game_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $credit_types = $_POST['credit_types']; // Array of credit types
    $amounts = $_POST['amounts']; // Array of amounts

    // Handle game photo upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["game_photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Handle credit icon upload
    $credit_icon_file = $target_dir . basename($_FILES["credit_icon"]["name"]);

    // Check if image file is valid
    $check = getimagesize($_FILES["game_photo"]["tmp_name"]);
    $icon_check = getimagesize($_FILES["credit_icon"]["tmp_name"]);
    
    if ($check !== false && $icon_check !== false) {
        if (move_uploaded_file($_FILES["game_photo"]["tmp_name"], $target_file) && move_uploaded_file($_FILES["credit_icon"]["tmp_name"], $credit_icon_file)) {
            // Insert game into database
            $sql = "INSERT INTO games (name, description, photo_path, credit_icon) 
                    VALUES ('$game_name', '$description', '$target_file', '$credit_icon_file')";
            
            if (mysqli_query($conn, $sql)) {
                $game_id = mysqli_insert_id($conn);
                
                // Insert multiple credit types and amounts
                for ($i = 0; $i < count($credit_types); $i++) {
                    $credit_type = mysqli_real_escape_string($conn, $credit_types[$i]);
                    $amount = mysqli_real_escape_string($conn, $amounts[$i]);
                    mysqli_query($conn, "INSERT INTO game_topups (game_id, credit_type, amount) 
                                        VALUES ('$game_id', '$credit_type', '$amount')");
                }
                
                $success_message = "Game added successfully!";
            } else {
                $error_message = "Error adding game: " . mysqli_error($conn);
            }
        } else {
            $error_message = "Sorry, there was an error uploading your files.";
        }
    } else {
        $error_message = "One or more files are not valid images.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Games Top-Up</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding:0;
            background-color: #f0f0f0;
            display:flex;
        }

        .sidebar {
            min-width: 200px;
            background-color: #333;
            height: 100vh;
            padding: 20px;
            position: sticky;
            top: 0;
            left: 0;
        }
        .sidebar h2 {
            color: white;
            text-align: start;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            text-align: start;
        }
        .sidebar a:hover {
            background-color: #555;
        }        
        .dashboard-container {
            width:100%;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .dashboard-container h1 {
            color: #333;
        }
        .dashboard-container .form-group {
            margin-bottom: 15px;
        }
        .dashboard-container label {
            display: block;
            margin-bottom: 5px;
        }
        .dashboard-container input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .dashboard-container button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
        .credit-amount-group {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }
        .credit-amount-group input[type="text"] {
            width: 200px;
        }
        .credit-amount-group input[type="number"] {
            width: 150px;
        }
        .remove-btn {
            background-color: #ff4444;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .remove-btn:hover {
            background-color: #cc0000;
        }
    </style>  
    <script>
        function addCreditAmountField() {
            var container = document.getElementById("credit-amount-fields");
            var div = document.createElement("div");
            div.className = "credit-amount-group";
            div.innerHTML = `
                <input type="text" name="credit_types[]" placeholder="Enter credit type (e.g., Diamonds, Gems)" required>
                <input type="number" name="amounts[]" placeholder="Enter top-up amount" required>
                <button type="button" class="remove-btn" onclick="removeCreditAmount(this)">Remove</button>
            `;
            container.appendChild(div);
        }

        function removeCreditAmount(button) {
            var container = document.getElementById("credit-amount-fields");
            if (container.children.length > 1) {
                button.parentElement.remove();
            }
        }
    </script>  
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="admin.php">Add New Game</a>
        <a href="games.php">Show Games</a>
        <a href="manage-user.php">Manage User</a>
        <a href="login.php">Logout</a>
    </div>

    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>
        
        <!-- Display success/error messages -->
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Add Game Form -->
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="game_name">Game Name:</label>
                <input type="text" id="game_name" name="game_name" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <!-- Replaced Credit Type and Amounts section -->
            <div class="form-group">
                <label>Credit Types and Amounts:</label>
                <div id="credit-amount-fields">
                    <div class="credit-amount-group">
                        <input type="text" name="credit_types[]" placeholder="Enter credit type (e.g., Diamonds, Gems)" required>
                        <input type="number" name="amounts[]" placeholder="Enter top-up amount" required>
                        <button type="button" class="remove-btn" onclick="removeCreditAmount(this)">Remove</button>
                    </div>
                </div>
                <button type="button" onclick="addCreditAmountField()">+ Add More</button>
            </div>

            <div class="form-group">
                <label for="credit_icon">Credit Icon:</label>
                <input type="file" id="credit_icon" name="credit_icon" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="game_photo">Game Photo:</label>
                <input type="file" id="game_photo" name="game_photo" accept="image/*" required>
            </div>

            <button type="submit">Add Game</button>
        </form>
    </div>
</body>
</html>