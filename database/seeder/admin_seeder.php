<?php
require_once __DIR__ . '/../config/db.php';

try {
    $createTableSql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(50) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($createTableSql);

    // Seed admin user
    $username = 'Jessa Luxe Salon';
    $email = 'jls@gmail.com';
    $password = password_hash('jls1234', PASSWORD_DEFAULT);
    $role = 'admin';
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    // Check if admin user already exists
    $check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $check->execute([':email' => $email]);

    if ($check->rowCount() > 0) {
        echo "Admin user already exists.";
        exit;
    }

    // Insert admin user
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
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
