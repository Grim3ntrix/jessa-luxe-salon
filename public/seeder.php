<?php
require_once __DIR__ . '/../config/db.php';

$username = 'Jessa Luxe Salon';
$email = 'jls@gmail.com';
$password = password_hash('jls1234', PASSWORD_DEFAULT);
$role = 'admin';
$created_at = date('Y-m-d H:i:s');
$updated_at = $created_at;

$check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
$check->execute([':email' => $email]);

if ($check->rowCount() > 0) {
    echo "Admin user already exists.";
    exit;
}

$sql = "INSERT INTO users (username, email, password, role, created_at, updated_at)
        VALUES (:username, :email, :password, :role, :created_at, :updated_at)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':username' => $username,
    ':email' => $email,
    ':password' => $password,
    ':role' => $role,
    ':created_at' => $created_at,
    ':updated_at' => $updated_at
]);

echo "Admin user seeded successfully!";
