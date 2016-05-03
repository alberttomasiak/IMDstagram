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
</head>
<body>
    <?php include 'nav.inc.php'; ?>
    <?php if ($timelinePosts == false): ?>
    	<p>There are no posts to display yet. Try following some people.</p>
    <?php else: ?>
    <?php foreach($timelinePosts as $key => $timelinePost): ?>
        <article class="postTimeline">
          <div class="postHeader">
          <div class="postUser">
           <!-- Profile picture -->
           <img src="<?php echo $userData['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture">
            
            <!-- Username link -->
            <a href="profile.php?profile=<?php echo $timelinePost['username']; ?>"><?php echo $timelinePost['username']; ?></a>
            </div>
            <!-- Timestamp -->
            <p class="postTimestamp"><?php echo $post->timeAgo($timelinePost['timestamp']); ?></p>
            </div>
            
            
            <!-- Image met link naar de bijhorende post pagina -->
            <a class="postImage" href="post.php?p=<?php echo $timelinePost['id'] ?>&u=<?php echo $timelinePost['userID'] ?>">
            <img src="<?php echo $timelinePost['path']; ?>" class="<?php echo $timelinePost['filter']; ?>" alt="">
        	</a>
        	
        	
        	<!-- wrapper voor comments en like -->
        	<div class="commentsWrapper">
				
       	
        	<!-- Hier komen de comments voor elke post -->
        	<div class="commentsPost">
        		<ul>
        			<!-- comments in li => username + comment -->
        		</ul>
        	</div>
        	</div>
        	
        </article>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php include 'footer.inc.php'; ?>
</body>
</html>
