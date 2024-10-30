<?php
    // Редірект на сторінку login-user.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        // Редірект на сторінку home.php (якщо Remember Me при попереднному вході на сайт)
        if (isset($_COOKIE['user_id'])) {
            header('Location: home.php');
            exit();
        } else {        
            header('Location: login-user.php');
            exit();
        }
    }

    // Редірект на сторінку search-by-date.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redirect_search_date'])) {
        header('Location: search-by-date.php');
        exit();
    }

    // Редірект на сторінку search_by_count.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_by_count'])) {
        header('Location: search-by-count.php');
        exit();
    }

    // Редірект на сторінку search-by-parameters.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_by_parameters'])) {
        header('Location: search-by-parameters.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP PROJECT 3</title>    
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
        </form>
    </header>

    <main>
        <div class="main-container">   
            <section class="containerIntrodaction">
                <h2>Discover<span class="containerIntrodaction-h1-span">the cosmos!</span></h2>
                <p class="containerIntrodaction-p">Each day a different image or photograph of our fascinating universe is featured, along with a brief explanation written by a professional astronomer.</p>
                <p class="containerIntrodaction-p">You can easily find and view photos or videos</p>
            </section>

            <section class="containerSearchMethods">        
                <form method="POST" class="searchMethods">
                    <button type="submit" name="redirect_search_date" class="buttonSarchMethod">Search by date</button>
                    <button  type="submit" name="search_by_count" class="buttonSarchMethod">Search in a random way</button>
                    <button  type="submit" name="search_by_parameters" class="buttonSarchMethod">Search by custom parameters</button>
                </form> 
            </section>       
        </div>  
    </main>
</body>
</html>
