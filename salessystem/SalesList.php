<?php
include '../includes/dbconnection.php';

// Fetch sales data with customer names
$sql = "SELECT sales.id, customers.name AS customer_name, sales.sale_date, sales.total_amount
        FROM sales
        JOIN customers ON sales.customer_id = customers.id";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Sales List</h1>
        <a href="SalesCreate.php" class="btn btn-primary mb-3">Create New Sale</a>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
