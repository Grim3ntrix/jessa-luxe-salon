<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Handle dynamic route first
if (preg_match('#^/api/admin/services/(\d+)$#', $uri, $matches)) {
    $_GET['service_id'] = $matches[1];
    require_once __DIR__ . '/../http/controller/service_single_controller.php';
    exit;
}

if (preg_match('#^/api/admin/schedules/(\d+)$#', $uri, $matches)) {
    $_GET['id'] = $matches[1];
    require_once __DIR__ . '/../http/controller/schedule_single_controller.php';
    exit;
}

switch ($uri) {
    // Seeder routes
    case '/db_seeder':
        require_once __DIR__ . '/../database/seeder/db_seeder.php';
        break;
    case '/adminseeder':
        require_once __DIR__ . '/../database/seeder/admin_seeder.php';
        break;
    // Admin routes
    case '/admin/profile':
        require_once __DIR__ . '/../view/partials/profile_index.php';
        break;
    case '/admin/dashboard':
        require_once __DIR__ . '/../view/admin/dashboard.php';
        break;
    case '/admin/appointments':
        require_once __DIR__ . '/../view/admin/appointment/appointment-index.php';
        break;
    case '/admin/services':
        require_once __DIR__ . '/../view/admin/service/service-index.php';
        break;
    case '/admin/schedule':
        require_once __DIR__ . '/../view/admin/schedule/schedule-index.php';
        break;
    case '/client/dashboard':
        require_once __DIR__ . '/../view/client/dashboard.php';
        break;
    // Auth routes
    case '/logout':
        require_once __DIR__ . '/../view/auth/logout.php';
        break;
    case '/signup':
        require_once __DIR__ . '/../view/auth/signup.php';
        break;
    case '/signup_request':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../http/requests/auth/signup_request.php';
        }
        break;
    case '/login_request':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../http/requests/auth/login_request.php';
        }
        break;
    // API routes
    case '/api/admin/services':
        require_once __DIR__ . '/../http/controller/services_controller.php';
        break;
    case '/api/admin/appointment':
        require_once __DIR__ . '/../http/controller/appointment_controller.php';
        break;
    case '/api/admin/appointment/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../http/controller/appointment_controller.php';
        }
        break;
    case '/api/admin/schedules':
        require_once __DIR__ . '/../http/controller/schedule_controller.php';
        break;
    default:
        require_once __DIR__ . '/../view/auth/login.php';
        break;
}
