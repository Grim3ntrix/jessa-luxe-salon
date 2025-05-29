<?php

require_once __DIR__ . '/../config/db.php';

function getSalonSchedules(PDO $pdo): array 
{
    $sql = "SELECT 
        s.id AS schedule_id,
        s.schedule_date,
        s.schedule_time,
        s.status,
        sv.id AS service_id,
        sv.name AS service_name,
        sv.description AS service_description,
        sv.duration AS service_duration,
        sv.price AS service_price
    FROM schedules s
    JOIN services sv ON s.service_id = sv.id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $schedules = [];
    foreach ($rows as $row) {
        $schedules[] = [
            'id' => $row['schedule_id'],
            'schedule_date' => $row['schedule_date'],
            'schedule_time' => $row['schedule_time'],
            'status' => $row['status'],
            'service' => [
                'id' => $row['service_id'],
                'name' => $row['service_name'],
                'description' => $row['service_description'],
                'duration' => $row['service_duration'],
                'price' => $row['service_price']
            ],
        ];
    }

    return $schedules;
}

function getSalonScheduleById(PDO $pdo, int $id): ?array 
{
    $sql = "
        SELECT schedules.*, services.name AS service_name 
        FROM schedules 
        JOIN services ON schedules.service_id = services.id
        WHERE schedules.id = :id LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
    return $schedule ?: null;
}

function addSalonSchedule(PDO $pdo, int $service_id, string $schedule_date, string $schedule_time): bool 
{
    $sql = "
        INSERT INTO schedules (service_id, schedule_date, schedule_time) 
        VALUES (:service_id, :schedule_date, :schedule_time)
    ";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':service_id' => $service_id,
        ':schedule_date' => $schedule_date,
        ':schedule_time' => $schedule_time,
    ]);
}

function updateSalonSchedule(PDO $pdo, int $id, int $service_id, string $schedule_date, string $schedule_time, string $status): bool 
{
    $sql = "
        UPDATE schedules 
        SET service_id = :service_id, schedule_date = :schedule_date, schedule_time = :schedule_time, status = :status 
        WHERE id = :id
    ";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':service_id' => $service_id,
        ':schedule_date' => $schedule_date,
        ':schedule_time' => $schedule_time,
        ':status' => $status
    ]);
}

function deleteSalonSchedule(PDO $pdo, int $id): bool 
{
    $sql = "DELETE FROM schedules WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

function scheduleCount(PDO $pdo): int
{
    $sql = "SELECT * FROM schedules WHERE status = 'available'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}
