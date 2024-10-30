<?php
    session_start();

    // Редірект на  стартову сторінку 
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['home'])) {
        // Редірект на сторінку home.php (якщо користувач авторизований)
        if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {
            header('Location: home.php');
            exit();
        } else {
            header('Location: /');
            exit();
        }
    }
?>

<?php
    require __DIR__ . '/vendor/autoload.php';

    use Palmo\source\search\ItemsByDate;
    use Palmo\source\store\MediaRepository;
    use Palmo\source\validation\Validator;
    use Palmo\source\validation\validators\DateValidator; 

    //Валідація введених параметрів для пошуку
    $errors = [];
    $data = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $validator = new Validator();

        $data['date'] = [
            'type' => 'date',
            'data'=> $_POST['date'] ?? '',
        ]; 

        $max = 10;
        $validator->addValidator('date', new DateValidator());

        $errors = $validator->validate($data);
    }
   
    $items = [];

    //Якщо валідація пройшла успішно
    if (empty($errors)) { 
        $foundItems  = new ItemsByDate(); 
        $items = $foundItems->getItems();
        $mediaRepository = new MediaRepository();
        $medias = $mediaRepository->getMedia();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Items</title>
        <style>
            .error {color: red;}
            .invalid {border: 1px solid red;}
            .pagination-form {text-align: center; margin-top: 20px;}
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
        <main class="main-container">
            <h2>Search by date</h2>
            <!-- The form for specifying search parameters -->
            <div class="wrapperInputsContainer"> 
                <div class="inputsContainer">
                    <form method="POST" enctype="multipart/form-data">
                        <div>  
                            <label for="inputDate">Date:</label>
                            <input type="date" id="inputDate" name="date"
                                value="<?= htmlspecialchars($_POST['date'] ?? '') ?>"
                                <?= isset($errors['date']) ? 'class="invalid"' : '' ?> 
                            />
                            <input type="submit" value="Search" class="styleButton">
                            <?php if (isset($errors['date'])): ?><p class="error"><?= $errors['date'] ?></p><?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Display of found Items -->
            <div>
                <?php if(empty($items)): ?><p>Sorry! No matching data found!</p><?php endif; ?>
            </div>

            <div>        
                <?php foreach ($items as $item) {?>                        
                    <div class="itemContainer">
                        <div>
                            <div>
                                <h3>
                                    Title:
                                    <?php echo $item['title']; ?>
                                </h3>
                            </div>
                            <div>
                                <span>
                                    <b>Date:</b>
                                </span>
                                <span>
                                    <?php echo $item['creation_date']; ?>
                                </span>
                            </div>

                            <div>
                                <span>
                                    <b>Explanation:</b>
                                </span>
                                <span>
                                    <?php echo $item['explanation']; ?>
                                </span>
                            </div>
                            <div>
                                <span>
                                    URL: 
                                </span>
                                <span>
                                    <?php echo $item['url']; ?>
                                </span>
                            </div>
                            <div>
                                <span>
                                    <b>Media type:</b>
                                </span>
                                <span>
                                    <?php 
                                        $filteredMedias = array_filter($medias, function($media) use ($item) {
                                            return $media['id'] == $item['media_id'];
                                        });
                                        $media = array_shift($filteredMedias);
                                        echo $media['type']; 
                                    ?>
                                </span>
                            </div>
                        </div>    
                        <button class="buttonDetails" onclick="window.location.href='<?php echo $item['url'] ?>'">Details</button>
                    </div>
                <?php }; ?>   
            </div>
        </main>
    </body>
</html>