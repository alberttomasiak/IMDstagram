<?php
include_once "classes/User.class.php";

if( !empty( $_POST ) ){
    $user = new User();
    $user->Email = $_POST['email'];
    $user->FullName = $_POST['fullName'];
    $user->Username = $_POST['username'];
    $user->Password = $_POST['password'];
    if($user->register()){
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
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    
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

        <?php include 'footer.inc.php'; ?>
    </div>
</body>
</html>
