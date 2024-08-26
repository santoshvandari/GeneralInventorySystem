<?php
include('../includes/dbconnection.php'); // Include your database connection file

$product_groups = $con->query("SELECT id, name FROM ProductGroups WHERE status='active'");
$units_of_measure = $con->query("SELECT id, name FROM UnitOfMeasure WHERE status='active'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $description = $con->real_escape_string($_POST['description']);
    $product_group_id = $con->real_escape_string($_POST['product_group']);
    $unit_of_measure_id = $con->real_escape_string($_POST['unit_of_measure']);
    $status = $con->real_escape_string($_POST['status']);

    $query = "INSERT INTO Products (name, description, productgroupid, unitofmeasureid, status) 
              VALUES ('$name', '$description', '$product_group_id', '$unit_of_measure_id', '$status')";
    if ($con->query($query)) {
        header('Location: ProductList.php');
    } else {
        echo "Error: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
</head>
<body>
    <h1>Create Product</h1>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Description:</label>
        <textarea name="description"></textarea><br>
        <label>Product Group:</label>
        <select name="product_group">
            <?php while($row = $product_groups->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br>
        <label>Unit of Measure:</label>
        <select name="unit_of_measure">
            <?php while($row = $units_of_measure->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br>
        <label>Status:</label>
        <select name="status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select><br>
        <input type="submit" value="Create">
    </form>
</body>
</html>
