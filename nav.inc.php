<nav class="navbar navbar-default">

    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">
                <img alt="Brand" src="public/images/logo.png">
            </a>
        </div>
        <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Search">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="profile.php?profile=<?php echo $_SESSION['username']; ?>"><?php if($_SESSION['loggedin']==true){ echo $_SESSION['username']; } ?></a></li>
            <li><a href="logout.php" class="">Log out</a></li>
        </ul>

    </div>


</nav>
<!--
<nav class="navbar navbar-default">

    <ul>
        <li>
            <a href="index.php" class="homeLogo">logo</a>
        </li>
        <li>
            <h4><a class="Log Out" href="logout.php"><a class="username" href="profile.php?profile=<?php echo $_SESSION['username']; ?>"><?php if($_SESSION['loggedin']==true){ echo $_SESSION['username']; } ?></a></a></h4>
        </li>
    </ul>
</nav>-->
