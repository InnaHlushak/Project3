<?php
     session_start();

    // якщо  користувач не  авторизований, то перенаправлення на index.php
    if(!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
        header("Location: /");
        exit();
    }

    // Редірект на сторінку form-profile-user.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile'])) {
        header('Location: profile-user.php');
        exit();
    }
    // Редірект на сторінку logout.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        header('Location: scripts/logout.php');
        exit();
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
            <button type="submit" name="profile" class="styleButton">Profile</button>
            <button type="submit" name="logout" class="styleButton">Logout</button>
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