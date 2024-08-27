<?php
// include('../includes/dbconnection.php'); // Include your database connection file
 include('../common/dashboard.php');
$query = "SELECT p.id, s.name as supplier_name, p.purchase_date, p.total_amount FROM Purchases p
          JOIN Suppliers s ON p.supplier_id = s.id";
$purchases = $con->query($query);
?>
    <div class="container mt-5">
        <h1 class="mb-4">Purchase List</h1>
        <a href="PurchaseCreate.php" class="btn btn-primary mb-3">Create New Purchase</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $purchases->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['purchase_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                    <td>
                        <a href="PurchaseView.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-info btn-sm">View</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<?php include('../common/footer.php'); ?>
