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


$resultsPerPage = 5;

if($paged > 0){
	$page_limit = $resultsPerPage*($paged-1);
	$pagination_sql = " LIMIT $page_limit, $resultsPerPage";
}else{
	$pagination_sql = " LIMIT 0, $resultsPerPage";
}
$timelinePosts = $conn->query($query.$pagination_sql);
$num_rows = $timelinePosts->fetch(PDO::FETCH_ASSOC);

if($num_rows > 0){ ?>

<?php include '../index.inc.php'; ?>

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