<?php
include '../includes/dbconnection.php';

$sale_id = $_GET['id'];

// Fetch sale details
$sql = "SELECT sales.id, customers.name AS customer_name, sales.sale_date, sales.total_amount
        FROM sales
        JOIN customers ON sales.customer_id = customers.id
        WHERE sales.id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $sale_id);
$stmt->execute();
$sale = $stmt->get_result()->fetch_assoc();

// Fetch sale items
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Sale Details</h1>
        <div class="mb-4">
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($sale['customer_name']); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($sale['sale_date']); ?></p>
            <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars(number_format($sale['total_amount'], 2)); ?></p>
        </div>
        <h2>Sale Items</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
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
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($item['unit_price'], 2)); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($item['quantity'] * $item['unit_price'], 2)); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="SalesList.php" class="btn btn-secondary mt-3">Back to Sales List</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
