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

<div class="container--custom">
<?php if ($timelinePosts == false): ?>
	<p class="text-center">There are no posts to display yet. Try following some people.</p>
<?php else: ?>
<?php foreach($timelinePosts as $key => $timelinePost): ?>
	<div class="post post--timeline">
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
		<a href="post.php?p=<?php echo $timelinePost['id'] ?>&u=<?php echo $timelinePost['userID'] ?>"><img src="<?php echo $timelinePost['path'] ?>" alt="" class="post__image <?php echo $timelinePost['filter']; ?>" id="singlePostImg"></a>
		<div class="post__info">
			<span><span id="likeCount" class="likeCount"><?php echo $post->countLikes($timelinePost['id']) ?></span> likes</span>
			<span><?php echo $post->timeAgo($timelinePost['timestamp']); ?></span>
		</div>
		<div class="post__description">
			<p><a href="profile.php?profile=<?php echo $timelinePost['username'] ?>"><?php echo $timelinePost['username']; ?></a> <?php echo $post->tagPostDescription($timelinePost['description']) ?></p>
		</div>
		<div class="post__comments">
			<?php $allComments = $comment->getLatestCommentsForPost($timelinePost['id'], 4)?>
			<?php if(count($allComments) > 0):?>
				<?php
					$commentCount = $comment->countCommentsForPost($timelinePost['id']);
					if($commentCount > 4){
						echo "<a href=post.php?p=".$timelinePost['id']."&u=".$timelinePost['userID']." class='post__comments__more'>View all ". $commentCount ." comments</a>";
					}
				?>
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
						<input type='submit' id='btnLike' value='Dislike' name='btnLike' class='btnLike heart heart--like' data-postid='". $timelinePost['id'] ."'>
						<input name='likePostID' id='likePostID' type='hidden' value='". $timelinePost['id'] ."'>
					</form>";
				}else{
					// NOT LIKED YET
					echo "<form action='' method='post'>
						<input type='submit' id='btnLike' value='Like' name='btnLike' class='btnLike heart' data-postid='". $timelinePost['id'] ."'>
						<input name='likePostID' id='likePostID' type='hidden' value='". $timelinePost['id'] ."'>
					</form>";
				}
			}
			?>

			<form action="" method="post">
				<div class="input-group">
					<input type="text" class="form-control inputComment" name="inputComment" id="inputComment" placeholder="Add a comment...">
					<span class="input-group-btn">
					<input type="submit" name="btnPlaceComment" id="btnPlaceComment" value="Submit" class="btn btn-default btnPlaceComment" data-postid="<?php echo $timelinePost['id'] ?>">
					</span>
				</div>
				<input type="hidden" id="inputPostID" name="inputPostID" value="<?php echo $timelinePost['id'];?>">
			</form>
			
		</div>
		<div class="scheidingStreep"></div>
        <div class="flagDeleteButton">
        	<a class="btn-open" href="#" onclick="return false;">...</a>
        </div>
       
	</div>
	 <div class="flagDeleteWrap <?php echo $timelinePost['id']."fdw"; ?>">
        <section class="flagDeleteMenu">
		<div class="commentsFlag">
			<form action="" method="POST">
				<input type="hidden" name="postID" class="flagID" value="<?php echo $timelinePost['id']; ?>">
				<?php if($post->checkIfFlagged($timelinePost['id']) == true): ?>
					<button type="submit" class="post__flag <?php echo "btn" . $timelinePost['id']; ?> flagged" name="flagPost"><span class="glyphicon <?php echo "f" . $timelinePost['id']; ?>"></span>Report inappropriate</button>
				<?php else: ?>
					<button type="submit" class="post__flag <?php echo "f" . $timelinePost['id']; ?>" name="flagPost"><span class="glyphicon <?php echo "f" . $timelinePost['id']; ?> "></span>Report inapropriate</button>
				<?php endif; ?>
			</form>
		</div>
		<div class="cancelFlagDelete">
        	<a href="#">Cancel</a>
        </div>
		</section>
		</div>
<?php endforeach; ?>
<?php endif; ?>
</div>

<?php include 'footer.inc.php'; ?>
</div>

<script type="text/javascript">
		$('.post').on("click", function(e){
			var flagID = $(this).find('input[type="hidden"]').val();
			console.log(flagID);
		
			if($(e.target).is('.flagDeleteButton a')){
				$('body').css('overflow', 'hidden');
				$("."+flagID+"fdw").show();
			}
			//e.preventDefault();
		});
		
	$('.flagDeleteWrap').on('click', function(e){
		$('.flagDeleteWrap').hide();
		$('body').css('overflow', 'scroll');
	});
	
	$('.cancelFlagDelete a').click(function(e){
		e.preventDefault();
	});
</script>
</body>
</html>
