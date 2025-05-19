<?php
$host = 'localhost';
$db = 'jessa_luxe_salon';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    echo "DB connection failed: " . $e->getMessage();
    exit;
}
