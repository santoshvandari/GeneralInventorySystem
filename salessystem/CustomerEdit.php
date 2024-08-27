<?php
include '../includes/dbconnection.php';

// Initialize variables
$id = '';
$name = '';
$email = '';
$phone = '';
$address = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Prepare SQL statement
    $sql = "UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssssi', $name, $email, $phone, $address, $id);

    if ($stmt->execute()) {
        header("Location: CustomerList.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
} else {
    $id = $_GET['id'];

    // Prepare SQL statement
    $sql = "SELECT * FROM customers WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if (!$customer) {
        die("Customer not found.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Customer</h1>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($customer['id']); ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($customer['phone']); ?>">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($customer['address']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
