<?php

require_once "../db.php";

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM properties WHERE id=?");
$stmt->execute([$id]);

$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($data);