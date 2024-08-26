<?php
// include('dbconnection.php'); // Include your database connection file
include('../includes/dbconnection.php'); // Include your database connection file

$id = $con->real_escape_string($_GET['id']);
$query = "SELECT * FROM Products WHERE id = $id";
$result = $con->query($query);
$product = $result->fetch_assoc();

$product_groups = $con->query("SELECT id, name FROM ProductGroups WHERE status='active'");
$units_of_measure = $con->query("SELECT id, name FROM UnitOfMeasure WHERE status='active'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $description = $con->real_escape_string($_POST['description']);
    $product_group_id = $con->real_escape_string($_POST['product_group']);
    $unit_of_measure_id = $con->real_escape_string($_POST['unit_of_measure']);
    $status = $con->real_escape_string($_POST['status']);

    $query = "UPDATE Products SET name = '$name', description = '$description', productgroupid = '$product_group_id', unitofmeasureid = '$unit_of_measure_id', status = '$status' WHERE id = $id";
    if ($con->query($query)) {
        header('Location: product_list.php');
    } else {
        echo "Error: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>
        <label>Description:</label>
        <textarea name="description"><?php echo $product['description']; ?></textarea><br>
        <label>Product Group:</label>
        <select name="product_group">
            <?php while($row = $product_groups->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $product['productgroupid']) echo 'selected'; ?>><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br>
        <label>Unit of Measure:</label>
        <select name="unit_of_measure">
            <?php while($row = $units_of_measure->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $product['unitofmeasureid']) echo 'selected'; ?>><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br>
        <label>Status:</label>
        <select name="status">
            <option value="active" <?php if ($product['status'] === 'active') echo 'selected'; ?>>Active</option>
            <option value="inactive" <?php if ($product['status'] === 'inactive') echo 'selected'; ?>>Inactive</option>
        </select><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
