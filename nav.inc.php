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
            <li><a href="profile.php?profile=<?php echo $_SESSION['username']; ?>"><?php if($_SESSION['loggedin']==true){ echo $_SESSION['username']; } ?></a></li>
            <li><a href="logout.php" class="">Log out</a></li>
        </ul>
        </div>
    </div>


</nav>