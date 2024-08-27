<?php
// include('../includes/dbconnection.php'); // Include your database connection file
include('../common/dashboard.php');

$id = $con->real_escape_string($_GET['id']);
$query = "DELETE FROM ProductGroups WHERE id = $id";
try{
    $con->query($query);
    header('Location: ProductGroupList.php');
}catch(Exception $e){
    echo "<script>alert('Failed to Delete');
    window.location.href='ProductGroupList.php';
    </script>";
    echo "Error: " . $con->error;
}
?>
