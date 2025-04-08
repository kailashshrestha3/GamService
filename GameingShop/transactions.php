<?php
session_start();
include 'connection.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch transaction details
$sql = "SELECT transactions.*, games.name AS game_name FROM transactions 
        JOIN games ON transactions.game_id = games.id 
        WHERE transactions.user_id = '$user_id' 
        ORDER BY transactions.transaction_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction History</title>
    <style>
        /* Add some basic styles for better display */
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

        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>My Transactions</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>Game</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Transaction Date</th>
                <th>Status</th>
                <th>Transaction Reference</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['game_name']); ?></td>
                    <td>₨ <?php echo number_format($row['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                    <td><?php echo htmlspecialchars($row['transaction_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['transaction_reference']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have not made any transactions yet.</p>
    <?php endif; ?>

</body>
</html>
