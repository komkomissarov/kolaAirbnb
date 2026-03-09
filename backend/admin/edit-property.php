<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.html');
    exit;
}

require_once __DIR__ . '/../../db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->execute([$id]);
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$property) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $city = trim($_POST['city']);
    $price = trim($_POST['price']);
    $desc = trim($_POST['description']);
    $image = $property['image'];

    if (!empty($_FILES['image']['name'])) {
        $imageDir = __DIR__ . '/../assets/images/';
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }
        $newImage = time() . '_' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imageDir . $newImage)) {
            if ($image && file_exists($imageDir . $image)) {
                unlink($imageDir . $image);
            }
            $image = $newImage;
        }
    }

    $stmt = $pdo->prepare("UPDATE properties SET title = ?, city = ?, price = ?, description = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $city, $price, $desc, $image, $id]);

    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Редактировать объект | Админка LuxRent</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; margin:0; }
header { background: #003366; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
header h1 { margin: 0; font-size: 1.5rem; }
header a { color: white; background: #c00; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; }
.container { max-width: 600px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
form input, form textarea { width: 100%; padding: 0.5rem; margin-bottom: 1rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
form button { padding: 0.6rem 1.2rem; background: #003366; color: white; border: none; border-radius: 4px; cursor: pointer; }
form button:hover { background: #002244; }
.current-image { margin-bottom: 1rem; }
.current-image img { max-width: 200px; border-radius: 4px; }
</style>
</head>
<body>

<header>
    <h1>Редактировать объект</h1>
    <a href="dashboard.php">Назад</a>
</header>

<div class="container">
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Название" value="<?= htmlspecialchars($property['title']) ?>" required>
        <input type="text" name="city" placeholder="Город" value="<?= htmlspecialchars($property['city']) ?>" required>
        <input type="number" name="price" placeholder="Цена" value="<?= htmlspecialchars($property['price']) ?>" required>
        
        <?php if ($property['image'] && file_exists(__DIR__ . '/../assets/images/' . $property['image'])): ?>
            <div class="current-image">
                <p>Текущее фото:</p>
                <img src="../assets/images/<?= htmlspecialchars($property['image']) ?>" alt="">
            </div>
        <?php endif; ?>
        
        <label>Загрузить новое фото (оставьте пустым, чтобы не менять):</label>
        <input type="file" name="image">
        
        <textarea name="description" placeholder="Описание" required rows="5"><?= htmlspecialchars($property['description']) ?></textarea>
        <button type="submit">Сохранить изменения</button>
    </form>
</div>

</body>
</html>
