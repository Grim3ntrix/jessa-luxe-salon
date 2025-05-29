<?php
require_once __DIR__ . '/../../config/db.php';

$dbName = 'jessa_luxe_salon';

try {

    $pdo->exec("DROP DATABASE IF EXISTS `$dbName`");
    echo "Database dropped if existed.\n" . "<br/>";


    $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci");
    echo "Database created.\n" . "<br/>";


    $pdo->exec("USE `$dbName`");
    echo "Using database $dbName.\n" . "<br/>";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
          `id` int NOT NULL AUTO_INCREMENT,
          `username` varchar(50) NOT NULL,
          `email` varchar(100) NOT NULL,
          `password` varchar(255) NOT NULL,
          `role` enum('admin','client') NOT NULL,
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `services` (
          `id` int NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          `description` text,
          `duration` int NOT NULL COMMENT 'Duration in minutes',
          `price` decimal(10,2) NOT NULL,
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `schedules` (
            `id` int NOT NULL AUTO_INCREMENT,
            `service_id` int NOT NULL,
            `schedule_date` date NOT NULL,
            `schedule_time` time NOT NULL,
            `status` enum('available', 'booked', 'completed', 'blocked', 'cancelled') DEFAULT 'available',
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `service_id` (`service_id`),
            CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `appointments` (
            `id` int NOT NULL AUTO_INCREMENT,
            `schedule_id` int NOT NULL,
            `user_id` int NOT NULL,
            `status` enum('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `schedule_id` (`schedule_id`),
            KEY `user_id` (`user_id`),
            CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
            CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `profile` (
          `id` int NOT NULL AUTO_INCREMENT,
          `user_id` int NOT NULL,
          `first_name` varchar(100) DEFAULT NULL,
          `middle_name` varchar(100) DEFAULT NULL,
          `last_name` varchar(100) DEFAULT NULL,
          `sex` enum('male','female','other') DEFAULT NULL,
          `date_of_birth` date DEFAULT NULL,
          `motto` varchar(255) DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `user_id` (`user_id`),
          CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
    ");

    echo "Tables created successfully.\n" . "<br/>";

    $insertUsers = "
        INSERT INTO `users` (`username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
        ('Jessa Luxe Salon', 'jls@gmail.com', :pass1, 'admin', NOW(), NOW()),
        ('Client', 'client@gmail.com', :pass2, 'client', NOW(), NOW())
    ";
    $stmt = $pdo->prepare($insertUsers);
    $stmt->execute([
        ':pass1' => password_hash('jls1234', PASSWORD_DEFAULT),
        ':pass2' => password_hash('c1234', PASSWORD_DEFAULT)
    ]);
    echo "Users seeded.\n";

    $pdo->exec("
        INSERT INTO `services` (`name`, `description`, `duration`, `price`, `created_at`, `updated_at`) VALUES
        ('Haircut', 'Standard haircut service', 30, 15.00, NOW(), NOW()),
        ('Hair Coloring', 'Professional hair coloring', 90, 45.00, NOW(), NOW()),
        ('Hair Rebonding', 'Straightening and smoothing treatment', 180, 120.00, NOW(), NOW()),
        ('Hair Perm', 'Create long-lasting curls or waves', 150, 100.00, NOW(), NOW()),
        ('Hair Treatment', 'Deep conditioning treatment', 60, 35.00, NOW(), NOW()),
        ('Blow Dry', 'Professional hair blow-dry styling', 30, 20.00, NOW(), NOW()),
        ('Hair Spa', 'Relaxing hair spa for nourishment', 60, 50.00, NOW(), NOW()),
        ('Manicure', 'Classic manicure with polish', 45, 25.00, NOW(), NOW()),
        ('Pedicure', 'Classic pedicure with polish', 60, 30.00, NOW(), NOW()),
        ('Gel Manicure', 'Gel-based manicure with extended wear', 60, 40.00, NOW(), NOW()),
        ('Gel Pedicure', 'Gel-based pedicure with extended wear', 75, 45.00, NOW(), NOW()),
        ('Facial', 'Basic facial treatment for skin rejuvenation', 60, 50.00, NOW(), NOW()),
        ('Body Massage', 'Relaxing full body massage', 90, 70.00, NOW(), NOW()),
        ('Waxing - Arms', 'Waxing service for arms', 45, 30.00, NOW(), NOW()),
        ('Waxing - Legs', 'Waxing service for legs', 60, 40.00, NOW(), NOW()),
        ('Makeup', 'Professional makeup application for events', 90, 100.00, NOW(), NOW())
    ");
    echo "Services seeded.\n" . "<br/>";

    $pdo->exec("
        INSERT INTO `schedules` (`service_id`, `schedule_date`, `schedule_time`, `status`, `created_at`, `updated_at`) VALUES
        (1, '2025-06-01', '10:00:00', 'available', NOW(), NOW()),
        (1, '2025-06-01', '11:00:00', 'available', NOW(), NOW()),
        (2, '2025-06-01', '12:00:00', 'available', NOW(), NOW())
    ");
    echo "Schedule seeded.\n" . "<br/>";

    $pdo->exec("
        INSERT INTO `appointments` (`schedule_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
        (1, 2, 'confirmed', NOW(), NOW())
    ");
    echo "Appointments seeded.\n" . "<br/>";

    echo "Database seeding finished.\n" . "<br/><br/>";

    echo "Accounts:\n" . "<br/><br/>";

    echo "(Admin) Username: jls@gmail.com, Password: jls1234\n" . "<br/><br/>";
    echo "(Client) Username: client@gmail.com, Password: c1234\n" . "<br/><br/>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
