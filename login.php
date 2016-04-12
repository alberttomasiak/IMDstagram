<?php
    include_once('classes/User.class.php');
    include_once("config.php");
    include_once("includes/functions.php");

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

    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="public/css/style.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
  <div class="wrapper">


  <div class="row" id="logo">
    <div class="col-xs-6">
    <a class="logo" href="<?php echo $_SERVER['PHP_SELF']; ?>"><img src="public/images/logo.png" width="420px"/></a>
  </div>
  <div class="col-xs-6" id="login">
      <h1>Inloggen</h1>
      <form class="loginForm" action="" method="post">
        <input type="text" class="inputfld" name="username" placeholder="Username">
        <input type="password" class="inputfld" name="password" placeholder="Password">
        <input type="submit" class="submitButton" name="submitLogin" value="Log in">
      </form>
      <div class="register">
        <p>Inloggen met <a href="indexfb.php">FACEBOOK</a></br>
        Ik kan niet inloggen? Klik <a href="#"> hier </a> om uw inloggegevens aan te vragen. </br>
        Heb je geen account? Klik <a href="signup.php"> hier </a> om u in te schrijven.
          
      </div>
  </div>

  
</div>
<?php include 'footer.inc.php'; ?>
</body>
</html>
