<?php
session_start();
    //отримання значень username i email із кукі/сесії
    if (isset($_COOKIE['username'])) {
        $username = $_COOKIE['username'];
    } else {
        $username = $_SESSION['username'] ?? '';
    }

    if (isset($_COOKIE['email'])) {
        $email = $_COOKIE['email'];
    } else {
        $email = $_SESSION['email'] ?? '';
    }
 
    // Редірект на сторінку home.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['home'])) {
        header('Location: home.php');
        exit();
    }
?>

<?php
    require __DIR__ . '/vendor/autoload.php';
    //валідація даних отриманих із форми
    use Palmo\source\validation\Validator;
    use Palmo\source\validation\validators\StringValidator;
    use Palmo\source\validation\validators\EmailValidator;
    use Palmo\source\validation\validators\NumberValidator;
    use Palmo\source\validation\validators\FloatValidator;
    use Palmo\source\validation\validators\ArrayValidator;
    use Palmo\source\validation\validators\RadioValidator;
    use Palmo\source\validation\validators\FileValidator;
    use Palmo\source\validation\validators\CheckboxValidator;

    $errors = [];
    $data = [];
    $successfully = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $validator = new Validator();

        $data['username'] = [
            'type' => 'string',
            'data'=>  $username,
        ]; 

        $data['email'] = [
            'type' => 'email',
            'data'=> $email,
        ]; 

        $data['age'] = [
            'type' => 'number',
            'data'=> $_POST['age'] ?? '',
        ];

        $data['hours'] = [
            'type' => 'float',
            'data'=> $_POST['hours'] ?? '',
        ];

        $data['interest[]'] = [
            'type' => 'array',
            'data'=> $_POST['interest'] ?? [],
        ];

        $data['erudition'] = [
            'type' => 'radio',
            'data'=> $_POST['erudition'] ?? [],
        ];

        $data['photo'] = [
            'type' => 'file',
            'data'=> $_FILES['photo'],
        ];
        
        $data['confirm'] = [
            'type' => 'checkbox',
            'data'=> $_POST['confirm'],
        ];

        $validator->addValidator('string', new StringValidator(3,15)); 
        $validator->addValidator('email', new EmailValidator());
        $validator->addValidator('number', new NumberValidator(7,100));
        $validator->addValidator('float', new FloatValidator(0,24));
        $validator->addValidator('array', new ArrayValidator());
        $validator->addValidator('radio', new RadioValidator());
        $validator->addValidator('file', new FileValidator());
        $validator->addValidator('checkbox', new CheckboxValidator());

        $errors = $validator->validate($data);

        if(empty($errors)) {
            $successfully = 'Data sent successfully!';
        } 
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Login User</title>
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
                <button type="submit" name="home" class="styleButton">HOME</button>
            </form>
        </header>
        <main class="main-container"></main>
            <h2>Profile</h2>
            <p>Hello <?= $username?>!</p>
            <p>Enter additional information about yourself:</p>
            <!-- Форма профілю користувача  -->
            <div class="wrapperInputsContainer"> 
                <div class="inputsContainer">
                    <form method="POST" enctype="multipart/form-data" action=''>
                        <div>
                            <label for="username">Name:</label>
                            <input type="text" id="username" name="username"  disabled required
                                value="<?= htmlspecialchars($username) ?>" 
                                <?= isset($errors['username']) ? 'class="invalid"' : '' ?> 
                            />
                            <?php if (isset($errors['username'])): ?><span class="error"><?= $errors['username'] ?></span><?php endif; ?>
                        </div>
                        <br>                    
                        <div>
                            <label for="email">E-mail:</label>
                            <input type="email" id="password" name="email" disabled required
                                value="<?= htmlspecialchars($email) ?>" 
                                <?= isset($errors['email']) ? 'class="invalid"' : '' ?> 
                            />
                            <?php if (isset($errors['email'])): ?><span class="error"><?= $errors['email'] ?></span><?php endif; ?>
                        </div>
                        <br>
                        <div>
                            <label for="age">Age:</label>
                            <input type="number" id="age" name="age" placeholder="of full years" required
                                value="<?= htmlspecialchars($data['age']['data'] ?? '')?>" 
                                <?= isset($errors['age']) ? 'class="invalid"' : '' ?> 
                            />
                            <?php if (isset($errors['age'])): ?><span class="error"><?= $errors['age'] ?></span><?php endif; ?>
                        </div>
                        <br>
                        <div>
                            <label for="hours">How many hours per day you plan to work with this site:</label>
                            <input type="text" id="hours" name="hours" placeholder="1.5" required
                                value="<?= htmlspecialchars($data['hours']['data'] ?? '')?>" 
                                <?= isset($errors['hours']) ? 'class="invalid"' : '' ?> 
                            />
                            <?php if (isset($errors['hours'])): ?><span class="error"><?= $errors['hours'] ?></span><?php endif; ?>
                        </div>
                        <br>
                        <div>
                            <label for="interest">Categories of interest:</label>
                            <br>
                            <select id="interest" name="interest[]" multiple 
                                <?= isset($errors['interest']) ? 'class="invalid"' : ''?> 
                            >
                                <option value="planets">Planets</option>
                                <option value="stars">Stars</option>
                                <option value="asteroids">Asteroids</option>
                                <option value="comets">Comets</option>
                                <option value="other">Other</option>
                            </select>
                            <?php if (isset($errors['interest[]'])): ?><span class="error"><?= $errors['interest[]'] ?></span><?php endif; ?>
                        </div>
                        <br>
                        <div>
                            <fieldset style="border: 1px solid #ccc; padding: 10px; display: inline-flex; align-items: center ; gap: 10px;">
                            <legend>Level of erudition: </legend>
                            <?php if (isset($errors['erudition'])): ?><span class="error"><?= $errors['erudition'] ?></span><?php endif; ?>
                                <input type="radio" name="erudition" id="professional"  value="professional"/>
                                <label for="professional">I am a professional astronomer</label>
                                <br>
                                <input type="radio" name="erudition"  id="study" value="study"/>
                                <label for="study">I study astronomy</label>
                                <br>
                                <input type="radio" name="erudition" id="curious" value="curious"/>
                                <label for="curious">I am just curious</label>
                            </fieldset>
                        </div>
                        <br>
                        <div>
                            <label for="photo">Your photo (file):</label>
                            <input type="file" id="photo" name="photo"/>
                            <?php if (isset($errors['photo'])): ?><span class="error"><?= $errors['photo'] ?></span><?php endif; ?>
                        </div>
                        <br>
                        <div>
                            <input type="checkbox" id="confirm" name="confirm" value = "yes" required
                                <?= isset($errors['confirm']) ? 'class="invalid"' : '' ?> 
                            />
                            <label for="confirm">I confirm that all data is correct</label>
                            <?php if (isset($errors['confirm'])): ?><span class="error"><?= $errors['confirm'] ?></span><?php endif; ?>
                        </div> 
                        <input type="submit" value="Submit" class="styleButton">
                        <p class="successfully"><?= $successfully ?></p>
                    </form>
                </div>
            </div>    
        </main>
    </body>
</html>
