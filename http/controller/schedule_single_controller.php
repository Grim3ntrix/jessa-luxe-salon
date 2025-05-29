<?php
require_once __DIR__ . '/../../model/schedule_model.php';

header('Content-Type: application/json');

$schedule_id = $_GET['id'] ?? null;

if (!$schedule_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Schedule ID required']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Fetch a single schedule by ID
            $schedule = getSalonScheduleById($pdo, (int)$schedule_id);
            if ($schedule) {
                echo json_encode(['success' => true, 'data' => $schedule]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Schedule not found']);
            }
            break;

        case 'PUT':
        case 'PATCH':
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['service_id'], $data['schedule_date'], $data['schedule_time'], $data['schedule_status'])) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
                exit;
            }

            $status = $data['schedule_status'] ?? 'available';

            $result = updateSalonSchedule(
                $pdo,
                (int)$schedule_id,
                (int)$data['service_id'],
                $data['schedule_date'],
                $data['schedule_time'],
                $status
            );

            echo json_encode(['success' => $result, 'message' => $result ? 'Schedule updated successfully!' : 'Failed to update schedule.']);
            break;

        case 'DELETE':
            $result = deleteSalonSchedule($pdo, (int)$schedule_id);
            echo json_encode(['success' => $result, 'message' => $result ? 'Schedule deleted successfully!' : 'Failed to delete schedule.']);
            break;

        default:
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
