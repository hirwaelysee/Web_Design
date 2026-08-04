<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

$search_query = '';
$search_results = array();
$has_searched = false;

// Handle search request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_query = $_POST['search_query'];
    
    // VALIDATION: Check if search field is empty
    if (empty($search_query)) {
        $_SESSION['error'] = "Please enter a product code or name to search.";
        header('Location: search_product.php');
        exit();
    }

    // Search in database
    $search = "SELECT id, product_code, product_name, quantity, unit_price,
              (quantity * unit_price) as stock_value
              FROM products 
              WHERE product_code LIKE '%$search_query%' OR product_name LIKE '%$search_query%'
              ORDER BY product_name ASC";
    
    $result = mysqli_query($conn, $search);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $search_results[] = $row;
        }
        $has_searched = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Product - Inventory System</title>
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
                <li><a href="view_inventory.php"><i class="fa-solid fa-boxes-stacked"></i> View Inventory</a></li>
                <li><a href="search_product.php"  class="active"><i class="fa-solid fa-magnifying-glass"></i> Search Product</a></li>
                <li><a href="print_report.php"><i class="fa-solid fa-chart-column"></i> Print Report</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">

            <!-- ARTICLE: Search Section -->
            <article class="search-section">
                <h2>Search Product</h2>
                <p class="section-intro">Find products by code or name</p>

                <!-- Display Error/Success Message -->
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="error-box">';
                    echo '<p class="error-message">❌ ' . $_SESSION['error'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['error']);
                }

                if (isset($_SESSION['success'])) {
                    echo '<div class="success-box">';
                    echo '<p class="success-message">✓ ' . $_SESSION['success'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['success']);
                }
                ?>

                <!-- Search Form -->
                <form method="POST" action="search_product.php" class="search-form">
                    <div class="form-group">
                        <label for="search_query">Search Query *</label>
                        <input 
                            type="text" 
                            id="search_query" 
                            name="search_query" 
                            placeholder="Enter product code or name..."
                            value="<?php echo htmlspecialchars($search_query); ?>"
                            required
                        >
                        <small>Search by product code (e.g., PROD001) or product name (e.g., Laptop)</small>
                    </div>
                    <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                </form>

            </article>

            <!-- SEARCH RESULTS SECTION -->
            <?php if ($has_searched): ?>
                <article class="results-section">
                    
                    <?php if (count($search_results) > 0): ?>
                        <h3>Search Results (<?php echo count($search_results); ?> found)</h3>
                        
                        <!-- Results Summary -->
                        <div class="results-summary">
                            <?php
                            $total_qty = 0;
                            $total_val = 0;
                            foreach ($search_results as $product) {
                                $total_qty += $product['quantity'];
                                $total_val += $product['stock_value'];
                            }
                            ?>
                            <p>
                                <strong>Total Quantity:</strong> <?php echo $total_qty; ?> items | 
                                <strong>Total Value:</strong> $<?php echo number_format($total_val, 2); ?>
                            </p>
                        </div>

                        <!-- Results Table -->
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
                                    <?php foreach ($search_results as $product): ?>
                                        <tr>
                                            <td><strong><?php echo $product['product_code']; ?></strong></td>
                                            <td><?php echo $product['product_name']; ?></td>
                                            <td><?php echo $product['quantity']; ?></td>
                                            <td>$<?php echo number_format($product['unit_price'], 2); ?></td>
                                            <td>$<?php echo number_format($product['stock_value'], 2); ?></td>
                                            <td class="action-cell">
                                                <a href="update_product.php?id=<?php echo $product['id']; ?>" class="btn-action btn-update">Update</a>
                                                <a href="view_inventory.php?delete_id=<?php echo $product['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php else: ?>
                        <!-- No Results Found -->
                        <div class="no-results">
                            <h3>❌ No Products Found</h3>
                            <p>No products match your search for: <strong>"<?php echo htmlspecialchars($search_query); ?>"</strong></p>
                            <p>Try searching with:</p>
                            <ul>
                                <li>Product code (e.g., PROD001)</li>
                                <li>Product name (e.g., Laptop)</li>
                                <li>Partial matches work too (e.g., "Pro" will find "Projector")</li>
                            </ul>
                            <a href="search_product.php" class="btn btn-primary">← New Search</a>
                        </div>
                    <?php endif; ?>

                </article>
            <?php endif; ?>

            <!-- SEARCH TIPS -->
            <article class="search-tips">
                <h3>Search Tips</h3>
                <div class="tips-list">
                    <div class="tip-item">
                        <h4>📌 Exact Search</h4>
                        <p>Enter the exact product code like "PROD001"</p>
                    </div>
                    <div class="tip-item">
                        <h4>🔍 Partial Search</h4>
                        <p>Enter part of the product name like "Lap" to find "Laptop"</p>
                    </div>
                    <div class="tip-item">
                        <h4>📝 Case Insensitive</h4>
                        <p>Search is not case sensitive - "prod" = "PROD"</p>
                    </div>
                    <div class="tip-item">
                        <h4>✅ Quick Actions</h4>
                        <p>Click Update to modify stock or Delete to remove product</p>
                    </div>
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