<?php
// Включение буферизации вывода
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new PDO('pgsql:host=postgres;dbname=myapp', 'admin', 'secret');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$_POST['username']]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            // Очищаем буфер перед перенаправлением
            ob_end_clean();
            header("Location: profile.php");
            exit;
        } else {
            $error = "Неверный логин или пароль";
        }
    } catch (PDOException $e) {
        $error = "Ошибка базы данных: " . $e->getMessage();
    }
}
// Закрываем буфер и выводим содержимое
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .error { color: red; margin-bottom: 15px; }
        input, button { display: block; margin: 10px 0; padding: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Вход</button>
        </form>
        
        <p>Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
    </div>
</body>
</html>
