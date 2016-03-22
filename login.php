<?php
    include_once('classes/User.class.php');

    if(isset($user)){
        if($user->is_loggedin()!=""){
          header('Location: index.php');
        }
    }

    if(!empty($_POST)){
      $user = new User();
      $username = $_POST['username'];
      $password = $_POST['password'];

      if($user->canLogin($username, $password)){
            $_SESSION['loggedin'] = "yes";
            $_SESSION['username'] = $_POST['username'];
            header('location: index.php');
      }else{
        $feedback = "Wrong email or password. Try again";
      }
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
    <div class="wrapper">


    <section class="login">
      <a class="logo" href="<?php echo $_SERVER['PHP_SELF']; ?>">HOME</a>
        <form class="loginForm" action="" method="post">
          <input type="text" class="inputfld" name="username" placeholder="Gebruikersnaam">
          <input type="password" class="inputfld" name="password" placeholder="Wachtwoord">
          <input type="submit" class="submitButton" name="submitLogin" value="Aanmelden">
        </form>
        <div class="register">
          <p>Problemen met het inloggen? Klik <a href="#"> hier </a> om uw inloggegevens aan te vragen.</p>
            <p>Heb je geen account? Klik <a href="signup.php"> hier </a> om u in te schrijven.</p>
        </div>


    </section>
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
