<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'loggedIn' => false,
        'role' => null,
        'name' => null
    ]);
    exit;
}

require_once __DIR__ . '/../../db.php';

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

echo json_encode([
    'loggedIn' => true,
    'role' => $_SESSION['role'],
    'name' => $user ? $user['name'] : null
]);
