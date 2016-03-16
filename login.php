<?php
    include_once "classes/User.class.php";

    if( !empty( $_POST ) ){
        $user = new User();

        $username = $_POST['username'];
        $password = $_POST['password'];
        if($user->login($username, $password)){
            header('location: index.php');
        }
        else{
            $feedback = "Incorrect password or email.";
        }
    }

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In</title>
    <link rel="stylesheet" href="./public/css/reset.css">
</head>
<body>
    <form action="" method="post">
        <input type="text" name="username" id="username" placeholder="Username">
        <input type="password" name="password" id="password" placeholder="Password">
        <input type="submit" value="Log In">
    </form>
    <span>Forgot your login details? <a href="#">Get help signing in.</a></span>
    <span>Don't have an account? <a href="signup.php">Sign up.</a></span>
</body>
</html>