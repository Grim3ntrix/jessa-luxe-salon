<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/admin/dashboard':
        require_once __DIR__ . '/../view/admin/dashboard.php';
        break;
    case '/client/dashboard':
        require_once __DIR__ . '/../view/client/dashboard.php';
        break;
    case '/logout':
        require_once __DIR__ . '/../view/auth/logout.php';
        break;
    case '/login_request':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../controller/auth/login_request.php';
        }
        break;
    default:
        require_once __DIR__ . '/../view/auth/login.php';
        break;
}
