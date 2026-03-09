<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../../index.html');
    exit;
}

require_once __DIR__ . '/../../db.php';

// Получаем всех пользователей
$users = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Админ панель LuxRent - Пользователи</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; margin:0; }
header { background: #003366; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
header h1 { margin: 0; font-size: 1.5rem; }
header a { color: white; background: #c00; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; }
.container { max-width: 1000px; margin: 2rem auto; }
table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #ddd; }
th { background-color: #003366; color: white; }
tr:hover { background-color: #f1f1f1; }
</style>
</head>
<body>

<header>
    <h1>Админ панель LuxRent</h1>
    <div>
        <a href="dashboard.php" style="background: #004080; margin-right: 10px;">Недвижимость</a>
        <a href="users.php" style="background: #004080; margin-right: 10px;">Пользователи</a>
        <a href="logout.php">Выйти</a>
    </div>
</header>

<div class="container">
    <h2>Список пользователей</h2>
    <?php if (!$users): ?>
        <p>Пользователи не найдены.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Дата регистрации</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id']) ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td><?= htmlspecialchars($u['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
