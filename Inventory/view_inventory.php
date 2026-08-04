<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    $delete = "DELETE FROM products WHERE id = '$delete_id'";
    
    if (mysqli_query($conn, $delete)) {
        $_SESSION['success'] = "Product deleted successfully!";
        header('Location: view_inventory.php');
        exit();
    } else {
        $_SESSION['error'] = "Error deleting product.";
        header('Location: view_inventory.php');
        exit();
    }
}

// Get search parameter if exists
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query products
if (!empty($search)) {
    $query = "SELECT id, product_code, product_name, quantity, unit_price,
             (quantity * unit_price) as stock_value
             FROM products 
             WHERE product_code LIKE '%$search%' OR product_name LIKE '%$search%'
             ORDER BY product_name ASC";
} else {
    $query = "SELECT id, product_code, product_name, quantity, unit_price,
             (quantity * unit_price) as stock_value
             FROM products 
             ORDER BY product_name ASC";
}

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inventory - Inventory System</title>
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
                <li><a href="index.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>
                <li><a href="add_product.php"><i class="fa-solid fa-square-plus"></i> Add Product</a></li>
                <li><a href="view_inventory.php" class="active"><i class="fa-solid fa-boxes-stacked"></i> View Inventory</a></li>
                <li><a href="search_product.php"><i class="fa-solid fa-magnifying-glass"></i> Search Product</a></li>
                <li><a href="print_report.php"><i class="fa-solid fa-chart-column"></i> Print Report</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">

            <!-- ARTICLE: View Inventory -->
            <article class="inventory-section">
                <h2>All Products</h2>
                <p class="section-intro">View and manage all products in your inventory</p>

                <!-- Display Success/Error Message -->
                <?php
                if (isset($_SESSION['success'])) {
                    echo '<div class="success-box">';
                    echo '<p class="success-message">✓ ' . $_SESSION['success'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['success']);
                }

                if (isset($_SESSION['error'])) {
                    echo '<div class="error-box">';
                    echo '<p class="error-message">❌ ' . $_SESSION['error'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['error']);
                }
                ?>

                <!-- Quick Filter -->
                <div class="quick-filter">
                    <form method="GET" action="view_inventory.php" class="filter-form">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Search by product code or name..."
                            value="<?php echo $search; ?>"
                        >
                        <button type="submit" class="filter-btn">Search</button>
                        <?php if (!empty($search)): ?>
                            <a href="view_inventory.php" class="clear-btn">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Products Table -->
                <div class="table-wrapper">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Stock Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    $total_value = 0;
                                    $total_quantity = 0;
                                    
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $total_value += $row['stock_value'];
                                        $total_quantity += $row['quantity'];
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $row['product_code']; ?></strong></td>
                                            <td><?php echo $row['product_name']; ?></td>
                                            <td><?php echo $row['quantity']; ?></td>
                                            <td>$<?php echo number_format($row['unit_price'], 2); ?></td>
                                            <td>$<?php echo number_format($row['stock_value'], 2); ?></td>
                                            <td class="action-cell">
                                                <a href="update_product.php?id=<?php echo $row['id']; ?>" class="btn-action btn-update">Update</a>
                                                <a href="view_inventory.php?delete_id=<?php echo $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <!-- TOTALS ROW -->
                                    <tr class="totals-row">
                                        <td colspan="2"><strong>TOTALS</strong></td>
                                        <td><strong><?php echo $total_quantity; ?></strong></td>
                                        <td></td>
                                        <td><strong>$<?php echo number_format($total_value, 2); ?></strong></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center no-data">
                                            No products found. <a href="add_product.php">Add one now</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="inventory-actions">
                    <a href="add_product.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Product</a>
                    <a href="print_report.php" class="btn btn-secondary"><i class="fa-solid fa-print"></i> Print Report</a>
                </div>

            </article>

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