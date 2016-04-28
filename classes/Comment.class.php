<?php

include_once "Db.class.php";

class Comment
{
	private $m_sComment;
	

	
	public function __set($p_sProperty, $p_vValue)
	{
		switch($p_sProperty)
		{
			case "Comment":
				$this->m_sComment = $p_vValue;
				break;
		}	   
	}
	
	public function __get($p_sProperty)
	{
		$vResult = null;
		switch($p_sProperty)
		{
		case "Comment": 
			$vResult = $this->m_sComment;
			break;
		}
		return $vResult;
	}


		public function Save() {
			$conn = Db::getInstance();
			//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$statement = $conn->prepare('INSERT INTO comment(comment) VALUES(:comment)');
			$statement->bindValue(':comment', $this->Comment);
			$statement->execute();
		}

		public function GetRecentActivities($p_iPostID) {
			$conn = Db::getInstance();
			$allComments = $conn->query("SELECT * FROM comment WHERE postID=:postID ORDER BY id DESC;");
			//$allComments->bindparam(":userID", $p_iUserID);
			return $allComments;
		}

	}
	
?>
