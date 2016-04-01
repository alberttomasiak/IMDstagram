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

        function __get($p_sProperty)
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

        // RETURNS ALL DATA FOR A SPECIFIC USER
        public function getAll($p_vUsername){
            $conn = Db::getInstance();

            $statement = $conn->prepare("SELECT * FROM user WHERE username=:username");
            $statement->bindparam(":username", $p_vUsername);
            $statement->execute();
            //$userData = mysql_fetch_array($result);
            $userData = $statement->fetch(PDO::FETCH_ASSOC);
            return $userData;
        }

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



    }





?>
