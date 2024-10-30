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

    use Palmo\source\search\ItemsByCount;
    use Palmo\source\store\MediaRepository;    
    use Palmo\source\validation\Validator;
    use Palmo\source\validation\validators\NumberValidator; 

     //Валідація введених параметрів для пошуку
     $errors = [];
     $data = [];
 
     if($_SERVER['REQUEST_METHOD'] === 'POST') {
         $validator = new Validator();
 
         $data['count'] = [
             'type' => 'number',
             'data'=> $_POST['count'] ?? '',
         ]; 
 
         $max = 20;
         $validator->addValidator('number', new NumberValidator(1, $max));
 
         $errors = $validator->validate($data);
     }

     $items = [];
     $total_pages = 1; //кількість сторінок пагінатора
     $current_page = $_POST['page'] ?? 1; // поточна сторінка пагінатора
     $max_show = 1; // кількість сторінок пагінатора, які відображаються спочатку

      //Якщо валідація пройшла успішно
     if (empty($errors)) { 
        //за введеними параметрами отримати відфільтровані дані    
        $foundItems  = new ItemsByCount(); 
        $items = $foundItems->getItems();
        $mediaRepository = new MediaRepository();
        $medias = $mediaRepository->getMedia();
        //перелбчислити кількість сторінок пагінатора
        $total_pages = $foundItems->getPages();
        $max_show = min(3,$total_pages);
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
            <h2>Random search by count</h2>
            <!-- Форма для введення параметрів для пошуку -->
            <div class="wrapperInputsContainer"> 
                <div class="inputsContainer">
                    <form method="POST" enctype="multipart/form-data" action="">
                        <div>  
                            <label for="count">Count for random selection:</label>
                            <input type="number" id="count" name="count" 
                                max="<?=$max?>" placeholder="1" 
                                value="<?= filter_var($data['count']['data'] ?? '', FILTER_VALIDATE_INT) ?>" 
                                <?= isset($errors['count']) ? 'class="invalid"' : '' ?> 
                            />
                            <input type="submit" value="Search" class="styleButton">
                            <?php if (isset($errors['count'])): ?><p class="error"><?= $errors['count'] ?></p><?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Відображення відфільтрованих даних -->
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
            
            <!-- Пагінатор -->           
            <form method="POST" action="" class="pagination-form">
                <!-- Показати перші сторінки -->
                <?php for ($i = 1; $i <= $max_show; $i++): ?>
                    <button type="submit" name="page" value="<?= $i ?>"
                        <?= ($i == $current_page) ? 'disabled' : '' ?>
                    >
                        <?= $i ?>
                    </button>
                <?php endfor; ?>

                <!-- Показати "..." якщо є проміжні сторінки між початком і поточним блоком -->
                <?php if ($current_page > $max_show + 1): ?>
                    <span>...</span>
                <?php endif; ?>

                <!-- Показати поточну сторінку з сусідніми, якщо вона не є початковою частиною -->
                <?php for ($i = max($max_show + 1, $current_page - 1); $i <= min($total_pages - 1, $current_page + 1); $i++): ?>
                    <button type="submit" name="page" value="<?= $i ?>"
                        <?= ($i == $current_page) ? 'disabled' : '' ?>
                    >
                        <?= $i ?>
                    </button>
                <?php endfor; ?>

                <!-- Показати "..." якщо є проміжні сторінки між кінцем і поточним блоком -->
                <?php if ($current_page < $total_pages - 2): ?>
                    <span>...</span>
                <?php endif; ?>

                <!-- Показати останню сторінку -->
                <?php if ($total_pages > $max_show): ?>
                    <button type="submit" name="page" value="<?= $total_pages ?>"
                        <?= ($total_pages == $current_page) ? 'disabled' : '' ?>
                    >
                        <?= $total_pages ?>
                    </button>
                <?php endif; ?>

                <!-- Значення фільтра count передається як приховане поле, щоб фільтр залишався активним після переходу між сторінками-->
                <input type="hidden" name="count" value="<?= htmlspecialchars($_POST['count'] ?? '') ?>">
            </form>
        </main>
    </body>
</html>