<?php
include '../includes/dbconnection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Check if the customer exists
    $sql = "SELECT * FROM customers WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Customer exists, proceed to delete
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Perform deletion
            $sql = "DELETE FROM customers WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $id);

            if ($stmt->execute()) {
                header("Location: CustomerDelete.php");
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            // Show confirmation form
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Delete Customer</title>
                <link rel="stylesheet" type="text/css" href="../assets/styles.css">
            </head>
            <body>
                <h1>Delete Customer</h1>
                <p>Are you sure you want to delete this customer?</p>
                <form method="post" action="">
                    <button type="submit">Yes, Delete</button>
                    <a href="CustomerList.php">Cancel</a>
                </form>
            </body>
            </html>
            <?php
        }
    } else {
        echo "Customer not found.";
    }
} else {
    echo "No customer ID provided.";
}
?>
