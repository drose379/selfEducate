<?php

class getLocalLessons {
	public function run() {
		
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);

		$subject = $post["subject"];

		$localLessons = $this->getLocalLessons($subject);
		$tags = $this->getLessonTags($localLessons);
		$this->sendResponse($localLessons,$tags);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function getLocalLessons($subject) {
		$localLessons = [];

		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT lesson_name FROM lesson WHERE subject = :subject");
		$stmt->bindParam(':subject',$subject);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$localLessons[] = $result;		
		}
		$localLessons = array_column($localLessons,'lesson_name');		
		return $localLessons;
	} 

	public function getLessonTags($localLessons) {
		$tags = [];
		$con = $this->getConnection();
		$stmt = $con->prepare("SELECT lesson,tag FROM tags_attached WHERE lesson = :lesson");
		$stmt->bindParam(':lesson',$lessonName);
		foreach ($localLessons as $lessonName) {
			
			$stmt->execute();
			while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$tags[] = $result;
			}
		}
		return $tags;
	}

	public function sendResponse(array $localLessons,$tags) {
		$master = [];
		$master["lessonInfo"] = $localLessons;
		$master["tags"] = $tags;
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($master);
	}
}