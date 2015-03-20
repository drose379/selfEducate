<?php

class getBookmarks {
	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$owner_id = $post["owner_id"];
		$bookmarks = $this->getBookmarks($owner_id);
		$bookmarkInfo = $this->getBookmarkInfo($bookmarks);
		$this->sendResponse($bookmarkInfo);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function getBookmarks($owner_id) {
		$bookmarks = [];
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT subject_name FROM subject_bookmarks WHERE owner_id = :id");
		$stmt->bindParam(':id',$owner_id);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$bookmarks[] = $result;
		}
		return $bookmarks;
	}

	public function getBookmarkInfo($bookmarks) {
		$bookmarkInfo = [];
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT name,start_date,lesson_count FROM subject WHERE name = :bookmark");
		$stmt->bindParam(':bookmark',$bookmark);
		foreach ($bookmarks as $bookmarkSub) {
			$bookmark = $bookmarkSub["subject_name"];
			$stmt->execute();
			while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$bookmarkInfo[] = $result;
			}
		}
		return $bookmarkInfo;
	}

	public function sendResponse($bookmarkInfo) {
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($bookmarkInfo);
	}
}