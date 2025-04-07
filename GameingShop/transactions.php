<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT t.*, g.name AS game_name FROM transactions t 
          JOIN games g ON t.game_id = g.id
          WHERE t.player_id IN (SELECT player_id FROM players WHERE user_id = '$user_id')
          ORDER BY t.transaction_date DESC";

$result = mysqli_query($conn, $query);
?>

<h2>Your Payment History</h2>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <p><?php echo $row['transaction_date']; ?> - 
       <?php echo $row['game_name']; ?> - 
       <?php echo $row['credit_type']; ?> (Rs.<?php echo $row['amount']; ?>) via <?php echo $row['payment_method']; ?></p>
<?php } ?>
