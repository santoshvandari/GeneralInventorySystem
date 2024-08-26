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
        } else {
            throw new Exception("Error: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $con->rollback();
        echo $e->getMessage();
    }
}

// Resetting pointers to fetch data correctly
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
    <form method="post" action="" onsubmit="return validateForm()">
        <label for="customer_id">Customer:</label>
        <select id="customer_id" name="customer_id" required>
            <?php while ($row = $customers->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
        </select>
        <div id="sale_items">
            <div class="sale_item">
                <label for="product_id[]">Product:</label>
                <select name="product_id[]" onchange="updateProductSelection(this)" required>
                    <option value="" disabled selected>Select a product</option>
                    <?php
                    $products->data_seek(0);
                    while ($row = $products->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php } ?>
                </select>
                <label for="quantity[]">Quantity:</label>
                <input type="number" name="quantity[]" min="1" oninput="calculateTotal()" required>
                <label for="unit_price[]">Unit Price:</label>
                <input type="number" name="unit_price[]" step="0.01" min="0" oninput="calculateTotal()" required>
            </div>
        </div>
        <button type="button" onclick="addItem()">Add Item</button>
        <h3>Total Amount: $<span id="total_amount_display">0.00</span></h3>
        <input type="hidden" name="total_amount" id="total_amount" value="0">
        <button type="submit">Save</button>
    </form>
    <script>
        function addItem() {
            var container = document.getElementById('sale_items');
            var newItem = document.createElement('div');
            newItem.classList.add('sale_item');

            newItem.innerHTML = `
                <label for="product_id[]">Product:</label>
                <select name="product_id[]" onchange="updateProductSelection(this)" required>
                    <option value="" disabled selected>Select a product</option>
                    <?php
                    $products->data_seek(0);
                    while ($row = $products->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php } ?>
                </select>
                <label for="quantity[]">Quantity:</label>
                <input type="number" name="quantity[]" min="1" oninput="calculateTotal()" required>
                <label for="unit_price[]">Unit Price:</label>
                <input type="number" name="unit_price[]" step="0.01" min="0" oninput="calculateTotal()" required>
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
