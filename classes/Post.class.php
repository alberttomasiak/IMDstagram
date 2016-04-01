<?php
    include_once "Db.class.php";

    class Post{
        // RETURNS ALL POSTS FOR A SPECIFIC USER (used on profile page)
        public function getAll($p_UserID){
            $conn = Db::getInstance();

            $statement = $conn->prepare("SELECT * FROM post WHERE userID=:userID ORDER BY timestamp DESC");
            $statement->bindparam(":userID", $p_UserID);
            $statement->execute();

            if($statement->rowCount() > 0){
                $result = $statement -> fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }else{
                return false;
            }

        }

        // LIKE A POST
        public function like(){

        }
    }

?>