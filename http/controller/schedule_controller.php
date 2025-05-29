<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/schedule_model.php';

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $schedules = getSalonSchedules($pdo);
        echo json_encode(['success' => true, 'data' => $schedules]);

    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['service_id'], $data['schedule_date'], $data['schedule_time'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
            exit;
        }

        $result = addSalonSchedule(
            $pdo,
            (int) $data['service_id'],
            trim($data['schedule_date']),
            trim($data['schedule_time'])
        );

            echo json_encode(['success' => $result, 'message' => $result ? 'Schedule added successfully!' : 'Failed to add schedule.']);

    } elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $_GET['id'] ?? null; // or from URL parameter, or $data['id']
        // Or you get $id from URL (usually REST API: PUT /api/admin/schedules/{id})
        // So you may need to pass $id from query or route param.

        // Since your JS calls: axios.put(`/api/admin/schedules/${scheduleId}`, scheduleData)
        // The id is in URL, so parse it from URL

        // If your API is at e.g. /api/admin/schedules/{id}, you should get $id from URL, like you do in schedule_single_controller.php.

        // So to fix, get id from $_GET or from the URL path

        // For now, get id from $_GET (or wherever)
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Schedule ID required.']);
            exit;
        }

        if (!isset($data['service_id'], $data['schedule_date'], $data['schedule_time'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields for update.']);
            exit;
        }

        $status = $data['status'];

        $result = updateSalonSchedule(
            $pdo,
            (int)$id,
            (int)$data['service_id'],
            trim($data['schedule_date']),
            trim($data['schedule_time']),
            $status
        );

        echo json_encode(['success' => $result, 'message' => $result ? 'Schedule updated successfully!' : 'Failed to update schedule.']);
    } elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing schedule ID.']);
            exit;
        }

        $result = deleteSalonSchedule($pdo, (int) $data['id']);

        echo json_encode(['success' => $result, 'message' => $result ? 'Schedule deleted successfully!' : 'Failed to delete schedule.']);

    } else {
        echo json_encode(['success' => false, 'message' => 'Unsupported request method.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
