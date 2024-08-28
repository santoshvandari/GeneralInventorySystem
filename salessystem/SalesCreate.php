<?php
// Include database connection and common dashboard
include('../common/base.php');

// Initialize variables to store error messages and form values
$error_message = '';
$customer_id = $_POST['customer_id'] ?? null;
$total_amount = $_POST['total_amount'] ?? 0;
$products = $_POST['product_id'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$unit_prices = $_POST['unit_price'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $con->begin_transaction();
    try {
        // Validate stock availability
        foreach ($products as $index => $product_id) {
            $quantity = $quantities[$index];
            
            $stmt=$con->prepare("SELECT remaining_stock FROM stock WHERE product_id=?");
            $stmt->bind_param('i', $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row1 = $result->fetch_assoc();

            $stmt=$con->prepare("SELECT name FROM products WHERE id=?");
            $stmt->bind_param('i', $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row2 = $result->fetch_assoc();
            
            if (!$row1 || $row1['remaining_stock'] < $quantity) {
                // Ensure product name is available, fallback to a placeholder if not
                $product_name = $row2['name'] ?? 'Unknown product';
                $error_message = 'Error: Insufficient stock for product: ' . htmlspecialchars($product_name);
                throw new Exception($error_message);
            }
        }

        // Insert sale record
        $stmt = $con->prepare("INSERT INTO sales (customer_id, total_amount) VALUES (?, ?)");
        $stmt->bind_param('id', $customer_id, $total_amount);
        if ($stmt->execute()) {
            $sale_id = $stmt->insert_id;

            // Insert sale items and update stock
            $stmt = $con->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
            $update_stock_stmt = $con->prepare("UPDATE stock SET sales_stock = sales_stock + ? WHERE product_id = ?");

            foreach ($products as $index => $product_id) {
                $quantity = $quantities[$index];
                $unit_price = $unit_prices[$index];
                
                // Insert sale item
                $stmt->bind_param('iiid', $sale_id, $product_id, $quantity, $unit_price);
                if (!$stmt->execute()) {
                    throw new Exception("Error inserting sale item: " . $stmt->error);
                }

                // Update stock
                $update_stock_stmt->bind_param('ii', $quantity, $product_id);
                if (!$update_stock_stmt->execute()) {
                    throw new Exception("Error updating stock: " . $update_stock_stmt->error);
                }
            }

            $con->commit();
            header("Location: SalesList.php");
            exit();
        } else {
            throw new Exception("Error inserting sale: " . $stmt->error);
        }
    } catch (Exception $e) {
        $con->rollback();
        // Error message will be displayed above the form
        // echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Fetch customers and products
$customers = $con->query("SELECT * FROM customers");
$products_list = $con->query("SELECT * FROM products");
?>

<div class="container mt-5">
    <h1 class="mb-4">Create Sale</h1>
    
    <?php if ($error_message): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="customer_id">Customer:</label>
            <select id="customer_id" name="customer_id" class="form-control" required>
                <option value="0" <?php echo $customer_id === '0' ? 'selected' : ''; ?>>No customer</option>
                <?php while ($row = $customers->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>" <?php echo $customer_id == $row['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                <?php } ?>
            </select>
        </div>
        <div id="sale_items">
            <?php foreach ($products as $index => $product_id): ?>
                <div class="sale_item mb-3">
                    <div class="form-group">
                        <label for="product_id[]">Product:</label>
                        <select name="product_id[]" class="form-control" onchange="updateProductSelection(this)" required>
                            <option value="" disabled>Select a product</option>
                            <?php
                            $products_list->data_seek(0);
                            while ($row = $products_list->fetch_assoc()) { ?>
                                <option value="<?php echo htmlspecialchars($row['id']); ?>" <?php echo $product_id == $row['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity[]">Quantity:</label>
                        <input type="number" name="quantity[]" class="form-control" min="1" oninput="calculateTotal()" value="<?php echo htmlspecialchars($quantities[$index] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="unit_price[]">Unit Price:</label>
                        <input type="number" name="unit_price[]" class="form-control" step="0.01" min="0" oninput="calculateTotal()" value="<?php echo htmlspecialchars($unit_prices[$index] ?? ''); ?>" required>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addItem()">Add Item</button>
        <h3>Total Amount: $<span id="total_amount_display"><?php echo htmlspecialchars($total_amount); ?></span></h3>
        <input type="hidden" name="total_amount" id="total_amount" value="<?php echo htmlspecialchars($total_amount); ?>">
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
                    $products_list->data_seek(0);
                    while ($row = $products_list->fetch_assoc()) { ?>
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
            if (select !== currentSelect) {
                var options = select.querySelectorAll('option');
                options.forEach(option => {
                    option.disabled = selectedValues.includes(option.value) && option.value !== currentSelect.value;
                });
            }
        });
    }

    function validateForm() {
        var valid = true;
        var items = document.querySelectorAll('.sale_item');
        
        items.forEach(function(item) {
            var productSelect = item.querySelector('select[name="product_id[]"]');
            var quantityInput = item.querySelector('input[name="quantity[]"]');
            var unitPriceInput = item.querySelector('input[name="unit_price[]"]');
            
            if (!productSelect.value || !quantityInput.value || !unitPriceInput.value) {
                valid = false;
                alert('Please fill out all fields.');
            }
        });

        return valid;
    }
</script>
