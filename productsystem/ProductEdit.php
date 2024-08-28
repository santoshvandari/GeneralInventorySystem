<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/dashboard.php');

$id = $con->real_escape_string($_GET['id']);
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $id);

$result = $stmt->execute();
$product = $result->fetch_assoc();

$product_groups = $con->query("SELECT id, name FROM ProductGroups WHERE status='active'");
$units_of_measure = $con->query("SELECT id, name FROM UnitOfMeasure WHERE status='active'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $description = $con->real_escape_string($_POST['description']);
    $product_group_id = $con->real_escape_string($_POST['product_group']);
    $unit_of_measure_id = $con->real_escape_string($_POST['unit_of_measure']);
    $status = $con->real_escape_string($_POST['status']);

    // $query = "UPDATE products SET name = '$name', description = '$description', productgroupid = '$product_group_id', unitofmeasureid = '$unit_of_measure_id', status = '$status' WHERE id = $id";
    $query = "UPDATE products SET name = ?, description = ?, productgroupid = ?, unitofmeasureid = ?, status = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssss", $name, $description, $product_group_id, $unit_of_measure_id, $status, $id);

    if ($stmt->execute()) {
        header('Location: ProductList.php');
    } else {
        echo "Error: " . $con->error;
    }
}
?>

    <div class="container mt-5">
        <h1 class="mb-4">Edit Product</h1>
        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-control"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Product Group:</label>
                <select name="product_group" class="form-select">
                    <?php while($row = $product_groups->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $product['productgroupid']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($row['name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Unit of Measure:</label>
                <select name="unit_of_measure" class="form-select">
                    <?php while($row = $units_of_measure->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $product['unitofmeasureid']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($row['name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Status:</label>
                <select name="status" class="form-select">
                    <option value="active" <?php if ($product['status'] === 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if ($product['status'] === 'inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="ProductList.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

<?php include('../common/footer.php'); ?>