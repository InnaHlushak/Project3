<?php
    require __DIR__ . '/vendor/autoload.php';
    session_start();

    // Перевірка, чи користувач вже авторизований
    if(isset($_SESSION['user_id'])) {
        header("Location: scripts/dashboard.php");
        exit();
    }
?>
<?php
    // Редірект на сторінку register-user.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        header('Location: register-user.php');
        exit();
    }

    // Редірект на  стартову сторінку index.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['home'])) {
        header('Location: /');
        exit();
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Register User</title>
        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="./favicon.ico">
        <style>
            .error {color: red;}
            .invalid {border: 1px solid red;}
        </style>    
        <!-- Custom styles for this template -->
        <link href="css/styles.css" rel="stylesheet">   
    </head>
    <body> 
        <header class="header-container">        
            <p>
                <img  src="../assets/favicon-NASA.png" alt="logo NASA" width="30px" height="30px">
                <a href="https://apod.nasa.gov/apod/astropix.html" target="_blank">Astronomy Picture of the Day</a>
            </p>          
            <form method="POST">
                <button type="submit" name="register" class="styleButton">Sign Up</button>
                <button type="submit" name="home" class="styleButton">HOME</button>
            </form>
        </header>
        <main class="main-container">
            <section class="containerIntrodaction">
                <h2>User login</h2>

                <div class="inputsContainer">
                    <!-- Форма реєстрації  -->
                    <form method="POST" action="scripts/main.php">
                        <div>
                            <label for="username">Name:</label>
                            <input type="text" id="username" name="username" required/>
                        </div>
                        <br>                    
                        <div>
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required/>
                        </div>
                        <br>
                        <div>
                            <input type="checkbox" id="remember" name="remember" value = "yes"/>
                            <label for="remember">Remember Me</label>
                        </div>
                        <div>
                            <!-- Вивід помилок -->
                            <?php
                            if(isset($_SESSION['error'])) {
                                $error = $_SESSION['error'];
                                echo "<p style='color:red;'>$error</p>";
                            }
                            ?>
                        </div>
                        <br>
                        <input type="submit" value="Login" class="styleButton">
                    </form>
                </div>
            </section>
        </main>            
    </body>
</html>