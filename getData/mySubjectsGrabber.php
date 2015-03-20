<?php

class mySubjectsGrabber {

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$ownerID = $post["owner_id"];
		$this->getSubjectInfo($ownerID);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function getSubjectInfo($ownerID) {
		$masterArray = [];
		$connection = $this->getConnection();
		$stmt = $connection->prepare("SELECT name,start_date,lesson_count FROM subject WHERE owner_id = :id");
		$stmt->bindParam(':id',$ownerID);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$masterArray[] = $result;
		}
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($masterArray);
	}

}