<?php

require_once 'connect.php';

class getLocalSubs {

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$owner_id = $post[0];
		$connection = Connection::get();
		$subjectInfo = $this->getSubjectInfo($owner_id,$connection);
		$categories = $this->getCategories($connection,$subjectInfo);
		$allCategories = $this->grabAllCategories($connection);
		$this->sendResponse($allCategories,$categories,$subjectInfo);
	}

//getSubjectInfo

	public function getSubjectInfo($owner_id,$connection) {
		$master = [];
		$stmt = $connection->prepare("SELECT name,category FROM subject WHERE owner_id = :owner_id"); //add where owner_id = :owner_id
		$stmt->bindParam(':owner_id',$owner_id);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$master[] = $result;
		}
		return $master;
	}

	public function getCategories($connection,$subjectInfo) {
		$allCategories = [];
		$stmt = $connection->prepare("SELECT category,description FROM subject_category WHERE category = :category");
		foreach($subjectInfo as $subject) {
			$stmt->bindParam(':category',$subject["category"]);
			$stmt->execute();
			while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$allCategories[] = $result;
			}
		}
		return $allCategories;
	}

	public function grabAllCategories($connection) {
		$cats = [];
		$stmt = $connection->prepare("SELECT category FROM subject_category");
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$cats[] = $result;
		}
		return $cats;
	}

	public function sendResponse($allCategories,$categories,$subjectInfo) {
		$masterArray = [];
		header('Content Type:text/plain;charset=utf:8');
		$masterArray["allCategories"] = $allCategories;
		$masterArray["categories"] = $categories;
		$masterArray["fullSubInfo"] = $subjectInfo;
		echo json_encode($masterArray);
	}
	

}