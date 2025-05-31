<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../../model/book_schedule_model.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['schedule_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing schedule ID.']);
    exit;
}

$scheduleId = (int) $input['schedule_id'];
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Client not logged in.']);
    exit;
}

try {
    $schedule = getScheduleById($pdo, $scheduleId);

    if (!$schedule) {
        echo json_encode(['success' => false, 'message' => 'Schedule not found.']);
        exit;
    }

    if ($schedule['status'] !== 'available') {
        echo json_encode(['success' => false, 'message' => 'Schedule is not available.']);
        exit;
    }

    $updateResult = updateScheduleStatus($pdo, $scheduleId, 'booked');

    if (!$updateResult) {
        echo json_encode(['success' => false, 'message' => 'Failed to update schedule.']);
        exit;
    }

    $insertResult = insertAppointment($pdo, $scheduleId, $userId);

    if ($insertResult) {
        echo json_encode(['success' => true, 'message' => 'Appointment booked successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create appointment.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
