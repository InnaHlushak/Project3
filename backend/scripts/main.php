<?php
require __DIR__ . '../../vendor/autoload.php';
session_start();
use Palmo\source\Db;

// Обробка форми входу
if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $db = new Db();
        $dbh = $db->getHandler();
        $stmt = $dbh->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if(!empty($user)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            //перевірка чи відповідає введений пароль хешу-пароля в БД
            if (password_verify($password, $user['password'])) {
                //зберігання інформації про користувача у сесії
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                header("Location: dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Invalid password";
                header("Location: /login-user.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid login";
            header("Location: /login-user.php");
            exit();
        }
    }
