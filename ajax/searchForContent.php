<?php

	include_once('../classes/Db.class.php');
	$conn = Db::getInstance();
	$sql = "SELECT * FROM user where username LIKE '%".$_POST['searchQuery']."%'";
	$array = $conn->query($sql);

	foreach($array as $key){

	?>

	<div class="searchResult"><a href="profile.php?profile=<?php echo $key['username'] ?>"><?php echo $key['username'] ?></a></div>

	<?php

	}

?>
