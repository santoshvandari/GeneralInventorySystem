<?php
    include('dbconnection.php');
    // delete all the database tables and create the following tables:
    $con->query("DROP TABLE IF EXISTS ProductGroups");
    $con->query("DROP TABLE IF EXISTS UnitOfMeasure");
    $con->query("DROP TABLE IF EXISTS products");
    $productgroup="CREATE TABLE IF NOT EXISTS ProductGroups (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                status ENUM('active', 'inactive') DEFAULT 'active');";
    $UnitOfMeasure="CREATE TABLE IF NOT EXISTS UnitOfMeasure (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                code VARCHAR(50) NOT NULL,
                description TEXT,
                status ENUM('active', 'inactive') DEFAULT 'active');";
    $producttable="CREATE TABLE IF NOT EXISTS Products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                unitofmeasureid INT,
                productgroupid INT,
                status ENUM('active', 'inactive') DEFAULT 'active',
                FOREIGN KEY (unitofmeasureid) REFERENCES UnitOfMeasure(id),
                FOREIGN KEY (productgroupid) REFERENCES ProductGroups(id) );";    
    if($con->query($productgroup)){
        echo "product Group table created successfully";
    }else{
        echo "Error creating product Group table: " . $con->error;
    }
    if($con->query($UnitOfMeasure)){
        echo "Unit of Measure table created successfully";
    }else{
        echo "Error creating Unit of Measure table: " . $con->error;
    }
    if($con->query($producttable)){
        echo "product table created successfully";
    }else{
        echo "Error creating product table: " . $con->error;
    }
?>