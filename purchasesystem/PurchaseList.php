<?php
// Include your database connection file and dashboard
// include('../includes/dbconnection.php');
include('../common/base.php');

// Query to fetch purchases along with purchase items
$query = "
    SELECT p.id, s.name as supplier_name, p.purchase_date, p.total_amount,
           pi.quantity, pi.unit_price
    FROM Purchases p
    JOIN Suppliers s ON p.supplier_id = s.id
    JOIN PurchaseItems pi ON p.id = pi.purchase_id
";

$purchases = $con->query($query);
?>

<div class="container mt-5">
    <h1 class="mb-4">Purchase List</h1>
    <a href="PurchaseCreate.php" class="btn btn-primary mb-3">Create New Purchase</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Supplier</th>
                <th>Total Amount</th>
                <th>Quantity</th>
                <th>Purchase Price</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 0; ?>
            <?php while($row = $purchases->fetch_assoc()): ?>
            <tr>
                <td><?php echo ++$counter; ?></td>
                <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity'] * $row['unit_price']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['unit_price']); ?></td>
                <td><?php echo htmlspecialchars($row['purchase_date']); ?></td>
                <td>
                    <a href="PurchaseView.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-info btn-sm">View</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../common/footer.php'); ?>
