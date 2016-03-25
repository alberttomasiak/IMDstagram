<?php
    include_once "Db.class.php";

    class User
    {
        private $m_sEmail;
        private $m_sFullName;
        private $m_sUsername;
        private $m_sPassword;


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
            }
        }

        public function register()
        {
            try
            {
                $new_password = password_hash($this->m_sPassword, PASSWORD_DEFAULT);
                $conn = Db::getInstance();
                $statement = $conn->prepare("INSERT INTO user(email, fullName, username, password, profilePicture)
                                                           VALUES(:email, :fullName, :username, :password, 'public/images/defaultprofilepic.jpg')");

                $statement->bindparam(":email", $this->m_sEmail);
                $statement->bindparam(":fullName", $this->m_sFullName);
                $statement->bindparam(":username", $this->m_sUsername);
                $statement->bindparam(":password", $new_password);
                if($statement->execute()){
                    return true;
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
    }




?>
