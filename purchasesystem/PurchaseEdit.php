<?php
include('../includes/dbconnection.php'); // Include your database connection file

$id = $con->real_escape_string($_GET['id']);
$query = "SELECT * FROM Purchases WHERE id = $id";
$result = $con->query($query);
if (!$result) {
    die("Error fetching purchase: " . $con->error);
}
$purchase = $result->fetch_assoc();

$suppliers = $con->query("SELECT id, name FROM Suppliers WHERE status='active'");
$products = $con->query("SELECT id, name FROM products WHERE status='active'");
$purchase_items = $con->query("SELECT * FROM PurchaseItems WHERE purchase_id = $id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id = $con->real_escape_string($_POST['supplier']);
    $purchase_date = $con->real_escape_string($_POST['purchase_date']);
    $total_amount = 0;
    $status = $con->real_escape_string($_POST['status']);

    // Update the purchase details
    $update_query = "UPDATE Purchases SET supplier_id = '$supplier_id', purchase_date = '$purchase_date', status = '$status' WHERE id = $id";
    if (!$con->query($update_query)) {
        die("Error updating purchase: " . $con->error);
    }

    // Delete existing purchase items
    $con->query("DELETE FROM PurchaseItems WHERE purchase_id = $id");
    
    // Insert new purchase items
    foreach ($_POST['products'] as $key => $product_id) {
        $quantity = $con->real_escape_string($_POST['quantities'][$key]);
        $unit_price = $con->real_escape_string($_POST['prices'][$key]);
        $total_price = $quantity * $unit_price;
        $total_amount += $total_price;

        $insert_query = "INSERT INTO PurchaseItems (purchase_id, product_id, quantity, unit_price) 
                         VALUES ('$id', '$product_id', '$quantity', '$unit_price')";
        if (!$con->query($insert_query)) {
            die("Error inserting purchase item: " . $con->error);
        }
    }

    // Update the total amount
    $update_total_query = "UPDATE Purchases SET total_amount = '$total_amount' WHERE id = $id";
    if (!$con->query($update_total_query)) {
        die("Error updating total amount: " . $con->error);
    }

    header('Location: PurchaseList.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Purchase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Purchase</h1>
        <form method="post" action="">
            <div class="mb-3">
                <label for="supplier" class="form-label">Supplier:</label>
                <select id="supplier" name="supplier" class="form-select">
                    <?php while($row = $suppliers->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>" <?php echo ($row['id'] == $purchase['supplier_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="purchase_date" class="form-label">Date:</label>
                <input type="date" id="purchase_date" name="purchase_date" class="form-control" value="<?php echo htmlspecialchars($purchase['purchase_date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select id="status" name="status" class="form-select">
                    <option value="pending" <?php echo ($purchase['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="completed" <?php echo ($purchase['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>

            <h3>Products</h3>
            <div id="product-list">
                <?php while($item = $purchase_items->fetch_assoc()): ?>
                <div class="mb-3">
                    <label class="form-label">Product:</label>
                    <select name="products[]" class="form-select">
                        <?php
                        // Reset the products result pointer to the beginning
                        $products->data_seek(0);
                        while($product = $products->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($product['id']); ?>" <?php echo ($product['id'] == $item['product_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($product['name']); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                    <label class="form-label">Quantity:</label>
                    <input type="number" name="quantities[]" class="form-control" value="<?php echo htmlspecialchars($item['quantity']); ?>" required>
                    <label class="form-label">Unit Price:</label>
                    <input type="number" step="0.01" name="prices[]" class="form-control" value="<?php echo htmlspecialchars($item['unit_price']); ?>" required>
                </div>
                <?php endwhile; ?>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addProduct()">Add Another Product</button><br><br>

            <input type="submit" class="btn btn-primary" value="Update">
        </form>
    </div>

    <script>
        function addProduct() {
            var productList = document.getElementById('product-list');
            var newProductDiv = document.createElement('div');
            newProductDiv.classList.add('mb-3');

            newProductDiv.innerHTML = `
                <label class="form-label">Product:</label>
                <select name="products[]" class="form-select">
                    <?php
                    // Reset the products result pointer to the beginning
                    $products->data_seek(0);
                    while($product = $products->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($product['id']); ?>">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
                <label class="form-label">Quantity:</label>
                <input type="number" name="quantities[]" class="form-control" required>
                <label class="form-label">Unit Price:</label>
                <input type="number" step="0.01" name="prices[]" class="form-control" required>
            `;

            productList.appendChild(newProductDiv);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
