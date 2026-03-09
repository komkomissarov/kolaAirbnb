<?php
session_start();
header('Content-Type: application/json');

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Введите email и пароль'
        ]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Неверный email или пароль'
        ]);
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    $redirect = ($user['role'] === 'admin') ? 'backend/admin/dashboard.php' : 'catalog.html';

    echo json_encode([
        'status' => 'success',
        'message' => 'Вход успешен',
        'redirect' => $redirect
    ]);
}
?>