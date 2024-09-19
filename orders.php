<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['role']) === 'admin') {
    // header("Location: ../Login/index.php"); // Redirect to login if not logged in
    echo "You aren't an admin!!!";
    // header("Location: ../Login/index.php");
    exit();
}
?>



<?php
include ("config.php");

?>

<!DOCTYPE html>
<html lan="en">
    <head>
        <meta charset="utf-8">
        <title>Order History</title>   
        <link rel="stylesheet" href="orders.css">
    </head>
    <body>
        <header>
            <div class="header">
                <h1>BookShop</h1>
                <nav>
                    <a href="index.html">Home</a>
                    <a href="cart.html">Cart</a>
                    <a href="login.html">Login</a>
                </nav>
            </div>
        </header>
        <div class="slidebar">
            <h2>My profile</h2>
            <button onclick="window.location.href = '../dealkade/index.php'">
            Dashboard
            </button>
            <button onclick="window.location.href = '../dealkade/details.php'">
            Account Details
            </button>
            <button style="background-color:white; color:black; " onclick="window.location.href = '#'">
            Past Orders
            </button>
        </div>
        <div class="cont">
            <h1>Order History</h1>
            <form action="#" method="POST">
                <table class="table">
                    <tr>
                        <th>Order ID</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Order Created Date</th>   
                    </tr>
                    <?php
                        
                        $view= " SELECT `orders_items`.`order_id`, `orders_items`.`quantity`,  `orders`.`total_amount`,`orders_items`.`created_at` FROM `orders_items` INNER JOIN `orders`  ON `orders_items`.`order_id` = `orders`.`id`WHERE `orders`.`user_id` = 2 " ;  //need to remove and add sessions
                        $query1 = mysqli_query($conn,$view);
                        
                        if(!$query1){
                            die("invalid quary".mysqli_error($conn));
                        }
                        while($row = mysqli_fetch_array($query1)){
                    ?>
                    <tr>
                        <td><?php echo $row['order_id']?></td>
                        <td><?php echo $row['quantity']?></td>
                        <td><?php echo $row['total_amount']?></td>
                        <td><?php echo $row['created_at']?></td>    
                    </tr>
                    <?php }?>
                </table>
            </form>
            
        </div>
        
    <footer>
        <p>&copy; 2024 Book Shop. Powered by Hesanda.</p>
    </footer>
                   
    </body>
</html>

