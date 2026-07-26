<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get data from form
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // VALIDATION 1: Check if all fields are empty
    if (empty($full_name) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header('Location: signup.php');
        exit();
    }

    // VALIDATION 2: Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please enter a valid email address.";
        header('Location: signup.php');
        exit();
    }

    // VALIDATION 3: Check if passwords match
    if ($password != $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header('Location: signup.php');
        exit();
    }

    // VALIDATION 4: Check password length
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long.";
        header('Location: signup.php');
        exit();
    }

    // VALIDATION 5: Check if email already exists
    $check_email = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "This email already exists. Please login instead.";
        header('Location: signup.php');
        exit();
    }

    // VALIDATION 6: Check if username already exists
    $check_username = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $check_username);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "This username already exists. Please choose a different one.";
        header('Location: signup.php');
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into database
    $insert = "INSERT INTO users (full_name, email, username, password) 
               VALUES ('$full_name', '$email', '$username', '$hashed_password')";
    
    if (mysqli_query($conn, $insert)) {
        
        // Get the user ID
        $user_id = mysqli_insert_id($conn);
        
        // Set session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['full_name'] = $full_name;
        
        // Success - redirect to profile
        header('Location: profile.php');
        exit();
        
    } else {
        $_SESSION['error'] = "Error creating account. Please try again.";
        header('Location: signup.php');
        exit();
    }

} else {
    header('Location: signup.php');
    exit();
}

mysqli_close($conn);
?>