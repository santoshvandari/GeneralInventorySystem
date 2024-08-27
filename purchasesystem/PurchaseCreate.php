<?php
include('../includes/dbconnection.php'); // Include your database connection file

$suppliers = $con->query("SELECT id, name FROM Suppliers WHERE status='active'");
$products = $con->query("SELECT id, name FROM products WHERE status='active'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id = $con->real_escape_string($_POST['supplier']);
    $purchase_date = $con->real_escape_string($_POST['purchase_date']);
    $status = $con->real_escape_string($_POST['status']);
    $total_amount = 0;

    // Insert the new purchase record
    $query = "INSERT INTO Purchases (supplier_id, purchase_date, total_amount, status) 
              VALUES ('$supplier_id', '$purchase_date', '$total_amount', '$status')";
    if ($con->query($query)) {
        $purchase_id = $con->insert_id;

        // Insert purchase items
        foreach ($_POST['products'] as $key => $product_id) {
            $quantity = $con->real_escape_string($_POST['quantities'][$key]);
            $unit_price = $con->real_escape_string($_POST['prices'][$key]);
            $total_price = $quantity * $unit_price;
            $total_amount += $total_price;

            $insert_item_query = "INSERT INTO PurchaseItems (purchase_id, product_id, quantity, unit_price) 
                                  VALUES ('$purchase_id', '$product_id', '$quantity', '$unit_price')";
            if (!$con->query($insert_item_query)) {
                die("Error inserting purchase item: " . $con->error);
            }
        }

        // Update the total amount in the purchase record
        $update_amount_query = "UPDATE Purchases SET total_amount = '$total_amount' WHERE id = '$purchase_id'";
        if (!$con->query($update_amount_query)) {
            die("Error updating total amount: " . $con->error);
        }

        header('Location: PurchaseList.php');
        exit();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Create Purchase</h1>
        <form method="post" action="">
            <div class="mb-3">
                <label for="supplier" class="form-label">Supplier:</label>
                <select id="supplier" name="supplier" class="form-select" required>
                    <?php while($row = $suppliers->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="purchase_date" class="form-label">Date:</label>
                <input type="date" id="purchase_date" name="purchase_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <h3>Products</h3>
            <div id="product-list">
                <div class="mb-3 product-row">
                    <label class="form-label">Product:</label>
                    <select name="products[]" class="form-select" required>
                        <?php
                        // Reset the products result pointer to the beginning
                        $products->data_seek(0);
                        while($row = $products->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                    <label class="form-label">Quantity:</label>
                    <input type="number" name="quantities[]" class="form-control" required>
                    <label class="form-label">Unit Price:</label>
                    <input type="number" step="0.01" name="prices[]" class="form-control" required>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addProduct()">Add Another Product</button><br><br>

            <input type="submit" class="btn btn-primary" value="Create">
        </form>
    </div>

    <script>
        function addProduct() {
            var productList = document.getElementById('product-list');
            var newProductDiv = document.createElement('div');
            newProductDiv.classList.add('mb-3', 'product-row');

            newProductDiv.innerHTML = `
                <label class="form-label">Product:</label>
                <select name="products[]" class="form-select" required>
                    <?php
                    // Reset the products result pointer to the beginning
                    $products->data_seek(0);
                    while($row = $products->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
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
