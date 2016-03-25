<nav>
    <ul>
        <li>
            <a href="index.php" class="homeLogo">logo</a>
        </li>
        <li></li>
        <li>
            <h4><a class="Log Out" href="logout.php"><a class="username" href="profile.php?profile=<?php echo $_SESSION['username']; ?>"><?php if($_SESSION['loggedin']==true){ echo $_SESSION['username']; } ?></a></a></h4>
        </li>
    </ul>
</nav>

