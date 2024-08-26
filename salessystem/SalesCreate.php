<?php
include '../includes/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $total_amount = $_POST['total_amount'];

    $sql = "INSERT INTO sales (customer_id, total_amount) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('id', $customer_id, $total_amount);

    if ($stmt->execute()) {
        $sale_id = $stmt->insert_id;
        // Insert sale items
        foreach ($_POST['product_id'] as $index => $product_id) {
            $quantity = $_POST['quantity'][$index];
            $unit_price = $_POST['unit_price'][$index];
            $sql = "INSERT INTO sale_items (sale_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('iiid', $sale_id, $product_id, $quantity, $unit_price);
            $stmt->execute();
        }
        header("Location: SalesList.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}

$customers = $con->query("SELECT * FROM customers");
$products = $con->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Sale</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
</head>
<body>
    <h1>Create Sale</h1>
    <form method="post" action="">
        <label for="customer_id">Customer:</label>
        <select id="customer_id" name="customer_id" required>
            <?php while ($row = $customers->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
        </select>
        <div id="sale_items">
            <div class="sale_item">
                <label for="product_id[]">Product:</label>
                <select name="product_id[]" required>
                    <?php while ($row = $products->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php } ?>
                </select>
                <label for="quantity[]">Quantity:</label>
                <input type="number" name="quantity[]" required>
                <label for="unit_price[]">Unit Price:</label>
                <input type="number" name="unit_price[]" step="0.01" required>
            </div>
        </div>
        <button type="button" onclick="addItem()">Add Item</button>
        <button type="submit">Save</button>
    </form>
    <script>
        function addItem() {
            var container = document.getElementById('sale_items');
            var newItem = document.createElement('div');
            newItem.classList.add('sale_item');
            newItem.innerHTML = `
                <label for="product_id[]">Product:</label>
                <select name="product_id[]" required>
                    <?php while ($row = $products->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php } ?>
                </select>
                <label for="quantity[]">Quantity:</label>
                <input type="number" name="quantity[]" required>
                <label for="unit_price[]">Unit Price:</label>
                <input type="number" name="unit_price[]" step="0.01" required>
            `;
            container.appendChild(newItem);
        }
    </script>
</body>
</html>
