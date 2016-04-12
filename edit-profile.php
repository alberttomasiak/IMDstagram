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

    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">

    <script type="text/javascript" src="public/js/realtimeUsernameCheck.js"></script>
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
        <div class="usernameFeedback" style="display: none;"></div>

        <label for="biography">Biography</label>
        <textarea name="biography" id="biography" cols="30" rows="10"><?php echo $userData['bio']; ?></textarea>
        
        <label for="website">Website</label>
        <input type="text" id="website" name="website" value="<?php echo $userData['website']; ?>">
        
        <input type="submit" value="Submit">
    </form>
    <?php include 'footer.inc.php' ?>
</body>
</html>