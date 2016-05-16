<?php
include_once "classes/User.class.php";
session_start();

if(isset($_SESSION['loggedin'])){
    header('location: index.php');
}
/*
if( !empty( $_POST ) ){
    $user = new User();
    $user->Email = $_POST['email'];
    $user->FullName = $_POST['fullName'];
    $user->Username = $_POST['username'];
    $user->Password = $_POST['password'];
    if($user->register()){
        //$feedback = "Account created successfully.";
        //$_SESSION['loggedin'] = "yes";
        //header("location: login.php");
        if($user->canLogin($_POST['username'], $_POST['password'])){
            //$_SESSION['loggedin'] = "yes";
            header("location: index.php");
        }
    }else{
        $feedback = "Something went wrong. Try again.";
    }
    // zorg ervoor dat je niet opnieuw naar database schrijft wanneer je refresht
    //header("location: index.php");
}*/

if( !empty( $_POST ) ){
    try{
        $user = new User();
        $user->Email = $_POST['email'];
        $user->FullName = $_POST['fullName'];
        $user->Username = $_POST['username'];
        $user->Password = $_POST['password'];
        $user->register();

        //$feedback = "Account created successfully.";
        //$_SESSION['loggedin'] = "yes";
        //header("location: login.php");
        if($user->canLogin($_POST['username'], $_POST['password'])){
            //$_SESSION['loggedin'] = "yes";
            header("location: index.php");
        }
    }catch(Exception $e){
        $feedback = $e->getMessage();
    }

}



?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign up | IMDstagram</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <script src="public/js/interaction.js"></script>
</head>
<body>
<div class="background--full">
	<div class="page--wrap__welcome">
    <div class="form--login">
        <div class="form--login__logo"></div>
        <h4 class="form--login__tagline">Sign up to see photos and videos from your friends.</h4>
        <form action="" class="login__form login__form--register" method="post">
            <?php if(isset($feedback)){ echo "<div class='alert alert-danger' role='alert'>".$feedback."</div>";}?>
            <div class="form-group">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
            </div>

            <div class="form-group">
                <input type="text" class="form-control"  name="fullName" id="fullName" placeholder="Full name">
            </div>

            <div class="form-group">
                <input type="text" class="form-control"  name="username" id="username" placeholder="Username">
            <div class="usernameFeedback"></div>
            </div>

            <div class="form-group">
                <input type="password" class="form-control"  name="password" id="password" placeholder="Password">
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary form--login__btn" value="Sign Up">
            </div>
        </form>

        <p class="text-center">Already a member? <a href="login.php">Log in here</a></p>

    </div>
    <div class="push"></div>
    </div>
    <div class="footer--login"><?php include 'footer.inc.php'; ?></div>
	
</div>
</body>
</html>
