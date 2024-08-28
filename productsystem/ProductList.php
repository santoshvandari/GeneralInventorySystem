<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/base.php');

$query = "SELECT p.id, p.name, p.description, pg.name as product_group, uom.name as UnitOfMeasure, p.status 
          FROM products p 
          JOIN ProductGroups pg ON p.productgroupid = pg.id 
          JOIN UnitOfMeasure uom ON p.unitofmeasureid = uom.id";
$result = $con->query($query);
?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Products</h1>
            <a href="ProductCreate.php" class="btn btn-primary">Create New Product</a>
        </div>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>S.N.</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Product Group</th>
                    <th>Unit of Measure</th>
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
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['product_group']; ?></td>
                    <td><?php echo $row['UnitOfMeasure']; ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td>
                        <a href="ProductEdit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="ProductDelete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

 <?php include('../common/footer.php'); ?>
