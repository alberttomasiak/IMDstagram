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

        // START DEBUG RELATIONSHIPS
        //$relation = $user->checkRelationship($userData['id']);
        //var_dump($relation);
        // STOP DEBUG RELATIONSHIPS

        if( !empty( $_POST['btnFollow'] ) ) {
            $profileID = $_POST['requestID'];
            try{
                $user->follow($_POST['profileID']);
                header('Location: '.$_SERVER['REQUEST_URI']);
            }catch(Exception $e){
                echo "follow error";
            }
        }
        if( !empty( $_POST['btnUnfollow'] ) ) {
            $profileID = $_POST['requestID'];
            try{
                $user->stopFollowing($_POST['profileID']);
                header('Location: '.$_SERVER['REQUEST_URI']);
            }catch(Exception $e){
                echo "follow error";
            }
        }
        if( !empty( $_POST['btnPending'] ) ) {
            $profileID = $_POST['requestID'];
            try{
                $user->stopFollowing($_POST['profileID']);
                header('Location: '.$_SERVER['REQUEST_URI']);
            }catch(Exception $e){
                echo "follow error";
            }
        }
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
<div class="nav--profile">
<?php include 'nav.inc.php'; ?>
</div>
<div class="hideMe">
    <header id="profileheader" class="row">
        <div class="profilepic col-xs-3">
            <img src="<?php echo $userData['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture">
        </div>
        <div class="col-xs-9 col-md-6">
        <div id="usernamewrap">
        <h1><?php echo $userData['username']; ?></h1>

        <?php
            // SHOW EDIT PROFILE INSTEAD OF FOLLOW WHEN IT'S YOUR OWN PROFILE
            /*if(isset($_SESSION['loggedin'])){
                if($userData['username'] == $_SESSION['username']){
                   echo "<a href='edit-profile.php' class='btn'>Edit profile</a>";
                }else if($user->isFollowing($userData['id']) == false){
                    //echo "<button class='btn btn-primary' data-id='" . $userData['id'] . "' id='btnFollow'>Follow</button>";
                    echo "<input type='submit' class='btn btn-primary' data-action='follow' data-id='" . $userData['id'] . "' id='btnFollow' value='follow'>";
                }else{
                    echo "<input type='submit' class='btn btn-primary active' data-action='stopfollowing' data-id='" . $userData['id'] . "' id='btnFollow' value='Following'>";
                }
            }*/
        ?>
        <?php
            if($userData['id'] == $_SESSION['userID']){
                // your profile
                echo "<a href='edit-profile.php' class='btn'>Edit profile</a>";
            }else{
                if($user->isFollowing($userData['id'])){
                    // toon following
                    echo "<form action='' method='post'>
                                <input type='submit' id='btnFollow' class='btn btn-success' name='btnUnfollow' value='Following'
                                data-id='".$userData['id']."' data-action='stopfollowing'>
                                <input type='hidden' name='profileID' value='".$userData['id']."'>
                                </form>";
                }else{
                    if($user->isPending($userData['id'])){
                        // toon pending
                        echo "<form action='' method='post'>
                                <input type='submit' id='btnFollow' class='btn btn-default' name='btnPending' value='Pending'
                                data-id='".$userData['id']."' data-action='stopfollowing'>
                                <input type='hidden' name='profileID' value='".$userData['id']."'>
                                </form>";
                    }else{
                        // toon follow
                        echo "<form action='' method='post'>
                                <input type='submit' id='btnFollow' class='btn btn-primary' name='btnFollow' value='Follow'
                                data-id='".$userData['id']."' data-action='follow'>
                                <input type='hidden' name='profileID' value='".$userData['id']."'>
                                </form>";
                    }
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
                <span id="stats--posts"><?php echo $user->countPosts($userData['id']); ?></span> posts
            </li>
            <li>
                <a href="followers.php?profile=<?php echo $userData["username"] ?>" data-toggle="modal" data-target="#followersModal">
                    <span id="stats--followers"><?php echo $user->countFollowers($userData['id']); ?></span> followers
                </a>
            </li>
            <li>
                <a href="following.php?profile=<?php echo $userData["username"] ?>" data-toggle="modal" data-target="#followingModal">
                    <span id="stats--following"><?php echo $user->countFollowing($userData['id']); ?></span> following
                </a>
            </li>
        </ul>
        </div>
    </header>
</div>

<header class="header--profile">
    <div class="container--custom--header">
        <div class="profileheader">
            <div class="profileheader__top">
                <img src="<?php echo $userData['profilePicture']; ?>" alt="" class="profileheader__picture">
                <div class="profileheader__right">
                    <div class="profileheader__top__title">
                        <h1 class="profileheader__username"><?php echo $userData['username']; ?></h1>
                        <?php
                        if($userData['id'] == $_SESSION['userID']){
                            // your profile
                            echo "<a href='edit-profile.php' class='btn btn-default profileheader__button'>Edit profile</a>";
                        }else{
                            if($user->isFollowing($userData['id'])){
                                // toon following
                                echo "<form action='' method='post'>
                                <input type='submit' id='btnFollow' class='btn btn-success profileheader__button' name='btnUnfollow' value='Following'
                                data-id='".$userData['id']."' data-action='stopfollowing'>
                                <input type='hidden' name='profileID' value='".$userData['id']."'>
                                </form>";
                            }else{
                                if($user->isPending($userData['id'])){
                                    // toon pending
                                    echo "<form action='' method='post'>
                                <input type='submit' id='btnFollow' class='btn btn-default profileheader__button' name='btnPending' value='Pending'
                                data-id='".$userData['id']."' data-action='stopfollowing'>
                                <input type='hidden' name='profileID' value='".$userData['id']."'>
                                </form>";
                                }else{
                                    // toon follow
                                    echo "<form action='' method='post'>
                                <input type='submit' id='btnFollow' class='btn btn-primary profileheader__button' name='btnFollow' value='Follow'
                                data-id='".$userData['id']."' data-action='follow'>
                                <input type='hidden' name='profileID' value='".$userData['id']."'>
                                </form>";
                                }
                            }
                        }
                        ?>
                    </div>

                    <div class="profileheader__about profileheader__about--big">
                        <p>
                        <h2 id="fullname" class="profileheader__about__name"><?php echo $userData['fullName']; ?></h2>
                                <span class="profileheader__about__bio">
                                <?php echo $userData['bio']; ?>
                                </span>
                        <a href="<?php echo $userData['website']; ?>" class="profileheader__about__site"><?php echo $userData['website']; ?></a>
                        </p>
                    </div>

                    <div class="profileheader__profilestats profileheader__profilestats--big">
                        <a href="#" class="profilestats__item">
                            <span id="stats--posts" ><?php echo $user->countPosts($userData['id']); ?></span> posts
                        </a>
                        <a href="#" class="profilestats__item" data-toggle="modal" data-target="#followersModal">
                            <span id="stats--followers"><?php echo $user->countFollowers($userData['id']); ?></span> followers
                        </a>
                        <a href="#" class="profilestats__item" data-toggle="modal" data-target="#followingModal">
                            <span id="stats--following"><?php echo $user->countFollowing($userData['id']); ?></span> following
                        </a>
                    </div>
                </div>
            </div>

            <div class="profileheader__about profileheader__about--small">
                <p>
                <h2 id="fullname" class="profileheader__about__name"><?php echo $userData['fullName']; ?></h2>
                        <span class="profileheader__about__bio">
                        <?php echo $userData['bio']; ?>
                        </span>
                <a href="#" class="profileheader__about__site"><?php echo $userData['website']; ?></a>
                </p>
            </div>
            <div class="profileheader__profilestats profileheader__profilestats--small">
                <a href="#" class="profilestats__item">
                    <span id="stats--posts" ><?php echo $user->countPosts($userData['id']); ?></span> posts
                </a>
                <a href="#" class="profilestats__item" data-toggle="modal" data-target="#followersModal">
                    <span id="stats--followers"><?php echo $user->countFollowers($userData['id']); ?></span> followers
                </a>
                <a href="#" class="profilestats__item" data-toggle="modal" data-target="#followingModal">
                    <span id="stats--following"><?php echo $user->countFollowing($userData['id']); ?></span> following
                </a>
            </div>
        </div>
    </div>
</header>

    <section class="ownContainer">

        <?php if(($user->isPrivate($userData['id']) == false)
            || ($user->isFollowing($userData['id']) == true)
            || ($userData['id'] == $_SESSION['userID'])): ?>
            <!-- SHOW POSTS -->

            <!-- SHOW POSTS OR SHOW MESSAGE WHEN THERE ARE NO POSTS -->
            <?php if($userPosts == false): ?>
                <p class="text-center">No posts yet.</p>
            <?php else: ?>
                <div class="gallery">
                    <?php foreach( $userPosts as $key => $userPost ): ?>
                        <a href="post.php?p=<?php echo $userPost['id'] .'&u='. $userData['id'] ?>" style="background-image: url(<?php echo "'".$userPost['path']."'" ?>)" class="gallery__item <?php echo $userPost['filter']; ?>"></a>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- HIDE POSTS -->
            <h4 class="text-center">This account is private</h4>
            <p class="text-center">Request to follow <?php echo $userData['username']?> to see their photos.</p>
        <?php endif; ?>

    </section>
    <?php include 'footer.inc.php'; ?>


    <?php if(($user->isPrivate($userData['id']) == false)
        || ($user->isFollowing($userData['id']) == true)
        || ($userData['id'] == $_SESSION['userID'])): ?>

        <!-- POPUP FOR FOLLOWERS - not visible -->
        <div id="followersModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Followers</h4>
                    </div>
                    <div class="modal-body">
                        <ul class="followlist">
                            <?php foreach($followers as $key => $follower): ?>
                                <li>
                                    <div class="userinfo">
                                        <a href="profile.php?profile=<?php echo $follower['username'] ?>">
                                            <img src="<?php echo $follower['profilePicture']; ?>" alt="<?php echo $follower['username'] ?>'s profile picture" class="userinfo__picture">
                                        </a>
                                        <div class="userinfo__text">
                                            <a href="profile.php?profile=<?php echo $follower['username'] ?>" class="userinfo__username"><?php echo $follower['username'] ?></a>
                                            <span class="userinfo__name"><?php echo $follower['fullName']; ?></span>
                                        </div>
                                    </div>
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
                        <ul class="followlist">
                            <?php foreach($following as $key => $follower): ?>
                                <li>
                                    <div class="userinfo">
                                        <a href="profile.php?profile=<?php echo $follower['username'] ?>">
                                            <img src="<?php echo $follower['profilePicture']; ?>" alt="<?php echo $follower['username'] ?>'s profile picture" class="userinfo__picture">
                                        </a>
                                        <div class="userinfo__text">
                                            <a href="profile.php?profile=<?php echo $follower['username'] ?>" class="userinfo__username"><?php echo $follower['username'] ?></a>
                                            <span class="userinfo__name"><?php echo $follower['fullName']; ?></span>
                                        </div>
                                    </div>
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

    <?php endif; ?>



</body>
</html>      