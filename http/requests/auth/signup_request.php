<?php
session_start();
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../validation/auth/signup_validation.php';
require_once __DIR__ . '/../../controller/auth/signup_controller.php';

$username        = trim($_POST['username'] ?? '');
$email           = trim($_POST['email'] ?? '');
$password        = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

$_SESSION['old'] = [
    'username' => $username,
    'email' => $email
];

$error = validateSignUpInput($username, $email, $confirmPassword, $password);

if ($error) {
    $_SESSION['error'] = $error;
    header("Location: /signup");
    exit;
}

signUpUser($username, $email, $password, $pdo);
