<?php
include_once("../classes/Post.class.php");
session_start();

$post = new Post();
$postID = $_POST['postID'];

if($post->checkIfLiked($postID) == true){
    // ALREADY LIKED
    if($post->like($postID)){
        // DISLIKE
        $response['status'] = 'success';
        $response['action'] = 'disliked';
    }
}else{
    // NOT LIKED
    if($post->like($postID)){
        // LIKE
        $response['status'] = 'success';
        $response['action'] = 'liked';
    }
}

header('Content-type: application/json');
echo json_encode($response);
?>