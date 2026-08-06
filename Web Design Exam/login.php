<?php
session_start();
include 'db.php';

if (isset($_SESSION['username']) || isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['username'], $_SESSION['user_id'])) {
        session_unset();
        session_destroy();
        session_start();
    } else {
        header('Location: management.php');
        exit;
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $conn->prepare('SELECT user_id, fullname, email, password FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userId, $fullName, $email, $passwordHash);
            $stmt->fetch();

            if (password_verify($password, $passwordHash)) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                header('Location: management.php');
                exit;
            }
        }

        $error = 'Invalid username or password. Please try again or sign up.';
    }
}

$success = '';
if (isset($_GET['registered'])) {
    $success = 'Registration successful. Please login.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Login Page</title>
</head>
<body>
    <div class="card">
        <h2><i class="fa-solid fa-right-to-bracket"></i> Login</h2>
        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php" id="loginForm">
            <div class="form-group">
                <label for="username"><i class="fa-solid fa-user"></i> Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username">
            </div>
            <div class="form-group">
                <label for="password"><i class="fa-solid fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p class="small-text">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
    <script src="script.js"></script>
</body>
</html>