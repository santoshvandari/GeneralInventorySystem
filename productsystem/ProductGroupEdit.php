<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/dashboard.php');

$id = $con->real_escape_string($_GET['id']);
$query = "SELECT * FROM ProductGroups WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $id);
$result = $stmt->execute();
$product_group = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $description = $con->real_escape_string($_POST['description']);
    $status = $con->real_escape_string($_POST['status']);

    // $query = "UPDATE ProductGroups SET name = '$name', description = '$description', status = '$status' WHERE id = $id";
    $query = "UPDATE ProductGroups SET name = ?, description = ?, status = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssss", $name, $description, $status, $id);

    if ($stmt->execute()) {
        header('Location: ProductGroupList.php');
    } else {
        echo "<div class='alert alert-danger'>Error: " . $con->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Product Group</h1>
        <form method="post" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product_group['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product_group['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select id="status" name="status" class="form-select">
                    <option value="active" <?php if ($product_group['status'] === 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if ($product_group['status'] === 'inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="ProductGroupList.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

<?php include('../common/footer.php'); ?>