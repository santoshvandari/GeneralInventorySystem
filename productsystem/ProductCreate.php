<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/dashboard.php');

$product_groups = $con->query("SELECT id, name FROM ProductGroups WHERE status='active'");
$units_of_measure = $con->query("SELECT id, name FROM UnitOfMeasure WHERE status='active'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $description = $con->real_escape_string($_POST['description']);
    $product_group_id = $con->real_escape_string($_POST['product_group']);
    $unit_of_measure_id = $con->real_escape_string($_POST['unit_of_measure']);
    $status = $con->real_escape_string($_POST['status']);

    $query = "INSERT INTO products (name, description, productgroupid, unitofmeasureid, status) 
              VALUES ('$name', '$description', '$product_group_id', '$unit_of_measure_id', '$status')";
    if ($con->query($query)) {
        header('Location: ProductList.php');
    } else {
        echo "Error: " . $con->error;
    }
}
?>

    <div class="container mt-5">
        <h1 class="mb-4">Create Product</h1>
        <form method="post" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="product_group" class="form-label">Product Group:</label>
                <select class="form-select" id="product_group" name="product_group" required>
                    <?php while($row = $product_groups->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="unit_of_measure" class="form-label">Unit of Measure:</label>
                <select class="form-select" id="unit_of_measure" name="unit_of_measure" required>
                    <?php while($row = $units_of_measure->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
<?php include '../common/footer.php'; ?>