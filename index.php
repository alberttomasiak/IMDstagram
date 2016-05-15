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

<?php include 'index.inc.php'; ?>

</div>
	<div class="loadbutton"><div class="circle"><span class="loadmorePosts" data-page="2">Load More</span></div></div>
<?php include 'footer.inc.php'; ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
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
		
		$('.loadmorePosts').on("click", function(e){
			$.ajax({
				url: "ajax/loadMorePosts.php",
				type: "POST",
				dataType: 'html',
				data: {page: $(this).data('page'),},
				success: function(data){
					$('.container--custom').append(data);
					if($('.loadbuttonNoFeed').html() != ""){
						$('.loadbutton').hide();
					}
				}
			});
			e.preventDefault();
		});
		
	});
</script>
</body>
</html>
