<?php
require_once __DIR__ . '/../config/db.php';

function getScheduleById($pdo, $scheduleId) {
    $stmt = $pdo->prepare("SELECT * FROM schedules WHERE id = ?");
    $stmt->execute([$scheduleId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateScheduleStatus($pdo, $scheduleId, $status) {
    $stmt = $pdo->prepare("UPDATE schedules SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $scheduleId]);
}

function insertAppointment($pdo, $scheduleId, $userId) {
    $stmt = $pdo->prepare("INSERT INTO appointments (schedule_id, user_id, created_at) VALUES (?, ?, ?)");
    return $stmt->execute([
        $scheduleId,
        $userId,
        date('Y-m-d H:i:s')
    ]);
}
