<?php
include_once("../classes/Post.class.php");
session_start();
$post = new Post();

if(!empty($_POST)){
	$flagID = $_POST['flagID'];
	if($post->checkIfFlagged($flagID)){
		$status['flagged'] = "true";
	}else{
		$status['flagged'] = "false";
	}
	
	header('Content-Type: application/json; charset=utf-8', true);
	echo json_encode($status);
}
?>