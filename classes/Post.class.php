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
			$hashtagEnd = "%#" . $p_vTag;
			$statement = $conn->prepare("SELECT * FROM post WHERE description LIKE :tag OR description LIKE :tagEnd ORDER BY timestamp DESC");
			$statement->bindparam(":tag", $hashtag);
			$statement->bindparam(":tagEnd", $hashtagEnd);
			$statement->execute();

			if($statement->rowCount() > 0){
				$result = $statement -> fetchAll(PDO::FETCH_ASSOC);
				return $result;
			}else{
				return false;
			}
		}

        // RETURNS ALL POSTS FROM USERS YOU FOLLOW
        public function getAllTimeline(){
			$conn = Db::getInstance();
			$activeUser = $_SESSION['userID'];
			
			$statement = $conn->prepare("SELECT user.username, user.profilePicture, post.id, post.userID, post.path, post.location, post.timestamp, post.filter, post.location
 											FROM post
 											INNER JOIN user
 											ON post.userID=user.id
 											INNER JOIN follow
 											ON user.id=follow.followingID
 											WHERE follow.followerID = '$activeUser'");
			//$statement->bindparam(":sessionID", $_SESSION['userID']);
            //SELECT * FROM post WHERE userID IN ( SELECT followingID FROM follow WHERE followerID=:followerID)
			$statement->execute();

			if($statement->rowCount() > 0){
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				return $result;
			}
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
			
			if($check !== false){
				$uploadReady = 1;
				return $uploadReady;
			}else{
				$uploadReady = 0;
				return $uploadReady;
			}
			
			
		}
		
		public function checkFileSize(){
			if($_FILES["postImage"]["size"] > 2097152){
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
				$uploadReady = 0;
				return $uploadReady;
			}else{
				$uploadReady = 1;
				return $uploadReady;
			}
		}
		
		public function checkAspectRatio(){
			$file = $_FILES['postImage']['tmp_name'];
			$post = new Post();
			
			if($post->checkIfImage()){
				list($width, $height) = getimagesize($file);
				$ratio = $width / $height;	
				
				if($ratio > 0.6666666666666667){
					$uploadReady = 1;
					return $uploadReady;
				}else{
					$uploadReady = 0;
					return $uploadReady;
				}
			}
		}
		
		public function checkFileDimensions(){
			$file = $_FILES['postImage']['tmp_name'];
			$post = new Post();
			
			if($post->checkIfImage()){
				list($width, $height) = getimagesize($file);
				
				if($width >= 600 && $height >= 338){
					$uploadReady = 1;
					return $uploadReady;
				}else{
					$uploadReady = 0;
					return $uploadReady;
				}
			}
		}
		
		public function createPost($p_file_tmp_name){
			// declaratie van variabelen.
			$uploadReady = 0;
			$file_name = $_SESSION['userID']."-".time().".jpg";
			//$p_file_tmp_name = $_FILES['postImage']['tmp_name'];
			$file_path = "img/".$file_name;
			$imageFileType = pathinfo($file_path, PATHINFO_EXTENSION);
			$check = getimagesize($p_file_tmp_name);
			$path = realpath(dirname(getcwd()));
			//echo $path;
			
			$post = new Post();
			
			// Alle bovenstaande functies uitvoeren voor een post gemaakt kan worden.
			$post->checkIfImage();
			$post->checkFileSize();
			$post->checkFileFormat();
			$post->checkAspectRatio();
			$post->checkFileDimensions();
				
			// uploadReady op 1 zetten als alles goed gaat bij functies hier boven vermeld.
			if ($post->checkIfImage() && $post->checkFileSize() && $post->checkFileFormat() && $post->checkAspectRatio() && $post->checkFileDimensions()){
				$uploadReady = 1;
			}
			
			// Uploaden tegenhouden als iets verkeerd gaat.
			if ($uploadReady == 0){
				//echo "Your image couldn't be uploaded. Please try again.";
			}else if($uploadReady == 1){
			// als er een bestand is, stuur het naar het img/ mapje + schrijf path en description naar DB.
				if($file_name){
				$db = Db::getInstance();
				$statement = $db->prepare("insert into post (userID, path, description, location, filter) values (:userID, :path, :description, :location, :filter)");
				$statement->bindValue(":userID", $_SESSION['userID']);
				$statement->bindValue(":path", $file_path);
				$statement->bindValue(":description", $_POST['description']);
				$statement->bindValue(":location", $_POST['location']);
				$statement->bindValue(":filter", $_POST['filter']);
				$result = $statement->execute();
				MOVE_UPLOADED_FILE($p_file_tmp_name, "../img/$file_name");
				//echo "Post succesfully made.";				
				}
			}
		}
		
		public function deletePost($p_vPostID){
			$db = Db::getInstance();
			
			$statementInappropriate = $db->prepare("delete i from inappropriate i INNER JOIN post p on i.id = i.postID where i.postID = :postID");
			$statementInappropriate->bindValue(":postID", $p_vPostID);
			
			if($statementInappropriate->execute()){
				$statementComments = $db->prepare("delete c from comment c
												INNER JOIN post p
												on p.id = c.postID
												where c.postID = :postID");
				$statementComments->bindValue(":postID", $p_vPostID);
			
				if($statementComments->execute()){
					$statementLikes = $db->prepare("delete l from likes l
												INNER JOIN post p
												on p.id = l.postID
												where l.postID = :postID");
					$statementLikes->bindValue(":postID", $p_vPostID);
				
					if($statementLikes->execute()){
						$statementPost = $db->prepare("delete p from post p where id = :postID");
						$statementPost->bindValue(":postID", $p_vPostID);
						$statementPost->execute();
						return true;
					}
				}
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
		
		public function checkIfFlagged($p_vPostID){
			$db = Db::getInstance();
			$statement = $db->prepare("SELECT * FROM inappropriate WHERE postID=:postID AND userID=:userID");
			$statement->bindparam(":postID", $p_vPostID);
			$statement->bindparam(":userID", $_SESSION['userID']);
			$statement->execute();
			if($statement->rowCount() > 0){
				return true;
			}else{
				return false;
			}
		}
		
		public function flagPost($p_vPostID){
			$db = Db::getInstance();
			$statement = $db->prepare("INSERT INTO inappropriate (postID, userID) values(:postID, :userID)");
			$statement->bindparam(":postID", $p_vPostID);
			$statement->bindparam("userID", $_SESSION['userID']);
			if ($statement->execute()){
				return true;
			}else{
				return false;
			}
		}
		
		public function unFlagPost($p_vPostID){
			$db = Db::getInstance();
			$statement = $db->prepare("DELETE FROM inappropriate WHERE postID=:postID AND userID=:userID");
			$statement->bindparam(":postID", $p_vPostID);
			$statement->bindparam(":userID", $_SESSION['userID']);
			if($statement->execute()){
				return true;
			}else{
				return false;
			}
		}
		
		public function countFlags($p_vPostID){
			$db = Db::getInstance();
			$statement = $db->prepare("SELECT * FROM inappropriate WHERE postID=:postID");
			$statement->bindparam(":postID", $p_vPostID);
			$statement->execute();
			if($statement->rowCount() == 3){
				//$post->deletePost($p_vPostID);
				return true;
			}else{
				return false;
			}
		}
		


		// niet in gebruik de functie hieronder is voldoende
		// GET A SINGLE TIMESTAMP
		/*public function getTimestamp(){
			$conn = Db::getInstance();
			
			$statement = $conn->prepare("SELECT TOP 1 timestamp FROM post");;
			$statement->execute();

			if($statement->rowCount() > 0){
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				return $result;
			}
		}*/
		
		// CONVERT POST TIME FROM TIMESTAMP TO .. AGO
		public function timeAgo($p_vTimestamp){
			date_default_timezone_set('Europe/Brussels');
			
			
			$time_ago = strtotime($p_vTimestamp);
			
			
			$currentTime = time();
			$differenceInTime = $currentTime - $time_ago;
			$seconds = $differenceInTime;
			
			// tijdseenheden declareren
			$minutes = round($seconds / 60); 		// 60 seconden
			$hours = round($seconds / 3600);		// 60 x 60
			$days = round($seconds / 86400);		// 24 x 60 x 60
			$weeks = round($seconds / 604800);		// 7 x 24 x 60 x 60
			$months = round($seconds / 2629440);	// ((365+365+365+365+366))/5/12) x 24 x 60 x 60
			$years = round($seconds / 31553280);	// (365+365+365+365+366)/5 x 24 x 60 x 60
			
			if($seconds <= 60){
				return "Less than a minute ago.";
			}
			else if ($minutes <= 60){
				if($minutes==1){
					return "1 minute ago";
				}
				else{
					return "$minutes minutes ago";
				}
			}
			else if($hours <= 24){
				if($hours == 1){
					return "1 hour ago";
				}else{
					return "$hours hours ago";
				}
			}
			else if($days <= 7){
				if($days == 1){
					return "Yesterday";
				}else{
					return "$days days ago";
				}
			}
			else if ($weeks <= 4.3){
				//4.3 ----> 52/12
				if($weeks == 1){
					return "Last week";
				}else{
					return "$weeks weeks ago";
				}
			}
			else if($months <= 12){
				if($months == 1){
					return "Last month";
				}else{
					return "$months months ago";
				}
			}
			else{
				if($years == 1){
					return "Last year";
				}else{
					return "$years years ago";
				}
			}
		}
	}
?>