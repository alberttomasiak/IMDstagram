<?php
include_once("../classes/User.class.php");
session_start();
$user = new User();

$file = $_FILES['profileImage']['tmp_name'];

$user->checkIfImage();
$user->checkFileSize();
$user->checkAspectRatio();
$user->checkFileDimensions();

if(!$user->checkIfImage()){
	$status['image'] = "false";
}
	
if(!$user->checkFileSize()){
	$status['size'] = "false";
}
	
if(!$user->checkAspectRatio()){
	$status['ratio'] = "false";
}
	
if(!$user->checkFileDimensions()){
	$status['dimensions'] = "false";
}
	
if($user->checkIfImage() && $user->checkFileSize() && $user->checkAspectRatio() && $user->checkFileDimensions()){
	$result = $user->setProfilePicture();
	$status['check'] = "success";
}else{
	$status['check'] = "error";
}


header('Content-Type: application/json; charset=utf-8', true);
echo json_encode($status);
?>