<?php
include('../includes/dbconnection.php'); // Include your database connection file

$id = $con->real_escape_string($_GET['id']);
$query = "SELECT * FROM Purchases WHERE id = $id";
$result = $con->query($query);
$purchase = $result->fetch_assoc();

$suppliers = $con->query("SELECT id, name FROM Suppliers WHERE status='active'");
$products = $con->query("SELECT id, name FROM Products WHERE status='active'");
$purchase_items = $con->query("SELECT * FROM PurchaseItems WHERE purchase_id = $id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id = $con->real_escape_string($_POST['supplier']);
    $purchase_date = $con->real_escape_string($_POST['purchase_date']);
    $total_amount = 0;
    $status = $con->real_escape_string($_POST['status']);

    $con->query("UPDATE Purchases SET supplier_id = '$supplier_id', purchase_date = '$purchase_date', status = '$status' WHERE id = $id");

    $con->query("DELETE FROM PurchaseItems WHERE purchase_id = $id");
    foreach ($_POST['products'] as $key => $product_id) {
        $quantity = $con->real_escape_string($_POST['quantities'][$key]);
        $unit_price = $con->real_escape_string($_POST['prices'][$key]);
        $total_price = $quantity * $unit_price;
        $total_amount += $total_price;

        $con->query("INSERT INTO PurchaseItems (purchase_id, product_id, quantity, unit_price) 
                     VALUES ('$id', '$product_id', '$quantity', '$unit_price')");
    }

    $con->query("UPDATE Purchases SET total_amount = '$total_amount' WHERE id = '$id'");

    header('Location: PurchaseList.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Purchase</title>
</head>
<body>
    <h1>Edit Purchase</h1>
    <form method="post" action="">
        <label>Supplier:</label>
        <select name="supplier">
            <?php while($row = $suppliers->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $purchase['supplier_id']) ? 'selected' : ''; ?>>
                <?php echo $row['name']; ?>
            </option>
            <?php endwhile; ?>
        </select><br>
        <label>Date:</label>
        <input type="date" name="purchase_date" value="<?php echo $purchase['purchase_date']; ?>" required><br>
        <label>Status:</label>
        <select name="status">
            <option value="pending" <?php echo ($purchase['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="completed" <?php echo ($purchase['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
        </select><br>

        <h3>Products</h3>
        <div id="product-list">
            <?php while($item = $purchase_items->fetch_assoc()): ?>
            <div>
                <label>Product:</label>
                <select name="products[]">
                    <?php while($product = $products->fetch_assoc()): ?>
                    <option value="<?php echo $product['id']; ?>" <?php echo ($product['id'] == $item['product_id']) ? 'selected' : ''; ?>>
                        <?php echo $product['name']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
                <label>Quantity:</label>
                <input type="number" name="quantities[]" value="<?php echo $item['quantity']; ?>" required>
                <label>Unit Price:</label>
                <input type="number" step="0.01" name="prices[]" value="<?php echo $item['unit_price']; ?>" required>
            </div>
            <?php endwhile; ?>
        </div>
        <button type="button" onclick="addProduct()">Add Another Product</button><br>

        <input type="submit" value="Update">
    </form>

    <script>
        function addProduct() {
            var productSelect = document.querySelector('#product-list div').innerHTML;
            var div = document.createElement('div');
            div.innerHTML = productSelect;
            document.getElementById('product-list').appendChild(div);
        }
    </script>
</body>
</html>
