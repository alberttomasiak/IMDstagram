<?php
include_once("../classes/User.class.php");
session_start();

$user = new User();

// get posted values
$action = $_POST['action'];
$profileID = $_POST['profileID'];

if($action == 'follow'){
    if($user->isPrivate($profileID) == true){
        // private profile - follow request - change button to pending
        $user->follow($profileID);
        $response['status'] = 'success';
        $response['action'] = 'pending';
    }else if($user->isPrivate($profileID) == false){
        // public profile - follow - change button to following
        $user->follow($profileID);
        $response['status'] = 'success';
        $response['action'] = 'following';
    }

}else if($action == 'stopfollowing'){
    $user->stopFollowing($profileID);
    $response['status'] = 'success';
    $response['action'] = 'notfollowing';
}


header('Content-type: application/json');
echo json_encode($response);

?>