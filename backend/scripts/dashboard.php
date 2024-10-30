<?php
session_start();

// Перевірка, чи користувач авторизований
if(!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    header("Location: /");
    session_destroy();
    exit();
} else {
    // Отримання ID користувача
    $userID = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
    $name = $_SESSION['username'] ?? $_COOKIE['username'];
    header("Location: /home.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

<h2>Hello, <?php echo $name; ?>!</h2>
</body>
</html>