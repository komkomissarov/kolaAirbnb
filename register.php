<?php
session_start();
header('Content-Type: application/json');

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$password || !$confirm) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Все поля обязательны'
        ]);
        exit;
    }

    if ($password !== $confirm) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Пароли не совпадают'
        ]);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Пароль должен быть минимум 6 символов'
        ]);
        exit;
    }

    // проверка email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Пользователь с таким email уже существует'
        ]);
        exit;
    }

    // хэширование
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // роль
    $role = ($email === 'admin@luxrent.ru') ? 'admin' : 'user';

    // добавление
    $stmt = $pdo->prepare(
        "INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)"
    );

    if ($stmt->execute([$name, $email, $hash, $role])) {

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['role'] = $role;

        $redirect = ($role === 'admin') ? 'backend/admin/dashboard.php' : 'catalog.html';

        echo json_encode([
            'status' => 'success',
            'message' => 'Регистрация успешна',
            'redirect' => $redirect
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка регистрации'
        ]);
    }
}