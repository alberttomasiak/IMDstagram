<?php
    include_once('classes/User.class.php');
    session_start();

    if(isset($_SESSION['loggedin'])){
        header('location: index.php');
    }

    if(!empty($_POST)){
      $user = new User();
      $username = $_POST['username'];
      $password = $_POST['password'];

      if($user->canLogin($username, $password)){
            $_SESSION['loggedin'] = "yes";
            //$_SESSION['username'] = $_POST['username'];
            header('location: index.php');
      }else{
        $feedback = "Wrong username or password. Try again.";
      }
    }

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Log in | IMDStagram</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">

</head>
<body>
<div class="background--full">
   <div class="page--wrap__welcome">
    <div class="form--login">
        <div class="form--login__logo"></div>
        <h4 class="form--login__tagline">Log in to your IMDstagram account.</h4>

        <form action="" class="login__form" method="post">
            <?php if(isset($feedback)){ echo "<div class='alert alert-danger' role='alert'>".$feedback."</div>";}?>
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary form--login__btn" name="submitLogin" value="Log in">
            </div>
        </form>

        <p class="text-center">Not a member? <a href="signup.php">Sign up now</a></p>
		
    </div>
    <div class="push"></div>
    </div>
    <div class="footer--login"><?php include 'footer.inc.php'; ?></div>
</div>
    
</body>
</html>