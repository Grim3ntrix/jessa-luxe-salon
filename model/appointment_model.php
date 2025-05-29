<?php

require_once __DIR__ . '/../config/db.php';

function getAppointments(PDO $pdo): array 
{
    $sql  = "SELECT 
        a.id AS appointment_id,
        a.status,
        s.id AS schedule_id,
        s.schedule_date,
        s.schedule_time,
        sv.id AS service_id,
        sv.name AS service_name,
        sv.description AS service_description,
        sv.duration AS service_duration,
        sv.price AS service_price,
        u.id AS user_id,
        u.username AS user_name
    FROM appointments a
    JOIN schedules s ON a.schedule_id = s.id
    JOIN services sv ON s.service_id = sv.id
    JOIN users u ON a.user_id = u.id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $appointments = [];
    foreach ($rows as $row) {
        $appointments[] = [
            'id' => $row['appointment_id'],
            'appointment_date' => $row['schedule_date'],
            'appointment_time' => $row['schedule_time'],
            'status' => $row['status'],
            'service' => [
                'id' => $row['service_id'],
                'name' => $row['service_name'],
                'description' => $row['service_description'],
                'duration' => $row['service_duration'],
                'price' => $row['service_price']
            ],
            'user' => [
                'id' => $row['user_id'],
                'username' => $row['user_name']
            ]
        ];
    }

    return $appointments;
}

function updateAppointmentStatus(PDO $pdo, int $id, string $status): bool
{
    $sql = "UPDATE appointments SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount() > 0;
}

function appointmentCount(PDO $pdo): int
{
    $sql = "SELECT * FROM appointments";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}
