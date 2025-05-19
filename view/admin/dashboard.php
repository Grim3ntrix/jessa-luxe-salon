<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit;
}
echo "Welcome, Admin!";
?>
<a href="/logout">Logout</a>