<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/base.php');

$query = "SELECT * FROM UnitOfMeasure";
$result = $con->query($query);
?>
    <div class="container mt-5">
        <h1 class="mb-4">Units of Measure</h1>
        <a href="UnitOfMeasureCreate.php" class="btn btn-primary mb-3">Create New Unit of Measure</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 0; ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo ++$counter; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['code']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td>
                        <a href="UnitOfMeasureEdit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a> 
                        <a href="UnitOfMeasureDelete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<?php include('../common/footer.php'); ?>
