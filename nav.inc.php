<?php
include_once("classes/Db.class.php");
include_once("classes/User.class.php");
$user = new User();
$notificationNumber = $user->countPendingRequests();
?><nav class="nav">
    <div class="nav__container">
        <a href="index.php" class="nav__logo">logo</a>

        <form class="nav__form" action="" method="post" role="search">
            <input type="text" id="searchBox" autocomplete="off" name="search" class="searchid form-control" placeholder="Search">
            <ul id="searchResults"></ul>
        </form>

        <div class="nav__links">
            <?php if($notificationNumber > 0): ?>
            <a href="notifications.php" class="nav__links__item" aria-label="Notifications" title="Notifications">
                <span class="glyphicon glyphicon-bell blink" aria-hidden="true"></span>
            </a>
            <?php endif; ?>
            <a href="uploadpost.php" class="nav__links__item" aria-label="Make a post" title="Post"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span></a>
            <a href="profile.php?profile=<?php echo $_SESSION['username']; ?>" class="nav__links__item" aria-label="Your profile" title="<?php echo $_SESSION['username']; ?>">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
            </a>
            <a href="logout.php" class="nav__links__item" aria-label="Log out" title="Log out">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
    </div>
</nav>
<script src="public/js/interaction.js" type="text/javascript"></script>