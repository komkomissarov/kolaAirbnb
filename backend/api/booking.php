<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Неверный формат данных"]);
    exit;
}

$name = trim($data["name"] ?? '');
$phone = trim($data["phone"] ?? '');
$property_id = $data["property_id"] ?? null;

if (empty($name) || empty($phone) || empty($property_id)) {
    echo json_encode(["status" => "error", "message" => "Пожалуйста, заполните все поля"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO bookings (property_id, name, phone) VALUES (?, ?, ?)");
    $stmt->execute([$property_id, $name, $phone]);

    echo json_encode(["status" => "success", "message" => "Заявка успешно отправлена!"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Ошибка при сохранении заявки"]);
}
