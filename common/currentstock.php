<?php include 'dashboard.php'; ?>
<?php

// Fetch product data including average rate, purchase quantity, sold quantity, remaining quantity, and status
$sql = "
    SELECT 
        p.id, 
        p.name,
        p.status,
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
                    // Fetch stock information for the current product
                    $stockinfo_result = $con->query("SELECT * FROM stock WHERE product_id=" . $row['id'].";");
                    $stockinfo = $stockinfo_result->fetch_assoc();

                    // Determine the stock status
                    $status = ($stockinfo && $stockinfo['remaining_stock']) > 0 ? 'Available' : 'Out of Stock';
                    $status_class = ($stockinfo && $stockinfo['remaining_stock']) > 0 ? 'text-success' : 'text-danger';

                    // Handle average rate
                    $average_rate = $row['average_rate'] !== null ? number_format($row['average_rate'], 2) : '0.00';

                    // Handle missing stock info
                    $purchase_stock = ($stockinfo && $stockinfo['purchase_stock']) ? $stockinfo['purchase_stock'] : '0';
                    $sales_stock = ($stockinfo && $stockinfo['sales_stock']) ? $stockinfo['sales_stock'] : '0';
                    $remaining_stock = ($stockinfo && $stockinfo['remaining_stock']) ? $stockinfo['remaining_stock'] : '0';
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>$<?php echo $average_rate; ?></td>
                        <td><?php echo htmlspecialchars($purchase_stock); ?></td>
                        <td><?php echo htmlspecialchars($sales_stock); ?></td>
                        <td><?php echo htmlspecialchars($remaining_stock); ?></td>
                        <td class="<?php echo $status_class; ?>"><?php echo htmlspecialchars($status); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php include 'footer.php'; ?>
