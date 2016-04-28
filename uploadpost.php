<?php 

include_once('classes/Db.class.php');
include_once('classes/Post.class.php');
include_once('classes/User.class.php');
session_start();

if(!empty($_POST)){
	$post = new Post();
	try{
		$post->createPost($_FILES['postImage']['tmp_name']);
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
	<script src="public/js/jquery-2.2.3.min.js"></script>
	<link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
	<script src="public/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="public/css/style.css" type="text/css">
</head>
<body>
<script type="text/javascript"> 
	$(document).ready(function(){
		
		$("#postSubmit").on("click", function(e){
			var image = $("#fileToUpload");
			var message = $("#postMessage");
			
			$.ajax({
				url: "ajax/postUpload.php", 
				type: "POST",             
				data: new FormData($("#postForm")[0]),
				dataType: 'json',
				contentType: false,       
				cache: false,             
				processData:false,        
				success: function(data)  
				{
					if(image.val() == "" || message.val() == ""){
						$("#postFeedback").html("The fields cannot be empty.");
					}else if(image != "" && message != ""){
						if(data.check == "success"){
							$("#postFeedback").html("The image has been succesfully uploaded.");
							$("#postForm").find('textarea').val('');
							$("#postForm").find('input:file').val('');
						}else if(data.check == "error"){
							if(data.image == "false"){
								$("#postFeedback").html("This is not an image.");
								$("#postForm").find('input:file').val('');
							}else if(data.image != "false"){
								if(data.size == "false"){
									$("#postFeedback").html("This file is too large to be uploaded");
									$("#postForm").find('input:file').val('');
								}
								if(data.format == "false"){
									$("#postFeedback").html("This is not an image.");
									$("#postForm").find('input:file').val('');
								}
								if(data.ratio == "false"){
									$("#postFeedback").html("The ratio of your image is off. Choose a different image");
									$("#postForm").find('input:file').val('');
								}
							}
						}
					}
					
				},
				error: function (request, status, error) {
					console.log(request);
					
				}
			});
			e.preventDefault();
		});
	});
</script>

 <?php include 'nav.inc.php'; ?>

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