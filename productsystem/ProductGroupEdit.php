<?php
include('dbconnection.php'); // Include your database connection file

$id = $con->real_escape_string($_GET['id']);
$query = "SELECT * FROM ProductGroups WHERE id = $id";
$result = $con->query($query);
$product_group = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $description = $con->real_escape_string($_POST['description']);
    $status = $con->real_escape_string($_POST['status']);

    $query = "UPDATE ProductGroups SET name = '$name', description = '$description', status = '$status' WHERE id = $id";
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
    <title>Edit Product Group</title>
</head>
<body>
    <h1>Edit Product Group</h1>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $product_group['name']; ?>" required><br>
        <label>Description:</label>
        <textarea name="description"><?php echo $product_group['description']; ?></textarea><br>
        <label>Status:</label>
        <select name="status">
            <option value="active" <?php if ($product_group['status'] === 'active') echo 'selected'; ?>>Active</option>
            <option value="inactive" <?php if ($product_group['status'] === 'inactive') echo 'selected'; ?>>Inactive</option>
        </select><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
