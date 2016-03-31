<?php
    include_once 'classes/User.class.php';
    session_start();

    if(isset($_SESSION['loggedin'])){
        $profile = $_GET['profile'];

        $user = new User();
        $userData = $user->getAll($profile);
    }else{
        header('location: login.php');
    }


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $userData['fullName']; ?> | IMDstagram</title>

    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="./public/css/style.css">
</head>
<body>

    <?php include 'nav.inc.php'; ?>

    <section>
        <img src="<?php echo $userData['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture" class="profilePicture">
        <h1><?php echo $userData['username']; ?></h1>

        <?php
            // SHOW EDIT PROFILE INSTEAD OF FOLLOW WHEN IT'S YOUR OWN PROFILE
            if(isset($_SESSION['loggedin'])){
                if($userData['username'] == $_SESSION['username']){
                   echo "<a href='edit-profile.php' class='btn btn-primary'>Edit profile</a>";
                }else{
                    echo "<a href='#' class='btn btn-primary'>Follow</a>";
                }
            }
        ?>

        <div class="about">
            <h2><?php echo $userData['fullName']; ?></h2>
            <span>
                <?php echo $userData['bio']; ?>
            </span>
            <a href="<?php echo $userData['website']; ?>"><?php echo $userData['website']; ?></a>
        </div>
        <ul>
            <li>
                <span>506</span> posts
            </li>
            <li>
                <a href="followers.php?profile=<?php echo $userData["username"] ?>">
                    <span>2000</span> followers
                </a>
            </li>
            <li>
                <a href="following.php?profile=<?php echo $userData["username"] ?>">
                    <span>692</span> following
                </a>
            </li>
        </ul>
    </section>

    <!-- SHOW UPLOAD PICTURE BUTTON WHEN IT'S YOUR OWN PROFILE-->
    <?php if(isset($_SESSION['loggedin']) && $userData['username'] == $_SESSION['username']): ?>
        <section>
            <a href="uploadpost.php">Upload a picture</a>
        </section>
    <?php endif; ?>

    <section>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
        <article>
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
        </article>
    </section>

    <?php include 'footer.inc.php'; ?>
</body>
</html>      