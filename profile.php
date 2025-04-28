<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $db = new PDO('pgsql:host=postgres;dbname=myapp', 'admin', 'secret');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $db->prepare("SELECT id, username FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка базы данных: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать, <?= htmlspecialchars($user['username']) ?>!</h1>
        <p>ID пользователя: <?= htmlspecialchars($user['id']) ?></p>
        
        <a href="logout.php">Выйти</a> |
        <a href="/">На главную</a>
    </div>
</body>
</html>
