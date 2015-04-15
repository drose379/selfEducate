<?php

class getBookmarks {
	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$owner_id = $post["owner_id"];
		
		$categories = $this->getCategories();
		$bookmarks = $this->getBookmarks($owner_id);
		$this->sendResponse($categories,$bookmarks);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}


	public function getCategories() {
		$allCategories = [];
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT category,description FROM subject_category");
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$allCategories[] = $result;
		}
		return $allCategories;
	}

	public function getBookmarks($ownerID) {
		$bookmarks = [];
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT category,subject_name FROM subject_bookmarks WHERE owner_id = :ownerID");
		$stmt->bindParam(':ownerID',$ownerID);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$bookmarks[] = $result;
		}
		return $bookmarks;
	}

	public function sendResponse($categories,$bookmarkInfo) {
		$masterArray = [];
		header('Content Type:text/plain;charset=utf:8');
		$masterArray["categories"] = $categories;
		$masterArray["bookmarks"] = $bookmarkInfo;
		echo json_encode($masterArray);
	}

}