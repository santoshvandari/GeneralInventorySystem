<?php
// include '../includes/dbconnection.php';
include('../common/base.php');


// Fetch sales data with customer names
$sql = "SELECT sales.id, customers.name AS customer_name, sales.sale_date, sales.total_amount
        FROM sales
        JOIN customers ON sales.customer_id = customers.id";
$result = $con->query($sql);
?>
    <div class="container mt-5">
        <h1>Sales List</h1>
        <a href="SalesCreate.php" class="btn btn-primary mb-3">Create New Sale</a>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>S.N.</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 0; ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <!-- row count use the counter -->
                        <td><?php echo ++$counter; ?></td>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['sale_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                        <td>
                            <a href="SalesView.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-info btn-sm">View</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php include('../common/footer.php'); ?>
