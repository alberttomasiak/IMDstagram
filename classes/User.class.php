<?php
    include_once "Db.class.php";

    class User
    {
		public $uploadReady = 1;
        private $m_sEmail;
        private $m_sFullName;
        private $m_sUsername;
        private $m_sPassword;
        // update profile
        private $m_sBio;
        private $m_sWebsite;
        private $m_iPrivate;


        function __SET($p_sProperty, $p_vValue)
        {
            switch ($p_sProperty){
                case "Email":
                    $this->m_sEmail = $p_vValue;
                    break;
                case "FullName":
                    $this->m_sFullName = $p_vValue;
                    break;
                case "Username":
                    $this->m_sUsername = $p_vValue;
                    break;
                case "Password":
                    $this->m_sPassword = $p_vValue;
                    break;
                case "Bio":
                    $this->m_sBio = $p_vValue;
                    break;
                case "Website":
                    $this->m_sWebsite = $p_vValue;
                    break;
                case "Private":
                    $this->m_iPrivate = $p_vValue;
                    break;
            }
        }

        function __GET($p_sProperty)
        {
            switch( $p_sProperty){
                case "Email":
                    return $this->m_sEmail;
                    break;
                case "FullName":
                    return $this->m_sFullName;
                    break;
                case "Username":
                    return $this->m_sUsername;
                    break;
                case "Password":
                    return $this->m_sPassword;
                    break;
                case "Bio":
                    return $this->m_sBio;
                    break;
                case "Website":
                    return $this->m_sWebsite;
                    break;
            }
        }

        public function register()
        {
            $conn = Db::getInstance();

            $email = $this->m_sEmail;
            $username = $this->m_sUsername;
            $fullname = htmlspecialchars($this->m_sFullName);
            $password = $this->m_sPassword;

            if(!empty($email) && !empty($username) && !empty($password)){
                if($this->UsernameAvailable() == true){
                    if($this->EmailAvailable($email) == true){
                        if(strlen($username) > 3 && strlen($username) < 16){
                            if(preg_match('/^[a-z0-9-_]+$/', $username)){
                                if(strlen($password) > 5 && strlen($password) < 21){
                                    if(strlen($fullname) < 31){
                                        $new_password = password_hash($password, PASSWORD_DEFAULT);
                                        $statement = $conn->prepare("INSERT INTO user(email, fullName, username, password, profilePicture)
                                                       VALUES(:email, :fullName, :username, :password, 'public/images/defaultprofilepic.jpg')");

                                        $statement->bindparam(":email", $email);
                                        $statement->bindparam(":fullName", $fullname);
                                        $statement->bindparam(":username", $username);
                                        $statement->bindparam(":password", $new_password);
                                        if ($statement->execute()) {
                                            return true;
                                        }
                                    }else{
                                        throw new Exception("Fullname can't be longer than 30 characters.");
                                    }
                                }else{
                                    throw new Exception("Password has to be 6-15 characters long.");
                                }
                            }else{
                                throw new Exception("Username can only contain alphanumerical characters, - and _. Capital letters are not allowed.");
                            }
                        }else{
                            throw new Exception("Username has to be 4-20 characters long.");
                        }
                    }else{
                        throw new Exception("This email address is already in use.");
                    }
                }else{
                    throw new Exception("Username already taken.");
                }
            }else{
                throw new Exception("Email, Username and Password are required fields.");
            }
        }

        public function canLogin($p_username, $p_password)
        {
            try
            {
                $conn = Db::getInstance();
                $statement = $conn->prepare("SELECT * FROM user WHERE username=:username");
                $statement->bindparam(":username", $p_username);
                $statement->execute();
                $userRow=$statement->fetch(PDO::FETCH_ASSOC);
                if($statement->rowCount() > 0)
                {
                    if(password_verify($p_password, $userRow['password']))
                    {
                        session_start();
                        $_SESSION['loggedin'] = "yes";
                        $_SESSION['username'] = $p_username;
                        $_SESSION['userID'] = $userRow['id'];
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        // RETURNS ALL DATA FOR A SPECIFIC USER BY USERNAME
        public function getUserDetailsByUsername($p_vUsername){
            $conn = Db::getInstance();

            $statement = $conn->prepare("SELECT * FROM user WHERE username=:username");
            $statement->bindparam(":username", $p_vUsername);
            $statement->execute();
            $userData = $statement->fetch(PDO::FETCH_ASSOC);
            return $userData;
        }
		
        // RETURNS ALL DATA FOR A SPECIFIC USER BY USERID
        public function getUserDetailsByUserID($p_iUserID){
            $conn = Db::getInstance();

            $statement = $conn->prepare("SELECT * FROM user WHERE id=:userID");
            $statement->bindparam(":userID", $p_iUserID);
            $statement->execute();
            $userData = $statement->fetch(PDO::FETCH_ASSOC);
            return $userData;
        }
		

        // dit is de nieuwe update profile die mogelijk niet werkt
        public function updateProfile(){
            $email = $this->m_sEmail;
            $username = $this->m_sUsername;
            $fullname = htmlspecialchars($this->m_sFullName);
            $bio = htmlspecialchars($this->m_sBio);
            $website = htmlspecialchars($this->m_sWebsite);
            if(!empty($email) && !empty($username)) {
                if (strlen($username) > 3 && strlen($username) < 16) {
                    if($this->UsernameAvailable() == true || $username == $_SESSION['username']){
                        if(preg_match('/^[a-z0-9-_]+$/', $username)){
                            if(strlen($fullname) < 31){
                                $conn = Db::getInstance();
                                $statement = $conn->prepare("UPDATE user
                                              SET email = :email,
                                                  fullName = :fullName,
                                                  username = :username,
                                                  bio = :bio,
                                                  website = :website,
                                                  private = :private
                                              WHERE id = :id");

                                $statement->bindparam(":email", $this->m_sEmail);
                                $statement->bindparam(":fullName", $fullname);
                                $statement->bindparam(":username", $username);
                                $statement->bindparam(":bio", $bio);
                                $statement->bindparam(":website", $website);
                                $statement->bindparam(":id", $_SESSION['userID']);
                                $statement->bindparam(":private", $this->m_iPrivate);
                                if ($statement->execute()) {
                                    $_SESSION['username'] = $username;
                                    return true;
                                } else {
                                    throw new Exception("Something went wrong");
                                }
                            }else{
                                throw new Exception("Fullname can't be longer than 30 characters");
                            }
                        }else{
                            throw new Exception("Username can only contain alphanumerical characters, - and _. Capital letters are not allowed.");
                        }

                    }else{
                        throw new Exception("Username already taken");
                    }
                }else{
                    throw new Exception("Username has to be 4-15 characters long");
                }
            } else {
                throw new Exception("Username and Email are required fields");
            }
        }


        // CHECK IF A SPECIFIC USERNAME IS STILL AVAILABLE
        public function UsernameAvailable()
        {
            //include("Connection.php"); //open connection to Dbase
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT * FROM user WHERE username=:username");
            $stmt->bindparam(":username", $this->m_sUsername);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                // username taken
                return false;
            } else{
                // still available
                return true;
            }
        }

        // CHECK IF A SPECIFIC EMAIL IS STILL AVAILABLE
        public function EmailAvailable($p_vEmail)
        {
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT * FROM user WHERE email=:email");
            $stmt->bindparam(":email", $p_vEmail);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                // email in use
                return false;
            } else{
                // email still available
                return true;
            }
        }


        // FOLLOW ANOTHER USER
        public function follow($p_iFollowingID){
            $private = $this->isPrivate($p_iFollowingID); // true or false
            $conn = Db::getInstance();
            if($private == false){
                $statement = $conn->prepare("INSERT INTO follow(followerID, followingID, accepted, blocked)
                                                           VALUES(:followerID, :followingID, '1', '0')");

                $statement->bindparam(":followerID", $_SESSION['userID']);
                $statement->bindparam(":followingID", $p_iFollowingID);
                if ($statement->execute()) {
                    // following
                    // record 1 0 0
                    return true;
                }
            }elseif($private == true){
                $statement = $conn->prepare("INSERT INTO follow(followerID, followingID, accepted, blocked)
                                                           VALUES(:followerID, :followingID, '0', '0')");

                $statement->bindparam(":followerID", $_SESSION['userID']);
                $statement->bindparam(":followingID", $p_iFollowingID);
                if ($statement->execute()) {
                    // send friend request
                    // record 0 0 0
                    return true;
                }
            }
        }

        // ACCEPT A FOLLOW REQUEST
        public function acceptFriend($p_iProfileID){
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE follow
                                        SET accepted='1'
                                        WHERE followerID=:followerID AND followingID=:followingID");

            $statement->bindparam(":followerID", $p_iProfileID);
            $statement->bindparam(":followingID", $_SESSION['userID']);
            if ($statement->execute()) {
                // accept request
                // record 1 0 0
                return true;
            }
        }

        // DECLINE A FOLLOW REQUEST
        public function declineFriend($p_iProfileID){
            $conn = Db::getInstance();
            $statement = $conn->prepare("DELETE FROM follow WHERE followerID=:followerID AND followingID=:followingID");

            $statement->bindparam(":followerID", $p_iProfileID);
            $statement->bindparam(":followingID", $_SESSION['userID']);
            if ($statement->execute()) {
                // decline request
                // delete record
                return true;
            }
        }

        // STOP FOLLOWING A USER
        public function stopFollowing($p_iFollowingID){
            $conn = Db::getInstance();
            $statement = $conn->prepare("DELETE FROM follow WHERE followerID=:followerID AND followingID=:followingID");

            $statement->bindparam(":followerID", $_SESSION['userID']);
            $statement->bindparam(":followingID", $p_iFollowingID);
            if ($statement->execute()) {
                // delete record
                // you stopped following this person
                return true;
            }
        }

        // CHECK IF LOGGED IN USER IS FOLLOWING A SPECIFIC USER
        public function isFollowing($p_iFollowingID){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT * FROM follow WHERE followerID=:followerID AND followingID=:followingID AND accepted='1'");
            $stmt->bindparam(":followerID", $_SESSION['userID']);
            $stmt->bindparam(":followingID", $p_iFollowingID);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                // you are following this person
                return true;
            } else{
                // you are not following this user but you may be waiting till he accepts you
                return false;
            }
        }

        // CHECK IF LOGGED IN USER HAS A PENDING REQUEST WITH ANOTHER USER
        public function isPending($p_iFollowingID){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT * FROM follow WHERE followerID=:followerID AND followingID=:followingID AND accepted='0'");
            $stmt->bindparam(":followerID", $_SESSION['userID']);
            $stmt->bindparam(":followingID", $p_iFollowingID);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                // you requested to follow this person
                return true;
            } else{
                // you didn't request to follow this person
                return false;
            }
        }

        // CHECK IF A PROFILE IS PRIVATE
        public function isPrivate($p_iProfileID){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT private FROM user WHERE id=:userID");
            $stmt->bindparam(":userID", $p_iProfileID);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $private = $result['private'];
            if($private == 1){
                // private account
                return true;
            }else if($private == 0){
                // public account
                return false;
            }
        }

        // RETURNS THE RELATIONSHIP BETWEEN 2 USERS
        public function checkRelationship($p_iProfileID){
            // currentUserID == logged in user, $p_iProfileID == other user
            $currentUserID = $_SESSION['userID'];

            if($currentUserID != $p_iProfileID){
                $private = $this->isPrivate($p_iProfileID); // true or false
                $following = $this->isFollowing($p_iProfileID); // true or false
                $pending = $this->isPending($p_iProfileID);
                if($private == true){
                    // private profile
                    if($following == true){
                        echo "You are FOLLOWING this PRIVATE profile";
                    }else{
                        if($pending == true){
                            echo "You ASKED TO FOLLOW this PRIVATE profile";
                        }else{
                            echo "You are NOT FOLLOWING this PRIVATE profile";
                        }
                    }

                }else{
                    // public profile
                    if($following == true){
                        //echo "You are FOLLOWING this PUBLIC profile";
                    }else{
                        //echo "You are NOT FOLLOWING this PUBLIC profile";
                    }
                }
            }else{
                //echo "This is your own profile";
            }
        }

        public function getPendingRequests(){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT follow.followerID, user.id, user.username, user.fullName, user.profilePicture
                                    FROM follow
                                    INNER JOIN user
                                    ON follow.followerID=user.id
                                    WHERE followingID=:followingID AND accepted='0'");
            $stmt->bindparam(":followingID", $_SESSION['userID']);
            $stmt->execute();
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else{
                return false;
            }
        }

        public function countPendingRequests(){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT *
                                    FROM follow
                                    WHERE followingID=:followingID AND accepted='0'");
            $stmt->bindparam(":followingID", $_SESSION['userID']);
            if($stmt->execute()){
                $result = count($stmt->fetchAll());
                return $result;

            } else{
                return false;
            }
        }

        // RETURNS HOW MUCH POSTS SOMEONE HAS
        public function countPosts($p_iUserID){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT * FROM post WHERE userID=:userID");
            $stmt->bindparam(":userID", $p_iUserID);
            $stmt->execute();
            if($stmt->execute()){
                return $stmt->rowCount();
            } else{
                return false;
            }
        }

        // RETURNS THE NUMBER OF FOLLOWERS A USER HAS
        public function countFollowers($p_iUserID){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT * FROM follow WHERE followingID=:followingID AND accepted='1'");
            $stmt->bindparam(":followingID", $p_iUserID);
            $stmt->execute();
            if($stmt->execute()){
                return $stmt->rowCount();
            } else{
                return false;
            }
        }

        // RETURNS THE NUMBER OF PEOPLE THE USER IS FOLLOWING
        public function countFollowing($p_iUserID){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT * FROM follow WHERE followerID=:followerID AND accepted='1'");
            $stmt->bindparam(":followerID", $p_iUserID);
            $stmt->execute();
            if($stmt->execute()){
                return $stmt->rowCount();
            } else{
                return false;
            }
        }

        // RETURNS ALL FOLLOWERS FOR A USER
        public function getFollowers($p_iUserID){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT follow.followerID, user.id, user.username, user.fullName, user.profilePicture
                                    FROM follow
                                    INNER JOIN user
                                    ON follow.followerID=user.id
                                    WHERE followingID=:followingID AND accepted='1'");
            $stmt->bindparam(":followingID", $p_iUserID);
            $stmt->execute();
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else{
                return false;
            }
        }

        // RETURNS ALL ACCOUNTS A USER IS FOLLOWING
        public function getFollowing($p_iUserID){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT follow.followingID, user.id, user.username, user.fullName, user.profilePicture
                                    FROM follow
                                    INNER JOIN user
                                    ON follow.followingID=user.id
                                    WHERE followerID=:followerID AND accepted='1'");
            $stmt->bindparam(":followerID", $p_iUserID);
            $stmt->execute();
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else{
                return false;
            }
        }

        public  function changePassword($p_sOldPassword, $p_sNewPassword, $p_sNewPasswordConfirm){
            if(empty($p_sOldPassword) || empty($p_sNewPassword) || empty($p_sNewPasswordConfirm)){
                // echo: vul alle velden in
                throw new Exception("Please fill in all the fields.");
            }else{
                if(strlen($p_sNewPassword) > 5){
                    // lang genoeg
                    $conn = Db::getInstance();
                    $statement = $conn->prepare("SELECT * FROM user WHERE id=:userID");
                    $statement->bindparam(":userID", $_SESSION['userID']);
                    $statement->execute();
                    $userRow=$statement->fetch(PDO::FETCH_ASSOC);

                    if(password_verify($p_sOldPassword, $userRow['password']))
                    {
                        // OldPassword is juist
                        if($p_sNewPassword == $p_sNewPasswordConfirm){
                            $newPassword = password_hash($p_sNewPassword, PASSWORD_DEFAULT);
                            $stmt = $conn->prepare("UPDATE user
                                                    SET password=:password
                                                    WHERE id=:userID");
                            $stmt->bindparam(":password", $newPassword);
                            $stmt->bindparam(":userID", $_SESSION['userID']);
                            if ($stmt->execute()) {
                                // password changed
                                return true;
                            }else{
                                throw new Exception("Something went wrong whilst updating our database. Your password has not been changed.");
                            }
                        }else{
                            // echo: nieuwe passwoorden niet hetzelfde
                            throw new Exception("New password confirmation isn't right.");
                        }
                    }
                    else
                    {
                        //echo old password fout
                        throw new Exception("Your current password isn't right.");
                    }
                }else{
                    throw new Exception("New password has to be 6 characters or longer.");
                }
            }
        }

        public function checkIfProfileExists($p_vUsername){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT username FROM user WHERE username=:username");
            $stmt->bindparam(":username", $p_vUsername);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return true;
            } else{
                return false;
            }
        }

        public function echoMe(){
            echo "Here's your echo";
        }
		
		public function checkIfImage(){
			$file = $_FILES['profileImage']['tmp_name'];
			$check = getimagesize($file);
			
			if($check !== false){
				$uploadReady = 1;
				return $uploadReady;
			}else{
				$uploadReady = 0;
				return $uploadReady;
			}
		}
		
		public function checkFileSize(){
			$file = $_FILES['profileImage']['tmp_name'];
			
			if($file > 1000000){
				$uploadReady = 0;
				return $uploadReady;
			}else{
				$uploadReady = 1;
				return $uploadReady;
			}
		}
		
		public function checkAspectRatio(){
			$file = $_FILES['profileImage']['tmp_name'];
			$user = new User();
			
			if($user->checkIfImage()){
				list($width, $height) = getimagesize($file);
				$ratio = $width / $height;
				
				if($ratio == 1){
					$uploadReady = 1;
					return $uploadReady;
				}else{
					$uploadReady = 0;
					return $uploadReady;
				}
			}
		}
		
		public function checkFileDimensions(){
			$file = $_FILES['profileImage']['tmp_name'];
			$user = new User();
			
			if($user->checkIfImage()){
				list($width, $height) = getimagesize($file);
				
				if($width >= 100 && $width <= 500 && $height >= 100 && $height <= 500){
					$uploadReady = 1;
					return $uploadReady;
				}else{
					$uploadReady = 0;
					return $uploadReady;
				}
			}
		}
		
        public function setProfilePicture(){
            $file = $_FILES['profileImage']['tmp_name'];
			// upload default tegenhouden
			$uploadReady = 0;
			$user = new User();
			$file_name = "pp-" . $_SESSION['userID']."-".time().".jpg";
			$file_path = "img/profilepics/".$file_name;
			$imageFileType = pathinfo($file_path, PATHINFO_EXTENSION);
			$check = getimagesize($file);
			$path = realpath(dirname(getcwd()));
			
			if($user->checkIfImage() && $user->checkFileSize() && $user->checkAspectRatio() && $user->checkFileDimensions()){
				$uploadReady = 1;
			}else{
				$uploadReady = 0;
			}
			
			if($uploadReady == 0){
				// Image voldoet niet aan de voorwaarden.
			}else if($uploadReady == 1){
				$db = Db::getInstance();
				$statement = $db->prepare("UPDATE user SET profilePicture = :profilePicture WHERE id = :id");
				$statement->bindValue(":id", $_SESSION['userID']);
				$statement->bindValue(":profilePicture", $file_path);
				$result = $statement->execute();
				MOVE_UPLOADED_FILE($file, "../img/profilepics/$file_name");
			}
        }
    }
?>
