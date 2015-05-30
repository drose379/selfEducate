<?php

class getTags {

	private $subject;
	private $tagsArray;

	public function run() {
		/*
		$this->getTags();
		$this->sendResponse();
		*/
		echo "Testing!";
	}

	/*
	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=self_educate','root','HwAlJAgstN');
		return $connection;
	}

	public function getTags() {
		$connection = $this->getConnection();
		$stmt = $connection->prepare("SELECT tag_name FROM tag_names");		
		$stmt->execute();
		$innerArray = [];
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$innerArray[] = $result;
		}
		$this->tagsArray = $innerArray;
	}

	public function sendResponse() {
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($this->tagsArray);
	}
	*/
}