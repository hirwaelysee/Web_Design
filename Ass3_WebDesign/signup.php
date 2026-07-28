<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - User Profile System</title>
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
                <li><a href="signup.php" class="active"><i class="fa-solid fa-user-plus"></i> Sign Up</a></li>
                <li><a href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
                <li><a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a></li>
                <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container single-column-layout">

            <!-- the image section -->
            <div class="signup-image">
                <img src="images/signup2.avif" alt="Sign up illustration">
            </div>

            <!-- ARTICLE: Sign-Up Form -->
            <article class="signup-container">
                <h2>Create Your Account</h2>
                <p class="intro-text">Join us today and start managing your profile</p>

                <!-- Display Error Message -->
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="error-box">';
                    echo '<p class="error-message">❌ ' . $_SESSION['error'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['error']);
                }
                ?>

                <form action="signup_process.php" method="POST" class="signup-form">

                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input 
                            type="text" 
                            id="full_name" 
                            name="full_name" 
                            placeholder="e.g., Elysee Hirwa"
                            required
                        >
                    </div>

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

                    <!-- Username -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            placeholder="e.g., elysee123"
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
                            placeholder="Enter a strong password"
                            required
                        >
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            placeholder="Re-enter your password"
                            required
                        >
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">Sign Up</button>

                </form>

                <!-- Login Link -->
                <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>

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