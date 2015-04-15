<?php

class getSelectedBookmark {

	protected $con;

	public function run() {
		$this->con = $this->getConnection();
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$ownerID = $post["owner_id"];
		$bookmark = $post["subject"];
		//match ownerId and bookamrk naem up to record in subject_bookmark table
		$bookmarkInfo = $this->getBookmarkInfo($ownerID,$bookmark);
		$this->sendResponse($bookmarkInfo);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function getBookmarkInfo($ownerID,$bookmark) {
		$bookmarkInfo = [];
		$stmt = $this->con->prepare("SELECT category,subscribed,lesson_privacy,bookmark_id FROM subject_bookmarks WHERE owner_id = :ownerID AND subject_name = :bookmark");
		$stmt->bindParam(':ownerID',$ownerID);
		$stmt->bindParam(':bookmark',$bookmark);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$bookmarkInfo[] = $result;
		}	
		return $bookmarkInfo;
	}

	public function sendResponse($bookmarkInfo) {
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($bookmarkInfo);
	}
}