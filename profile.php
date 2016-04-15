<?php
    include_once 'classes/User.class.php';
    include_once 'classes/Post.class.php';
    session_start();

    if(isset($_SESSION['loggedin'])){
        $profile = $_GET['profile'];

        $user = new User();
        $userData = $user->getUserDetailsByUsername($profile);

        $post = new Post();
        $userPosts = $post->getAllForUser($userData['id']);
        //var_dump($userPosts);
    }else{
        header('location: login.php');
    }


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $userData['fullName']; ?> | IMDstagram</title>

    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <script src="public/js/interaction.js"></script>
</head>
<body>

<?php include 'nav.inc.php'; ?>
<div class="container">
    <header id="profileheader" class="row">
        <div class="profilepic col-xs-3">
            <img src="<?php echo $userData['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture">
        </div>
        <div class="col-xs-9 col-md-6">
        <div id="usernamewrap">
        <h1><?php echo $userData['username']; ?></h1>

        <?php
            // SHOW EDIT PROFILE INSTEAD OF FOLLOW WHEN IT'S YOUR OWN PROFILE
            if(isset($_SESSION['loggedin'])){
                if($userData['username'] == $_SESSION['username']){
                   echo "<a href='edit-profile.php' class='btn'>Edit profile</a>";
                }else if($user->isFollowing($userData['id']) == false){
                    //echo "<button class='btn btn-primary' data-id='" . $userData['id'] . "' id='btnFollow'>Follow</button>";
                    echo "<input type='submit' class='btn btn-primary' data-action='follow' data-id='" . $userData['id'] . "' id='btnFollow' value='follow'>";
                }else{
                    echo "<input type='submit' class='btn btn-primary active' data-action='stopfollowing' data-id='" . $userData['id'] . "' id='btnFollow' value='Following'>";
                }
            }
        ?>

        </div>
        <div class="about">
            <p>
            <h2 id="fullname"><?php echo $userData['fullName']; ?></h2>
            <span>
                <?php echo $userData['bio']; ?>
            </span>
            <a href="<?php echo $userData['website']; ?>"><?php echo $userData['website']; ?></a>
            </p>
        </div>
        <ul class="profilestats">
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
        </div>
    </header>

    <!-- SHOW UPLOAD PICTURE BUTTON WHEN IT'S YOUR OWN PROFILE -->
    <?php if(isset($_SESSION['loggedin']) && $userData['username'] == $_SESSION['username']): ?>
        <section>
            <a href="uploadpost.php">Upload a picture</a>
        </section>
    <?php endif; ?>

    <section>
        <!-- SHOW POSTS OR SHOW MESSAGE WHEN THERE ARE NO POSTS -->
        <?php if($userPosts == false): ?>
            <p>No posts yet.</p>
        <?php else: ?>
            <?php foreach( $userPosts as $key => $userPost ): ?>
                <article>
                    <a href="post.php?p=<?php echo $userPost['id'] ?>&u=<?php echo $userData['id'] ?>">
                        <img src="<?php echo $userPost['path'] ?>" alt="">
                    </a>
                </article>
            <?php endforeach; ?>

            <!--<article>
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
            </article>-->
        <?php endif; ?>
    </section>

    <?php include 'footer.inc.php'; ?>
</div>
</body>
</html>      