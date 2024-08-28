<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/base.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $code = $con->real_escape_string($_POST['code']);
    $description = $con->real_escape_string($_POST['description']);
    $status = $con->real_escape_string($_POST['status']);

    // $query = "INSERT INTO UnitOfMeasure (name, code, description, status) VALUES ('$name', '$code', '$description', '$status')";
    $query = "INSERT INTO UnitOfMeasure (name, code, description, status) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssss", $name, $code, $description, $status);

    if ($stmt->execute()) {
        header('Location: UnitOfMeasureList.php');
        exit();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $con->error . "</div>";
    }
}
?>
    <div class="container mt-5">
        <h1 class="mb-4">Create Unit of Measure</h1>
        <form method="post" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">Code:</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-select" id="status" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="UnitOfMeasureList.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

 <?php include('../common/footer.php'); ?>