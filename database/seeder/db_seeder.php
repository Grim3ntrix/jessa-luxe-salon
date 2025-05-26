<?php
require_once __DIR__ . '/../../config/db.php';

$dbName = 'jessa_luxe_salon';

try {
    // 1. Drop the database if exists (like migrate:fresh)
    $pdo->exec("DROP DATABASE IF EXISTS `$dbName`");
    echo "Database dropped if existed.\n" . "<br/>";

    // 2. Create the database
    $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci");
    echo "Database created.\n" . "<br/>";

    // 3. Select the database
    $pdo->exec("USE `$dbName`");
    echo "Using database $dbName.\n" . "<br/>";

    // 4. Create tables
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
        CREATE TABLE IF NOT EXISTS `appointments` (
          `id` int NOT NULL AUTO_INCREMENT,
          `service_id` int NOT NULL,
          `user_id` int DEFAULT NULL COMMENT 'Filled when client books the slot',
          `appointment_date` date NOT NULL,
          `appointment_time` time NOT NULL,
          `status` enum('available','booked','cancelled') DEFAULT 'available',
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `service_id` (`service_id`),
          KEY `user_id` (`user_id`),
          CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
          CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
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

    // 5. Insert seed data for users
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

    // 6. Insert seed data for services (example)
    $pdo->exec("
        INSERT INTO `services` (`name`, `description`, `duration`, `price`, `created_at`, `updated_at`) VALUES
        ('Haircut', 'Standard haircut service', 30, 15.00, NOW(), NOW()),
        ('Hair Coloring', 'Professional hair coloring', 90, 45.00, NOW(), NOW())
    ");
    echo "Services seeded.\n" . "<br/>";

    // You can add more seed inserts for appointments, profile etc similarly

    echo "Database seeding finished.\n" . "<br/><br/>";

    echo "Accounts:\n" . "<br/><br/>";

    echo "(Admin) Username: jls@gmail.com, Password: jls1234\n" . "<br/><br/>";
    echo "(Client) Username: client@gmail.com, Password: c1234\n" . "<br/><br/>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
