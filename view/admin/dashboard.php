<?php 
session_start();
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jessa Luxe Salon - Admin Dashboard</title>
    <link rel="stylesheet" href="/css/nav.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../navigation/admin-nav.php'; ?>

<div class="container">
    <p>Welcome, Admin!</p>

    <div class="dashboard-cards">
    <div class="dashboard-card border-violet">
        <div class="card-header">
            <span class="icon-box violet">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar">
                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                    <line x1="16" x2="16" y1="2" y2="6"/>
                    <line x1="8" x2="8" y1="2" y2="6"/>
                    <line x1="3" x2="21" y1="10" y2="10"/>
                </svg>
            </span>
            <h3>Appointments</h3>
        </div>
        <p class="card-value">120</p>
        <p class="card-description">Total appointments today</p>
    </div>

    <div class="dashboard-card border-green">
        <div class="card-header">
            <span class="icon-box green">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scissors">
                    <circle cx="6" cy="6" r="3"/>
                    <circle cx="6" cy="18" r="3"/>
                    <line x1="20" x2="8.12" y1="4" y2="15.88"/>
                    <line x1="14.47" x2="20" y1="14.48" y2="20"/>
                    <line x1="8.12" x2="12" y1="8.12" y2="12"/>
                </svg>
            </span>
            <h3>Total Services</h3>
        </div>
        <p class="card-value">35</p>
        <p class="card-description">Service offerings</p>
    </div>

    <div class="dashboard-card border-orange">
        <div class="card-header">
            <span class="icon-box orange">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign">
                    <line x1="12" x2="12" y1="2" y2="22"/>
                    <path d="M17 5H9a3 3 0 0 0 0 6h6a3 3 0 0 1 0 6H6"/>
                </svg>
            </span>
            <h3>Revenue</h3>
        </div>
        <p class="card-value">₱45,000</p>
        <p class="card-description">This day earnings</p>
    </div>
</div>

</div>

</body>
</html>
