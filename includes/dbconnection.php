<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory";
try {
    $con=new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }else{
        echo "Connected successfully";
       
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


?>