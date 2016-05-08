<?php
    include_once 'classes/User.class.php';
	include_once 'classes/Post.class.php';
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

	if(!empty($_POST)){
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
    <section class="postsWrapper">
    <?php if ($timelinePosts == false): ?>
    	<p>There are no posts to display yet. Try following some people.</p>
    <?php else: ?>
    <?php foreach($timelinePosts as $key => $timelinePost): ?>
        <article class="postTimeline <?php echo $timelinePost['id']; ?>">
          <div class="postHeader">
          <div class="postUser">
           <!-- Profile picture -->
           <img src="<?php echo $userData['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture">
            
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
    <?php include 'footer.inc.php'; ?>
</body>
</html>
