<?php
include 'connection.php';
$query = "SELECT g.id, g.name, g.description, g.photo_path, 
                 GROUP_CONCAT(gt.credit_type ORDER BY gt.credit_type ASC) AS credit_types,
                 GROUP_CONCAT(gt.amount ORDER BY gt.amount ASC) AS top_up_amounts
          FROM games g
          LEFT JOIN game_topups gt ON g.id = gt.game_id
          GROUP BY g.id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Games List</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            display: flex;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            color: #333;
        }

        .container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .container th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .container th {
            background-color: #4CAF50;
            color: white;
        }

        .container tr:hover {
            background-color: #f5f5f5;
        }

        .container img {
            max-width: 100px;
            height: auto;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .sidebar {
            width: 200px;
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

        /* Style for Update and Delete buttons */
        .container a {
            padding: 8px 16px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            margin-right: 5px;
            font-size: 14px;
        }

        .container a.update-btn {
            background-color: #4CAF50;
            /* Green */
        }

        .container a.delete-btn {
            background-color: #f44336;
            /* Red */
        }

        .container a:hover {
            opacity: 0.8;
        }
    </style>
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
    <div class="container">
        <h1>Games List</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Credit Type</th>
                    <th>Top-Up Amount</th>
                    <th>Photo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['credit_types']); ?></td>
                            <td>Rs.<?php echo htmlspecialchars($row['top_up_amounts']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($row['photo_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"></td>
                            <td><a href="update_game.php?id=<?php echo $row['id']; ?>" class="update-btn">Update</a></td>
                            <td><a href="delete_game.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this game?');">Delete</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="no-data">No games available at this time.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
