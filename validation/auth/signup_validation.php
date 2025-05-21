<?php

function validateSignUpInput(string $username, string $email, string $confirmPassword, string $password): ?string
{
   if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        return "Some fields are missing. Kindly ensure all form fields are filled in before continuing.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    if ($password !== $confirmPassword) {
        return "Passwords do not match.";
    }

    return null;
}