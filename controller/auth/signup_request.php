<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

$username        = trim($_POST['username'] ?? '');
$email           = trim($_POST['email'] ?? '');
$confirmPassword = $_POST['confirm_password'] ?? '';
$password        = $_POST['password'] ?? '';

$_SESSION['old'] = [
    'username' => $username,
    'email' => $email
];

if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
    $_SESSION['error'] = "Some fields are missing. Kindly ensure all form fields are filled in before continuing.";
    header("Location: /signup");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: /signup");
    exit;
}

if ($password !== $confirmPassword) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: /signup");
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$role = 'client';

try {
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $email, $hashedPassword, $role]);

    $_SESSION['success'] = "Your account has been created! You can now log in.!";

    unset($_SESSION['old']);

    header("Location: /login");
    exit;
} catch (PDOException $e) {
    $errorMessage = $e->getMessage();

    if ($e->getCode() === '23000') {
        $_SESSION['error'] = "Email already exists.";
    } elseif (str_contains($errorMessage, 'column') || str_contains($errorMessage, 'null')) {
        $_SESSION['error'] = "Signup failed due to a database issue: missing or invalid column value.";
    } else {
        $_SESSION['error'] = "Something went wrong: " . $errorMessage;
    }

    header("Location: /signup");
    exit;
}
