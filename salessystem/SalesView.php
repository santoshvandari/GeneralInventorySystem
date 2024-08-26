<?php
include '../includes/dbconnection.php';

$sale_id = $_GET['id'];
$sql = "SELECT sales.id, customers.name AS customer_name, sales.sale_date, sales.total_amount
        FROM sales
        JOIN customers ON sales.customer_id = customers.id
        WHERE sales.id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $sale_id);
$stmt->execute();
$sale = $stmt->get_result()->fetch_assoc();

$sql = "SELECT sale_items.*, products.name AS product_name
        FROM sale_items
        JOIN products ON sale_items.product_id = products.id
        WHERE sale_items.sale_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $sale_id);
$stmt->execute();
$sale_items = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sale View</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
</head>
<body>
    <h1>Sale Details</h1>
    <p><strong>Customer:</strong> <?php echo $sale['customer_name']; ?></p>
    <p><strong>Date:</strong> <?php echo $sale['sale_date']; ?></p>
    <p><strong>Total Amount:</strong> <?php echo $sale['total_amount']; ?></p>
    <h2>Sale Items</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $sale_items->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['unit_price']; ?></td>
                    <td><?php echo $item['quantity'] * $item['unit_price']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="SalesList.php">Back to Sales List</a>
</body>
</html>
