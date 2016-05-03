<?php

include_once("../classes/Post.class.php");
session_start();
$post = new Post();

$post->checkIfImage();
$post->checkFileSize();
$post->checkFileFormat();
$post->checkAspectRatio();
$post->checkFileDimensions();

if(!$post->checkIfImage()){
	$imageCheck['image'] = "false";
}

if(!$post->checkFileSize()){
	$imageCheck['size'] = "false";
}
	
if(!$post->checkFileFormat()){
	$imageCheck['format'] = "false";
}
	
if(!$post->checkAspectRatio()){
	$imageCheck['ratio'] = "false";
}
	
if(!$post->checkFileDimensions()){
	$imageCheck['dimensions'] = "false";
}

	
if($post->checkIfImage() && $post->checkFileSize() && $post->checkFileFormat() && $post->checkAspectRatio() && $post->checkFileDimensions()){
	$imageCheck['check'] = "success";
}else{
	$imageCheck['check'] = "failed";
}

header('Content-Type: application/json; charset=utf-8', true);
echo json_encode($imageCheck);

?>