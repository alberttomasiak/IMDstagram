<?php
include_once("../classes/Db.class.php");
include_once("../classes/User.class.php");
include_once("../classes/Post.class.php");
include_once("../classes/Comment.class.php");
session_start();
$username = $_SESSION['username'];
$user = new User();
$userData = $user->getUserDetailsByUsername($username); 
		
$post = new Post();
$conn = Db::getInstance();

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

if(isset($_POST['page'])):
$paged = $_POST['page'];
$activeUser = $_SESSION['userID'];
$query = "SELECT user.username, user.profilePicture, user.profilePicture, post.id, post.userID, post.path, post.description, post.timestamp, post.filter, post.location
 											FROM post
 											INNER JOIN user
 											ON post.userID=user.id
 											INNER JOIN follow
 											ON user.id=follow.followingID
 											WHERE follow.followerID = '$activeUser' AND follow.accepted = 1
 											ORDER BY timestamp DESC";


$resultsPerPage = 20;

if($paged > 0){
	$page_limit = $resultsPerPage*($paged-1);
	$pagination_sql = " LIMIT $page_limit, $resultsPerPage";
}else{
	$pagination_sql = " LIMIT 0, $resultsPerPage";
}
$timelinePosts = $conn->query($query.$pagination_sql);
$num_rows = $timelinePosts->fetch(PDO::FETCH_ASSOC);

if($num_rows > 0){ ?><!DOCTYPE html>
<html lang="en">
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
<div class="container--custom">
<?php include '../index.inc.php'; ?>
</div>
<?php
}
if($num_rows == $resultsPerPage){ ?>
<div class="loadbutton"><input type="button" class="loadmore" value="Load More" data-page="<?php echo $paged+1 ;?> "></div>
<?php
}else{
	echo "<div class='loadbuttonNoFeed'><p>No more content to show</p></div>";
}
endif;
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.post').on("click", function(e){
			var flagID = $(this).find('input[type="hidden"]').val();
		
			if($(e.target).is('.flagDeleteButton a')){
				$('body').css('overflow', 'hidden');
				$('body').css('margin-right', '16px');
				$("."+flagID+"fdw").show();
			}
			//e.preventDefault();
		});
		
		$('.flagDeleteWrap').on('click', function(e){
			$('.flagDeleteWrap').hide();
			$('body').css('margin-right', '0px');
			$('body').css('overflow', 'scroll');
		});
	
		$('.cancelFlagDelete a').click(function(e){
			e.preventDefault();
		});

	});
</script>
</body>
</html>


