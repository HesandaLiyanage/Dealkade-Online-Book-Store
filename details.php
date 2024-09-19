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


    $view ="SELECT * FROM `users` WHERE  `username_or_email` = 'admin@example.com'";  //need toremove and add sessions
    $newdata =mysqli_query($conn,$view);
    
    if(!$newdata){
        die('could not connect:');
    }

    $row = mysqli_fetch_array($newdata);
    
    

    /*if(isset($_POST['save']))

        $username =  $_POST['username_or_email'];
        $password = $_POST['password'];
        $name =  $_POST['name'];
        $address = $_POST['address'];
        $telephone = $_POST['phone_number'];

    
        $data= "UPDATE `users` SET `username_or_email`='[$username]',`password`='[$password]',`name`='[$name]',`address`='[$address]',`phone_number`='[$phone_number]' WHERE 1
        $newdata =mysqli_query($conn,$data);
    
        if(!$newdata){  
            die('could not connect:');
        }
        header('location:Login.php');
        
        mysqli_close($conn);
    } */   

?>

<!DOCTYPE html>
<html lan="en">
    <head>
        <meta charset="utf-8">
        <title>Account Deatils</title>   
        <link rel="stylesheet" href="details.css">
    </head>
    <body>
        <div class="header">
            <h1>BookShop</h1>
            <nav>
                <a href="index.html">Home</a>
                <a href="cart.html">Cart</a>
                <a href="login.html">Login</a>
            </nav>
        </div>
            <div class="container">
                <div class="content">
                    <form id="signup-form" class="form" method="POST" action="#">
                        <h2 id="increase">Account Deatils</h2>
                        <label class="form-label">Username</label>
                        <input type="text" id="new-username" name="username_or_email" placeholder="Username/Email" value = <?php echo $row["username_or_email"] ?>>
                        <label class="form-label"> Password</label>
                        <input type="password" id="new-password" name="password" placeholder="Password" value = <?php echo $row["password"] ?> >
                        <label class="form-label">Name</label>
                        <input type="text" id="name" name="name" placeholder="Name" value = <?php echo $row["name"] ?>>
                        <label class="form-label">Address</label>
                        <input type="text" id="address" name="address" placeholder="Address"value = <?php echo $row["address"] ?> >
                        <label class="form-label">Telephone</label>
                        <input type="text" id="tel" name="phone_number" placeholder="Telephone_Number" value = <?php echo $row["phone_number"] ?>>
                        <button type="submit" name="save">Save</button>
                    </form>
                </div>
                
                <div class="slidebar">
                    <h2>My profile</h2>
                    <button onclick="window.location.href = '../dealkade/index.php'">
                        Dashboard
                    </button>
                    <button style="background-color:white; color:black; "onclick="window.location.href = '#'">
                        Account Details
                    </button>
                    <button onclick="window.location.href = '../dealkade/orders.php'">
                        Past Orders
                    </button>
                </div>
            </div>
        
        <footer>
            <p>&copy; 2024 Book Shop. Powered by Hesanda.</p>
        </footer>
                 
    </body>
</html>