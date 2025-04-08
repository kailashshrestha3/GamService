<?php
include('connection.php'); // your db connection file
session_start();

if (isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: home.php");
    exit();
}

$result = mysqli_query($conn, "SELECT username, email FROM signup");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
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
        .content {
            padding: 20px;
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        h2 {
            margin-top: 0;
        }
   
</style>
<body>
<div class="sidebar">
        <h2>Dashboard</h2>
        <a href="admin.php">Add New Game</a>
        <a href="games.php">Show Games</a>
        <a href="manage-user.php">Manage User</a>
        <a href="login.php">Logout</a>
    </div>
    
    <h2>All Registered Users</h2>
    
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Email</th>
        </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo htmlspecialchars($row['username']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
    </tr>
    <?php } ?>
</table>

    </body>
    </html>