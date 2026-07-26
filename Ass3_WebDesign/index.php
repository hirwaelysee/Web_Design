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
    <title>User Profile System - Home</title>
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
                <li><a href="index.php" class="active">Home</a></li>
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
            
            <!-- ARTICLE: Main Content -->
            <article class="hero-section">
                <h2>Welcome to User Profile System</h2>
                <p>Create an account, manage your profile, and keep your information secure.</p>
                <a href="signup.php" class="btn">Get Started</a>
            </article>

            <div class="content-wrapper">
                
                <!-- SECTION: Features -->
                <section class="features">
                    <h2>Features</h2>
                    <div class="feature-list">
                        <div class="feature-item">
                            <h3>Easy Sign Up</h3>
                            <p>Create your account in minutes with just basic information.</p>
                        </div>
                        <div class="feature-item">
                            <h3>Secure Password</h3>
                            <p>Your password is encrypted and stored securely.</p>
                        </div>
                        <div class="feature-item">
                            <h3>Manage Profile</h3>
                            <p>View and update your profile information anytime.</p>
                        </div>
                    </div>
                </section>

                <!-- ASIDE: Helpful Tips -->
                <aside class="sidebar">
                    <h3>Helpful Tips</h3>
                    <ul>
                        <li>Use a strong password with numbers and symbols</li>
                        <li>Keep your email address up to date</li>
                        <li>Review your profile regularly</li>
                        <li>Never share your login credentials</li>
                    </ul>
                </aside>

            </div>

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