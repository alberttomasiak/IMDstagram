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
<?php include 'nav.inc.php'; ?>
<div class="container">
<div class="row detailpostRow">
    <header class="col-xs-12 detailpostHeader">
        <a href="profile.php?profile=<?php echo $userData['username'] ?>">
        <img src="<?php echo $userData['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture">
        <?php echo $userData['username'] ?>
        </a>
    </header>

    <div class="col-xs-12">
        <img src="<?php echo $postData['path'] ?>" alt="" id="singlePostImg">
    </div>

    <div class="col-xs-12 detailpostLikesAndTime">
        <span><span id="likeCount"><?php echo $post->countLikes($postData['id']) ?></span> likes</span>
        <span><?php echo $postData['timestamp'] ?></span>
    </div>

    <div class="col-xs-12">
        <p><?php echo $post->tagPostDescription($postData['description']) ?></p>
    </div>

    <div class="col-xs-12">
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
    </div>


    <form action="" class="col-xs-12">
        <div class="form-group">
            <input type="text" placeholder="Add a comment..." class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" value="Submit" class="btn btn-primary">
        </div>
    </form>
</div>
</div>
</body>
</html>
