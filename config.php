<?php
    $server="localhost";
    $user ="root";
    $password="";
    $db ="dealkade";
    
    $conn = mysqli_connect($server,$user,$password,$db);
    
    if(!$conn){
        die("connection error");
    }

    
    
?>