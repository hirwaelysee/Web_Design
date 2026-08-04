<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

// Get all products
$query = "SELECT id, product_code, product_name, quantity, unit_price,
         (quantity * unit_price) as stock_value
         FROM products 
         ORDER BY product_name ASC";

$result = mysqli_query($conn, $query);

// Calculate totals
$total_products = 0;
$total_quantity = 0;
$total_value = 0;
$products = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
        $total_products++;
        $total_quantity += $row['quantity'];
        $total_value += $row['stock_value'];
    }
}

// Get current date and time
$current_date = date('F d, Y');
$current_time = date('h:i A');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report - Inventory System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* PRINT STYLES */
        @media print {
            .navbar,
            .print-controls,
            .back-link,
            .screen-only {
                display: none !important;
            }

            body {
                background-color: white;
            }

            .main-content {
                padding: 0;
            }

            .report-container {
                box-shadow: none;
                padding: 0;
            }

            .report-header {
                border-bottom: 2px solid #000;
            }

            .data-table {
                page-break-inside: avoid;
            }

            .data-table tbody tr {
                page-break-inside: avoid;
            }

            .totals-row {
                border-top: 2px solid #000;
                border-bottom: 2px solid #000;
            }
        }

        /* REPORT STYLES */
        .report-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
        }

        .report-header h1 {
            color: #2c3e50;
            font-size: 32px;
            margin-bottom: 5px;
        }

        .report-header p {
            color: #555;
            font-size: 14px;
            margin: 5px 0;
        }

        .report-meta {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .meta-item {
            text-align: center;
        }

        .meta-label {
            color: #555;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .meta-value {
            color: #2c3e50;
            font-size: 18px;
            font-weight: bold;
        }

        .report-table-section {
            margin-bottom: 30px;
        }

        .report-table-section h3 {
            color: #2c3e50;
            font-size: 18px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .report-table thead {
            background-color: #2c3e50;
            color: white;
        }

        .report-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #34495e;
        }

        .report-table td {
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 13px;
        }

        .report-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .report-table tbody tr:hover {
            background-color: #f0f0f0;
        }

        .report-totals-row {
            background-color: #ecf0f1;
            font-weight: bold;
        }

        .report-totals-row td {
            padding: 15px 12px;
            border: 2px solid #2c3e50;
        }

        .no-data-message {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 14px;
        }

        .print-controls {
            background-color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .print-btn {
            background-color: #27ae60;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 0 10px;
        }

        .print-btn:hover {
            background-color: #229954;
        }

        .back-btn {
            background-color: #3498db;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            margin: 0 10px;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #2980b9;
        }

        .report-footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #999;
            font-size: 12px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .report-container {
                padding: 20px;
            }

            .report-header h1 {
                font-size: 24px;
            }

            .report-meta {
                grid-template-columns: 1fr;
            }

            .report-table th,
            .report-table td {
                padding: 8px;
                font-size: 12px;
            }

            .print-btn,
            .back-btn {
                padding: 10px 20px;
                font-size: 12px;
                margin: 5px;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="header">
        <div class="container">
            <h1>Inventory & Stock Management System</h1>
            <p class="tagline">Manage your product inventory efficiently</p>
        </div>
    </header>

    <!-- NAVIGATION (Hidden when printing) -->
    <nav class="navbar">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="index.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>
                <li><a href="add_product.php"><i class="fa-solid fa-square-plus"></i> Add Product</a></li>
                <li><a href="view_inventory.php"><i class="fa-solid fa-boxes-stacked"></i> View Inventory</a></li>
                <li><a href="search_product.php"><i class="fa-solid fa-magnifying-glass"></i> Search Product</a></li>
                <li><a href="print_report.php" class="active"><i class="fa-solid fa-chart-column"></i> Print Report</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">

            <!-- PRINT CONTROLS -->
            <div class="print-controls screen-only">
                <button class="print-btn" onclick="window.print();"><i class="fa-solid fa-print"></i> Print Report</button>
                <a href="view_inventory.php" class="back-btn">← Back to Inventory</a>
            </div>

            <!-- REPORT CONTAINER -->
            <article class="report-container">

                <!-- REPORT HEADER -->
                <div class="report-header">
                    <h1>Inventory Report</h1>
                    <p>Comprehensive Product Inventory Summary</p>
                    <p>Generated on: <strong><?php echo $current_date; ?></strong> at <strong><?php echo $current_time; ?></strong></p>
                </div>

                <!-- REPORT METADATA -->
                <div class="report-meta">
                    <div class="meta-item">
                        <div class="meta-label">Total Products</div>
                        <div class="meta-value"><?php echo $total_products; ?></div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Total Items in Stock</div>
                        <div class="meta-value"><?php echo $total_quantity; ?></div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Total Inventory Value</div>
                        <div class="meta-value">$<?php echo number_format($total_value, 2); ?></div>
                    </div>
                </div>

                <!-- INVENTORY TABLE -->
                <div class="report-table-section">
                    <h3>Product Inventory Details</h3>

                    <?php if (count($products) > 0): ?>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Stock Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['product_code']; ?></td>
                                        <td><?php echo $product['product_name']; ?></td>
                                        <td><?php echo $product['quantity']; ?></td>
                                        <td>$<?php echo number_format($product['unit_price'], 2); ?></td>
                                        <td>$<?php echo number_format($product['stock_value'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="report-totals-row">
                                    <td colspan="2">TOTAL</td>
                                    <td><?php echo $total_quantity; ?></td>
                                    <td></td>
                                    <td>$<?php echo number_format($total_value, 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="no-data-message">
                            <p>❌ No products in inventory. Add products to generate a report.</p>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- REPORT NOTES -->
                <div class="report-notes">
                    <h3>Notes:</h3>
                    <ul style="color: #555; font-size: 13px; margin-left: 20px;">
                        <li>This report contains all products currently in the inventory system.</li>
                        <li>Stock Value is calculated as: Quantity × Unit Price</li>
                        <li>Report generated: <?php echo $current_date; ?> <?php echo $current_time; ?></li>
                        <li>For the latest information, please regenerate this report.</li>
                    </ul>
                </div>

                <!-- REPORT FOOTER -->
                <div class="report-footer">
                    <p>&copy; 2026 Inventory & Stock Management System. All rights reserved.</p>
                    <p>For more information, visit the Dashboard or View Inventory page.</p>
                </div>

            </article>

        </div>
    </main>

    <!-- FOOTER (Hidden when printing) -->
    <footer class="footer screen-only">
        <div class="container">
            <p>&copy; 2026 Inventory & Stock Management System. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

<?php
mysqli_close($conn);
?>