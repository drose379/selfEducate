<?php

class pubSubNames1 {

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$owner_id = $post["owner_id"];

		$subNames = $this->getSubNames($owner_id);
		$bookmarks = $this->getBookmarks($owner_id);

		$this->sendResponse($subNames,$bookmarks);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

		public function getBookmarks($ownerID) {
		$bookmarkArray = [];
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT subject_name FROM subject_bookmarks WHERE owner_id = :id");
		$stmt->bindParam(':id',$ownerID);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$bookmarkArray[] = $result;
		}
		return $bookmarkArray;
	}

	public function getSubNames($owner_id) {
		$nameArray = [];
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT name FROM subject WHERE owner_id <> :id AND privacy = :privacy");
		$stmt->bindParam(':id',$owner_id);
		$stmt->bindValue(':privacy',"PUBLIC");
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$nameArray[] = $result;
		}
		return $nameArray;
	}

	public function sendResponse($subjects,$bookmarks) {
		$master = [];
		$master["subjects"] = $subjects;
		$master["bookmarks"] = $bookmarks;
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($master);
	}

}