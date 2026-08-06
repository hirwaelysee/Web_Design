<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "financial records";
$altDbname = "financial_records";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS `financial records` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->query("CREATE DATABASE IF NOT EXISTS `financial_records` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

if (!$conn->select_db($dbname)) {
    if (!$conn->select_db($altDbname)) {
        die("Database not found: financial records or financial_records");
    }
}

$conn->set_charset('utf8mb4');

$conn->query("CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS expenses (
    expense_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    customer_fullname VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    expense_date DATE NOT NULL,
    description TEXT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash','mobile money','bank') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX(customer_phone),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
?>