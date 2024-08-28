<?php
// include '../includes/dbconnection.php';
include('../common/base.php');

$sql = "SELECT * FROM Suppliers";
$result = $con->query($sql);
?>

    <div class="container mt-5">
        <h1 class="mb-4">Supplier List</h1>
        <a href="SupplierCreate.php" class="btn btn-primary mb-3">Create New Supplier</a>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact Info</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact_info']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <a href="SupplierEdit.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="SupplierDelete.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include('../common/footer.php'); ?>
