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
      echo "ERROR";
    }
    // zorg ervoor dat je niet opnieuw naar database schrijft wanneer je refresht
    //header("location: index.php");
}



?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>

    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">

    <script type="text/javascript" src="public/js/realtimeUsernameCheck.js"></script>
</head>
<body>
    <div class="wrapperSignup">
        <section class="registerForm">
          <a class="logo" href="<?php echo $_SERVER['PHP_SELF']; ?>">HOME</a>
            <form class="loginForm" action="" method="post">
              <input type="email" class="inputfld" name="email" id="email" placeholder="Email">

              <input type="text" class="inputfld"  name="fullName" id="fullName" placeholder="Name">

              <input type="text" class="inputfld"  name="username" id="username" placeholder="Username">
                <div class="usernameFeedback"></div>

              <input type="password" class="inputfld"  name="password" id="password" placeholder="Password">

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
