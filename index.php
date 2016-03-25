<?php
    include_once 'classes/User.class.php';
    session_start();

    if(isset($_SESSION['loggedin'])){

    }else{
      header('location: login.php');
    }


?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>IMDStagram</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="public/css/reset.css" media="screen" title="no title" charset="utf-8">
  <link rel="stylesheet" href="public/css/style.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
    <?php include 'nav.inc.php'; ?>

    <?php include 'footer.inc.php'; ?>
</body>
</html>
