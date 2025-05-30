<?php

require_once __DIR__ . '/../../model/appointment_model.php';
require_once __DIR__ . '/../../model/service_model.php';
require_once __DIR__ . '/../../model/schedule_model.php';

function getAppointmentCount(PDO $pdo)
{
    return $appointments = appointmentCount($pdo);
}

function getServicesCount(PDO $pdo)
{
    return $services = serviceCount($pdo);
}

function getDailySchedule(PDO $pdo)
{
    return $schedules = scheduleCount($pdo);
}