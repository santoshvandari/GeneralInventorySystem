<?php
include '../includes/dbconnection.php';

$supplier_id = $_GET['id'];

$sql = "DELETE FROM Suppliers WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $supplier_id);

if ($stmt->execute()) {
    header("Location: SupplierList.php");
    exit();
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}
?>
