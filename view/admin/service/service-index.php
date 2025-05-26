<?php 
session_start();
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jessa Luxe Salon - Services</title>
    <link rel="stylesheet" href="/css/nav.css">
    <link rel="stylesheet" href="/css/style.css">

    <!-- Tabulator CDN -->
    <link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
    <script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>

<?php include __DIR__ . '/../../navigation/admin-nav.php'; ?>

<div class="container">
    <p>Manage salon services</p>

    <div id="services-table"></div>
</div>

<!-- JS -->
<script src="/js/services-table.js"></script>

</body>
</html>
