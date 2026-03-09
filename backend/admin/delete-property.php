<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit;
}

require_once __DIR__ . '/../../db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT image FROM properties WHERE id=?");
    $stmt->execute([$id]);
    $prop = $stmt->fetch();
    
    if ($prop && $prop['image']) {
        $file = __DIR__ . '/../assets/images/' . $prop['image'];
        if (file_exists($file)) {
            unlink($file);
        }
    }
}

$stmt=$pdo->prepare("DELETE FROM properties WHERE id=?");
$stmt->execute([$id]);

header("Location: dashboard.php");