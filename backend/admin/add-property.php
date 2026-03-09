<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.html');
    exit;
}

require_once __DIR__ . '../../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $city = trim($_POST['city']);
    $price = trim($_POST['price']);
    $desc = trim($_POST['description']);
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../assets/images/' . $image);
    }

    $stmt = $pdo->prepare("INSERT INTO properties (title, city, price, description, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $city, $price, $desc, $image]);

    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Добавить объект | Админка LuxRent</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; margin:0; }
header { background: #003366; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
header h1 { margin: 0; font-size: 1.5rem; }
header a { color: white; background: #c00; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; }
.container { max-width: 600px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
form input, form textarea { width: 100%; padding: 0.5rem; margin-bottom: 1rem; border: 1px solid #ccc; border-radius: 4px; }
form button { padding: 0.6rem 1.2rem; background: #003366; color: white; border: none; border-radius: 4px; cursor: pointer; }
form button:hover { background: #002244; }
</style>
</head>
<body>

<header>
    <h1>Добавить объект</h1>
    <a href="dashboard.php">Назад</a>
</header>

<div class="container">
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Название" required>
        <input type="text" name="city" placeholder="Город" required>
        <input type="number" name="price" placeholder="Цена" required>
        <input type="file" name="image">
        <textarea name="description" placeholder="Описание" required></textarea>
        <button type="submit">Добавить объект</button>
    </form>
</div>

</body>
</html>