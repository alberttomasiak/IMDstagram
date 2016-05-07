<?php
include_once("../classes/Post.class.php");
session_start();
$post = new Post();

if(!empty($_POST)){
	$flagID = $_POST['flagID'];
	if($post->checkIfFlagged($flagID)){
		$status['flagged'] = "true";
	}
	
	if($post->countFlags($flagID)){
		$post->deletePost($flagID);
	}
	
	if(!$post->checkIfFlagged($flagID) && !$post->countFlags($flagID)){
		if($post->countFlags($flagID) == true){
			//$post->deletePost($flagID);
		}
		$post->flagPost($flagID);
		$status['check'] = "true";
	}else{
		$post->unFlagPost($flagID);
		$status['check'] = "false";
	}
	
	header('Content-Type: application/json; charset=utf-8', true);
	echo json_encode($status);
}
?>