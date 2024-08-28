<?php
include('base.php');

// Fetch sales data (example: sales per month)
$salesData = [];
$salesLabels = [];
$sql = "SELECT MONTHNAME(sale_date) as month, SUM(total_amount) as total_sales 
        FROM sales 
        GROUP BY MONTH(sale_date)";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $salesLabels[] = $row['month'];
        $salesData[] = $row['total_sales'];
    }
}

// Fetch stock levels (example: stock levels by product)
$stockData = [];
$stockLabels = [];
$sql = "SELECT name, remaining_stock FROM products JOIN stock ON products.id = stock.product_id";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stockLabels[] = $row['name'];
        $stockData[] = $row['remaining_stock'];
    }
}
?>

<div class="container mt-5">
    <h1>System Overview</h1>
    
    <div class="row">
        <div class="col-md-6">
            <h3>Sales Chart</h3>
            <canvas id="salesChart"></canvas>
        </div>
        <div class="col-md-6">
            <h3>Stock Levels</h3>
            <canvas id="stockChart"></canvas>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Sales Chart
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($salesLabels); ?>,
            datasets: [{
                label: 'Sales',
                data: <?php echo json_encode($salesData); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Stock Levels Chart
    var ctx2 = document.getElementById('stockChart').getContext('2d');
    var stockChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($stockLabels); ?>,
            datasets: [{
                label: 'Stock Levels',
                data: <?php echo json_encode($stockData); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
