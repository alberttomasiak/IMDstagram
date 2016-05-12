<?php
    include_once 'classes/User.class.php';
    include_once 'classes/Post.class.php';
    include_once 'classes/Comment.class.php';
    session_start();

    $getPost = $_GET['p'];
    $getUserID = $_GET['u'];

    $post = new Post();
    $postData = $post->getAllPost($getPost);
	
    //var_dump($postData);
    $user = new User();
    $userData = $user->getUserDetailsByUserID($getUserID);

    // ALBERT JE MOET EEN SUBMIT KNOP KIEZEN :o
	/*if(!empty($_POST)){
		$deletePostID = $_POST['deletePostID'];
		$post = new Post();
		$post->deletePost($deletePostID);
		header('Location: profile.php?profile='.$_SESSION['username'].'');
	}*/

    if(($user->isPrivate($getUserID) == true) && ($user->isFollowing($getUserID) == false) && ($_SESSION['userID'] != $getUserID)){
        // RESTRICT ACCESS TO PRIVATE POSTS
        header('location: index.php');
    }

    $comment = new Comment();
    $allComments = $comment->getAllCommentsForPost($getPost);

    if(!empty($_POST['btnPlaceComment'])){
        if($comment->createComment($getPost, $_POST['inputComment'])){
            header('Location: '.$_SERVER['REQUEST_URI']);
        }else{
            //echo "Error";
        }
    }

    if(!empty($_POST['btnLike'])){
        $postID = $_POST['likePostID'];
        if($post->like($postID)){
            header('Location: '.$_SERVER['REQUEST_URI']);
        }else{
            echo "Error";
        }

    }

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/cssgram.min.css">
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <script src="public/js/interaction.js"></script>
</head>
<body>
<?php include 'nav.inc.php'; ?>

<div class="container--custom">

    <div class="post">
        <div class="post__header">
            <div class="userinfo">
                <a href="profile.php?profile=<?php echo $userData['username'] ?>">
                    <img src="<?php echo $userData['profilePicture']; ?>" alt="<?php echo $userData['username'] ?>'s profile picture" class="userinfo__picture">
                </a>
                <div class="userinfo__text">
                    <a href="profile.php?profile=<?php echo $userData['username'] ?>" class="userinfo__username"><?php echo $userData['username'] ?></a>
                    <span class="userinfo__location"><?php echo $postData['location']; ?></span>
                </div>
            </div>
        </div>
        <img src="<?php echo $postData['path'] ?>" alt="" class="post__image <?php echo $postData['filter']; ?>" id="singlePostImg">
        <div class="post__info">
            <span><span id="likeCount" class="likeCount"><?php echo $post->countLikes($postData['id']) ?></span> likes</span>
            <span><?php echo $post->timeAgo($postData['timestamp']); ?></span>
        </div>
        <div class="post__description">
            <p><a href="profile.php?profile=<?php echo $userData['username'] ?>"><?php echo $userData['username']; ?></a> <?php echo $post->tagPostDescription($postData['description']) ?></p>
        </div>
        <div class="post__comments">
            <?php if(count($allComments) > 0):?>
            <ul class="comments__list">
                <?php foreach( $allComments as $key => $c ): ?>
                <li class="comments__list__item">
                    <p><a href="profile.php?profile=<?php echo $c['username'] ?>"><?php echo $c['username']?></a> <?php echo $comment->tagComments($c['comment']);?></p>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
                <ul class="comments__list"></ul>
            <?php endif; ?>
        </div>
        <div class="post__actions">
            <?php
            if(isset($_SESSION['loggedin'])){
            if($post->checkIfLiked($postData['id']) == true){
                // ALREADY LIKED
                echo "<form action='' method='post'>
                            <input type='submit' id='btnLike' value='Dislike' name='btnLike' class='btnLike heart heart--like' data-postid='". $postData['id'] ."'>
                            <input name='likePostID' id='likePostID' type='hidden' value='". $postData['id'] ."'>
                        </form>";
            }else{
                // NOT LIKED YET
                echo "<form action='' method='post'>
                            <input type='submit' id='btnLike' value='Like' name='btnLike' class='btnLike heart' data-postid='". $postData['id'] ."'>
                            <input name='likePostID' id='likePostID' type='hidden' value='". $postData['id'] ."'>
                        </form>";
            }
            }
            ?>

            <form action="" method="post">
                <div class="input-group">
                    <input type="text" class="form-control inputComment" name="inputComment" id="inputComment" placeholder="Add a comment...">
                        <span class="input-group-btn">
                        <input type="submit" name="btnPlaceComment" id="btnPlaceComment" value="Submit" class="btn btn-default btnPlaceComment" data-postid="<?php echo $postData['id'] ?>>
                        </span>
                </div>
                <input type="hidden" id="inputPostID" value="<?php echo $postData['id'];?>">
            </form>
        </div>
        <div class="commentsFlag--Individual">
            <form action="" method="POST">
                <input type="hidden" name="postID" class="flagID" value="<?php echo $postData['id']; ?>">
                <?php if($post->checkIfFlagged($postData['id']) == true): ?>
                    <button type="submit" class="post__flag" name="flagPost"><span class="glyphicon <?php echo "f" . $postData['id']; ?> flagged glyphicon-flag"></span></button>
                <?php else: ?>
                    <button type="submit" class="post__flag" name="flagPost"><span class="glyphicon <?php echo "f" . $postData['id']; ?> glyphicon-flag"></span></button>
                <?php endif; ?>
            </form>
        </div>
        <?php if($_SESSION['username'] == $userData['username']): ?>
            <form action="" method="POST">
                <input type="hidden" name="deletePostID" class="deleteID" value="<?php echo $postData['id']; ?>">
                <button type="submit" class="post__delete" name="deletePost"><span class="glyphicon glyphicon-trash"></span></button>
            </form>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
