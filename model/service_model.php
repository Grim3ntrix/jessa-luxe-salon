<?php

function getSalonServices(PDO $pdo): array 
{
    $sql  = "SELECT * FROM services";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $services;
}
