<?php 
session_start();
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jessa Luxe Salon - Profile</title>
    <link rel="stylesheet" href="/css/nav.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../navigation/admin-nav.php'; ?>

<div class="container">
<p>This is the profile</p>
</div>


<!-- rest of your page content -->

</body>
</html>
