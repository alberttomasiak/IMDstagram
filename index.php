<?php
    include_once 'classes/User.class.php';
	include_once 'classes/Post.class.php';
	include_once 'classes/Comment.class.php';
    session_start();

    if(isset($_SESSION['loggedin'])){
		$username = $_SESSION['username'];
		
        $user = new User();
		$userData = $user->getUserDetailsByUsername($username); 
		
		$post = new Post();
		$timelinePosts = $post->getAllTimeline();

        // niet nodig
		//$timestamp = $post->getTimestamp();
		//$convTime = $post->timeAgo($timestamp);
		//var_dump($timelinePosts);
        //var_dump($username);
    }else{
      header('location: login.php');
    }

	// BUTTON NAME GEVEN
	/*if(!empty($_POST)){
		$flagID = $_POST['postID'];
		$post = new Post();
		if($post->countFlags($flagID) == true){
			$post->deletePost($flagID);
		}
		
		if($post->checkIfFlagged($flagID) == false && $post->countFlags($flagID) == false){
			if($post->countFlags($flagID) == true){
				$post->deletePost($flagID);
			}
			$post->flagPost($flagID);
		}else{
			$post->unFlagPost($flagID);
		}
		//header('location: profile.php?profile='.$_SESSION['username'].'');
	}*/

	$comment = new Comment();

	if(!empty($_POST['btnPlaceComment'])){
		if($comment->createComment($_POST['inputPostID'], $_POST['inputComment'])){
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

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>IMDStagram</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <link rel="stylesheet" href="public/css/cssgram.min.css">
    <script src="public/js/interaction.js"></script>
</head>
<body>

    <?php include 'nav.inc.php'; ?>
	<div class="hideMe">
    <section class="postsWrapper">
    <?php if ($timelinePosts == false): ?>
    	<p>There are no posts to display yet. Try following some people.</p>
    <?php else: ?>
    <?php foreach($timelinePosts as $key => $timelinePost): ?>
        <article class="postTimeline <?php echo $timelinePost['id']; ?>">
          <div class="postHeader">
          <div class="postUser">
           <!-- Profile picture -->
           <img src="<?php echo $timelinePost['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture">
            
            <!-- Username link -->
            <a href="profile.php?profile=<?php echo $timelinePost['username']; ?>"><?php echo $timelinePost['username']; ?></a>
            <p class="timelineLocation"><?php echo $timelinePost['location']; ?></p>
            </div>
            <!-- Timestamp -->
            <p class="postTimestamp"><?php echo $post->timeAgo($timelinePost['timestamp']); ?></p>
            </div>
            
            
            <!-- Image met link naar de bijhorende post pagina -->
            <a class="postImage" href="post.php?p=<?php echo $timelinePost['id'] ?>&u=<?php echo $timelinePost['userID'] ?>">
            <img src="<?php echo $timelinePost['path']; ?>" class="<?php echo $timelinePost['filter']; ?>" alt="">
        	</a>
        	
        	
        	<!-- wrapper voor comments, like & flag-->
        	<div class="commentsWrapper">
			<div class="commentsLike">
				
			</div>
       		
        	<!-- Hier komen de comments voor elke post -->
        	<div class="commentsPost">
        		<ul>
        			<!-- comments in li => username + comment -->
        		</ul>
        	</div>
        	
        	<div class="commentsFlag">
        		<form action="" method="POST">
        			<input type="hidden" name="postID" class="flagID" value="<?php echo $timelinePost['id']; ?>">
        			<?php if($post->checkIfFlagged($timelinePost['id']) == true): ?>
        			<button type="submit" class="post__flag" name="flagPost"><span class="glyphicon <?php echo "f" . $timelinePost['id']; ?> flagged glyphicon-flag"></span></button>
        			<?php else: ?>
        			<button type="submit" class="post__flag" name="flagPost"><span class="glyphicon <?php echo "f" . $timelinePost['id']; ?> glyphicon-flag"></span></button>
        			<?php endif; ?>
        		</form>
        	</div>
        	</div>
        	   
        	
        </article>
    <?php endforeach; ?>
    <?php endif; ?>
    </section>
	</div>

<div class="container--custom">

<?php foreach($timelinePosts as $key => $timelinePost): ?>
	<div class="post">
		<div class="post__header">
			<div class="userinfo">
				<a href="profile.php?profile=<?php echo $timelinePost['username'] ?>">
					<img src="<?php echo $timelinePost['profilePicture']; ?>" alt="<?php echo $timelinePost['username'] ?>'s profile picture" class="userinfo__picture">
				</a>
				<div class="userinfo__text">
					<a href="profile.php?profile=<?php echo $timelinePost['username'] ?>" class="userinfo__username"><?php echo $timelinePost['username'] ?></a>
					<span class="userinfo__location"><?php echo $timelinePost['location']; ?></span>
				</div>
			</div>
		</div>
		<img src="<?php echo $timelinePost['path'] ?>" alt="" class="post__image <?php echo $timelinePost['filter']; ?>" id="singlePostImg">
		<div class="post__info">
			<span><span id="likeCount"><?php echo $post->countLikes($timelinePost['id']) ?></span> likes</span>
			<span><?php echo $post->timeAgo($timelinePost['timestamp']); ?></span>
		</div>
		<div class="post__description">
			<p><a href="profile.php?profile=<?php echo $timelinePost['username'] ?>"><?php echo $timelinePost['username']; ?></a> <?php echo $post->tagPostDescription($timelinePost['description']) ?></p>
		</div>
		<div class="post__comments">
			<?php $allComments = $comment->getAllCommentsForPost($timelinePost['id'])?>
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
				if($post->checkIfLiked($timelinePost['id']) == true){
					// ALREADY LIKED
					echo "<form action='' method='post'>
						<input type='submit' id='btnLike' value='Dislike' name='btnLike' class='heart heart--like'>
						<input name='likePostID' id='likePostID' type='hidden' value='". $timelinePost['id'] ."'>
					</form>";
				}else{
					// NOT LIKED YET
					echo "<form action='' method='post'>
						<input type='submit' id='btnLike' value='Like' name='btnLike' class='heart'>
						<input name='likePostID' id='likePostID' type='hidden' value='". $timelinePost['id'] ."'>
					</form>";
				}
			}
			?>

			<form action="" method="post">
				<div class="input-group">
					<input type="text" class="form-control" name="inputComment" id="inputComment" placeholder="Add a comment...">
					<span class="input-group-btn">
					<input type="submit" name="btnPlaceComment" id="btnPlaceComment" value="Submit" class="btn btn-default">
					</span>
				</div>
				<input type="hidden" id="inputPostID" name="inputPostID" value="<?php echo $timelinePost['id'];?>">
			</form>
		</div>
		<div class="commentsFlag--Individual">
			<form action="" method="POST">
				<input type="hidden" name="postID" class="flagID" value="<?php echo $timelinePost['id']; ?>">
				<?php if($post->checkIfFlagged($timelinePost['id']) == true): ?>
					<button type="submit" class="post__flag" name="flagPost"><span class="glyphicon <?php echo "f" . $timelinePost['id']; ?> flagged glyphicon-flag"></span></button>
				<?php else: ?>
					<button type="submit" class="post__flag" name="flagPost"><span class="glyphicon <?php echo "f" . $timelinePost['id']; ?> glyphicon-flag"></span></button>
				<?php endif; ?>
			</form>
		</div>
	</div>
<?php endforeach; ?>

</div>

    <?php include 'footer.inc.php'; ?>
</body>
</html>
