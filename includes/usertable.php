<?php

    include("dbconnection.php");

    // -- Create Customer Table
    $users="CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'salesperson') NOT NULL DEFAULT 'salesperson',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";
        
    if($con->query($users)){
        echo "Users Table created successfully<br>";
    }

?>