<?php
$message = '';
if (isset($_GET['error'])) {
    $message = urldecode($_GET['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - User Profile System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="card">
        <h2><i class="fa-solid fa-user-plus"></i> Create Account</h2>
        <?php if ($message): ?>
            <div class="message error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form action="signup_process.php" method="POST" id="signupForm">
            <div class="form-group">
                <label for="full_name"><i class="fa-solid fa-id-badge"></i> Full Name</label>
                <input type="text" id="full_name" name="full_name" placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label for="email"><i class="fa-solid fa-envelope"></i> Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email address">
            </div>
            <div class="form-group">
                <label for="username"><i class="fa-solid fa-user"></i> Username</label>
                <input type="text" id="username" name="username" placeholder="Choose a username">
            </div>
            <div class="form-group">
                <label for="password"><i class="fa-solid fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" placeholder="Create a password">
            </div>
            <div class="form-group">
                <label for="confirm_password"><i class="fa-solid fa-lock"></i> Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password">
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <p class="small-text">Already have an account? <a href="logout.php">Login here</a></p>
    </div>
    <script src="script.js"></script>
</body>
</html>