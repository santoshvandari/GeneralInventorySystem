<?php
include '../includes/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $total_amount = $_POST['total_amount'];

    // Begin a transaction
    $con->begin_transaction();

    try {
        // Insert sale
        $sql = "INSERT INTO sales (customer_id, total_amount) VALUES (?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('id', $customer_id, $total_amount);

        if ($stmt->execute()) {
            $sale_id = $stmt->insert_id;

            // Insert sale items
            $sql = "INSERT INTO sale_items (sale_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($sql);

            foreach ($_POST['product_id'] as $index => $product_id) {
                $quantity = $_POST['quantity'][$index];
                $unit_price = $_POST['unit_price'][$index];
                $stmt->bind_param('iiid', $sale_id, $product_id, $quantity, $unit_price);

                if (!$stmt->execute()) {
                    throw new Exception("Error: " . $stmt->error);
                }
            }

            // Commit transaction
            $con->commit();
            header("Location: SalesList.php");
            exit();
        } else {
            throw new Exception("Error: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $con->rollback();
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Fetch customers and products
$customers = $con->query("SELECT * FROM customers");
$products = $con->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Sale</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Create Sale</h1>
        <form method="post" action="" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="customer_id">Customer:</label>
                <select id="customer_id" name="customer_id" class="form-control" required>
                    <?php while ($row = $customers->fetch_assoc()) { ?>
                        <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div id="sale_items">
                <div class="sale_item mb-3">
                    <div class="form-group">
                        <label for="product_id[]">Product:</label>
                        <select name="product_id[]" class="form-control" onchange="updateProductSelection(this)" required>
                            <option value="" disabled selected>Select a product</option>
                            <?php
                            $products->data_seek(0);
                            while ($row = $products->fetch_assoc()) { ?>
                                <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity[]">Quantity:</label>
                        <input type="number" name="quantity[]" class="form-control" min="1" oninput="calculateTotal()" required>
                    </div>
                    <div class="form-group">
                        <label for="unit_price[]">Unit Price:</label>
                        <input type="number" name="unit_price[]" class="form-control" step="0.01" min="0" oninput="calculateTotal()" required>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary mb-3" onclick="addItem()">Add Item</button>
            <h3>Total Amount: $<span id="total_amount_display">0.00</span></h3>
            <input type="hidden" name="total_amount" id="total_amount" value="0">
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function addItem() {
            var container = document.getElementById('sale_items');
            var newItem = document.createElement('div');
            newItem.classList.add('sale_item', 'mb-3');

            newItem.innerHTML = `
                <div class="form-group">
                    <label for="product_id[]">Product:</label>
                    <select name="product_id[]" class="form-control" onchange="updateProductSelection(this)" required>
                        <option value="" disabled selected>Select a product</option>
                        <?php
                        $products->data_seek(0);
                        while ($row = $products->fetch_assoc()) { ?>
                            <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity[]">Quantity:</label>
                    <input type="number" name="quantity[]" class="form-control" min="1" oninput="calculateTotal()" required>
                </div>
                <div class="form-group">
                    <label for="unit_price[]">Unit Price:</label>
                    <input type="number" name="unit_price[]" class="form-control" step="0.01" min="0" oninput="calculateTotal()" required>
                </div>
            `;
            container.appendChild(newItem);
            updateProductSelection();
        }

        function calculateTotal() {
            var total = 0;
            var items = document.querySelectorAll('.sale_item');
            items.forEach(function(item) {
                var quantity = item.querySelector('input[name="quantity[]"]').value;
                var unit_price = item.querySelector('input[name="unit_price[]"]').value;
                if (quantity && unit_price) {
                    total += parseFloat(quantity) * parseFloat(unit_price);
                }
            });
            document.getElementById('total_amount_display').innerText = total.toFixed(2);
            document.getElementById('total_amount').value = total.toFixed(2);
        }

        function updateProductSelection(currentSelect) {
            var allSelects = document.querySelectorAll('select[name="product_id[]"]');
            var selectedValues = Array.from(allSelects).map(select => select.value);

            allSelects.forEach(select => {
                Array.from(select.options).forEach(option => {
                    if (option.value && selectedValues.includes(option.value) && option.value !== select.value) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            });
        }

        function validateForm() {
            var items = document.querySelectorAll('.sale_item');
            if (items.length === 0) {
                alert('Please add at least one product.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
