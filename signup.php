<?php
include_once "classes/User.class.php";

if( !empty( $_POST ) ){
    $user = new User();
    $user->Email = $_POST['email'];
    $user->FullName = $_POST['fullName'];
    $user->Username = $_POST['username'];
    $user->Password = $_POST['password'];
    if($user->canSignUp()){
        $feedback = "Account created successfully.";
        $_SESSION['loggedin'] = "yes";
        header("location: login.php");
    }else{
      echo "FOUT";
    }
    // zorg ervoor dat je niet opnieuw naar database schrijft wanneer je refresht
    //header("location: index.php");
}



?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/reset.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="public/css/style.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
    <div class="wrapperSignup">
    <section class="registerForm">
      <a class="logo" href="<?php echo $_SERVER['PHP_SELF']; ?>">HOME</a>
        <form class="loginForm" action="" method="post">
          <input type="email" class="inputfld" name="email" id="email" placeholder="Email">

          <input type="text" class="inputfld"  name="fullName" id="fullName" placeholder="Naam">

          <input type="text" class="inputfld"  name="username" id="username" placeholder="Gebruikersnaam">

          <input type="password" class="inputfld"  name="password" id="password" placeholder="Wachtwoord">

          <input type="submit" class="submitButton" value="Sign Up">
        </form>
        <div class="register">
            <p>Heb je al een account? Klik <a href="login.php"> hier </a> om u aan te melden.</p>
        </div>


    </section>
  </div>
    <footer>
      <div class="footerWrapperSignup">
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
