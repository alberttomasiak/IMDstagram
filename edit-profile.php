<?php
    include_once 'classes/User.class.php';
    session_start();

    if(isset($_SESSION['loggedin'])){
        $user = new User();
        $userData = $user->getUserDetailsByUsername($_SESSION['username']);
        $feedbackPassword = "";

        if( !empty( $_POST['btnSubmitEdit'] ) ){
            try {
                $user->Email = $_POST['email'];
                $user->FullName = $_POST['fullName'];
                $user->Username = $_POST['username'];
                $user->Bio = $_POST['biography'];
                $user->Website = $_POST['website'];
                $user->Private = $_POST['radioPrivate'];
                $user->updateProfile($userData['id']);
                $feedback = "Account updated successfully.";
                header("location: logout.php");
                /*
                if ($user->updateProfile($userData['id'])) {
                    $feedback = "Account updated successfully.";
                    header("location: logout.php");
                } else {
                    echo "ERROR";
                }*/
            }catch(Exception $e){
                $feedback = $e->getMessage();
            }
        }

        if( !empty( $_POST['btnChangePassword'] ) ) {
            $currentPass = $_POST['currentPass'];
            $newPass = $_POST['newPass'];
            $newPassConfirm = $_POST['newPassConfirm'];
            try{
                $user->changePassword($currentPass, $newPass, $newPassConfirm);
                $feedbackPassword = "<div class='alert alert-success' role='alert'>Password changed</div>";
            }catch(Exception $e){
                $feedbackPassword = "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
            }

        }

        if( !empty( $_POST['btnChangeProfilePicture'] ) ){
            try {
                $user->setProfilePicture($_FILES["fileToUpload"]["name"]);
            }catch(Exception $e){
                $feedback = $e->getMessage();
            }
        }
    }else{
        header('location: login.php');
    }


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit your profile</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="public/js/jquery-2.2.3.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <script src="public/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css" type="text/css">

    <script type="text/javascript" src="public/js/realtimeUsernameCheck.js"></script>
</head>
<body>
    <?php include 'nav.inc.php' ?>
    <div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1>Edit profile</h1>
        </div>
    </div>
    <div class="row">
    <div class="col-sm-7 col-md-6">
    <form action="" method="post">
        <h3>Profile information</h3>
        <?php if(isset($feedback)): ?>
            <div class="alert alert-danger" role="alert"><?php echo $feedback; ?></div>
        <?php endif; ?>
        <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="fullName" value="<?php echo $userData['fullName']; ?>" class="form-control">
        </div>

        <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $userData['email']; ?>" class="form-control">
        </div>

        <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo $userData['username']; ?>" class="form-control">
        </div>
        <div class="usernameFeedback" style="display: none;"></div>

        <div class="form-group">
        <label for="biography">Biography</label>
        <textarea name="biography" id="biography" cols="30" rows="5" class="form-control"><?php echo $userData['bio']; ?></textarea>
        </div>

        <div class="form-group">
        <label for="website">Website</label>
        <input type="text" id="website" name="website" value="<?php echo $userData['website']; ?>" class="form-control">
        </div>

        <?php if($userData['private'] == "0"): ?>
            <div class="form-group control-group">
                <label class="radio control-label">Make my profile:</label>
                <label class="radio-inline"><input type="radio" name="radioPrivate" value="1">Private</label>
                <label class="radio-inline"><input checked type="radio" name="radioPrivate" value="0">Public</label>
            </div>
        <?php endif; ?>
        <?php if($userData['private'] == "1"): ?>
            <div class="form-group control-group">
                <label class="radio control-label">Make my profile:</label>
                <label class="radio-inline"><input checked type="radio" name="radioPrivate" value="1">Private</label>
                <label class="radio-inline"><input type="radio" name="radioPrivate" value="0">Public</label>
            </div>
        <?php endif; ?>

        <input type="submit" name="btnSubmitEdit" value="Submit" class="btn btn-primary">
    </form>
    </div>
    <div class="col-sm-7 col-md-6">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <h3>Profile picture</h3>
                <img src="<?php echo $userData['profilePicture']; ?>" alt="" style="border-radius: 100%">
                <div class="form-group">
                    <label for="avatarUpload">Choose profile picture</label>
                    <input type="file" name="fileToUpload" class="form-control" id="avatarUpload">
                </div>
            </div>
            <input type="submit" name="btnChangeProfilePicture" value="Change profile picture" class="btn btn-primary">
        </form>
    </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h1>Account settings</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7 col-md-6">
            <form action="" method="post">
                <h3>Change password</h3>
                <?php echo $feedbackPassword; ?>

                <div class="form-group">
                    <label for="currentPass">Current password</label>
                    <input type="password" name="currentPass" id="currentPass" class="form-control">
                </div>

                <div class="form-group">
                    <label for="newPass">New password</label>
                    <input type="password" name="newPass" id="newPass" class="form-control">
                </div>

                <div class="form-group">
                    <label for="newPassConfirm">Confirm new password</label>
                    <input type="password" name="newPassConfirm" id="newPassConfirm" class="form-control">
                </div>

                <input type="submit" value="Change password" name="btnChangePassword" class="btn btn-primary">
            </form>
        </div>
    </div>
    </div>
    <?php include 'footer.inc.php' ?>
    </div>
</body>
</html>