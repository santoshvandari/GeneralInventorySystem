<?php
// include('dbconnection.php'); // Include your database connection file
include('../includes/dbconnection.php'); // Include your database connection file




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $code = $con->real_escape_string($_POST['code']);
    $description = $con->real_escape_string($_POST['description']);
    $status = $con->real_escape_string($_POST['status']);

    $query = "INSERT INTO UnitOfMeasure (name, code, description, status) VALUES ('$name', '$code', '$description', '$status')";
    if ($con->query($query)) {
        header('Location: UnitOfMeasureList.php');
    } else {
        echo "Error: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Unit of Measure</title>
</head>
<body>
    <h1>Create Unit of Measure</h1>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Code:</label>
        <input type="text" name="code" required><br>
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
