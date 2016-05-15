<?php
include_once("../classes/Db.class.php");
include_once("../classes/User.class.php");
session_start();
$username = $_SESSION['username'];
$user = new User();
$userData = $user->getUserDetailsByUsername($username);
$conn= Db::getInstance();

if(isset($_POST['page'])):
$paged = $_POST['page'];
$sqlUsers = "SELECT * FROM user where username LIKE '%".$_SESSION['searchQuery']."%'";
$arrayUsers = $conn->query($sqlUsers);

$resultsPerPage = 5;

if($paged>0){
	$page_limit=$resultsPerPage*($paged-1);
	$pagination_sql = " LIMIT $page_limit, $resultsPerPage";
}else{
	$pagination_sql = " LIMIT 0, $resultsPerPage";
}
$result = $conn->query($sqlUsers.$pagination_sql);
$num_rows = $result->fetch(PDO::FETCH_ASSOC);
if($num_rows > 0){ ?>
	<?php foreach($result as $data): ?>
	<div class="searchResult"><a href="profile.php?profile=<?php echo $data['username'] ?>"><img src="<?php echo $data['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture"><?php echo $data['username'] ?></a></div>
	<?php endforeach; ?>
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