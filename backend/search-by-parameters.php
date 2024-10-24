<?php
    require __DIR__ . '/vendor/autoload.php';

    use Palmo\source\search\ItemsByParams;
    use Palmo\source\store\MediaRepository;   
   
    $foundItems  = new ItemsByParams(); 
    $items = $foundItems->getItems();
    $mediaRepository = new MediaRepository();
    $medias = $mediaRepository->getMedia();

    //Пагінація (кількість сторінок)
    $total_pages = $foundItems->getPages();
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
            <p><a href="home.php">HOME</a></p>
        </header>
        <main class="main-container">
            <h2>Search by custom parameters</h2>
            <!-- Форма для вибору параметрів для фільтрування -->
            <div class="wrapperInputsContainer"> 
                <div class="inputsContainer">
                <form method="POST" enctype="multipart/form-data">
                        <div>  
                            <label for="startDate">From date:</label>
                            <input type="date" id="startDate" name="start_date"/>
                            <label for="endDate">To date:</label>
                            <input type="date" id="endDate" name="end_date"/>
                        </div>
                        <div>
                            <label for="title">Title:</label>
                            <input type="text" id="title" name="title"> 
                        </div>
                        <div>
                            <label for="explanation">Explanation:</label><br>
                            <textarea id="explanation" name="explanation" rows="4" cols="50" placeholder="Enter an explanation fragment..."></textarea>
                        </div>
                        <div>
                            <fieldset style="border: 1px solid #ccc; padding: 10px; display: inline-flex; align-items: center ; gap: 10px;">
                                <legend>Media type: </legend>
                                <label>
                                    <input type="radio" name="media" value="both" checked> Both types
                                </label>
                                <label>
                                    <input type="radio" name="media" value="image"> Image
                                </label>
                                <label>
                                    <input type="radio"  name="media" value="video"> Video
                                </label>
                            </fieldset>
                        </div>                        
                        <div>
                            <input type="submit" value="Search" class="styleButton">
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
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <button type="submit" name="page" value="<?= $i ?>" 
                        <?= ($i == ($_POST['page'] ?? 1)) ? 'disabled' : '' ?>
                    >
                        <?= $i ?>
                    </button>
                    <?php endfor; ?>
                <!-- щоб значення параметрів фільтру залишалися  після переходу між сторінками-->
                <input type="hidden" name="start_date" value="<?= htmlspecialchars($_POST['start_date'] ?? '') ?>">
                <input type="hidden" name="end_date" value="<?= htmlspecialchars($_POST['end_date'] ?? '') ?>">
                <input type="hidden" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                <input type="hidden" name="explanation" value="<?= htmlspecialchars($_POST['explanation'] ?? '') ?>">
                <input type="hidden" name="media" value="<?= htmlspecialchars($_POST['media'] ?? '') ?>">
            </form>
        </main>
    </body>
</html>    