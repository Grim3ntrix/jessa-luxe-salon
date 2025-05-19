<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);

$user = $stmt->fetch();

if ($user && $password === $user['password']) {
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
