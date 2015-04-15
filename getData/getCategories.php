<?php

class getCategories {
	public function run() {
		$allCategories = $this->getCategories();
		$this->sendResponse($allCategories);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function getCategories() {
		$allCategories = [];
		$con = $this->getConnection();
		//$stmt = $con->prepare("SELECT DISTINCT category FROM subject");
		$stmt = $con->prepare("SELECT category FROM subject_category");
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$allCategories[] = $result;
		}
		return $allCategories;
	}

	public function sendResponse($categories) {
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($categories);
	}

}