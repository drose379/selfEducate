<?php

class getLocalLessons {
	public function run() {
		
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);

		$subject = $post["subject"];

		$localLessons = $this->getLocalLessons($subject);
		$this->sendResponse($localLessons);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function getLocalLessons($subject) {
		$localLessons = [];

		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT lesson_name,imageURL FROM lesson WHERE subject = :subject");
		$stmt->bindParam(':subject',$subject);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$localLessons[] = $result;
		}
				
		return $localLessons;
	} 

	public function sendResponse(array $localLessons) {
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($localLessons);
	}
}