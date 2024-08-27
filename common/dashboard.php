<?php
    include '../includes/dbconnection.php';

?>
 <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                overflow-x: hidden;
            }
            .sidebar {
                min-height: 100vh;
                background-color: #f8f9fa;
                padding: 15px;
            }
            .sidebar a {
                text-decoration: none;
                color: #000;
                padding: 10px;
                display: block;
                border-radius: 5px;
                margin-bottom: 5px;
                transition: all 0.4s;
            }
            .sidebar a:hover {
                background-color: #e2e6ea;
            }
            .content {
                padding: 20px;
            }
        </style>
    </head>
    <body>


    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand fs-2 text-uppercase" href="/">Inventory System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Santosh Bhandari<?php #echo $_SESSION['username']; ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <h4>Menu</h4>
                <a href="../productsystem/ProductGroupList.php">Product Group</a>
                <a href="../productsystem/UnitOfMeasureList.php">Unit of Measure</a>
                <a href="../productsystem/ProductList.php">Product</a>
                <a href="../purchasesystem/SupplierList.php">Suppliers</a>
                <a href="../purchasesystem/PurchaseList.php">Purchase</a>
                <a href="../salessystem/CustomerList.php">Customer</a>
                <a href="../salessystem/SalesList.php">Sales</a>
                <a href="../common/currentstock.php">Inventory Status</a>
                <?php
                    // if($_SESSION['role'] == 'admin') {
                    //     echo '<a href="userlist.php">Users</a>';
                    // }
                    ?>
                <a href="../common/userlist.php">Users</a>
                <a href="#">Logout</a>
            </div>

            <!-- Content -->
            <div class="col-md-9 col-lg-10 content">

