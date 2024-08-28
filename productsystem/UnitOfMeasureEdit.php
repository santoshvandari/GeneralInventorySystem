<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/dashboard.php');

$id = $con->real_escape_string($_GET['id']);
$query = "SELECT * FROM UnitOfMeasure WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $id);
$result = $stmt->execute();
$unit_of_measure = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $code = $con->real_escape_string($_POST['code']);
    $description = $con->real_escape_string($_POST['description']);
    $status = $con->real_escape_string($_POST['status']);

    // $query = "UPDATE UnitOfMeasure SET name = '$name', code = '$code', description = '$description', status = '$status' WHERE id = $id";
    $query = "UPDATE UnitOfMeasure SET name = ?, code = ?, description = ?, status = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssss", $name, $code, $description, $status, $id);
    if ($stmt->execute()) {
        header('Location: UnitOfMeasureList.php');
    } else {
        echo "Error: " . $con->error;
    }
}
?>

    <div class="container mt-5">
        <h1 class="mb-4">Edit Unit of Measure</h1>
        <form method="post" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($unit_of_measure['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">Code:</label>
                <input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars($unit_of_measure['code']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($unit_of_measure['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-select" id="status" name="status">
                    <option value="active" <?php if ($unit_of_measure['status'] === 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if ($unit_of_measure['status'] === 'inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="UnitOfMeasureList.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

<?php include('../common/footer.php'); ?>