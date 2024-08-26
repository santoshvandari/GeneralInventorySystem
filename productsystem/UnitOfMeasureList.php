<?php
// include('dbconnection.php'); // Include your database connection file
include('../includes/dbconnection.php'); // Include your database connection file




$query = "SELECT * FROM UnitOfMeasure";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Units of Measure</title>
</head>
<body>
    <h1>Units of Measure</h1>
    <a href="UnitOfMeasureCreate.php">Create New Unit of Measure</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Code</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['code']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td>
                <a href="UnitOfMeasureEdit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="UnitOfMeasureDelete.php?id=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
