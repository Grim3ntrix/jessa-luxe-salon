<?php 
session_start();
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Jessa Luxe Salon - Daily Schedules</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/css/nav.css" />
    <link rel="stylesheet" href="/css/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>
<body>

<?php include __DIR__ . '/../../navigation/client-nav.php'; ?>

<div class="container">
    <p class="intro-text">Discover Daily Schedules</p>

    <div id="schedule-cards" class="schedule-cards">
        <!-- Cards will be injected here -->
    </div>
</div>

<!-- JS -->
<script src="/js/client/schedule-table.js"></script>

</body>
</html>
