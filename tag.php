<?php
    include_once 'classes/User.class.php';
    include_once 'classes/Post.class.php';
    session_start();

    if(isset($_SESSION['loggedin'])){
        $hashtag = $_GET['tag'];
        $user = new User();

        $post = new Post();
        $taggedPosts = $post->getPostsByTag($hashtag);

        $resultCount = 0;
        if($taggedPosts != false){
            $resultCount = count($taggedPosts);
        }

    }else{
        header('location: login.php');
    }

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo '#' . $_GET['tag'] . ' | IMDstagram';?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/cssgram.min.css">
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <script src="public/js/interaction.js"></script>
</head>
<body>
<?php include 'nav.inc.php'; ?>
    <div class="container">
        <header class="row tagpageHeader">
            <div class="col-xs-12">
                <h1><?php echo '#' . $_GET['tag'];?></h1>
                <span><span><?php echo $resultCount; ?></span> posts</span>
            </div>
        </header>
    </div>
    <section class="ownContainer">
        <h4>Most recent</h4>

        <?php if($taggedPosts == false): ?>
            <p>No posts tagged with <?php echo '#' . $_GET['tag'];?>.</p>
        <?php else: ?>
            <div class="gallery">
            <?php foreach( $taggedPosts as $key => $taggedPost ): ?>

<a href="post.php?p=<?php echo $taggedPost['id'] .'&u='.$taggedPost['userID'] ?>" style="background-image: url(<?php echo "'".$taggedPost['path']."'" ?>)" class="gallery__item <?php echo $taggedPost['filter']; ?>"></a>

            <?php endforeach; ?>
            </div>

            <!--<article>
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coca_Cola-bxyz.jpg" alt="">
            </article>-->
        <?php endif; ?>

    </section>
    <?php include 'footer.inc.php'; ?>


</body>
</html>