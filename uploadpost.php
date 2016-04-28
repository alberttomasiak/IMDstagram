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
	<title>Upload post</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="public/js/jquery-2.2.3.min.js"></script>
	<link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
	<script src="public/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="public/css/style.css" type="text/css">
</head>
<body>
<script type="application/javascript" src="public/js/uploadPostCheck.js"></script>
	<div class="container">
	<div class="row">
		<h1>Make a post</h1>
		<div class="col-sm-7 col-md-5">
		<p id="postFeedback"></p>
		<form action="uploadpost.php" id="postForm" method="POST" enctype="multipart/form-data">
			<div class="form-group">
				<label for="postImage">Upload image</label>
				<input type="file" id="fileToUpload" name="postImage" class="form-control">
			</div>

			<div class="form-group">
				<label for="description">Write a caption</label>
				<textarea name="description" id="postMessage" cols="30" rows="5" class="form-control"></textarea>
			</div>
			<input type="submit" id="postSubmit" value="Create post" name="upload_image" class="btn btn-primary">
		</form>
		</div>
	</div>
	</div>
</body>
</html>