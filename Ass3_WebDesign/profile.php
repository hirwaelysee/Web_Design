<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in first.";
    header('Location: login.php');
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get user data from database
$query = "SELECT full_name, email, username, created_at FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

// Check if user exists
if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "User not found.";
    header('Location: login.php');
    exit();
}

// Get user data
$user = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - User Profile System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <!-- HEADER -->
    <header class="header">
        <img src="images/logo.jpg" alt="System Logo" class="header-logo" />
        <div class="container">
            <h1>User Profile System</h1>
            <p class="tagline">Manage your profile with ease</p>
        </div>
    </header>

    <!-- NAVIGATION -->
    <nav class="navbar">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="signup.php"><i class="fa-solid fa-user-plus"></i> Sign Up</a></li>
                <li><a href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
                <li><a href="profile.php" class="active"><i class="fa-solid fa-user"></i> Profile</a></li>
                <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">

            <!-- ARTICLE: User Profile -->
            <article class="profile-container">
                
                <!-- Display Success Message -->
                <?php
                if (isset($_SESSION['success'])) {
                    echo '<div class="success-box">';
                    echo '<p class="success-message">✓ ' . $_SESSION['success'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['success']);
                }
                ?>

                <!-- Welcome Message -->
                <div class="welcome-section">
                    <img src="images/profile.png" alt="Profile illustration" />
                    <h2>Welcome, <?php echo $user['full_name']; ?>!</h2>
                    <p class="welcome-text">Here is your profile information</p>
                </div>

                <!-- Profile Information -->
                <div class="profile-info">
                    
                    <div class="info-group">
                        <label>Full Name:</label>
                        <p><?php echo $user['full_name']; ?></p>
                    </div>

                    <div class="info-group">
                        <label>Email:</label>
                        <p><?php echo $user['email']; ?></p>
                    </div>

                    <div class="info-group">
                        <label>Username:</label>
                        <p><?php echo $user['username']; ?></p>
                    </div>

                    <div class="info-group">
                        <label>Member Since:</label>
                        <p><?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="profile-actions">
                    <a href="update-profile.php" class="btn btn-update">Update Profile</a>
                    <a href="delete-profile.php" class="btn btn-delete" onclick="return confirm('Are you sure? This cannot be undone.');">Delete Account</a>
                </div>

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