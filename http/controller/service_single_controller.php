<?php

require_once __DIR__ . '/../../model/service_model.php';

header('Content-Type: application/json');

$service_id = $_GET['service_id'] ?? null;

if (!$service_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Service ID required']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Fetch a single service
            $service = getSalonServiceById($pdo, (int)$service_id);
            if ($service) {
                echo json_encode(['success' => true, 'data' => $service]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Service not found']);
            }
            break;

        case 'PUT':
        case 'PATCH':
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['name'], $data['description'], $data['duration'], $data['price'])) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
                exit;
            }

            $result = updateSalonService(
                $pdo,
                (int)$service_id,
                trim($data['name']),
                trim($data['description']),
                (int)$data['duration'],
                (float)$data['price']
            );

            echo json_encode(['success' => $result, 'message' => $result ? 'Service updated successfully!' : 'Failed to update service.']);
            break;

        case 'DELETE':
            $result = deleteSalonService($pdo, (int)$service_id);
            echo json_encode(['success' => $result, 'message' => $result ? 'Service deleted successfully!' : 'Failed to delete service.']);
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
