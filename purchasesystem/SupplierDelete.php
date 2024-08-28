<?php
// include '../includes/dbconnection.php';
include('../common/base.php');


$supplier_id = $_GET['id'];

$sql = "DELETE FROM Suppliers WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $supplier_id);
try{
    $stmt->execute();
    header('Location: SupplierList.php');
}catch(exception $e){
    echo "<script>alert('Failed to Delete');
    window.location.href='SupplierList.php';
    </script>";
    echo "Error: " . $con->error;
}
?>
