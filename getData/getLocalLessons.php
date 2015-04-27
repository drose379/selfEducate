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
		$stmt = $con->prepare("SELECT lesson_name,imageBase64 FROM lesson WHERE subject = :subject");
		$stmt->bindParam(':subject',$subject);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$localLessons[] = $result;
		}

		$lessonNames= array_column($localLessons,'lesson_name');
		$lessonImages = array_column($localLessons,'imageBase64');
			
		foreach ($lessonImages as $imageCode) {
			gzinflate($imageCode);
		}

		$masterLessonInfo = [];
		//$masterLessonInfo["names"] = $lessonNames;
		$masterLessonInfo["images"] = $lessonImages;
				
		return $masterLessonInfo;
	} 

	public function sendResponse(array $localLessons) {
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($localLessons);
	}
}