<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username_or_email']) === 'admin') {
    // header("Location: ../Login/index.php"); // Redirect to login if not logged in
    echo "You aren't an user!!!";
    // header("Location: ../Login/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lan="en">
    <head>
        <meta charset="utf-8">
        <title>My Account</title>
        <link rel="stylesheet" href="../css/indexstyle.css">
    </head>
    <body>

<header>
    
    <div class="header">
        <h1>BookShop</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>
            <a href="../login/index.php">Login</a>
        </nav>
    </div>

</header>  
                <div class="card">
                    <h2 class ="main-text">Dashboard</h2>
                    <h3 class ="sub-text">Control panel</h3>
                        <div class="order-btn"  >
                            <a href="read.php"><button type="submit" name='user'>User Details</button></a>
                            <a href="orders.php"><button type="submit" name='history' >Order History</button></a>          
					   </div>
                </div>   
            
            <footer>
                <p>&copy; 2024 Book Shop. Powered by Hesanda.</p>
            </footer>
                             
    </body>
</html>