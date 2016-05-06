<?php
    include_once 'classes/User.class.php';
    session_start();

    if(isset($_SESSION['loggedin'])){
        $user = new User();
        $pendingRequests = $user->getPendingRequests();

        if( !empty( $_POST['btnAccept'] ) ) {
            $requestID = $_POST['requestID'];
            try{
                $user->acceptFriend($requestID);
                $pendingRequests = $user->getPendingRequests();
                header('Location: '.$_SERVER['REQUEST_URI']);
            }catch(Exception $e){
                echo "accept error";
            }
        }
        if( !empty( $_POST['btnDecline'] ) ) {
            $requestID = $_POST['requestID'];
            try{
                $user->declineFriend($requestID);
                $pendingRequests = $user->getPendingRequests();
                header('Location: '.$_SERVER['REQUEST_URI']);
            }catch(Exception $e){
                echo "decline error";
            }
        }

    }else{
        header('location: login.php');
    }

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IMDstagram | Notifications</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <script src="public/js/interaction.js"></script>
</head>
<body>
    <?php include 'nav.inc.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1>Notifications</h1>
                <h4>These users want to follow you</h4>
                <ul>
                    <?php foreach($pendingRequests as $key => $request): ?>
                        <li>
                            <form action="" method="post">
                            <img src="<?php echo $request['profilePicture']; ?>" alt="<?php echo $request['username']; ?>'s profile picture">
                            <a href="profile.php?profile=<?php echo $request['username']; ?>"><?php echo $request['username']; ?></a>
                            <span><?php echo $request['fullName']; ?></span>
                            <input type="hidden" name="requestID" value="<?php echo $request['followerID'];?>">
                            <input type="submit" name="btnAccept" value="Accept">
                            <input type="submit" name="btnDecline" value="Decline">
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>