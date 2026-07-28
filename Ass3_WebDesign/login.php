<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if fields are empty
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please enter both email and password.";
        header('Location: login.php');
        exit();
    }

    // Check if email exists
    $query = "SELECT id, full_name, username, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['error'] = "Email not found. Please sign up first.";
        header('Location: login.php');
        exit();
    }

    // Get user data
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['id'];
    $user_full_name = $user['full_name'];
    $user_username = $user['username'];
    $hashed_password = $user['password'];

    // Check if password is correct
    if (password_verify($password, $hashed_password)) {
        
        // Password correct - set session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['full_name'] = $user_full_name;
        $_SESSION['username'] = $user_username;
        $_SESSION['email'] = $email;
        
        // Go to profile
        header('Location: profile.php');
        exit();
        
    } else {
        $_SESSION['error'] = "Incorrect password.";
        header('Location: login.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - User Profile System</title>
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
                <li><a href="login.php"  class="active"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
                <li><a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a></li>
                <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">

            <!-- ARTICLE: Login Form -->
            <article class="login-container">
                <h2>Login to Your Account</h2>
                <p class="intro-text">Welcome back! Enter your credentials</p>

                <!-- Display Error Message -->
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="error-box">';
                    echo '<p class="error-message">❌ ' . $_SESSION['error'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['error']);
                }
                ?>

                <form action="login.php" method="POST" class="signup-form">

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="e.g., elysee@example.com"
                            required
                        >
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">Login</button>

                </form>

                <!-- Signup Link -->
                <p class="login-link">Don't have an account? <a href="signup.php">Sign up here</a></p>

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