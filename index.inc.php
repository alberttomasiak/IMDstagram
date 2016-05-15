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