<?php
include_once("classes/Db.class.php");
include_once("classes/User.class.php");
session_start();

$Get_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$parts = explode('=', $Get_url);
$value = $parts[count($parts) - 1];

$username = $_SESSION['username'];
$user = new User();
$userData = $user->getUserDetailsByUsername($username);


// USERS
$resultsPerPage = 5;
$conn = Db::getInstance();
$sqlUsers = "SELECT * FROM user where username LIKE '%".$_SESSION['searchQuery']."%' LIMIT 0, $resultsPerPage";
$arrayUsers = $conn->query($sqlUsers);




// HASHTAG
$sqlHashtags = "SELECT post.description, p.userID FROM post
				LEFT JOIN user
				ON user.id = post.userID
				WHERE user.id IS NULL 
				AND post.userID IS NOT NULL";
$arrayHashtags = $conn->query($sqlHashtags);
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Search Results | Imdstagram</title>
    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
</head>
<body>

		<script type="text/javascript">
		$(document).ready(function(){
			$('.loadmore').on("click", function(e){
				$.ajax({
					url: "ajax/loadMoreResults.php",
					type: "POST",
					dataType: 'html',
					data: { page: $(this).data('page'),
					},
					success: function(data){
						$('#moreSearchResults').append(data);
						if($('.loadbuttonNoFeed').html() != ''){
							$('.loadbutton').hide();
						}
					}
				});
				e.preventDefault();
			});
		});
		</script>
	<?php include_once("nav.inc.php"); ?>

	<section class="moreResults">
	<h3>Search results for: <?php echo $value; ?></h3>
	<div class="scheiding"></div>
	<div id="moreSearchResults">
		
		<?php if(!$arrayHashtags): ?>
		<div class="searchResult searchTag"><a href="tag.php?tag=<?php echo $_SESSION['searchQuery'] ?>"><?php echo "<p class='hashtagSearch'>#</p>".$_SESSION['searchQuery']; ?></a></div>
		<?php endif; ?>
		
		<!-- ARRAY USERS HIER -->
		<?php foreach($arrayUsers as $data): ?>
		<div class="searchResult"><a href="profile.php?profile=<?php echo $data['username'] ?>"><img src="<?php echo $data['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture"><?php echo $data['username'] ?></a></div>
		<?php endforeach; ?>
		
	</div>
		<div class="loadbutton"><div class="circle"><span class="loadmore" data-page="2">Load more</span></div></div>
	</section>

</body>
</html>