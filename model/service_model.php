<?php

function getSalonServices(PDO $pdo): array 
{
    $stmt = $pdo->query("SELECT * FROM services");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSalonServiceById(PDO $pdo, int $id): ?array
{
    $sql = "SELECT * FROM services WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
    return $service ?: null;
}

function addSalonService(PDO $pdo, string $name, string $description, int $duration, float $price): bool 
{
    $sql = "INSERT INTO services (name, description, duration, price) VALUES (:name, :description, :duration, :price)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':duration' => $duration,
        ':price' => $price
    ]);
}

function updateSalonService(PDO $pdo, int $id, string $name, string $description, int $duration, float $price): bool 
{
    $sql = "UPDATE services SET name = :name, description = :description, duration = :duration, price = :price WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':name' => $name,
        ':description' => $description,
        ':duration' => $duration,
        ':price' => $price
    ]);
}

function deleteSalonService(PDO $pdo, int $id): bool 
{
    $sql = "DELETE FROM services WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}
