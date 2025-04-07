<?php
include 'connection.php';

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all games
$sql = "SELECT * FROM games";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Error in games query: " . mysqli_error($conn);
}

// Fetch trending/popular games
$sql_trending = "SELECT * FROM games LIMIT 5";
$result_trending = mysqli_query($conn, $sql_trending);
if (!$result_trending) {
    echo "Error in trending query: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Shop</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    .trending-section {
        margin: 20px 0;
        padding: 20px;
        background-color: #f5f5f5;
    }
    .trending-section h2 {
        text-align: center;
    }

    .trending-slider {
        position: relative;
        overflow: hidden;
        max-width: 1200px;
        margin: 0 auto;
    }

    .slider-container {
        display: flex;
        transition: transform 0.5s ease;
    }

    .trending-card {
        width: 1200px;
        height: 300px;
        flex-shrink: 0;
        text-align: center;
    }

    .trending-card img {
        width: 100%;
        height: 100%;
        object-fit: fill;
    }

    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        z-index: 10;
    }

    .prev-btn {
        left: 10px;
    }

    .next-btn {
        right: 10px;
    }

    /* Navbar Styles */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #99a8b4;
        color: white;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        margin: 0 15px;
    }

    .nav-buttons {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-input {
        padding: 5px;
        border: none;
        border-radius: 4px;
    }

    .login-btn, .register-btn {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        background-color: #ff6b00;
        cursor: pointer;
    }

    .login-btn a, .register-btn a {
        color: white;
        text-decoration: none;
    }

    /* Search Results Styles */
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        width: 300px;
        background-color: #f5f5f5; /* Same as trending-section */
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        max-height: 300px;
        overflow-y: auto;
        display: none;
        z-index: 1000;
    }

    .search-result-item {
        padding: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #333;
    }

    .search-result-item:hover {
        background-color: #e0e0e0;
    }

    .search-result-item img {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }

    .search-result-item h4 {
        margin: 0;
        font-size: 14px;
    }

    /* Game Container Styles */
    .game-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
    }

    .game-card {
        width: 200px;
        text-align: center;
    }

    .game-card img {
        width: 100%;
        /* height: 100%; */
    }

    .game-card h3 {
        margin: 10px 0;
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
            <input type="text" placeholder="Search..." class="search-input" id="searchInput">
            <div class="search-results" id="searchResults"></div>
            <button class="login-btn"><a href="login.php">Login</a></button>
            <button class="register-btn"><a href="signup.php">SignUp</a></button>
        </div>
    </nav>

    <!-- Trending Games Slider Section -->
    <div class="trending-section">
        <h2>Trending Games for Top-Up</h2>
        <div class="trending-slider">
            <button class="slider-btn prev-btn"><</button>
            <div class="slider-container">
                <?php
                if ($result_trending && mysqli_num_rows($result_trending) > 0) {
                    while ($row = mysqli_fetch_assoc($result_trending)) {
                        echo '<a href="game-details.php?id=' . htmlspecialchars($row['id']) . '">';
                        echo '<div class="trending-card">';
                        echo '<img src="' . htmlspecialchars($row['photo_path']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '</div>';
                        echo '</a>';
                    }
                } else {
                    echo '<p>No trending games available</p>';
                }
                ?>
            </div>
            <button class="slider-btn next-btn">></button>
        </div>
    </div>

    <!-- Regular Games Section -->
    <div class="game-container">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<a href="game-details.php?id=' . htmlspecialchars($row['id']) . '">';
                echo '<div class="game-card">';
                echo '<img src="' . htmlspecialchars($row['photo_path']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo '<p>No games available</p>';
        }
        ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Slider Functionality
        const sliderContainer = document.querySelector('.slider-container');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        const cards = document.querySelectorAll('.trending-card');
        let currentIndex = 0;
        const cardWidth = 1200;
        const totalCards = cards.length;

        if (sliderContainer && prevBtn && nextBtn && totalCards > 0) {
            sliderContainer.style.width = `${cardWidth * totalCards}px`;

            function updateSlider() {
                sliderContainer.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
            }

            nextBtn.addEventListener('click', () => {
                currentIndex++;
                if (currentIndex >= totalCards) {
                    currentIndex = 0;
                }
                updateSlider();
            });

            prevBtn.addEventListener('click', () => {
                currentIndex--;
                if (currentIndex < 0) {
                    currentIndex = totalCards - 1;
                }
                updateSlider();
            });

            sliderContainer.style.transition = 'transform 0.5s ease';
        } else {
            console.error('Slider elements not found or no cards available');
        }

        // Search Functionality
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        // Store game data from PHP in JavaScript
        const games = [
            <?php
            mysqli_data_seek($result, 0); // Reset pointer to start
            while ($row = mysqli_fetch_assoc($result)) {
                echo '{';
                echo 'id: "' . htmlspecialchars($row['id']) . '",';
                echo 'name: "' . htmlspecialchars($row['name']) . '",';
                echo 'photo_path: "' . htmlspecialchars($row['photo_path']) . '"';
                echo '},';
            }
            ?>
        ];

        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.toLowerCase().trim();
            
            // Clear previous results
            searchResults.innerHTML = '';
            
            if (searchTerm === '') {
                searchResults.style.display = 'none';
                return;
            }

            // Filter games based on search term
            const filteredGames = games.filter(game => 
                game.name.toLowerCase().includes(searchTerm)
            );

            if (filteredGames.length > 0) {
                filteredGames.forEach(game => {
                    const resultItem = document.createElement('a');
                    resultItem.href = `game-details.php?id=${game.id}`;
                    resultItem.className = 'search-result-item';
                    resultItem.innerHTML = `
                        <img src="${game.photo_path}" alt="${game.name}">
                        <h4>${game.name}</h4>
                    `;
                    searchResults.appendChild(resultItem);
                });
                searchResults.style.display = 'block';
            } else {
                searchResults.innerHTML = '<p style="padding: 10px;">No results found</p>';
                searchResults.style.display = 'block';
            }
        });

        // Hide results when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>