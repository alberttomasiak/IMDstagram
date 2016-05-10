<?php

include_once "Db.class.php";

class Comment
{
	/*
	private $m_sComment;
	//private $m_iUserID;
	private $m_iPostID;

	function __SET($p_sProperty, $p_vValue)
	{
		switch ($p_sProperty){
			case "Comment":
				$this->m_sComment = $p_vValue;
				break;
			case "Author":
				$this->m_sUserID = $p_vValue;
				break;
			case "Post":
				$this->m_sPostID = $p_vValue;
				break;
		}
	}

	function __GET($p_sProperty)
	{
		switch( $p_sProperty){
			case "Comment":
				return $this->m_sComment;
				break;
			case "Author":
				return $this->m_sUserID;
				break;
			case "Post":
				return $this->m_sPostID;
				break;
		}
	}

	public function Save(){
		$conn = Db::getInstance();
		$statement = $conn->prepare("INSERT INTO comment(userID, postID, comment)
                                                           VALUES(:userID, :postID, :comment)");

		$statement->bindparam(":userID", $_SESSION['userID']);
		$statement->bindparam(":postID", $this->m_iPostID);
		$statement->bindparam(":comment", $this->m_sComment);
		if ($statement->execute()) {
			return true;
		}
	}*/

	public function createComment($p_PostId, $p_vComment){
		$conn = Db::getInstance();
		if(!empty($p_vComment) && strlen(htmlspecialchars($p_vComment)) < 301){
			$statement = $conn->prepare("INSERT INTO comment(userID, postID, comment)
                                                           VALUES(:userID, :postID, :comment)");

			$statement->bindparam(":userID", $_SESSION['userID']);
			$statement->bindparam(":postID", $p_PostId);
			$statement->bindparam(":comment", htmlspecialchars($p_vComment));
			if ($statement->execute()) {
				return true;
			}
		}else{
			return false;
		}
	}

	public function getAllCommentsForPost($p_iPostID){
		$conn = Db::getInstance();
		$stmt = $conn->prepare("SELECT user.username, comment.comment
                                    FROM comment
                                    INNER JOIN user
                                    ON comment.userID=user.id
                                    WHERE postID=:postID
                                    ORDER BY timestamp ASC");
		$stmt->bindparam(":postID", $p_iPostID);
		if($stmt->execute()){
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else{
			return false;
		}
	}

	public function getLatestCommentsForPost($p_iPostID){
		$conn = Db::getInstance();
		$stmt = $conn->prepare("SELECT user.username, comment.comment
                                    FROM comment
                                    INNER JOIN user
                                    ON comment.userID=user.id
                                    WHERE postID=:postID
                                    ORDER BY timestamp ASC LIMIT 10");
		$stmt->bindparam(":postID", $p_iPostID);
		if($stmt->execute()){
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else{
			return false;
		}
	}

	// LINK TO HASHTAGS IN COMMENTS
	public function tagComments($p_vText){
		preg_match_all('/#(\w+)/',$p_vText,$matches);
		foreach ($matches[1] as $match) {
			$p_vText = str_replace("#$match", "<a href='tag.php?tag=$match'>#$match</a>", "$p_vText");
		}
		return $p_vText;
	}


}
	
?>
