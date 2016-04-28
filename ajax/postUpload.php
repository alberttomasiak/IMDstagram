<?php
include_once("../classes/Post.class.php");
session_start();
$post = new Post();

if(!empty($_POST)){
	
	$file_tmp_name = $_FILES['postImage']['tmp_name'];
	
	$post->checkIfImage();
	$post->checkFileSize();
	$post->checkFileFormat();
	$post->checkAspectRatio();
	
	if(!$post->checkIfImage()){
		$uploadStatus['image'] = "false";
	}
	
	if(!$post->checkFileSize()){
		$uploadStatus['size'] = "false";
	}
	
	if(!$post->checkFileFormat()){
		$uploadStatus['format'] = "false";
	}
	
	if(!$post->checkAspectRatio()){
		$uploadStatus['ratio'] = "false";
	}
	
	if($post->checkIfImage() && $post->checkFileSize() && $post->checkFileFormat() && $post->checkAspectRatio() && $_POST['description']){
		$result = $post->createPost($file_tmp_name);
		$uploadStatus['check'] = "success";
	}else{
		$uploadStatus['check'] = "error";
	}
	
	header('Content-Type: application/json; charset=utf-8', true);
	echo json_encode($uploadStatus);
}

?>