<?php

    include("dbconnection.php");

    // -- Suppliers Table
    $suppliers="CREATE TABLE IF NOT EXISTS Suppliers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            contact_info TEXT,
            status ENUM('active', 'inactive') DEFAULT 'active'
        );";

    // -- Purchases Table
    $purchases="CREATE TABLE IF NOT EXISTS Purchases (
        id INT AUTO_INCREMENT PRIMARY KEY,
        supplier_id INT,
        purchase_date DATE,
        total_amount DECIMAL(10, 2),
        status ENUM('pending', 'completed') DEFAULT 'pending',
        FOREIGN KEY (supplier_id) REFERENCES Suppliers(id)
    );";

// -- PurchaseItems Table
    $purchaseitems="CREATE TABLE IF NOT EXISTS PurchaseItems (
        id INT AUTO_INCREMENT PRIMARY KEY,
        purchase_id INT,
        product_id INT,
        quantity INT,
        unit_price DECIMAL(10, 2),
        total_price DECIMAL(10, 2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
        FOREIGN KEY (purchase_id) REFERENCES Purchases(id),
        FOREIGN KEY (product_id) REFERENCES Products(id)
    );";

    if($conn->query($suppliers)){
        echo "Suppliers Table created successfully<br>";
    } 
    if($conn->query($purchases)){
        echo "Purchases Table created successfully<br>";
    }
    if($conn->query($purchaseitems)){
        echo "PurchaseItems Table created successfully<br>";
    }



?>