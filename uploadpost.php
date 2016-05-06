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

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="public/js/jquery-2.2.3.min.js"></script>
	<link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
	<script src="public/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="public/css/style.css" type="text/css">
	<link rel="stylesheet" href="public/css/cssgram.min.css">
</head>
<body>
<script type="text/javascript"> 
	$(document).ready(function(){
		
		function previewImage(input){
			if(input.files && input.files[0]){
				
				var reader = new FileReader();
				
				reader.onload = function(e){
					$("#previewImage").attr('src', e.target.result);
					$('.filterPreview').attr('src', e.target.result);
					$("#previewImage").css('width', '100%');
					$('.filterPreview').css('width', 80);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		
		$('.filterSelection ul li a').on('click', function(e){
			e.preventDefault();
		});
		
		$('._1977').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('_1977'); $(".selectedFilter").val('_1977'); });
		$('.aden').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('aden'); $(".selectedFilter").val('aden'); });
		$('.brooklyn').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('brooklyn'); $(".selectedFilter").val('brooklyn'); });
		$('.clarendon').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('clarendon'); $(".selectedFilter").val('clarendon'); });
		$('.earlybird').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('earlybird'); $(".selectedFilter").val('earlybird'); });
		$('.gingham').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('gingham'); $(".selectedFilter").val('gingham'); });
		$('.hudson').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('hudson'); $(".selectedFilter").val('hudson'); });
		$('.inkwell').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('inkwell'); $(".selectedFilter").val('inkwell'); });
		$('.lark').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('lark'); $(".selectedFilter").val('lark'); });
		$('.lofi').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('lofi'); $(".selectedFilter").val('lofi'); });
		$('.mayfair').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('mayfair'); $(".selectedFilter").val('mayfair'); });
		$('.moon').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('moon'); $(".selectedFilter").val('moon'); });
		$('.nashville').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('nashville'); $(".selectedFilter").val('nashville'); });
		$('.perpetua').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('perpetua'); $(".selectedFilter").val('perpetua'); });
		$('.reyes').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('reyes'); $(".selectedFilter").val('reyes'); });
		$('.rise').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('rise'); $(".selectedFilter").val('rise'); });
		$('.slumber').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('slumber'); $(".selectedFilter").val('slumber'); });
		$('.toaster').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('toaster'); $(".selectedFilter").val('toaster'); });
		$('.walden').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('walden'); $(".selectedFilter").val('walden'); });
		$('.willow').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('willow'); $(".selectedFilter").val('willow'); });
		$('.xpro2').on('click', function(e){ $('#previewImage').removeClass(); $('#previewImage').addClass('xpro2'); $(".selectedFilter").val('xpro2'); });
		
		
		$("#fileToUpload").change(function(e){
			
			$.ajax({
				url: "ajax/uploadPreview.php",
				type: "POST",
				data: new FormData($('#postForm')[0]),
				dataType: 'json',
				contentType: false,
				cache: false,
				processData: false,
				success: function(data){
					console.log(data);
					if(data.check == "success"){
						$("#previewImage").css('display', 'block');
						$('.filterSelection').css('display', 'block');
						$('.filterSelection').css('width', '100%');
						$('.filterSelection').css('overflow-x', 'scroll');
						$('.filterSelection ul li img').css('display', 'block');
						$("#postFeedback").html("");
						previewImage(e.target);
					}else{
						$("#previewImage").css('display', 'none');
						$('.filterSelection').css('display', 'none');
						$("#postForm").find('input:file').val('');
						
						if(data.image == "false"){
							$("#postFeedback").html("This file is not an image.");
						}else if(data.image != "false"){
							if(data.ratio == "false"){
								$("#postFeedback").html("The aspect ratio of this image is off. Choose a different image.");
							}
							
							if(data.size == "false"){
								$("#postFeedback").html('This image is too large. Choose a different one.');
							}
								
							if(data.format == "false"){
								$("#postFeedback").html('This is not an image.');
							}
								
							if(data.dimensions == "false"){
								$("#postFeedback").html('This image is not large enough. The minimum size is 600x380');
							}
						}
					}
				}
			});
		});
		
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
							$('.filterSelection').css('display', 'none');
							$('#previewImage').css('display','none');
						}
					}
				},
				error: function (request, status, error) {
					console.log(error);
				}
			});
			e.preventDefault();
		});
	});
</script>

 <?php include 'nav.inc.php'; ?>

<script type="application/javascript" src="public/js/interaction.js"></script>
	<div class="container">
	<div class="row">
		<h1>Make a post</h1>
		<div class="col-sm-9">
		<section class="filterWrap">
		<div><img src="" id="previewImage" alt=""></div>
		<div class="filterSelection">
			<ul>
				<li><a href="#" class="filter"><img src="" class="filterPreview _1977" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview aden" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview brooklyn" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview clarendon" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview earlybird" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview gingham" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview hudson" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview inkwell" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview lark" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview lofi" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview mayfair" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview moon" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview nashville" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview perpetua" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview reyes" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview rise" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview slumber" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview toaster" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview walden" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview willow" alt=""/></a></li>
				<li><a href="#" class="filter"><img src="" class="filterPreview xpro2" alt=""/></a></li>
			</ul>
		</div>
		</section>
		
		<p id="postFeedback"></p>
		<form action="uploadpost.php" id="postForm" method="POST" enctype="multipart/form-data">
			<div class="form-group">
				<label for="postImage">Upload image</label>
				<input type="file" id="fileToUpload" name="postImage" class="form-control">
				
			</div>
				
				<input type="hidden" class="selectedFilter" name="filter" value="normal">
				
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