<?php
include('../includes/dbconnection.php'); // Include your database connection file

$suppliers = $con->query("SELECT id, name FROM Suppliers WHERE status='active'");
$products = $con->query("SELECT id, name FROM Products WHERE status='active'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id = $con->real_escape_string($_POST['supplier']);
    $purchase_date = $con->real_escape_string($_POST['purchase_date']);
    $total_amount = 0;
    $status = $con->real_escape_string($_POST['status']);

    $query = "INSERT INTO Purchases (supplier_id, purchase_date, total_amount, status) 
              VALUES ('$supplier_id', '$purchase_date', '$total_amount', '$status')";
    if ($con->query($query)) {
        $purchase_id = $con->insert_id;
        foreach ($_POST['products'] as $key => $product_id) {
            $quantity = $con->real_escape_string($_POST['quantities'][$key]);
            $unit_price = $con->real_escape_string($_POST['prices'][$key]);
            $total_price = $quantity * $unit_price;
            $total_amount += $total_price;

            $con->query("INSERT INTO PurchaseItems (purchase_id, product_id, quantity, unit_price) 
                         VALUES ('$purchase_id', '$product_id', '$quantity', '$unit_price')");
        }

        // Update the total amount in the purchase record
        $con->query("UPDATE Purchases SET total_amount = '$total_amount' WHERE id = '$purchase_id'");

        header('Location: PurchaseList.php');
    } else {
        echo "Error: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Purchase</title>
</head>
<body>
    <h1>Create Purchase</h1>
    <form method="post" action="">
        <label>Supplier:</label>
        <select name="supplier">
            <?php while($row = $suppliers->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br>
        <label>Date:</label>
        <input type="date" name="purchase_date" required><br>
        <label>Status:</label>
        <select name="status">
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
        </select><br>

        <h3>Products</h3>
        <div id="product-list">
            <div>
                <label>Product:</label>
                <select name="products[]">
                    <?php while($row = $products->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
                <label>Quantity:</label>
                <input type="number" name="quantities[]" required>
                <label>Unit Price:</label>
                <input type="number" step="0.01" name="prices[]" required>
            </div>
        </div>
        <button type="button" onclick="addProduct()">Add Another Product</button><br>

        <input type="submit" value="Create">
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
