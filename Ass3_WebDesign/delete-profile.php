<?php
session_start();
require_once 'config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in first.";
    header('Location: signup.php');
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Delete user from database
$delete = "DELETE FROM users WHERE id = '$user_id'";

if (mysqli_query($conn, $delete)) {
    
    // Destroy session
    session_destroy();
    
    // Set message in new session
    session_start();
    $_SESSION['message'] = "Your account has been deleted successfully.";
    
    // Redirect to home page
    header('Location: index.php');
    exit();
    
} else {
    $_SESSION['error'] = "Error deleting account. Please try again.";
    header('Location: profile.php');
    exit();
}

mysqli_close($conn);
?>