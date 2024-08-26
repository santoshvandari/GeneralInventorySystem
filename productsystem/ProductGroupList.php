<?php
include('../includes/dbconnection.php'); // Include your database connection file

$query = "SELECT * FROM ProductGroups";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Groups</title>
</head>
<body>
    <h1>Product Groups</h1>
    <a href="ProductGroupCreate.php">Create New Product Group</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td>
                <a href="ProductGroupEdit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="ProductGroupDelete.php?id=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
