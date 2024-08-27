<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/dashboard.php');

$query = "SELECT * FROM ProductGroups";
$result = $con->query($query);
?>

    <div class="container mt-5">
        <h1 class="mb-4">Product Groups</h1>
        <a href="ProductGroupCreate.php" class="btn btn-primary mb-3">Create New Product Group</a>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td>
                        <a href="ProductGroupEdit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="ProductGroupDelete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php include('../common/footer.php'); ?>