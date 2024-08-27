<?php
include('../includes/dbconnection.php'); // Include your database connection file

// Check if the purchase ID is set in the URL
if (isset($_GET['id'])) {
    $purchase_id = intval($_GET['id']);

    // Fetch the purchase details
    $purchase_query = "SELECT p.id, s.name AS supplier_name, p.purchase_date, p.total_amount, p.status 
                       FROM Purchases p
                       JOIN Suppliers s ON p.supplier_id = s.id
                       WHERE p.id = ?";
    $stmt = $con->prepare($purchase_query);
    $stmt->bind_param('i', $purchase_id);
    $stmt->execute();
    $purchase_result = $stmt->get_result();
    $purchase = $purchase_result->fetch_assoc();

    // Fetch the items in the purchase
    $items_query = "SELECT pi.quantity, pi.unit_price, pi.total_price, pr.name AS product_name
                    FROM PurchaseItems pi
                    JOIN products pr ON pi.product_id = pr.id
                    WHERE pi.purchase_id = ?";
    $stmt = $con->prepare($items_query);
    $stmt->bind_param('i', $purchase_id);
    $stmt->execute();
    $items_result = $stmt->get_result();
} else {
    echo "Invalid purchase ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Purchase Details</h1>
        <?php if ($purchase): ?>
            <div class="card mb-4">
                <div class="card-header">
                    Purchase ID: <?php echo htmlspecialchars($purchase['id']); ?>
                </div>
                <div class="card-body">
                    <p><strong>Supplier:</strong> <?php echo htmlspecialchars($purchase['supplier_name']); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($purchase['purchase_date']); ?></p>
                    <p><strong>Total Amount:</strong> <?php echo htmlspecialchars($purchase['total_amount']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($purchase['status']); ?></p>
                </div>
            </div>

            <h3 class="mb-4">Purchased Items</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($item['unit_price']); ?></td>
                            <td><?php echo htmlspecialchars($item['total_price']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <a href="PurchaseList.php" class="btn btn-primary mt-4">Back to Purchase List</a>
        <?php else: ?>
            <p>Purchase not found.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
