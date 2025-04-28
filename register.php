<?php
// Включение буферизации вывода
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo '<title>Register</title>';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new PDO('pgsql:host=postgres;dbname=myapp', 'admin', 'secret');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if (empty($_POST['username'])) {
            throw new Exception('Имя пользователя не может быть пустым');
        }
        
        if (empty($_POST['password'])) {
            throw new Exception('Пароль не может быть пустым');
        }

        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([
            $_POST['username'], 
            password_hash($_POST['password'], PASSWORD_BCRYPT)
        ]);
        
        // Очищаем буфер перед перенаправлением
        ob_end_clean();
        header("Location: login.php");
        exit;
        
    } catch (PDOException $e) {
        // Выводим ошибки только после очистки буфера
        ob_end_clean();
        die("Ошибка базы данных: " . $e->getMessage());
    } catch (Exception $e) {
        ob_end_clean();
        die("Ошибка: " . $e->getMessage());
    }
}

// Отладочный вывод только для GET-запросов
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Зарегистрироваться</button>
    </form>';
}

// Выводим буфер (если не было перенаправления)
ob_end_flush();
?>
