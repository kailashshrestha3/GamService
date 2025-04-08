<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Top Up</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #121826;
      color: white;
      margin: 0;
      padding: 20px;
    }

    #confirmationModal, #scanToPay {
      background-color: #1f2937;
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
    }

    button {
      padding: 10px 20px;
      background-color: #3b82f6;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #2563eb;
    }

    ul {
      list-style-type: none;
      padding-left: 0;
    }

    ul li::before {
      content: "✔️ ";
      margin-right: 8px;
      color: #10b981;
    }
  </style>
</head>
<body>

  <!-- 🧾 Top-Up Form -->
  <form id="topUpForm">
    <h2>Top Up</h2>
    <label>Amount:</label>
    <input type="number" required>
    <br><br>
    <button type="button" onclick="showModal()">Continue</button>
  </form>

  <!-- 💬 Confirmation Modal -->
  <div id="confirmationModal" style="display:none;">
    <h3>Confirm Your Payment</h3>
    <p>You're about to pay. Confirm to continue.</p>
    <button id="payNowBtn">Pay Now</button>
  </div>

  <!-- 📲 Scan to Pay Section -->
  <div id="scanToPay" style="display:none; display: flex; gap: 20px; align-items: flex-start;">
    <div>
      <h3>Scan to Pay</h3>
      <img src="image.png" alt="QR Code" width="300">
      <br>
      <a href="image.png" download>
        <button style="margin-top: 10px;">⬇️ Download QR Code</button>
      </a>
    </div>
    <div>
      <h3>Payment Instructions</h3>
      <ul>
        <li>Make sure your registered phone number matches your payment app.</li>
        <li>Open your preferred mobile payment app.</li>
        <li>Scan the QR code shown above.</li>
        <li>Confirm and complete the payment.</li>
        <li>Payment will be automatically detected.</li>
        <li>Stay on this page until the payment is complete.</li>
      </ul>
    </div>
  </div>

  <!-- ✅ JavaScript -->
  <script>
    function showModal() {
      document.getElementById("confirmationModal").style.display = "block";
    }

    document.getElementById("payNowBtn").addEventListener("click", function () {
      // Hide form and modal
      document.getElementById("topUpForm").style.display = "none";
      document.getElementById("confirmationModal").style.display = "none";

      // Show Scan to Pay section
      document.getElementById("scanToPay").style.display = "flex";
    });
  </script>

</body>
</html>
