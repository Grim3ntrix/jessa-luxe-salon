<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/appointment_model.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $appointmentId = $input['id'];
        $newStatus = $input['status'];

        $allowedStatuses = ['pending', 'confirmed', 'cancelled'];
        if (!in_array($newStatus, $allowedStatuses)) {
            throw new Exception('Invalid status value.');
        }

        $updated = updateAppointmentStatus($pdo, $appointmentId, $newStatus);

        if ($updated) {
            echo json_encode(['success' => true, 'message' => 'Appointment status updated.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Appointment not found or not updated.']);
        }
    } else {
        $appointment = getAppointments($pdo);
        echo json_encode(['success' => true, 'data' => $appointment]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
