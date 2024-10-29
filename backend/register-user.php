<?php
    session_start();    

    // Редірект на  сторінку login-user.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        header('Location: /login-user.php');
        exit();
    }

    // Редірект на  стартову сторінку index.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['home'])) {
        header('Location: /');
        exit();
    }
?>

<?php
    require __DIR__ . '/vendor/autoload.php';

    use Palmo\source\Db;
    use Palmo\source\validation\Validator;
    use Palmo\source\validation\validators\StringValidator;
    use Palmo\source\validation\validators\EmailValidator;
    use Palmo\source\validation\validators\PasswordValidator;

    //Валідація даних із форми реєстрації
    $errors = [];
    $data = [];
    $successfully = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $validator = new Validator();

        $data['username'] = [
            'type' => 'string',
            'data'=> $_POST['username'] ?? '',
        ]; 

        $data['email'] = [
            'type' => 'email',
            'data'=> $_POST['email'] ?? '',
        ]; 

        $data['password'] = [
            'type' => 'password',
            'data'=> $_POST['password'] ?? '',
        ]; 

        $validator->addValidator('string', new StringValidator(3,15)); 
        $validator->addValidator('email', new EmailValidator());
        $validator->addValidator('password', new PasswordValidator(6));       

        $errors = $validator->validate($data);

        //Якщо валідація успішна, то дані заносяться у БД в таблицю users
        if (empty($errors)) {
            $username = $_POST["username"];   
            $password = $_POST["password"];
            //хешування пароля
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $email = $_POST["email"];
            $created_at = date("Y-m-d H:i:s");

            //попередня перевідка чи такий користувач уже не зареєстрований в БД
            $db = new Db();
            $dbh = $db->getHandler();
            $stmt1 = $dbh->prepare("SELECT * FROM users WHERE username = :username");
            $stmt1->bindParam(':username', $username);
            $stmt1->execute();
            $user = $stmt1->fetch(PDO::FETCH_ASSOC);

            if(!empty($user)) {
                if ($user['email'] === $email) {
                    $error = "Such user is already registered.";
                } 
            } else {
                //зберегти інформацію про користувача в БД
                $sql = "INSERT INTO users (username, password, email, created_at)
                VALUES (:username, :password, :email, :created_at)";
                $stmt2 = $dbh->prepare($sql);
                $stmt2->bindParam(':username', $username);
                $stmt2->bindParam(':password', $hash);
                $stmt2->bindParam(':email', $email);
                $stmt2->bindParam(':created_at', $created_at);
                $stmt2->execute();

                $successfully = 'You are successfully registered! Go to the Sign In page and re-enter your name and password';                
            }
        } 
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
            .successfully {color: green;}
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
                <button type="submit" name="login" class="styleButton">Sign In</button>
                <button type="submit" name="home" class="styleButton">HOME</button>
            </form>
        </header>
        <main class="main-container">
            <section class="containerIntrodaction">
                <h2>User registration </h2>
                
                <?php
                // Вивід помилок
                if (isset($error)) {
                    echo "<p style='color:red;'>$error</p>";
                }
                ?>
            
                <div class="inputsContainer">
                    <form method="POST" enctype="multipart/form-data" action=''>
                        <div>
                            <label for="username">Name:</label>
                            <input type="text" id="username" name="username" required
                                value="<?= htmlspecialchars($data['username']['data'] ?? '') ?>" 
                                <?= isset($errors['username']) ? 'class="invalid"' : '' ?> 
                            />
                            <?php if (isset($errors['username'])): ?><span class="error"><?= $errors['username'] ?></span><?php endif; ?>
                        </div>
                        <br>                    
                        <div>
                            <label for="email">E-mail:</label>
                            <input type="email" id="password" name="email" required
                                value="<?= htmlspecialchars($data['email']['data'] ?? '') ?>" 
                                <?= isset($errors['email']) ? 'class="invalid"' : '' ?> 
                            />
                            <?php if (isset($errors['email'])): ?><span class="error"><?= $errors['email'] ?></span><?php endif; ?>
                        </div>
                        <br>
                        <div>
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required
                                value="<?= htmlspecialchars($data['password']['data'] ?? '') ?>" 
                                <?= isset($errors['password']) ? 'class="invalid"' : '' ?> 
                            />
                            <?php if (isset($errors['password'])): ?><span class="error"><?= $errors['password'] ?></span><?php endif; ?>
                        </div>
                        <br>
                        <input type="submit" value="SignUp" class="styleButton">
                        <p class="successfully"><?= $successfully ?></p>
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
