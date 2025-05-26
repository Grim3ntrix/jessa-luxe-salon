<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/appointment_model.php';

header('Content-Type: application/json');

try {
    $appointment = getAppointments($pdo);
    echo json_encode(['success' => true, 'data' => $appointment]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}