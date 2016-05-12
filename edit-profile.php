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
                $user->setProfilePicture();
            }catch(Exception $e){
                //$feedback = $e->getMessage();
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

    <script type="text/javascript" src="public/js/interaction.js"></script>
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
        <form action="" method="POST" id="profilePicForm" enctype="multipart/form-data">
            <div class="form-group">
                <h3>Profile picture</h3>
                <img class="profPic" src="<?php echo $userData['profilePicture']; ?>" alt="" style="border-radius: 100%">
                <div class="form-group">
                   <p class="profilePicFeedback"></p>
                    <label for="profileImage">Choose profile picture</label>
                    <input type="file" name="profileImage" class="fileToUpload" class="form-control" id="avatarUpload">
                </div>
            </div>
            <input type="submit" name="btnChangeProfilePicture" value="Change profile picture" class="btn btn-primary profPicSubmit">
        </form>
    </div>
    </div>
    <div class="row accountSettings">
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
    
    <script type="text/javascript">
	$(document).ready(function(){
		function previewImage(input){
			
			if(input.files && input.files[0]){
				var reader = new FileReader();
				
				reader.onload = function(e){
				
				$('.profPic').attr('src', e.target.result);
				$('.profPic').css('width', 150);
				$('.profPic').css('height', 150);
				
				}
				reader.readAsDataURL(input.files[0]);
			}	
		}
		
		$('.fileToUpload').change(function(e){
			var image = $('.fileToUpload');
			
			$.ajax({
				url: "ajax/profilePicPreview.php",
				type: "POST",
				data: new FormData($("#profilePicForm")[0]),
				dataType: 'json',
				contentType: false,
				cache: false,
				processData: false,
				success: function(data)
				{
					//console.log(data);
					if(image.val() == ""){
						$('.profilePicFeedback').html("Choose an image.");
					}else{
						if(data.image == "false"){
							$('.profilePicFeedback').html("This file is not an image.");
						}else if(data.image != "false"){
							if(data.size == "false"){
								$('.profilePicFeedback').html("Your image is too large. Choose a different one please.");
							}
							
							if(data.ratio == "false"){
								$('.profilePicFeedback').html("Choose a square image.");
							}
							
							if(data.dimensions == "false"){
								$('.profilePicFeedback').html("Your image doesn't meet the size requirements. Choose an image between 100x100 and 500x500 pixels.");
							}
							
							if(data.check == "success"){
								previewImage(e.target);
							}
						}
					}
				},
				error: function(request, status, error){
					console.log(error);
				}
			});
			e.preventDefault();
		});
		
		$('.profPicSubmit').on("click", function(e){
			var image = $('.fileToUpload');
			
			$.ajax({
				url: "ajax/profilePicUpload.php",
				type: "POST",
				data: new FormData($("#profilePicForm")[0]),
				dataType: 'json',
				contentType: false,
				cache: false,
				processData: false,
				success: function(data)
				{
					console.log(data);
					
					if(image.val() == ""){
						$('.profilePicFeedback').html('Select an image.');
					}else{
						if(data.check == "success"){
							$('.profilePicFeedback').html('Your profile picture has been succesfully updated.');
							$('#profilePicForm').find('input:file').val('');
						}
					}
				},
				error: function(request, status, error){
					console.log(error);
				}
			});
		e.preventDefault();
	});
});
	</script>
</body>
</html>