<?php
    include_once "Db.class.php";
	include_once "./uploadpost.php";

    class Post{
		public $uploadReady = 1;
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
	}
?>