<?php

function loginUser(string $email, string $password, PDO $pdo): void
{
    $sql  = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        $redirect = $user['role'] === 'admin' ? "/admin/dashboard" : "/client/dashboard";
        header("Location: $redirect");
        exit;
    }

    $_SESSION['error'] = "Invalid email or password.";
    header("Location: /login");
    exit;
}
