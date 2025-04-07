<?php
include 'connection.php';

// Handle form submission for purchase
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in when trying to make a purchase
    if (!isset($_SESSION['user_id'])) {
        // If not logged in, redirect to login page
        header("Location: login.php");
        exit();
    }

    $player_id = mysqli_real_escape_string($conn, $_POST['player_id']);
    $top_up_amount = mysqli_real_escape_string($conn, $_POST['top_up_amount']);
    $credit_type = mysqli_real_escape_string($conn, $_POST['credit_type']);
    $game_id = mysqli_real_escape_string($conn, $_GET['id']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    // Check if player exists based on player_id only
    $player_check = "SELECT * FROM players WHERE player_id = '$player_id'";
    $player_result = mysqli_query($conn, $player_check);
    echo $player_id;
    if(mysqli_num_rows($player_result) > 0) {
        // Proceed with the transaction
        $sql = "INSERT INTO transactions (game_id, player_id, credit_type, amount, payment_method, transaction_date) 
                VALUES ('$game_id', '$player_id', '$credit_type', '$top_up_amount', '$payment_method', NOW())";
        
        if (mysqli_query($conn, $sql)) {
            $success_message = "Purchase successful! Your top-up of $credit_type $top_up_amount has been processed.";
        } else {
            $error_message = "Error processing purchase: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Player ID not found!";
    }
}

// Get the game ID from the URL
if (isset($_GET['id'])) {
    $game_id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM games WHERE id = '$game_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $game = mysqli_fetch_assoc($result);

        $topups_sql = "SELECT credit_type, amount FROM game_topups WHERE game_id = '$game_id'";
        $topups_result = mysqli_query($conn, $topups_sql);
        $topups = [];
        while ($row = mysqli_fetch_assoc($topups_result)) {
            $topups[] = [
                'credit_type' => $row['credit_type'],
                'amount' => $row['amount']
            ];
        }
    } else {
        echo "<p>Game not found!</p>";
        exit;
    }
} else {
    echo "<p>Invalid game ID!</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Kailash" ?> - Details</title>
    <style>
        /* Add your CSS here */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .navbar {
            background-color: #99a8b4;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            width: 100%;
        }

        .logo h3 {
            color: whitesmoke;
            font-size: larger;
        }

        .nav-links {
            margin-left: auto;
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #242729;
        }

        .nav-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: auto;
        }

        .search-input {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        button a {
            text-decoration: none;
            color: white;
            display: block;
        }

        .login-btn {
            background-color: transparent;
            color: white;
            border: 2px solid black;
        }

        .register-btn {
            background-color: #ff6b00;
            color: white;
        }

        .container {
            display: flex;
            max-width: 1200px;
            margin: 20px auto;
            gap: 30px;
            padding: 0 20px;
        }

        .game-details {
            flex: 1;
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 10px;
        }

        .game-details img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin: 15px 0;
        }

        .game-details h1 {
            color: #333;
            margin-bottom: 15px;
        }

        .game-details p {
            margin: 10px 0;
            color: #666;
        }

        .purchase-form {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .purchase-form h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .purchase-form form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .purchase-form label {
            font-weight: bold;
            color: #444;
        }

        .purchase-form input,
        .purchase-form select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .purchase-form button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .purchase-form button:hover {
            background-color: #0056b3;
        }

        .topup-options {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            gap: 8px;
        }

        .topup-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: pointer;
            background-color: #f5f5f5;
            transition: background-color 0.3s ease;
            width: 200px;
            height: 60px;
        }

        .topup-box:hover {
            background-color: #e6e6e6;
        }

        .topup-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .topup-name {
            font-weight: bold;
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .topup-amount {
            font-size: 16px;
            color: #007bff;
            margin: 0;
        }

        .topup-icon {
            width: 20px;
            height: 20px;
            object-fit: cover;
        }

        .success {
            color: #28a745;
            background-color: #d4edda;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .error {
            color: #dc3545;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
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
            <button class="login-btn"><a href="login.php">Login</a></button>
            <button class="register-btn"><a href="signup.php">SignUp</a></button>
        </div>
    </nav>

    <div class="container">
        <div class="game-details">
            <h1><?php echo $game['name']; ?></h1>
            <img src="<?php echo $game['photo_path']; ?>" alt="<?php echo $game['name']; ?>">
            <p><strong>Description:</strong> <?php echo $game['description']; ?></p>
        </div>

        <div class="purchase-form">
            <h2>Buy <?php echo $game['name']; ?></h2>

            <?php if (isset($success_message)): ?>
                <div class="success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST">
                <label for="player_id">Enter your Player ID:</label>
                <input type="text" id="player_id" name="player_id" required>

                <label for="top_up_amount">Top-up Amount:</label>
                <div class="topup-options">
                    <?php foreach ($topups as $topup): ?>
                        <div class="topup-box" onclick="selectTopUp('<?php echo $topup['credit_type']; ?>', <?php echo $topup['amount']; ?>)">
                            <div class="topup-details">
                                <span class="topup-name"><?php echo $topup['credit_type']; ?></span>
                                <span class="topup-amount">$<?php echo $topup['amount']; ?></span>
                            </div>
                            <img src="<?php echo $game['credit_icon']; ?>" alt="Credit" class="topup-icon">
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="top_up_amount" name="top_up_amount" required>
                <input type="hidden" id="credit_type" name="credit_type" required>

                <label for="payment_method">Select Payment Method:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>

                <button type="submit">Buy Now</button>
            </form>
        </div>
    </div>

    <script>
        function selectTopUp(creditType, amount) {
            document.getElementById('top_up_amount').value = amount;
            document.getElementById('credit_type').value = creditType;
            const allBoxes = document.querySelectorAll('.topup-box');
            allBoxes.forEach(box => box.style.border = '1px solid #ccc');
            event.currentTarget.style.border = '2px solid #007bff';
        }
    </script>
</body>
</html>
