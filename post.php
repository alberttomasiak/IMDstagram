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

    // CODE BRENT
    /*$comment = new Comment();
    
    //controleer of er een update wordt verzonden
    if(!empty($_POST['activitymessage']))
    {
        $cmmment->Comment = $_POST['activitymessage'];
        try 
        {
            $comment->Save();
        } 
        catch (Exception $e) 
        {
            $feedback = $e->getMessage();
        }
    }
    
    //altijd alle laatste activiteiten ophalen
    $recentActivities = $comment->GetRecentActivities();*/
    /*
    if(!empty($_POST['btnPlaceComment'])){
        $comment = new Comment();
        $comment->Post = $postData['id'];
        $comment->Comment = $_POST['inputComment'];
        $comment->Save();
        header('Location: '.$_SERVER['REQUEST_URI']);
    }*/
    $comment = new Comment();
    $allComments = $comment->getAllCommentsForPost($getPost);

    if(!empty($_POST['btnPlaceComment'])){
        if($comment->createComment($getPost, $_POST['inputComment'])){
            header('Location: '.$_SERVER['REQUEST_URI']);
        }else{
            echo "Error";
        }
    }

	if(!empty($_POST['deletePost'])){
		$deletePostID = $_POST['deletePostID'];
		$post = new Post();
		$post->deletePost($deletePostID);
		header('location: profile.php?profile='.$_SESSION['username'].'');
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
    <!--<script>
    $(document).ready(function(){
        $("#btnSubmit").on("click", function(e){
            
            var message = $("#activitymessage").val();

            $.ajax({
              type: "POST",
              url: "ajax/comment.php",
              data: { activitymessage: message }
            })
            .done(function( msg ) {
                //alert( "Data Saved: " + msg );
                var li = "<li style='display:none;'><strong><?php echo $userData['username'] ?>: </strong> " + message  + "</li>";
                $("#listupdates").prepend(li);
                $("#listupdates li").first().slideDown();
            });

            e.preventDefault();
            
        });
    });
    </script>-->
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
        <?php if($_SESSION['username'] == $userData['username']): ?>
        <form action="" method="POST">
            	<input type="hidden" name="deletePostID" class="deleteID" value="<?php echo $postData['id']; ?>">
			<button type="submit" class="post__delete" name="deletePost"><span class="glyphicon glyphicon-trash"></span></button>
        </form>
        <?php endif; ?>
    </header>

    <!--<div class="col-xs-12">-->
        <img src="<?php echo $postData['path'] ?>" alt="" id="singlePostImg">
    <!--</div>-->

    <div class="col-xs-12 detailpostLikesAndTime">
        <span><span id="likeCount"><?php echo $post->countLikes($postData['id']) ?></span> likes</span>
        <span><?php echo $post->timeAgo($postData['timestamp']); ?></span>
    </div>

    <div class="col-xs-12">
        <p><a href="#"><?php echo $userData['username'] ?></a> <?php echo $post->tagPostDescription($postData['description']) ?></p>
    </div>

    <div class="comments col-xs-12">
    <?php if(count($allComments) > 0):?>
    <a href="#">Load all comments</a>
    <ul class="comments__list">
        <?php foreach( $allComments as $key => $comment ): ?>
            <li class="comments__list__item">
                <p><a href="#"><?php echo $comment['username']?></a> <?php echo $comment['comment']?></p>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
        <ul class="comments__list"></ul>
    <?php endif; ?>

   
    <div class="col-xs-1">
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

    <form action="" class="col-xs-11" method="post">
        <div class="input-group">
            <input type="text" class="form-control" name="inputComment" id="inputComment" placeholder="Add a comment...">
        <span class="input-group-btn">
            <input type="submit" name="btnPlaceComment" id="btnPlaceComment" value="Submit" class="btn btn-default">
        </span>
        </div>
        <input type="hidden" id="inputPostID" value="<?php echo $postData['id'];?>">
    </form>

    </div>
    <div class="col-xs-2">
        <div class="row">
        <div class="col-xs-2 commentsFlag--Individual">
            <form action="" method="POST">
                <input type="hidden" name="postID" class="flagID" value="<?php echo $postData['id']; ?>">
                <?php if($post->checkIfFlagged($postData['id']) == true): ?>
                    <button type="submit" class="post__flag" name="flagPost"><span class="glyphicon <?php echo "f" . $postData['id']; ?> flagged glyphicon-flag"></span></button>
                <?php else: ?>
                    <button type="submit" class="post__flag" name="flagPost"><span class="glyphicon <?php echo "f" . $postData['id']; ?> glyphicon-flag"></span></button>
                <?php endif; ?>
            </form>
        </div>
        </div>
    </div>
</div>
</body>
</html>
