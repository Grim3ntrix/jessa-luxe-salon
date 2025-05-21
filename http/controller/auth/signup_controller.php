<?php

function signUpUser(string $username, string $email, string $password, PDO $pdo): void 
{
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

}