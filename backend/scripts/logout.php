<?php
session_start();

// Закриття сесії
session_destroy();

//Видалення кукі
if (isset($_COOKIE['username'])) {
    setcookie('user_id', '', time() - 1800, "/");
    setcookie('username', '', time() - 1800, "/");
    setcookie('password', '', time() - 1800, "/");
    setcookie('email', '', time() - 1800, "/");
}

// Перенаправлення на сторінку входу
header("Location: /");
exit();
?>