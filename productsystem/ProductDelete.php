<?php
include('../includes/dbconnection.php'); // Include your database connection file

$id = $con->real_escape_string($_GET['id']);
$query = "DELETE FROM products WHERE id = $id";
if ($con->query($query)) {
    header('Location: ProductList.php');
} else {
    echo "Error: " . $con->error;
}
?>
