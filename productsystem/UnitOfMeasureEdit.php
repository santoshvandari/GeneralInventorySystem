<?php
// include('dbconnection.php'); // Include your database connection file
include('../includes/dbconnection.php'); // Include your database connection file




$id = $con->real_escape_string($_GET['id']);
$query = "SELECT * FROM UnitOfMeasure WHERE id = $id";
$result = $con->query($query);
$unit_of_measure = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $code = $con->real_escape_string($_POST['code']);
    $description = $con->real_escape_string($_POST['description']);
    $status = $con->real_escape_string($_POST['status']);

    $query = "UPDATE UnitOfMeasure SET name = '$name', code = '$code', description = '$description', status = '$status' WHERE id = $id";
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
    <title>Edit Unit of Measure</title>
</head>
<body>
    <h1>Edit Unit of Measure</h1>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $unit_of_measure['name']; ?>" required><br>
        <label>Code:</label>
        <input type="text" name="code" value="<?php echo $unit_of_measure['code']; ?>" required><br>
        <label>Description:</label>
        <textarea name="description"><?php echo $unit_of_measure['description']; ?></textarea><br>
        <label>Status:</label>
        <select name="status">
            <option value="active" <?php if ($unit_of_measure['status'] === 'active') echo 'selected'; ?>>Active</option>
            <option value="inactive" <?php if ($unit_of_measure['status'] === 'inactive') echo 'selected'; ?>>Inactive</option>
        </select><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
