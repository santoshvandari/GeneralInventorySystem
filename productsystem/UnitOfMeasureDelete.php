<?php
// include('dbconnection.php'); // Include your database connection file
include('../includes/dbconnection.php'); // Include your database connection file



$id = $con->real_escape_string($_GET['id']);
$query = "DELETE FROM UnitOfMeasure WHERE id = $id";
try{
    $con->query($query);
    header('Location: UnitOfMeasureList.php');
}catch(Exception $e){
    echo "<script>alert('Failed to Delete');
    window.location.href='UnitOfMeasureList.php';
    </script>";
    echo "Error: " . $con->error;
}
?>
