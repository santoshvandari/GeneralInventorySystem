<?php

    include("dbconnection.php");

    // -- Create Customer Table
    $customer="CREATE TABLE IF NOT EXISTS customers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        phone VARCHAR(20),
        address TEXT
    );";
    
    // -- Create Sales Table
    $sales="CREATE TABLE IF NOT EXISTS sales (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_id INT NOT NULL,
        sale_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        total_amount DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (customer_id) REFERENCES customers(id)
    );";
    
    // -- Create Sale Items Table
    $salesitems="CREATE TABLE IF NOT EXISTS sale_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sale_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        unit_price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (sale_id) REFERENCES sales(id),
        FOREIGN KEY (product_id) REFERENCES products(id) -- assuming products table exists
    );";
    
    if($conn->query($customer)){
        echo "Customers Table created successfully<br>";
    }
    if($conn->query($sales)){
        echo "Sales Table created successfully<br>";
    }
    if($conn->query($salesitems)){
        echo "SalesItems Table created successfully<br>";
    }


?>