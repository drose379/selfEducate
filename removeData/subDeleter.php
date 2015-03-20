<?php

class subDeleter {

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function run(){
		$con = $this->getConnection();
		$this->deleteSubject($con);
	}

	public function deleteSubject($con) {
		$post = file_get_contents("php://input");
		file_put_contents("delete.txt", $post);
		//$post = json_decode($post,true);
		$subjectName = $post;
		$stmt = $con->prepare("DELETE FROM subject WHERE name = (:text1)");
		$stmt->bindParam(':text1',$subjectName);
		$stmt->execute();
		$this->sendResponse();
	}

	public function sendResponse() {
		http_response_code(204);
		exit;
	}
}