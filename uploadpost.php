<?php 

if(!empty($_POST)){
	include_once('classes/Db.class.php');
	include_once('classes/User.class.php');
	session_start();
	$user = new User();
	$userData = $user->getAll($_SESSION['username']);
	$uploadReady = 1;
	$file_name = $userData['id']."-".time().".jpg";
	$file_tmp_name = $_FILES['postImage']['tmp_name'];
	$file_path = "img/".$file_name;
	$imageFileType = pathinfo($file_path, PATHINFO_EXTENSION);
	$check = getimagesize($file_tmp_name);


	// CHECK IF IMAGE OR NOT
	if(!empty($_POST['upload_image'])){
		if($check !== false){
			echo "File is an image.";
			$uploadReady = 1;
		}else{
			echo "File is not an image"."<br>";
			$uploadReady = 0;
		}
	}

	// CHECK IF FILE ISN'T LARGER THAN 2MB
	if($_FILES["postImage"]["size"] > 2000000){
		echo "Your image is too large.";
		$uploadReady = 0;
	}

	// CHECK FILE FORMAT & ALLOW ONLY IMAGES & GIFS
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadReady = 0;
	}

	// CHECK IF CONDITIONS ABOVE ARE MET
	if ($uploadReady == 0){
		echo "Your image couldn't be uploaded. Please try again.";
	} else if($uploadReady == 1 && !empty($_POST['upload_image'])){
		// als er een bestand is stuur het naar het img/ mapje + schrijf path en description naar DB.
		if($file_name){
			$db = Db::getInstance();
			$statement = $db->prepare("insert into post (userID, path, description) values (:userID, :path, :description)");
			$statement->bindValue(":userID", $userData['id']);
			$statement->bindValue(":path", $file_path);
			$statement->bindValue(":description", $_POST['description']);
			$result = $statement->execute();
			MOVE_UPLOADED_FILE($file_tmp_name, "img/$file_name");

		}
	}
}
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="uploadpost.php" method="POST" enctype="multipart/form-data">
		<label for="postImage">Upload image</label>
		<input type="file" name="postImage">
		<label for="description">Write a caption</label>
		<textarea name="description" id="" cols="30" rows="10"></textarea>
		<input type="submit" value="Create post" name="upload_image">
	</form>
</body>
</html>