<?php
include('../includes/dbconnection.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $description = $con->real_escape_string($_POST['description']);
    $status = $con->real_escape_string($_POST['status']);

    $query = "INSERT INTO ProductGroups (name, description, status) VALUES ('$name', '$description', '$status')";
    if ($con->query($query)) {
        header('Location: ProductGroupList.php');
    } else {
        echo "Error: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product Group</title>
</head>
<body>
    <h1>Create Product Group</h1>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Description:</label>
        <textarea name="description"></textarea><br>
        <label>Status:</label>
        <select name="status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select><br>
        <input type="submit" value="Create">
    </form>
</body>
</html>
