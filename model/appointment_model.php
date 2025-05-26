<?php

function getAppointments(PDO $pdo): array 
{
    $sql  = "SELECT 
        a.id,
        a.appointment_date,
        a.appointment_time,
        a.status,
        s.id AS service_id,
        s.name AS service_name,
        u.id AS user_id,
        u.username AS user_name
    FROM appointments a
    JOIN services s ON a.service_id = s.id
    JOIN users u ON a.user_id = u.id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $appointments = [];
    foreach ($rows as $row) {
        $appointments[] = [
            'id' => $row['id'],
            'appointment_date' => $row['appointment_date'],
            'appointment_time' => $row['appointment_time'],
            'status' => $row['status'],
            'service' => [
                'id' => $row['service_id'],
                'name' => $row['service_name']
            ],
            'user' => [
                'id' => $row['user_id'],
                'username' => $row['user_name']
            ]
        ];
    }

    return $appointments;
}
