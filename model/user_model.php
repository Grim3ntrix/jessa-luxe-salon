<?php

require_once __DIR__ . '/../config/db.php';

function findUserByEmail(PDO $pdo, string $email): ?array
{
    $sql  = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function createUser( PDO $pdo, string $username, string $email, string $password, string $role ): bool
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    return $stmt->execute([$username, $email, $hashedPassword, $role]);
}