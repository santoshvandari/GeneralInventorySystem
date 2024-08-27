<?php
include '../includes/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $contact_info = $_POST['contact_info'];
    $status = $_POST['status'];

    $sql = "INSERT INTO Suppliers (name, contact_info, status) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('sss', $name, $contact_info, $status);

    if ($stmt->execute()) {
        header("Location: SupplierList.php");
        exit();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . htmlspecialchars($stmt->error) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Supplier</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Create Supplier</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="contact_info">Contact Info:</label>
                <input type="text" class="form-control" id="contact_info" name="contact_info">
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
        <a href="SupplierList.php" class="btn btn-secondary mt-3">Back to Supplier List</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
