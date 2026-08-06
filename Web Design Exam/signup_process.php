<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signup.php');
    exit;
}

$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if ($fullName === '' || $email === '' || $username === '' || $password === '' || $confirmPassword === '') {
    header('Location: signup.php?error=' . urlencode('Please fill in all fields.'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: signup.php?error=' . urlencode('Please enter a valid email address.'));
    exit;
}

if ($password !== $confirmPassword) {
    header('Location: signup.php?error=' . urlencode('Passwords do not match.'));
    exit;
}

$stmt = $conn->prepare('SELECT user_id FROM users WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    header('Location: signup.php?error=' . urlencode('That username is already taken.'));
    exit;
}
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssss', $fullName, $email, $username, $hash);

if ($stmt->execute()) {
    header('Location: login.php?registered=1');
    exit;
}

header('Location: signup.php?error=' . urlencode('Unable to create account. Try again.'));
exit;
?>