<?php

require_once "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

$name = $data["name"];
$phone = $data["phone"];
$property = $data["property_id"];

$stmt = $pdo->prepare("
INSERT INTO bookings(property_id,name,phone)
VALUES(?,?,?)
");

$stmt->execute([$property,$name,$phone]);

echo json_encode([
"status"=>"success"
]);