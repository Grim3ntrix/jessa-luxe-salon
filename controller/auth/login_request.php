<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$_SESSION['old'] = [
    'email' => $email
];

if (empty($email) || empty($password)) {
    $_SESSION['error'] = "Please enter both email and password to log in.";
    header("Location: /login");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: /login");
    exit;
}

$sql  = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);

$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'admin') {
        header("Location: /admin/dashboard");
    } else {
        header("Location: /client/dashboard");
    }
    exit;
} else {
    $_SESSION['error'] = "Invalid email or password.";
    header("Location: /");
    exit;
}
