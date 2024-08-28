<?php
// include '../includes/dbconnection.php';
include('../common/base.php');

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);

    // Prepare SQL statement
    $sql = "INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssss', $name, $email, $phone, $address);

    if ($stmt->execute()) {
        header("Location: CustomerList.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

    <div class="container mt-5">
        <h1>Create Customer</h1>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <form method="post" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-control" >
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <input type="text" id="address" name="address" class="form-control" >
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

<?php include('../common/footer.php'); ?>
