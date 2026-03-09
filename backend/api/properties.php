<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../db.php';

$city = $_GET['city'] ?? 'all';
$sql = "SELECT * FROM properties";
$params = [];

if ($city !== 'all') {
    $sql .= " WHERE city = ?";
    $params[] = $city;
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);