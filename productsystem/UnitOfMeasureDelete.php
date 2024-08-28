<?php
// include('dbconnection.php'); // Include your database connection file
include('../common/base.php'); // Include your database connection file



$id = $con->real_escape_string($_GET['id']);
$query = "DELETE FROM UnitOfMeasure WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $id);

try{
    $stmt->execute();
    header('Location: UnitOfMeasureList.php');
}catch(Exception $e){
    echo "<script>alert('Failed to Delete');
    window.location.href='UnitOfMeasureList.php';
    </script>";
    echo "Error: " . $con->error;
}
?>
