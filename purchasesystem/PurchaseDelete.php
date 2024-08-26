<?php
include('../includes/dbconnection.php'); // Include your database connection file

$id = $con->real_escape_string($_GET['id']);
$con->query("DELETE FROM PurchaseItems WHERE purchase_id = $id");
$con->query("DELETE FROM Purchases WHERE id = $id");

header('Location: PurchaseList.php');
?>
