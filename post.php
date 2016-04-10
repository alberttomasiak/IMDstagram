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
</head>
<body>


    <img src="<?php echo $userData['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture">
    <a href="profile.php?profile=<?php echo $userData['username'] ?>"><?php echo $userData['username'] ?></a>
    <img src="<?php echo $postData['path'] ?>" alt="">
    <span><?php echo $postData['timestamp'] ?></span>
    <p><?php echo $post->tagPostDescription($postData['description']) ?></p>
    <form action="">
        <input type="text" placeholder="Add a comment...">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
