<?php
include_once("../classes/User.class.php");
include_once("../classes/Comment.class.php");
session_start();

$comment = new Comment();

// get posted values
$text = $_POST['comment'];
$postID = $_POST['postID'];
$userID = $_SESSION['userID'];
$username = $_SESSION['username'];

if($comment->createComment($postID, $text)){
    $response['status'] = 'success';
    $response['text'] = $comment->tagComments(htmlspecialchars($text));
    $response['username'] = $username;
}else{
    $response['status'] = 'fail';
}


header('Content-type: application/json');
echo json_encode($response);

?>