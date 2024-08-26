<?php
include('../includes/dbconnection.php'); // Include your database connection file

$query = "SELECT p.id, p.name, p.description, pg.name as product_group, uom.name as unit_of_measure, p.status 
          FROM Products p 
          JOIN ProductGroups pg ON p.productgroupid = pg.id 
          JOIN UnitOfMeasure uom ON p.unitofmeasureid = uom.id";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
</head>
<body>
    <h1>Products</h1>
    <a href="ProductCreate.php">Create New Product</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Product Group</th>
            <th>Unit of Measure</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['product_group']; ?></td>
            <td><?php echo $row['unit_of_measure']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td>
                <a href="ProductEdit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="ProductDelete.php?id=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
