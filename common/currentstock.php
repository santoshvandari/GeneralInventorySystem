<?php
include '../includes/dbconnection.php';

// Fetch product data including average rate, purchase quantity, sold quantity, remaining quantity, and status
$sql = "
    SELECT 
        p.id, 
        p.name,
        p.status,
        COALESCE(SUM(pi.quantity), 0) AS purchase_quantity,
        COALESCE(SUM(si.quantity), 0) AS sold_quantity,
        COALESCE(SUM(pi.quantity), 0) - COALESCE(SUM(si.quantity), 0) AS remaining_quantity,
        COALESCE(SUM(pi.quantity * pi.unit_price), 0) / NULLIF(COALESCE(SUM(pi.quantity), 0), 0) AS average_rate
    FROM 
        products p
    LEFT JOIN 
        PurchaseItems pi ON p.id = pi.product_id
    LEFT JOIN 
        sale_items si ON p.id = si.product_id
    GROUP BY 
        p.id, p.name, p.status
";

$result = $con->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Current Stock</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Current Stock</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Average Rate</th>
                    <th>Purchase Quantity</th>
                    <th>Sold Quantity</th>
                    <th>Remaining Quantity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { 
                    // Determine the stock status
                    $status = $row['remaining_quantity'] > 0 ? 'Available' : 'OutOfStock';
                    $status_class = $row['remaining_quantity'] > 0 ? 'text-success' : 'text-danger';
                    // Handle average rate
                    $average_rate = $row['average_rate'] !== null ? number_format($row['average_rate'], 2) : '0.00';
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>$<?php echo $average_rate; ?></td>
                        <td><?php echo htmlspecialchars($row['purchase_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['sold_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['remaining_quantity']); ?></td>
                        <td class="<?php echo $status_class; ?>"><?php echo htmlspecialchars($status); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
