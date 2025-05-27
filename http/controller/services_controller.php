<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/service_model.php';

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $services = getSalonServices($pdo);
        echo json_encode(['success' => true, 'data' => $services]);

    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['name'], $data['description'], $data['duration'], $data['price'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
            exit;
        }

        $result = addSalonService(
            $pdo,
            trim($data['name']),
            trim($data['description']),
            (int) $data['duration'],
            (float) $data['price']
        );

        echo json_encode(['success' => $result, 'message' => $result ? 'Service added successfully!' : 'Failed to add service.']);

    } elseif ($method === 'PUT') {
        parse_str(file_get_contents(filename: "php://input"), $put_vars);

        $id = $put_vars['id'] ?? null;
        $name = $put_vars['name'] ?? null;
        $description = $put_vars['description'] ?? null;
        $duration = $put_vars['duration'] ?? null;
        $price = $put_vars['price'] ?? null;

        if (!$id || !$name || !$description || !$duration || !$price) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields for update.']);
            exit;
        }

        $result = updateSalonService(
            $pdo,
            (int) $id,
            trim($name),
            trim($description),
            (int) $duration,
            (float) $price
        );

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Service updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update service.']);
        }
    } elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing service ID.']);
            exit;
        }

        $result = deleteSalonService($pdo, (int) $data['id']);

        echo json_encode(['success' => $result, 'message' => $result ? 'Service deleted successfully!' : 'Failed to delete service.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Unsupported request method.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
