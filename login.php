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

    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">

</head>
<body>
  <div class="wrapper">


  <section class="login">
    <a class="logo" href="<?php echo $_SERVER['PHP_SELF']; ?>">HOME</a>
      <form class="loginForm" action="" method="post">
        <input type="text" class="inputfld" name="username" placeholder="Username">
        <input type="password" class="inputfld" name="password" placeholder="Password">
        <input type="submit" class="submitButton" name="submitLogin" value="Log in">
      </form>
      <div class="register">
        <p>Problemen met het inloggen? Klik <a href="#"> hier </a> om uw inloggegevens aan te vragen.</p>
          <p>Heb je geen account? Klik <a href="signup.php"> hier </a> om u in te schrijven.</p>
      </div>
  </section>

  <?php include 'footer.inc.php'; ?>
</div>
</body>
</html>
