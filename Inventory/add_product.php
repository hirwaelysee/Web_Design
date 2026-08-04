<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data
    $product_code = $_POST['product_code'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];

    // VALIDATION 1: Check if all fields are filled
    if (empty($product_code) || empty($product_name) || empty($quantity) || empty($unit_price)) {
        $_SESSION['error'] = "All fields are required.";
        header('Location: add_product.php');
        exit();
    }

    // VALIDATION 2: Check if quantity is a valid number
    if (!is_numeric($quantity) || $quantity < 1) {
        $_SESSION['error'] = "Quantity must be a valid positive number.";
        header('Location: add_product.php');
        exit();
    }

    // VALIDATION 3: Check if unit price is a valid number
    if (!is_numeric($unit_price) || $unit_price < 0) {
        $_SESSION['error'] = "Unit price must be a valid number.";
        header('Location: add_product.php');
        exit();
    }

    // VALIDATION 4: Check if product code already exists
    $check_code = "SELECT product_code FROM products WHERE product_code = '$product_code'";
    $result = mysqli_query($conn, $check_code);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "This product code already exists. Please use a different code.";
        header('Location: add_product.php');
        exit();
    }

    // Insert product into database
    $insert = "INSERT INTO products (product_code, product_name, quantity, unit_price) 
               VALUES ('$product_code', '$product_name', '$quantity', '$unit_price')";
    
    if (mysqli_query($conn, $insert)) {
        $_SESSION['success'] = "Product added successfully!";
        header('Location: view_inventory.php');
        exit();
    } else {
        $_SESSION['error'] = "Error adding product. Please try again.";
        header('Location: add_product.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Inventory System</title>
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
                <li><a href="add_product.php" class="active"><i class="fa-solid fa-square-plus"></i> Add Product</a></li>
                <li><a href="view_inventory.php"><i class="fa-solid fa-boxes-stacked"></i> View Inventory</a></li>
                <li><a href="search_product.php"><i class="fa-solid fa-magnifying-glass"></i> Search Product</a></li>
                <li><a href="print_report.php"><i class="fa-solid fa-chart-column"></i> Print Report</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">

            <!-- ARTICLE: Add Product Form -->
            <article class="form-container">
                <h2>Add New Product</h2>
                <p class="form-intro">Fill in the details below to add a new product to your inventory</p>

                <!-- Display Error Message -->
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="error-box">';
                    echo '<p class="error-message">❌ ' . $_SESSION['error'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['error']);
                }
                ?>

                <form action="add_product.php" method="POST" class="product-form">

                    <!-- Product Code -->
                    <div class="form-group">
                        <label for="product_code">Product Code *</label>
                        <input 
                            type="text" 
                            id="product_code" 
                            name="product_code" 
                            placeholder="e.g., PROD001"
                        >
                        <small>Must be unique. No duplicates allowed.</small>
                    </div>

                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="product_name">Product Name *</label>
                        <input 
                            type="text" 
                            id="product_name" 
                            name="product_name" 
                            placeholder="e.g., Laptop"
                        >
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="quantity">Quantity *</label>
                        <input 
                            type="number" 
                            id="quantity" 
                            name="quantity" 
                            placeholder="e.g., 50"
                            min="1"
                        >
                        <small>Number of items in stock</small>
                    </div>

                    <!-- Unit Price -->
                    <div class="form-group">
                        <label for="unit_price">Unit Price ($) *</label>
                        <input 
                            type="number" 
                            id="unit_price" 
                            name="unit_price" 
                            placeholder="e.g., 999.99"
                            step="0.01"
                            min="0"
                        >
                        <small>Price per item</small>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">Add Product</button>

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

</body>
</html>

<?php
mysqli_close($conn);
?>