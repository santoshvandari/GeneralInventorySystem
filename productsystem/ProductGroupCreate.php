<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/dashboard.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $description = $con->real_escape_string($_POST['description']);
    $status = $con->real_escape_string($_POST['status']);

    $query = "INSERT INTO ProductGroups (name, description, status) VALUES ('$name', '$description', '$status')";
    if ($con->query($query)) {
        header('Location: ProductGroupList.php');
    } else {
        echo "<div class='alert alert-danger'>Error: " . $con->error . "</div>";
    }
}
?>
    <div class="container mt-5">
        <h1>Create Product Group</h1>
        <form method="post" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea id="description" name="description" class="form-control" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select id="status" name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="ProductGroupList.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

<?php include('../common/footer.php'); ?>
