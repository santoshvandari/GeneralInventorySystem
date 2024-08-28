<?php
// include '../includes/dbconnection.php';
include('../common/base.php');
$supplier_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $contact_info = $_POST['contact_info'];
    $status = $_POST['status'];

    $sql = "UPDATE Suppliers SET name = ?, contact_info = ?, status = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('sssi', $name, $contact_info, $status, $supplier_id);

    if ($stmt->execute()) {
        header("Location: SupplierList.php");
        exit();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . htmlspecialchars($stmt->error) . "</div>";
    }
}

$sql = "SELECT * FROM Suppliers WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $supplier_id);
$stmt->execute();
$supplier = $stmt->get_result()->fetch_assoc();
?>

    <div class="container mt-5">
        <h1>Edit Supplier</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($supplier['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_info">Contact Info:</label>
                <input type="text" class="form-control" id="contact_info" name="contact_info" value="<?php echo htmlspecialchars($supplier['contact_info']); ?>">
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" class="form-control">
                    <option value="active" <?php echo $supplier['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $supplier['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
        <a href="SupplierList.php" class="btn btn-secondary mt-3">Back to Supplier List</a>
    </div>
<?php include('../common/footer.php'); ?>
