<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

// Get total products count
$count_query = "SELECT COUNT(*) as total_products FROM products";
$count_result = mysqli_query($conn, $count_query);
$count_data = mysqli_fetch_assoc($count_result);
$total_products = $count_data['total_products'];

// Get total stock value
$value_query = "SELECT SUM(quantity * unit_price) as total_value FROM products";
$value_result = mysqli_query($conn, $value_query);
$value_data = mysqli_fetch_assoc($value_result);
$total_value = $value_data['total_value'] ? $value_data['total_value'] : 0;

// Get total items in stock
$stock_query = "SELECT SUM(quantity) as total_stock FROM products";
$stock_result = mysqli_query($conn, $stock_query);
$stock_data = mysqli_fetch_assoc($stock_result);
$total_stock = $stock_data['total_stock'] ? $stock_data['total_stock'] : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory & Stock Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- HEADER -->
    <header class="header">
        <div class="container">
            <h1>Inventory & Stock Management System</h1>
            <p class="tagline">Manage your product inventory efficiently</p>
        </div>
    </header>

    <!-- NAVIGATION -->
    <nav class="navbar">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="index.php" class="active"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>
                <li><a href="add_product.php"><i class="fa-solid fa-square-plus"></i> Add Product</a></li>
                <li><a href="view_inventory.php"><i class="fa-solid fa-boxes-stacked"></i> View Inventory</a></li>
                <li><a href="search_product.php"><i class="fa-solid fa-magnifying-glass"></i> Search Product</a></li>
                <li><a href="print_report.php"><i class="fa-solid fa-chart-column"></i> Print Report</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">

            <!-- ARTICLE: Welcome Section -->
            <article class="welcome-section">
                <h2>Welcome to Inventory Management</h2>
                <p>Manage your products, track stock levels, and generate reports all in one place.</p>
            </article>

            <!-- SECTION: Dashboard Stats -->
            <section class="stats-section">
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <p class="stat-number"><?php echo $total_products; ?></p>
                    <p class="stat-label">Products in system</p>
                </div>

                <div class="stat-card">
                    <h3>Total Stock Items</h3>
                    <p class="stat-number"><?php echo $total_stock; ?></p>
                    <p class="stat-label">Items in inventory</p>
                </div>

                <div class="stat-card">
                    <h3>Total Stock Value</h3>
                    <p class="stat-number">$<?php echo number_format($total_value, 2); ?></p>
                    <p class="stat-label">Total inventory value</p>
                </div>
            </section>

            <!-- SECTION: Quick Actions -->
            <section class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="add_product.php" class="action-btn add-btn">
                        <span class="btn-icon"><i class="fa-solid fa-plus"></i></span>
                        Add New Product
                    </a>

                    <a href="view_inventory.php" class="action-btn view-btn">
                        <span class="btn-icon"><i class="fa-solid fa-boxes-stacked"></i></span>
                        View All Products
                    </a>

                    <a href="search_product.php" class="action-btn search-btn">
                        <span class="btn-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                        Search Product
                    </a>

                    <a href="print_report.php" class="action-btn print-btn">
                        <span class="btn-icon"><i class="fa-solid fa-print"></i></span>
                        Print Report
                    </a>
                </div>
            </section>

            <!-- SECTION: Recent Products -->
            <section class="recent-products">
                <h2>Recent Products</h2>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Stock Value</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT id, product_code, product_name, quantity, unit_price,
                                     (quantity * unit_price) as stock_value
                                     FROM products 
                                     ORDER BY created_at DESC 
                                     LIMIT 5";
                            $result = mysqli_query($conn, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['product_code']; ?></td>
                                        <td><?php echo $row['product_name']; ?></td>
                                        <td><?php echo $row['quantity']; ?></td>
                                        <td>$<?php echo number_format($row['unit_price'], 2); ?></td>
                                        <td>$<?php echo number_format($row['stock_value'], 2); ?></td>
                                        <td>
                                            <a href="update_product.php?id=<?php echo $row['id']; ?>" class="btn-small">Update</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6" class="text-center">No products found. <a href="add_product.php">Add one now</a></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Inventory & Stock Management System. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

<?php
mysqli_close($conn);
?>