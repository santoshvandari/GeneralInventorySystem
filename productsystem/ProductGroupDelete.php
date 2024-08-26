<?php
include('dbconnection.php'); // Include your database connection file

$id = $con->real_escape_string($_GET['id']);
$query = "DELETE FROM ProductGroups WHERE id = $id";
if ($con->query($query)) {
    header('Location: ProductGroupList.php');
} else {
    echo "Error: " . $con->error;
}
?>
