<?php
session_start();
if ($_SESSION['role'] !== 'client') {
    header("Location: ../public/index.php");
    exit;
}
echo "Welcome, Client!";
?>
<a href="../logout.php">Logout</a>