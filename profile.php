<?php
    include_once 'classes/User.class.php';
    session_start();

    if(isset($_SESSION['loggedin'])){

    }else{
        header('location: login.php');
    }


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Real Name | Instagram</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="./public/css/reset.css">
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="./public/css/style.css">
</head>
<body>
    <div class="wrapperProfile">
        <nav class="profileNav">
            <ul>
                <li>
                    <a href="index.php" class="homeLogo">logo</a>
                </li>
                <li></li>
                <li>
                    <h4><a class="afmelden" href="logout.php">Afmelden</a></h4>
                </li>
            </ul>
        </nav>
    </div>
    
    <section>
        <img src="https://scontent-ams3-1.cdninstagram.com/t51.2885-19/s150x150/12716783_212920045723586_226062489_a.jpg" alt="">
        <h1>cocacola</h1>
        <a href="">Follow</a>
        <div class="about">
            <h2>Coca-Cola</h2>
            <span>
                Watch our full Big Game ad in the link below.
            </span>
            <a href="youtu.be/OlZqBR3yTiw">youtu.be/OlZqBR3yTiw</a>
        </div>
        <ul>
            <li>
                <span>506</span> berichten
            </li>
            <li>
                <span>2000</span> volgers
            </li>
            <li>
                <span>692</span> volgend
            </li>
        </ul>
    </section>
    
    <section>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
    </section>
    
    <footer>
        <div class="footerWrapper">
            <a href="#">OVER ONS</a>
            <a href="#">ONDERSTEUNING</a>
            <a href="#">BLOG</a>
            <a href="#">PERS</a>
            <a href="#">API</a>
            <a href="#">VACATURES</a>
            <a href="#">PRIVACY</a>
            <a href="#">VOORWAARDEN</a>
            <a href="#">TAAL</a>
            <p>&copy; 2016 IMDSTAGRAM</p>
        </div>
    </footer>
</body>
</html>
   

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>IMDStagram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/reset.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="public/css/style.css" media="screen" title="no title" charset="utf-8">

      