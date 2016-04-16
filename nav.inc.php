<?php
include_once("classes/Db.class.php");
include_once("classes/User.class.php");

?><script src="public/js/searchForContent.js" type="text/javascript"></script>
<nav class="navbar navbar-default">

    <div class="container-fluid">
        <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">
                <img alt="Brand" src="public/images/logo.png">
            </a>
        </div>
        <form class="navbar-form navbar-left" action="" method="post" role="search">
            <div class="form-group">
                <input type="text" id="searchBox" name="search" class="searchid form-control" placeholder="Search">
                <ul id="searchResults"></ul>
            </div>
        </form>

        <ul class="nav navbar-nav navbar-right">
            <li><a href="uploadpost.php" aria-label="Make a post" title="Post"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span></a></li>
            <li><a href="index.php" aria-label="Notifications" title="Notifications"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span><span class="badge">5</span></a></li>
            <li><a href="profile.php?profile=<?php echo $_SESSION['username']; ?>" aria-label="Your profile" title="<?php echo $_SESSION['username']; ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a></li>
            <li><a href="logout.php" aria-label="Log out" title="Log out"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a></li>
            <!--<li><a href="profile.php?profile=<?php echo $_SESSION['username']; ?>"><?php if($_SESSION['loggedin']==true){ echo $_SESSION['username']; } ?></a></li>
            <li><a href="logout.php" class="">Log out</a></li>-->
        </ul>
        </div>
    </div>


</nav>