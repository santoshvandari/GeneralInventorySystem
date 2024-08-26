<?php
// include('dbconnection.php'); // Include your database connection file
include('../includes/dbconnection.php'); // Include your database connection file



$id = $con->real_escape_string($_GET['id']);
$query = "DELETE FROM UnitOfMeasure WHERE id = $id";
if ($con->query($query)) {
    header('Location: UnitOfMeasureList.php');
} else {
    echo "Error: " . $con->error;
}
?>
