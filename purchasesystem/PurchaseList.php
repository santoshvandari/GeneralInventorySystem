<?php
include('../includes/dbconnection.php'); // Include your database connection file

$query = "SELECT p.id, s.name as supplier_name, p.purchase_date, p.total_amount, p.status 
          FROM Purchases p
          JOIN Suppliers s ON p.supplier_id = s.id";
$purchases = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase List</title>
</head>
<body>
    <h1>Purchase List</h1>
    <a href="PurchaseCreate.php">Create New Purchase</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $purchases->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['supplier_name']; ?></td>
                <td><?php echo $row['purchase_date']; ?></td>
                <td><?php echo $row['total_amount']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <a href="PurchaseEdit.php?id=<?php echo $row['id']; ?>">Edit</a> |
                    <a href="PurchaseDelete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
