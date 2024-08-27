<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/dashboard.php');

$id = $con->real_escape_string($_GET['id']);
$query = "DELETE FROM products WHERE id = $id";
try{
    $con->query($query);
    header('Location: ProductList.php');
}catch(Exception $e){
    echo "<script>alert('Failed to Delete');
    window.location.href='ProductList.php';
    </script>";
    echo "Error: " . $con->error;
}
?>
