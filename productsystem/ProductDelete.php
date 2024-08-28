<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/base.php');

$id = $con->real_escape_string($_GET['id']);
$query = "DELETE FROM products WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $id);

try{
    $stmt->execute();
    header('Location: ProductList.php');
}catch(Exception $e){
    echo "<script>alert('Failed to Delete');
    window.location.href='ProductList.php';
    </script>";
    echo "Error: " . $con->error;
}
?>
