<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../../index.html');
    exit;
}

require_once __DIR__ . '/../../db.php';

// Получаем все объекты недвижимости
$properties = $pdo->query("SELECT * FROM properties ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Админ панель LuxRent</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; margin:0; }
header { background: #003366; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
header h1 { margin: 0; font-size: 1.5rem; }
header a { color: white; background: #c00; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; }
.container { max-width: 1000px; margin: 2rem auto; }
.card { background: white; padding: 1rem; margin-bottom: 1rem; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
.card div { flex: 1; }
.card img { width: 100px; height: 70px; object-fit: cover; border-radius: 4px; margin-right: 1rem; }
.card-actions a { text-decoration: none; padding: 0.4rem 0.8rem; border-radius: 4px; margin-left: 0.5rem; color: white; }
.edit { background: #0066cc; }
.delete { background: #cc0000; }
.add-btn { display: inline-block; margin-bottom: 1rem; padding: 0.6rem 1.2rem; background: #003366; color: white; text-decoration: none; border-radius: 4px; }
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
    <a href="add-property.php" class="add-btn">Добавить новый объект</a>

    <?php if (!$properties): ?>
        <p>Объекты недвижимости не найдены.</p>
    <?php else: ?>
        <?php foreach ($properties as $p): ?>
        <div class="card">
            <div style="display:flex; align-items:center;">
                <?php if ($p['image'] && file_exists(__DIR__ . '/../assets/images/' . $p['image'])): ?>
                    <img src="../assets/images/<?= htmlspecialchars($p['image']) ?>" alt="">
                <?php else: ?>
                    <img src="../assets/images/no-image.png" alt="">
                <?php endif; ?>
                <div>
                    <b><?= htmlspecialchars($p['title']) ?></b><br>
                    <?= htmlspecialchars($p['city']) ?> — <?= htmlspecialchars($p['price']) ?> €
                </div>
            </div>
            <div class="card-actions">
                <a href="edit-property.php?id=<?= $p['id'] ?>" class="edit">Редактировать</a>
                <a href="delete-property.php?id=<?= $p['id'] ?>" class="delete" onclick="return confirm('Удалить этот объект?')">Удалить</a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>