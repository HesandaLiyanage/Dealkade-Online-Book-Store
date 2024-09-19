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

include "config.php";

if(isset($_POST['submit'])){

    $id = $_POST['id']; // delet order from orders items table
    $sql = "DELETE FROM `orders_items` WHERE `order_id` = '$id'";
    $newdata =mysqli_query($conn,$sql);

    if(!$newdata){
        die('could not connect:');
    }

    $id1 = $_POST['id']; //delete oreder from orders table
    $sql1 = "DELETE FROM `orders` WHERE `id` = '$id'";
    $newdata1 =mysqli_query($conn,$sql1);

    if(!$newdata1){
        die('could not connect:');
    }
    mysqli_close($conn);}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <link rel="stylesheet" href="ordermgt.css">k
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

   

    <!-- Product List -->
    <div class="product-table">
        <div class="btn-add-new">
            <form action="#" method="POST">
            <input type="hidden" name ="id" id ="id">
            <button type = "submit" class="btn btn-add" name="submit" onclick = "cancelorder()" >Cancel Order</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    include "config.php";

                        $sql = " SELECT * FROM `orders`  WHERE 1";
                        $newdata =mysqli_query($conn,$sql);
                            
                        if(!$newdata){
                            die("invalid quary".mysqli_error($conn));
                        }
                        while($row = mysqli_fetch_array($newdata)){
                    ?>
                    <tr>
                        <td><?php echo $row['id']?></td>
                        <td><?php echo $row['user_id']?></td>
                        <td><?php echo $row['total_amount']?></td>
                        <td><?php echo $row['status']?></td>
                        <td><?php echo $row['created_at']?></td>
                        <td><?php echo $row['updated_at']?></td>    
                    </tr>
                    <?php }?>
            </tbody>
        </table>
    </div>

    <script>
        function cancelorder(){
                        var oid = prompt("Please Enter The Order ID ");
                        document.getElementById('id').value = oid;
                    }
    </script>

    <footer>
        <p>&copy; 2024 Book Shop. Powered by Hesanda.</p>
    </footer>
</body>
</html>
