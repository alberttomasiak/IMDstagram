<?php
    include_once 'classes/User.class.php';
    include_once 'classes/Post.class.php';
    session_start();

    $getPost = $_GET['p'];
    $getUsername = $_GET['u'];

    $post = new Post();
    $postData = $post->getAllPost($getPost);

    $user = new User();
    $userData = $user->getAll($getUsername);

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="./public/css/style.css">
    <script src="public/js/like.js"></script>
</head>
<body>


    <img src="<?php echo $userData['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture">
    <a href="profile.php?profile=<?php echo $userData['username'] ?>"><?php echo $userData['username'] ?></a>
    <img src="<?php echo $postData['path'] ?>" alt="">
    <span><span id="likeCount"><?php echo $post->countLikes($postData['id']) ?></span> likes</span>
    <span><?php echo $postData['timestamp'] ?></span>
    <p><?php echo $post->tagPostDescription($postData['description']) ?></p>
    <!--<a href="#" id="btnLike" role="button" class="" data-action="liked" data-postid="<?php echo $postData['id'] ?>">Like</a>-->

    <?php
    // CHECK IF YOU LIKED THE POST ALREADY
    if(isset($_SESSION['loggedin'])){
        if($post->checkIfLiked($postData['id']) == true){
            // ALREADY LIKED
            echo "<a href='#' id='btnLike' role='button' class='liked' data-action='dislike' data-postid='" . $postData['id'] . "'>Like</a>";
        }else{
            // NOT LIKED YET
            echo "<a href='#' id='btnLike' role='button' data-action='like' data-postid='" . $postData['id'] . "'>Dislike</a>";
        }
    }
    ?>


    <form action="">
        <input type="text" placeholder="Add a comment...">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
