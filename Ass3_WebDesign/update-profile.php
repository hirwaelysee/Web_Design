<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in first.";
    header('Location: signup.php');
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get current user data from database
$query = "SELECT full_name, email, username FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    // VALIDATION: Check if fields are empty
    if (empty($full_name) || empty($email) || empty($username)) {
        $_SESSION['error'] = "All fields are required.";
        header('Location: update-profile.php');
        exit();
    }

    // VALIDATION: Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please enter a valid email address.";
        header('Location: update-profile.php');
        exit();
    }

    // VALIDATION: Check if email is already used by another user
    $check_email = "SELECT id FROM users WHERE email = '$email' AND id != '$user_id'";
    $result = mysqli_query($conn, $check_email);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "This email is already used by another user.";
        header('Location: update-profile.php');
        exit();
    }

    // VALIDATION: Check if username is already used by another user
    $check_username = "SELECT id FROM users WHERE username = '$username' AND id != '$user_id'";
    $result = mysqli_query($conn, $check_username);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "This username is already used by another user.";
        header('Location: update-profile.php');
        exit();
    }

    // Update user data in database
    $update = "UPDATE users SET full_name = '$full_name', email = '$email', username = '$username' WHERE id = '$user_id'";
    
    if (mysqli_query($conn, $update)) {
        
        // Update session data
        $_SESSION['full_name'] = $full_name;
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        
        // Set success message
        $_SESSION['success'] = "Profile updated successfully!";
        
        // Redirect to profile page
        header('Location: profile.php');
        exit();
        
    } else {
        $_SESSION['error'] = "Error updating profile. Please try again.";
        header('Location: update-profile.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - User Profile System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- HEADER -->
    <header class="header">
        <div class="container">
            <h1>User Profile System</h1>
            <p class="tagline">Manage your profile with ease</p>
        </div>
    </header>

    <!-- NAVIGATION -->
    <nav class="navbar">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="signup.php">Sign Up</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">

            <!-- ARTICLE: Update Profile Form -->
            <article class="update-container">
                <h2>Update Your Profile</h2>
                <p class="intro-text">Edit your information below</p>

                <!-- Display Error Message -->
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="error-box">';
                    echo '<p class="error-message">❌ ' . $_SESSION['error'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['error']);
                }
                ?>

                <form action="update-profile.php" method="POST" class="signup-form">

                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input 
                            type="text" 
                            id="full_name" 
                            name="full_name" 
                            value="<?php echo $user['full_name']; ?>"
                        
                        >
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?php echo $user['email']; ?>"
                            
                        >
                    </div>

                    <!-- Username -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="<?php echo $user['username']; ?>"
                            
                        >
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">Save Changes</button>

                </form>

                <!-- Back Link -->
                <p class="back-link"><a href="profile.php">← Back to Profile</a></p>

            </article>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 User Profile System. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

<?php
mysqli_close($conn);
?>