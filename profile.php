<?php
    include_once 'classes/User.class.php';
    include_once 'classes/Post.class.php';
    session_start();

    if(isset($_SESSION['loggedin'])){
        $profile = $_GET['profile'];

        $user = new User();
        $userData = $user->getUserDetailsByUsername($profile);

        $post = new Post();
        $userPosts = $post->getAllForUser($userData['id']);
        //var_dump($userPosts);

        $followers = $user->getFollowers($userData['id']);
        //var_dump($followers);
        $following = $user->getFollowing($userData['id']);
        //var_dump($following);
    }else{
        header('location: login.php');
    }


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $userData['fullName']; ?> | IMDstagram</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <script src="public/js/interaction.js"></script>
    <link rel="stylesheet" href="public/css/cssgram.min.css">
</head>
<body>

<?php include 'nav.inc.php'; ?>
<div class="container">
    <header id="profileheader" class="row">
        <div class="profilepic col-xs-3">
            <img src="<?php echo $userData['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture">
        </div>
        <div class="col-xs-9 col-md-6">
        <div id="usernamewrap">
        <h1><?php echo $userData['username']; ?></h1>

        <?php
            // SHOW EDIT PROFILE INSTEAD OF FOLLOW WHEN IT'S YOUR OWN PROFILE
            if(isset($_SESSION['loggedin'])){
                if($userData['username'] == $_SESSION['username']){
                   echo "<a href='edit-profile.php' class='btn'>Edit profile</a>";
                }else if($user->isFollowing($userData['id']) == false){
                    //echo "<button class='btn btn-primary' data-id='" . $userData['id'] . "' id='btnFollow'>Follow</button>";
                    echo "<input type='submit' class='btn btn-primary' data-action='follow' data-id='" . $userData['id'] . "' id='btnFollow' value='follow'>";
                }else{
                    echo "<input type='submit' class='btn btn-primary active' data-action='stopfollowing' data-id='" . $userData['id'] . "' id='btnFollow' value='Following'>";
                }
            }
        ?>

        </div>
        <div class="about">
            <p>
            <h2 id="fullname"><?php echo $userData['fullName']; ?></h2>
            <span>
                <?php echo $userData['bio']; ?>
            </span>
            <a href="<?php echo $userData['website']; ?>"><?php echo $userData['website']; ?></a>
            </p>
        </div>
        <ul class="profilestats">
            <li>
                <span><?php echo $user->countPosts($userData['id']); ?></span> posts
            </li>
            <li>
                <a href="followers.php?profile=<?php echo $userData["username"] ?>" data-toggle="modal" data-target="#followersModal">
                    <span><?php echo $user->countFollowers($userData['id']); ?></span> followers
                </a>
            </li>
            <li>
                <a href="following.php?profile=<?php echo $userData["username"] ?>" data-toggle="modal" data-target="#followingModal">
                    <span><?php echo $user->countFollowing($userData['id']); ?></span> following
                </a>
            </li>
        </ul>
        </div>
    </header>
</div>

    <section class="ownContainer">
        <!-- SHOW UPLOAD PICTURE BUTTON WHEN IT'S YOUR OWN PROFILE -->
        <?php if(isset($_SESSION['loggedin']) && $userData['username'] == $_SESSION['username']): ?>
            <section>
                <a href="uploadpost.php">Upload a picture</a>
            </section>
        <?php endif; ?>

        <!-- SHOW POSTS OR SHOW MESSAGE WHEN THERE ARE NO POSTS -->
        <?php if($userPosts == false): ?>
            <p>No posts yet.</p>
        <?php else: ?>
            <div class="gallery">
            <?php foreach( $userPosts as $key => $userPost ): ?>
<a href="post.php?p=<?php echo $userPost['id'] .'&u='. $userData['id'] ?>" style="background-image: url(<?php echo "'".$userPost['path']."'" ?>)" class="gallery__item <?php echo $userPost['filter']; ?>"></a>

            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
    <?php include 'footer.inc.php'; ?>

    <!-- POPUP FOR FOLLOWERS - not visible -->
    <div id="followersModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Followers</h4>
                </div>
                <div class="modal-body">
                    <ul>
                        <?php foreach($followers as $key => $follower): ?>
                        <li>
                            <img src="<?php echo $follower['profilePicture']; ?>" alt="<?php echo $follower['username']; ?>'s profile picture">
                            <a href="profile.php?profile=<?php echo $follower['username']; ?>"><?php echo $follower['username']; ?></a>
                            <span><?php echo $follower['fullName']; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- POPUP FOR FOLLOWING - not visible -->
    <div id="followingModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Following</h4>
                </div>
                <div class="modal-body">
                    <ul>
                        <?php foreach($following as $key => $follower): ?>
                            <li>
                                <img src="<?php echo $follower['profilePicture']; ?>" alt="<?php echo $follower['username']; ?>'s profile picture">
                                <a href="profile.php?profile=<?php echo $follower['username']; ?>"><?php echo $follower['username']; ?></a>
                                <span><?php echo $follower['fullName']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


</body>
</html>      