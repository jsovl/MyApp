<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_sturtup_errors', 1);
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['user_id'])): ?>
            <h1>Здравствуйте, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
            <a href="profile.php">Профиль</a> | <a href="logout.php">Выйти</a>
        <?php else: ?>
            <h1>Добро пожаловать в My App</h1>
            <a href="login.php">Войти</a> | <a href="register.php">Зарегистрироваться</a>
        <?php endif; ?>
    </div>
</body>
</html>
