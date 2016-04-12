<?php
include_once("inc/facebook.php"); //include facebook SDK
######### Facebook API Configuration ##########
$appId = '489924181203481'; //Facebook App ID
$appSecret = '25bb324d6d72f1bb03dec2386b20ac61'; // Facebook App Secret
$homeurl = 'http://localhost/Facebook/';  //return to home
$fbPermissions = 'email';  //Required facebook permissions

//Call Facebook API
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $appSecret

));
$fbuser = $facebook->getUser();
?>