<?php

class getLocalLessons {
	public function run() {
		
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);

		$subject = $post["subject"];

		$localLessons = $this->getLocalLessons($subject);
		$objectives = $this->getObjectives($localLessons);
		$this->sendResponse($localLessons,$objectives);
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

	public function getObjectives($lessons) {
		$objectives = [];
		$con = $this->getConnection();
		foreach ($lessons as $lesson) {
			$lessonName = $lesson["lesson_name"];
			$stmt = $con->prepare("SELECT lesson,objective FROM lesson_objectives WHERE lesson = :lesson LIMIT 1");
			$stmt->bindParam(':lesson',$lessonName);
			$stmt->execute();
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$objectives[] = $result;
			}
		}
		return $objectives;
	}


	public function sendResponse(array $localLessons,$objectives) {
		$master = [];
		$master["lessonInfo"] = $localLessons;
		$master["objectives"] = $objectives;
		header('Content Type:text/plain;charset=utf:8');
		echo json_encode($master);
	}
}