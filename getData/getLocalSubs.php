<?php

class getLocalSubs {

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$owner_id = $post["owner_id"];
		$categories = $this->getCategories();
		$subjectInfo = $this->getSubjectInfo($owner_id);
		$this->sendResponse($categories,$subjectInfo);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	//getCategories

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



	//getSubjectInfo

	public function getSubjectInfo($owner_id) {
		$master = [];
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT name,category FROM subject WHERE owner_id = :owner_id"); //add where owner_id = :owner_id
		$stmt->bindParam(':owner_id',$owner_id);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$master[] = $result;
		}
		return $master;
	}

	public function sendResponse($categories,$subjectInfo) {
		$masterArray = [];
		header('Content Type:text/plain;charset=utf:8');
		$masterArray["categories"] = $categories;
		$masterArray["fullSubInfo"] = $subjectInfo;
		echo json_encode($masterArray);
	}
	

}