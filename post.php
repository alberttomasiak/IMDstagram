<?php
    include_once 'classes/User.class.php';
    include_once 'classes/Post.class.php';
    //include_once 'classes/Comment.class.php';
    session_start();

    $getPost = $_GET['p'];
    $getUserID = $_GET['u'];

    $post = new Post();
    $postData = $post->getAllPost($getPost);
    //var_dump($postData);
    $user = new User();
    $userData = $user->getUserDetailsByUserID($getUserID);

    // CODE BRENT
    /*$comment = new Comment();
    
    //controleer of er een update wordt verzonden
    if(!empty($_POST['activitymessage']))
    {
        $cmmment->Comment = $_POST['activitymessage'];
        try 
        {
            $comment->Save();
        } 
        catch (Exception $e) 
        {
            $feedback = $e->getMessage();
        }
    }
    
    //altijd alle laatste activiteiten ophalen
    $recentActivities = $comment->GetRecentActivities();*/

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <script src="public/js/interaction.js"></script>
    <script>
    $(document).ready(function(){
        $("#btnSubmit").on("click", function(e){
            
            var message = $("#activitymessage").val();

            $.ajax({
              type: "POST",
              url: "ajax/comment.php",
              data: { activitymessage: message }
            })
            .done(function( msg ) {
                //alert( "Data Saved: " + msg );
                var li = "<li style='display:none;'><strong><?php echo $userData['username'] ?>: </strong> " + message  + "</li>";
                $("#listupdates").prepend(li);
                $("#listupdates li").first().slideDown();
            });

            e.preventDefault();
            
        });
    });
</script>
</head>
<body>
<?php include 'nav.inc.php'; ?>
<div class="container">
<div class="row detailpostRow">
    <header class="col-xs-12 detailpostHeader">
        <a href="profile.php?profile=<?php echo $userData['username'] ?>">
        <img src="<?php echo $userData['profilePicture']; ?>" alt="<?php echo $userData['username']; ?>'s profile picture">
        <?php echo $userData['username'] ?>
        </a>
    </header>

    <!--<div class="col-xs-12">-->
        <img src="<?php echo $postData['path'] ?>" alt="" id="singlePostImg">
    <!--</div>-->

    <div class="col-xs-12 detailpostLikesAndTime">
        <span><span id="likeCount"><?php echo $post->countLikes($postData['id']) ?></span> likes</span>
        <span><?php echo $post->timeAgo($postData['timestamp']); ?></span>
    </div>

    <div class="col-xs-12">
        <p><?php echo $post->tagPostDescription($postData['description']) ?></p>
    </div>

    <div class="col-xs-1">
    <?php
    // CHECK IF YOU LIKED THE POST ALREADY
    if(isset($_SESSION['loggedin'])){
        if($post->checkIfLiked($postData['id']) == true){
            // ALREADY LIKED
            echo "<a href='#' id='btnLike' role='button' class='liked' data-action='dislike' data-postid='" . $postData['id'] . "'>Like</a>";
        }else{
            // NOT LIKED YET
            echo "<a href='#' id='btnLike' role='button' data-action='like' data-postid='" . $postData['id'] . "'>Dislike</a>";
        }
    }
    ?>
    </div>

    <form action="" class="col-xs-11">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Add a comment...">
        <span class="input-group-btn">
            <input type="submit" value="Submit" class="btn btn-default">
        </span>
        </div>
    </form>

    <!--
    <form method="post" action="">
        <div class="statusupdates">

        <input type="text" placeholder="Comment" id="activitymessage" name="activitymessage" />
        <input id="btnSubmit" type="submit" value="Place comment" />  
        </div>
    </form>

     <ul id="listupdates"></ul>
     -->

</div>
</div>
</body>
</html>
