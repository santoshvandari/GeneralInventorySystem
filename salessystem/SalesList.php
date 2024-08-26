<?php
include '../includes/dbconnection.php';

$sql = "SELECT sales.id, customers.name AS customer_name, sales.sale_date, sales.total_amount
        FROM sales
        JOIN customers ON sales.customer_id = customers.id";
$result = $con->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sales List</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
</head>
<body>
    <h1>Sales List</h1>
    <a href="SalesCreate.php">Create New Sale</a>
    <table border="1">
        <thead>
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
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['sale_date']; ?></td>
                    <td><?php echo $row['total_amount']; ?></td>
                    <td>
                        <a href="SalesView.php?id=<?php echo $row['id']; ?>">View</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
