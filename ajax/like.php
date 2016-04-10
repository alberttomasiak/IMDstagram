<?php
include_once("../classes/User.class.php");
include_once("../classes/Post.class.php");
session_start();

$post = new Post();
$user = new User();

// get posted values
$action = $_POST['action'];
$postID = $_POST['postID'];

if($action == 'like'){
    $post->like($postID);
    $response['status'] = 'success';
    $response['action'] = 'liked';
}else if($action == 'dislike'){
    $post->dislike($postID);
    $response['status'] = 'success';
    $response['action'] = 'disliked';
}


header('Content-type: application/json');
echo json_encode($response);
?>