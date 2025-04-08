<?php
session_start();
include 'connection.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user's purchases
$sql = "SELECT purchases.*, games.name AS game_name FROM purchases 
        JOIN games ON purchases.game_id = games.id 
        WHERE purchases.user_id = '$user_id' 
        ORDER BY purchases.purchase_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Purchases</title>
    <style>
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

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #eef;
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
            <?php if (isset($_SESSION['user_id'])): ?>
        <a href="my-purchases.php">My Purchases</a>
    <?php endif; ?>
        </div>
        <div class="nav-buttons">
            <input type="text" placeholder="Search..." class="search-input" id="searchInput">
            <div class="search-results" id="searchResults"></div>
            <button class="login-btn"><a href="login.php">Login</a></button>
            <button class="register-btn"><a href="signup.php">SignUp</a></button>
        </div>
    </nav>

    <h2>My Purchases</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <tr>
        <table>
            <tr>
                <th>Game</th>
                <th>Player ID</th>
                <th>Credit Type</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Date</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <td><?php echo $row['game_name']; ?></td>
                    <td><?php echo $row['player_id']; ?></td>
                    <td><?php echo $row['credit_type']; ?></td>
                    <td>Rs. <?php echo $row['amount']; ?></td>
                    <td><?php echo $row['payment_method']; ?></td>
                    <td><?php echo $row['purchase_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have not made any purchases yet.</p>
    <?php endif; ?>

</body>
</html>
