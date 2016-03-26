<?php
    include_once 'classes/User.class.php';
    session_start();

    if(isset($_SESSION['loggedin'])){
        $user = new User();
        $userData = $user->getAll($_SESSION['username']);

        if( !empty( $_POST ) ){
            $user->Email = $_POST['email'];
            $user->FullName = $_POST['fullName'];
            $user->Username = $_POST['username'];
            $user->Bio = $_POST['biography'];
            $user->Website = $_POST['website'];
            if($user->updateProfile($userData['id'])){
                $feedback = "Account updated successfully.";
                header("location: logout.php");
            }else{
                echo "ERROR";
            }
            // zorg ervoor dat je niet opnieuw naar database schrijft wanneer je refresht
            //header("location: index.php");
        }
    }else{
        header('location: login.php');
    }


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit your profile</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="./public/css/style.css">
</head>
<body>
    <?php include 'nav.inc.php' ?>
    <h1>Edit profile</h1>
    <form action="" method="post">
        <label for="name">Name</label>
        <input type="text" id="name" name="fullName" value="<?php echo $userData['fullName']; ?>">
        
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $userData['email']; ?>">
        
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo $userData['username']; ?>">
        
        <label for="biography">Biography</label>
        <textarea name="biography" id="biography" cols="30" rows="10"><?php echo $userData['bio']; ?></textarea>
        
        <label for="website">Website</label>
        <input type="text" id="website" name="website" value="<?php echo $userData['website']; ?>">
        
        <input type="submit" value="Submit">
    </form>
    <?php include 'footer.inc.php' ?>
</body>
</html>