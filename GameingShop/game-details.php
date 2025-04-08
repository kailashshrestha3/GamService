<?php
session_start(); 
include 'connection.php';



// Handle form submission for purchase
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    } else {
         // Validate Player ID first (ADD THIS PART)
         $player_id = $_POST['player_id'];
         if (!preg_match('/^[0-9]{1,6}$/', $player_id)) {
             die("Invalid Player ID - must be 1-6 digits");
         }

        // Get form inputs
        $user_id = $_SESSION['user_id'];
        $game_id = mysqli_real_escape_string($conn, $_GET['id']); // Game ID from URL
        $player_id = mysqli_real_escape_string($conn, $_POST['player_id']);
        $credit_type = mysqli_real_escape_string($conn, $_POST['credit_type']);
        $top_up_amount = mysqli_real_escape_string($conn, $_POST['top_up_amount']);
        $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

        // Insert into purchases table
        $insert_sql = "INSERT INTO purchases (user_id, game_id, player_id, credit_type, amount, payment_method, purchase_date)
                       VALUES ('$user_id', '$game_id', '$player_id', '$credit_type', '$top_up_amount', '$payment_method', NOW())";

        if (mysqli_query($conn, $insert_sql)) {
            $success_message = "Purchase successful!";
        } else {
            $error_message = "Failed to process your purchase. Error: " . mysqli_error($conn);
        }
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
    <title><?php echo $game['name'] ?? 'Game'; ?> - Purchase</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
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

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: #1f2c3a;
            color: white;
            padding: 2rem;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            position: relative;
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
        }

        .modal-content .checkmark {
            font-size: 50px;
            color: #28a745;
            margin-bottom: 10px;
        }

        .modal-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .modal-actions button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
        }

        .modal-actions button:first-child {
            background-color: #6c757d;
            color: white;
        }

        .modal-actions button:last-child {
            background-color: #ff8000;
            color: white;
        }

        /* Payment Modal Specific Styles */
        #paymentModal .modal-content {
            width: 350px;
        }

        #paymentModal img {
            width: 200px;
            height: 200px;
            margin: 0 auto 20px;
            display: block;
            background: white;
            padding: 10px;
            border-radius: 5px;
        }

        #paymentModal h3 {
            margin-bottom: 10px;
            color: #fff;
        }

        #paymentModal ul {
            margin: 10px 0 20px;
            padding-left: 20px;
            text-align: left;
            font-size: 14px;
        }

        #paymentModal li {
            margin-bottom: 8px;
            color: #ddd;
        }

        #paymentModal a {
            color: #ff8000;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        #paymentModal .modal-actions button:last-child {
            background-color: #28a745;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .modal-content {
                width: 90%;
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="my-purchases.php">My Purchases</a>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
    <span style="color:white; font-weight: bold;">Welcome!</span>
    <button class="login-btn"><a href="logout.php">Logout</a></button>
<?php else: ?>
    <button class="login-btn"><a href="login.php">Login</a></button>
    <button class="register-btn"><a href="signup.php">SignUp</a></button>
<?php endif; ?>

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

            <form method="POST" id="purchaseForm" onsubmit="event.preventDefault(); showConfirmation();">
                  <label for="player_id">Player ID (6 digits max):</label>
        <input type="text" 
               id="player_id" 
               name="player_id" 
               value="<?php echo isset($_POST['player_id']) ? htmlspecialchars($_POST['player_id']) : ''; ?>"
               maxlength="6"
               pattern="[0-9]{1,6}"
               title="Numbers only (1-6 digits)"
               required>

                <label for="top_up_amount">Top-up Amount:</label>
                <div class="topup-options">
                    <?php foreach ($topups as $topup): ?>
<div class="topup-box" onclick="selectTopUp('<?php echo $topup['credit_type']; ?>', <?php echo $topup['amount']; ?>, this)">
                            <div class="topup-details">
                                <span class="topup-name"><?php echo $topup['credit_type']; ?></span>
                                <span class="topup-amount">Rs. <?php echo $topup['amount']; ?></span>
                            </div>
                            <img src="<?php echo $game['credit_icon']; ?>" alt="Credit" class="topup-icon">
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="top_up_amount" name="top_up_amount" required>
                <input type="hidden" id="credit_type" name="credit_type" required>

                <label for="payment_method">Select Payment Method:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="Esewa">Esewa</option>
                    <option value="Khalti">Khalti</option>
                </select>

                <button type="submit">Buy Now</button>
            </form>
        </div>
    </div>

    <!-- Order Confirmation Modal -->
    <div id="orderModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="checkmark">&#10004;</div>
            <h2>Place Order</h2>
            <p><strong>Player Id</strong>: <span id="confirmPlayerId"></span></p>
            <p><strong>Item</strong>: <span id="confirmCreditType"></span></p>
            <p><strong>Price</strong>: Rs <span id="confirmAmount"></span></p>
            <p><strong>Payment</strong>: <span id="confirmPayment"></span></p>
            <div class="modal-actions">
                <button onclick="closeModal()">Cancel</button>
                <button onclick="submitPurchase()">Pay Now</button>
            </div>
        </div>
    </div>

    <!-- Payment QR Code Modal -->
    <div id="paymentModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Scan to Pay</h2>
            <div style="margin: 20px 0; text-align: center;">
                <!-- Replace with your actual QR code image -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PaymentForGame_<?php echo $game['name']; ?>" alt="Payment QR Code">
            </div>
            <div style="text-align: left; margin-bottom: 20px;">
                <h3>Payment Instructions</h3>
                <ul>
                    <li>Make sure your website registered phone number and payment phone number must be same.</li>
                    <li>Open your preferred mobile payment app.</li>
                    <li>Scan the QR code shown above.</li>
                    <li>Confirm and complete the payment.</li>
                    <li>Payment will be automatically detected.</li>
                    <li>Please wait on this page until payment is complete.</li>
                </ul>
            </div>
            <div style="margin-top: 15px;">
                <a href="#" style="color: #ff8000; text-decoration: none;">Download QR Code</a>
            </div>
            <div class="modal-actions">
                <button onclick="closePaymentModal()">Cancel Payment</button>
                <button style="background-color: #28a745;" onclick="paymentComplete()">Payment Done</button>
            </div>
        </div>
    </div>

    <script>
      function selectTopUp(creditType, amount, el) {
    document.getElementById('top_up_amount').value = amount;
    document.getElementById('credit_type').value = creditType;

    const allBoxes = document.querySelectorAll('.topup-box');
    allBoxes.forEach(box => box.style.border = '1px solid #ccc');
    el.style.border = '2px solid #007bff';
}

function showConfirmation() {
    const amount = document.getElementById('top_up_amount').value;
    const creditType = document.getElementById('credit_type').value;
    const paymentMethod = document.getElementById('payment_method').value;
    const playerId = document.getElementById('player_id').value;

    if (!amount || !creditType || !paymentMethod || !playerId) {
        alert("Please select all required fields.");
        return;
    }

    document.getElementById('confirmAmount').innerText = amount;
    document.getElementById('confirmCreditType').innerText = creditType;
    document.getElementById('confirmPayment').innerText = paymentMethod;
    document.getElementById('confirmPlayerId').innerText = playerId;

    document.getElementById('orderModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('orderModal').style.display = 'none';
}

function submitPurchase() {
    document.getElementById('orderModal').style.display = 'none';
    document.getElementById('paymentModal').style.display = 'flex';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}

function paymentComplete() {
    document.getElementById("purchaseForm").submit();
}

    </script>
</body>
</html>