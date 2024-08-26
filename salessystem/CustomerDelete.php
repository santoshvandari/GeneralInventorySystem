<?php
include '../includes/dbconnection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Perform deletion
    $sql = "DELETE FROM customers WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header("Location: CustomerList.php");
    } else {
    echo "Error: " . $stmt->error;
    }
}
?>
