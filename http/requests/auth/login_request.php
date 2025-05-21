<?php
session_start();
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../validation/auth/login_validation.php';
require_once __DIR__ . '/../../controller/auth/login_controller.php';

$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$_SESSION['old'] = ['email' => $email];

$error = validateLoginInput($email, $password);

if ($error) {
    $_SESSION['error'] = $error;
    header("Location: /login");
    exit;
}

loginUser($email, $password, $pdo);
