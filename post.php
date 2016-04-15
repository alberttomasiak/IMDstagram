<?php
    include_once 'classes/User.class.php';
    include_once 'classes/Post.class.php';
    session_start();

    $getPost = $_GET['p'];
    $getUsername = $_GET['u'];

    $post = new Post();
    $postData = $post->getAllPost($getPost);

    $user = new User();
    $userData = $user->getUserDetailsByUsername($getUsername);

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <script src="public/js/interaction.js"></script>
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
