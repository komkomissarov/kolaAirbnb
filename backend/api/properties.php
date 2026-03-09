<?php

require_once "../db.php";

$stmt = $pdo->query("SELECT * FROM properties ORDER BY id DESC");

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);