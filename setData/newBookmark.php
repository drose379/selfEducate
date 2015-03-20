<?php

class newBookmark {

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$owner_id = $post["owner_id"];
		$subject = $post["subject"];
		$this->addBookmark($owner_id,$subject);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	} 

	public function addBookmark($owner_id,$subject) {
		$con = $this->getConnection();
		$stmt = $con->prepare("INSERT INTO subject_bookmarks (owner_id,subject_name) VALUES (:id,:subject)");
		$stmt->bindParam(':id',$owner_id);
		$stmt->bindParam(':subject',$subject);
		$stmt->execute();
	}


}