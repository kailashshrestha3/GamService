<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Shop</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <!-- <img src="" alt="logo"> -->
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
            <button class="register-btn"><a href="signup.php">SignUp</a></button>        </div>
    </nav>
    <div class="container">
        <div class="contact-info">
            <h2>Contact Us</h2>
            <p>Want to make a purchase or need any help to find the best solutions for you? Feel free to contact us. Below are our contact details -</p>
            <ul>
                <li>  <i class="material-icons">email</i>Email: contact@gaming.com.np</li>
                <li> <i class="material-icons">phone</i> WhatsApp: 9848995198</li>
                <li>  <i class="material-icons">location_on</i> Address: Bhaktapur, Nepal</li>
            </ul>
        </div>
        <div class="contact-form">
            <form action="#">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="John Doe">
                
                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" placeholder="name@example.com">
                
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="Subject">
                
                <label for="message">Opinion</label>
                <textarea id="message" name="message" placeholder="Your message here..." rows="4"></textarea>
                
                <button type="submit">Submit now</button>
            </form>
        </div>
    </div>

</body>
</html>