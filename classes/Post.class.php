<?php
    include_once "Db.class.php";

    class Post{
        // RETURNS ALL POSTS FOR A SPECIFIC USER (used on profile page)
        public function getAllForUser($p_iUserID){
            $conn = Db::getInstance();

            $statement = $conn->prepare("SELECT * FROM post WHERE userID=:userID ORDER BY timestamp DESC");
            $statement->bindparam(":userID", $p_iUserID);
            $statement->execute();

            if($statement->rowCount() > 0){
                $result = $statement -> fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }else{
                return false;
            }

        }

        // RETURNS ALL POSTS FROM USERS YOU FOLLOW
        public function getAllTimeline($p_iCurrentUserID){

        }

        // LIKE A POST
        public function like(){

        }
    }

?>