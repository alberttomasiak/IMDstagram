<?php
include_once("../classes/User.class.php");
session_start();

$user = new User();

// get posted values
$action = $_POST['action'];
$followingID = $_POST['followingID'];

if($action == 'follow'){
    $user->follow($followingID);
    $response['status'] = 'success';
    $response['action'] = 'following';
}else if($action == 'stopfollowing'){
    $user->stopFollowing($followingID);
    $response['status'] = 'success';
    $response['action'] = 'notfollowing';
}


header('Content-type: application/json');
echo json_encode($response);
?>