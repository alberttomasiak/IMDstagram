<?php
    include_once "classes/User.class.php";

    if( !empty( $_POST ) ){
        $user = new User();
        $user->Email = $_POST['email'];
        $user->FullName = $_POST['fullName'];
        $user->Username = $_POST['username'];
        $user->Password = $_POST['password'];
        if($user->signUp()){
            $feedback = "Account created successfully.";
        }
        // zorg ervoor dat je niet opnieuw naar database schrijft wanneer je refresht
        header("location: index.php");
    }


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="./public/css/reset.css">
</head>
<body>
    <form action="" method="post">
        <input type="email" name="email" id="email" placeholder="Email">
        
        <input type="text" name="fullName" id="fullName" placeholder="Full name">
        
        <input type="text" name="username" id="username" placeholder="Username">
        
        <input type="password" name="password" id="password" placeholder="Password">
        
        <input type="submit" value="Sign Up">
    </form>
</body>
</html>