<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit;
}

require_once "../../db.php";

$id = $_GET['id'] ?? null;

$stmt=$pdo->prepare("DELETE FROM properties WHERE id=?");
$stmt->execute([$id]);

header("Location: dashboard.php");