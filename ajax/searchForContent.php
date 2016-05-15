<?php
	include_once('../classes/Db.class.php');
	include_once('../classes/User.class.php');
	session_start();
	
	$username = $_SESSION['username'];
	$user = new User();
	$userData = $user->getUserDetailsByUsername($username);
	$_SESSION['searchQuery'] = preg_replace('/#([\w-]+)/i', '$1', $_POST['searchQuery']);
	list($searchQuery) = explode(' ', $_SESSION['searchQuery']);
	
	$conn = Db::getInstance();
	$sqlUsers = "SELECT * FROM user where username LIKE '%".$_POST['searchQuery']."%'";
	$arrayUsers = $conn->query($sqlUsers);

	$sqlHashtags = "SELECT post.description, p.userID FROM post
					LEFT JOIN user
					ON user.id = post.userID
					WHERE user.id IS NULL 
					AND post.userID IS NOT NULL";


	//$sqlHashtags = "SELECT * from post where description LIKE '%".$_POST['searchQuery']."%'";
	$arrayHashtags = $conn->query($sqlHashtags);
	//foreach($arrayUsers as $key)
	
	$i = 0;
	foreach($arrayUsers as $key){
	?>
	<div class="searchResult"><a href="profile.php?profile=<?php echo $key['username'] ?>"><img src="<?php echo $key['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture"><?php echo $key['username'] ?></a></div>
	<?php
		if(++$i == 5) break;
	}

	
	if(!$arrayHashtags){
		
	?>

	<div class="searchResult searchTag"><a href="tag.php?tag=<?php echo $searchQuery; ?>"><?php echo "<p class='hashtagSearch'>#</p>".$searchQuery; ?></a></div>
	<div class="searchResult moreResultsLink"><a href="search.php?tag=<?php echo $_SESSION['searchQuery'] ?>">Show more results</a></div>
	<?php
	}
?>
