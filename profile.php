<?php
    include_once 'classes/User.class.php';
    session_start();

    if(isset($_SESSION['loggedin'])){

    }else{
      header('location: login.php');
    }


?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>IMDStagram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/reset.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="public/css/style.css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <div class="wrapperProfile">
        <nav class="profileNav">
          <ul>
            <li><a href="index.php" class="homeLogo">logo</a></li>
            <li></li>
            <li><h4><a class="afmelden" href="logout.php">Afmelden</a>
              </h4></li>
          </ul>
        </nav>


      </div>

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
