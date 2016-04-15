<?php
    include_once "Db.class.php";

    class User
    {
        private $m_sEmail;
        private $m_sFullName;
        private $m_sUsername;
        private $m_sPassword;
        // update profile
        private $m_sBio;
        private $m_sWebsite;


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
            try
            {
                $conn = Db::getInstance();

                // check if fields are not empty and username is 4 characters min
                if(strlen($this->m_sUsername) > 3 && !empty($this->m_sEmail) && !empty($this->m_sPassword)){
                    $stmt = $conn->prepare("SELECT * FROM user WHERE username=:username OR email=:email");
                    $stmt->bindparam(":username", $this->m_sUsername);
                    $stmt->bindparam(":email", $this->m_sEmail);
                    $stmt->execute();
                    // check if username or email isn't in use already
                    if($stmt->rowCount() > 0) {
                        return false;
                    }else{
                        // write to database
                        $new_password = password_hash($this->m_sPassword, PASSWORD_DEFAULT);
                        $statement = $conn->prepare("INSERT INTO user(email, fullName, username, password, profilePicture)
                                                           VALUES(:email, :fullName, :username, :password, 'public/images/defaultprofilepic.jpg')");

                        $statement->bindparam(":email", $this->m_sEmail);
                        $statement->bindparam(":fullName", $this->m_sFullName);
                        $statement->bindparam(":username", $this->m_sUsername);
                        $statement->bindparam(":password", $new_password);
                        if ($statement->execute()) {
                            return true;
                        }
                    }
                }else{
                    return false;
                }

            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
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
        /*
        // RETURNS THE USERNAME FOR A SPECIFIC USERID
        public function getUsernameByUserID($p_iUserID){
            $conn = Db::getInstance();

            $statement = $conn->prepare("SELECT username FROM user WHERE id=:userID");
            $statement->bindparam(":userID", $p_iUserID);
            $statement->execute();
            $username = $statement->fetch(PDO::FETCH_ASSOC);
            return $username;
        }
        */
        /*
        // RETURNS ALL DATA FOR A SPECIFIC USER ACCEPTS USERNAME OR USERID
        // how to use:
        // $user= new User(); $user.getAll('userID', SESSION['userID']; or $user.getAll('username', SESSION['username'];
        public function getAll($p_sProperty, $p_vValue){
            try
            {
                $conn = Db::getInstance();
                if($p_sProperty == "username"){
                    $statement = $conn->prepare("SELECT * FROM user WHERE username=:username");
                    $statement->bindparam(":username", $p_vValue);
                    $statement->execute();
                    $userData = $statement->fetch(PDO::FETCH_ASSOC);
                    return $userData;
                }else if($p_sProperty == "userID"){
                    $statement = $conn->prepare("SELECT * FROM user WHERE id=:userID");
                    $statement->bindparam(":userID", $p_vValue);
                    $statement->execute();
                    $userData = $statement->fetch(PDO::FETCH_ASSOC);
                    return $userData;
                }
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }
        */

        // USED FOR UPDATING A USERS PROFILE IN EDIT-PROFILE.PHP
        public function updateProfile($p_iUserID)
        {
            try
            {
                $conn = Db::getInstance();
                $statement = $conn->prepare("UPDATE user
                                              SET email = :email,
                                                  fullName = :fullName,
                                                  username = :username,
                                                  bio = :bio,
                                                  website = :website
                                              WHERE id = :id");

                $statement->bindparam(":email", $this->m_sEmail);
                $statement->bindparam(":fullName", $this->m_sFullName);
                $statement->bindparam(":username", $this->m_sUsername);
                $statement->bindparam(":bio", $this->m_sBio);
                $statement->bindparam(":website", $this->m_sWebsite);
                $statement->bindparam(":id", $p_iUserID);
                if($statement->execute()){
                    return true;
                }
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
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

        // FOLLOW ANOTHER USER
        public function follow($p_iFollowingID){
            $conn = Db::getInstance();
            $statement = $conn->prepare("INSERT INTO follow(followerID, followingID, accepted, blocked)
                                                           VALUES(:followerID, :followingID, '1', '0')");

            $statement->bindparam(":followerID", $_SESSION['userID']);
            $statement->bindparam(":followingID", $p_iFollowingID);
            if ($statement->execute()) {
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
                return true;
            }
        }

        // CHECK IF LOGGED IN USER IS FOLLOWING A SPECIFIC USER
        public function isFollowing($p_iFollowingID){
            $conn = Db::getInstance();
            $stmt = $conn->prepare("SELECT * FROM follow WHERE followerID=:followerID AND followingID=:followingID");
            $stmt->bindparam(":followerID", $_SESSION['userID']);
            $stmt->bindparam(":followingID", $p_iFollowingID);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return true;
            } else{
                return false;
            }
        }
    }
?>
