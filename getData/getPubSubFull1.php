<?php

class getPubSubFull1 {

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$subNames = $post;
		$subFullInfo = $this->getSubInfo($subNames);
		$this->sendResponse($subFullInfo);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function getSubInfo($subNames) {
		$master = [];
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT name,start_date,lesson_count FROM subject WHERE name = :name");
		$stmt->bindParam(':name',$sub);
		foreach ($subNames as $sub) {
			$stmt->execute();
			while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$master[] = $result;
			}
		}
		return $master;
	}

	public function sendResponse($subInfo) {
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($subInfo);
	}

}