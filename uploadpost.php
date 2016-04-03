<?php 

include_once('classes/Db.class.php');
include_once('classes/Post.class.php');
include_once('classes/User.class.php');
session_start();

if(!empty($_POST)){
	$post = new Post();
	try{
		$post->createPost();
	}
	catch(Exception $e){
		echo "Something went wrong.";
	}
}

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
</head>
<body>
<script type="application/javascript" src="public/js/uploadPostCheck.js"></script>
	
	<p id="postFeedback"></p>
	<form action="uploadpost.php" id="postForm" method="POST" enctype="multipart/form-data">
		<label for="postImage">Upload image</label>
		<input type="file" id="fileToUpload" name="postImage">
		<label for="description">Write a caption</label>
		<textarea name="description" id="postMessage" cols="30" rows="10"></textarea>
		<input type="submit" id="postSubmit" value="Create post" name="upload_image">
	</form>
</body>
</html>