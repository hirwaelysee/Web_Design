<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

// Get product ID from URL
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No product selected.";
    header('Location: view_inventory.php');
    exit();
}

$product_id = $_GET['id'];

// Fetch current product data
$query = "SELECT id, product_code, product_name, quantity, unit_price FROM products WHERE id = '$product_id'";
$result = mysqli_query($conn, $query);

// Check if product exists
if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Product not found.";
    header('Location: view_inventory.php');
    exit();
}

$product = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];

    // VALIDATION 1: Check if all fields are filled
    if (empty($product_name) || empty($quantity) || empty($unit_price)) {
        $_SESSION['error'] = "All fields are required.";
        header('Location: update_product.php?id=' . $product_id);
        exit();
    }

    // VALIDATION 2: Check if quantity is valid number
    if (!is_numeric($quantity) || $quantity < 0) {
        $_SESSION['error'] = "Quantity must be a valid number (0 or more).";
        header('Location: update_product.php?id=' . $product_id);
        exit();
    }

    // VALIDATION 3: Check if unit price is valid number
    if (!is_numeric($unit_price) || $unit_price < 0) {
        $_SESSION['error'] = "Unit price must be a valid number.";
        header('Location: update_product.php?id=' . $product_id);
        exit();
    }

    // Update product in database
    $update = "UPDATE products 
               SET product_name = '$product_name', quantity = '$quantity', unit_price = '$unit_price'
               WHERE id = '$product_id'";
    
    if (mysqli_query($conn, $update)) {
        $_SESSION['success'] = "Product updated successfully!";
        header('Location: view_inventory.php');
        exit();
    } else {
        $_SESSION['error'] = "Error updating product. Please try again.";
        header('Location: update_product.php?id=' . $product_id);
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product - Inventory System</title>
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

            <!-- ARTICLE: Update Product Form -->
            <article class="form-container">
                <h2>Update Product</h2>
                <p class="form-intro">Modify the product details below</p>

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

                <!-- Product Info Card -->
                <div class="product-info-card">
                    <h3>Product Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Product Code:</label>
                            <p><strong><?php echo $product['product_code']; ?></strong></p>
                            <small>(Cannot be changed)</small>
                        </div>
                        <div class="info-item">
                            <label>Current Stock Value:</label>
                            <p><strong>$<?php echo number_format($product['quantity'] * $product['unit_price'], 2); ?></strong></p>
                        </div>
                    </div>
                </div>

                <!-- Update Form -->
                <form action="update_product.php?id=<?php echo $product_id; ?>" method="POST" class="product-form">

                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="product_name">Product Name *</label>
                        <input 
                            type="text" 
                            id="product_name" 
                            name="product_name" 
                            value="<?php echo $product['product_name']; ?>"
                            required
                        >
                    </div>

                    <!-- Current Quantity -->
                    <div class="form-group">
                        <label for="quantity">Quantity *</label>
                        <div class="quantity-info">
                            <p class="current-qty">Current: <strong><?php echo $product['quantity']; ?></strong> items</p>
                        </div>
                        <input 
                            type="number" 
                            id="quantity" 
                            name="quantity" 
                            value="<?php echo $product['quantity']; ?>"
                            min="0"
                            required
                        >
                        <small>Update the number of items in stock</small>
                    </div>

                    <!-- Unit Price -->
                    <div class="form-group">
                        <label for="unit_price">Unit Price ($) *</label>
                        <div class="price-info">
                            <p class="current-price">Current: <strong>$<?php echo number_format($product['unit_price'], 2); ?></strong> per item</p>
                        </div>
                        <input 
                            type="number" 
                            id="unit_price" 
                            name="unit_price" 
                            value="<?php echo $product['unit_price']; ?>"
                            step="0.01"
                            min="0"
                            required
                        >
                        <small>Update the price per item</small>
                    </div>

                    <!-- New Stock Value Preview -->
                    <div class="stock-value-preview">
                        <h4>New Stock Value Preview:</h4>
                        <p>
                            <strong id="preview-value">
                                $<?php echo number_format($product['quantity'] * $product['unit_price'], 2); ?>
                            </strong>
                        </p>
                        <small>(Quantity × Unit Price)</small>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">Update Product</button>

                </form>

                <!-- Back Link -->
                <p class="back-link"><a href="view_inventory.php">← Back to Inventory</a></p>

            </article>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Inventory & Stock Management System. All rights reserved.</p>
        </div>
    </footer>

    <!-- SCRIPT: Live Preview of Stock Value -->
    <script>
        function updateStockValue() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            const stockValue = (quantity * unitPrice).toFixed(2);
            
            document.getElementById('preview-value').textContent = '$' + parseFloat(stockValue).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Update preview when inputs change
        document.getElementById('quantity').addEventListener('input', updateStockValue);
        document.getElementById('unit_price').addEventListener('input', updateStockValue);
    </script>

</body>
</html>

<?php
mysqli_close($conn);
?>