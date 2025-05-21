<?php
require_once __DIR__ . '/../../../model/user_model.php';

function signUpUser(string $username, string $email, string $password, PDO $pdo): void 
{
    $role = 'client';

    try {
        $success = createUser($pdo, $username, $email, $password, $role );

        if ($success) {
            $_SESSION['success'] = "Your account has been created! You can now log in.!";
            unset($_SESSION['old']);
            header("Location: /login");
            exit;
        } else {
            $_SESSION['error'] = "Something went wrong during signup.";
        }

    } catch (PDOException $e) {
        $errorMessage = $e->getMessage();

        if ($e->getCode() === '23000') {
            $_SESSION['error'] = "Email already exists.";
        } elseif (str_contains($errorMessage, 'column') || str_contains($errorMessage, 'null')) {
            $_SESSION['error'] = "Signup failed due to a database issue.";
        } else {
            $_SESSION['error'] = "Something went wrong: " . $errorMessage;
        }

        header("Location: /signup");
        exit;
    }

}