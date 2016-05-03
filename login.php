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

    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">

</head>
<body>
    <div class="container">


        <div class="row">
            <div class="col-sm-5">

                <form action="" class="login__form" method="post">
                    <?php if(isset($feedback)){ echo "<div class='alert alert-danger' role='alert'>".$feedback."</div>";}?>
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="submitLogin" value="Log in">
                    </div>
                </form>

                <p>Not a member? <a href="signup.php">Sign up now</a></p>

            </div>
        </div>

        <?php include 'footer.inc.php'; ?>
    </div>
</body>
</html>