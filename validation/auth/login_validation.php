<?php

function validateLoginInput(string $email, string $password): ?string
{
    if (empty($email) || empty($password)) {
        return "Please enter both email and password to log in.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    return null;
}