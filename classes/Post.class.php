<?php
    include_once "Db.class.php";

    class Post{
		public $uploadReady = 1;

		// RETURNS ALL THE DATA FOR A SINGLE POST
		public function getAllPost($p_iPostID){
			$conn = Db::getInstance();

			$statement = $conn->prepare("SELECT * FROM post WHERE id=:postID");
			$statement->bindparam(":postID", $p_iPostID);
			$statement->execute();

			$result = $statement->fetch(PDO::FETCH_ASSOC);
			return $result;
		}

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

		public function getPostsByTag($p_vTag){
			$conn = Db::getInstance();
			$hashtag = "%#" . $p_vTag . " %";
			$statement = $conn->prepare("SELECT * FROM post WHERE description LIKE :tag ORDER BY timestamp DESC");
			$statement->bindparam(":tag", $hashtag);
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
        public function like($p_iPostID){
			$conn = Db::getInstance();
			$statement = $conn->prepare("INSERT INTO likes(postID, userID)
                                                           VALUES(:postID, :userID)");

			$statement->bindparam(":postID", $p_iPostID);
			$statement->bindparam(":userID", $_SESSION['userID']);
			if ($statement->execute()) {
				return true;
			}
        }

		// STOP LIKING A POST
		public function dislike($p_iPostID){
			$conn = Db::getInstance();
			$statement = $conn->prepare("DELETE FROM likes WHERE postID=:postID AND userID=:userID");

			$statement->bindparam(":postID", $p_iPostID);
			$statement->bindparam(":userID", $_SESSION['userID']);
			if ($statement->execute()) {
				return true;
			}
		}

		// CHECK IF A USER LIKED A POST
		public function checkIfLiked($p_iPostID){
			$conn = Db::getInstance();
			$stmt = $conn->prepare("SELECT * FROM likes WHERE postID=:postID AND userID=:userID");
			$stmt->bindparam(":postID", $p_iPostID);
			$stmt->bindparam(":userID", $_SESSION['userID']);
			$stmt->execute();
			if($stmt->rowCount() > 0){
				return true;
			} else{
				return false;
			}
		}

		// RETURNS HOW MUCH LIKES A POST HAS
		public function countLikes($p_iPostID){
			$conn = Db::getInstance();
			$stmt = $conn->prepare("SELECT * FROM likes WHERE postID=:postID");
			$stmt->bindparam(":postID", $p_iPostID);
			$stmt->execute();
			if($stmt->execute()){
				return $stmt->rowCount();
			} else{
				return false;
			}
		}
		
		public function checkIfImage(){
			$file_name = $_SESSION['userID']."-".time().".jpg";
			$file_tmp_name = $_FILES['postImage']['tmp_name'];
			$check = getimagesize($file_tmp_name);
			
			if(!empty($_POST['upload_image'])){
				if($check !== false){
					$uploadReady = 1;
				}else{
					echo "File is not an image"."<br>";
					$uploadReady = 0;
				}
				return $uploadReady;
			}
		}
		
		public function checkFileSize(){
			if($_FILES["postImage"]["size"] > 2000000){
				echo "Your image is too large.";
				$uploadReady = 0;
				return $uploadReady;
			}else{
				$uploadReady = 1;
				return $uploadReady;
			}
			
		}
		
		public function checkFileFormat(){
			$file_name = $_SESSION['userID']."-".time().".jpg";
			$file_path = "img/".$file_name;
			$imageFileType = pathinfo($file_path, PATHINFO_EXTENSION);
			// Kijken naar de extensie van het bestand. Enkel JPG, PNG, JPEG & GIF zijn toegelaten.
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$uploadReady = 0;
				return $uploadReady;
			}else{
				$uploadReady = 1;
				return $uploadReady;
			}
		}
		
		public function createPost(){
			// declaratie van variabelen.
			$uploadReady = 0;
			$file_name = $_SESSION['userID']."-".time().".jpg";
			$file_tmp_name = $_FILES['postImage']['tmp_name'];
			$file_path = "img/".$file_name;
			$imageFileType = pathinfo($file_path, PATHINFO_EXTENSION);
			$check = getimagesize($file_tmp_name);
			
			$post = new Post();
			
			// Alle bovenstaande functies uitvoeren voor een post gemaakt kan worden.
			$post->checkIfImage();
			$post->checkFileSize();
			$post->checkFileFormat();
				
			// uploadReady op 1 zetten als alles goed gaat bij functies hier boven vermeld.
			if ($post->checkIfImage() && $post->checkFileSize() && $post->checkFileFormat()){
				$uploadReady = 1;
			}
			
			// Uploaden tegenhouden als iets verkeerd gaat.
			if ($uploadReady == 0){
				echo "Your image couldn't be uploaded. Please try again.";
			}else if($uploadReady == 1 && !empty($_POST['upload_image'])){
			// als er een bestand is, stuur het naar het img/ mapje + schrijf path en description naar DB.
				if($file_name){
				$db = Db::getInstance();
				$statement = $db->prepare("insert into post (userID, path, description) values (:userID, :path, :description)");
				$statement->bindValue(":userID", $_SESSION['userID']);
				$statement->bindValue(":path", $file_path);
				$statement->bindValue(":description", $_POST['description']);
				$result = $statement->execute();
				MOVE_UPLOADED_FILE($file_tmp_name, "img/$file_name");
				echo "Post succesfully made.";				}
			}
		}

		// RETURNS DESCRIPTION WITH HASHTAGS AS LINKS
		public function tagPostDescription($p_vDescription){
			preg_match_all('/#(\w+)/',$p_vDescription,$matches);
			foreach ($matches[1] as $match) {
				$p_vDescription = str_replace("#$match", "<a href='tag.php?tag=$match'>#$match</a>", "$p_vDescription");
			}
			return $p_vDescription;
		}
	}
?>