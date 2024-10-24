<?php
    require __DIR__ . '/vendor/autoload.php';
    session_start();

    // Перевірка, чи користувач вже авторизований
    if(isset($_SESSION['user_id'])) {
        header("Location: scripts/dashboard.php");
        exit();
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Backend</title>
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="./favicon.ico">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/checkout/">
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="css/form-validation.css" rel="stylesheet">
</head>
<body>
<h2>Вхід</h2>

<?php
// Вивід помилок
if(isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    echo "<p style='color:red;'>$error</p>";
}
?>

<form method="POST" action="scripts/main.php">
    <label for="username">Логін:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password" required><br>

    <input type="submit" value="Увійти">
</form>
</body>
</html>
