<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/service_model.php';

header('Content-Type: application/json');

try {
    $services = getSalonServices($pdo);
    echo json_encode(['success' => true, 'data' => $services]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}