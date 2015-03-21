<?php

class hideSubject {

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$owner_id = $post["owner_id"];
		$subject = $post["subject"];
		$this->hideSubject($owner_id,$subject);
		file_put_contents("hidden.txt",$subject);
	}

	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function hideSubject($owner_id,$subject) {
		$con = $this->getConnection();
		$stmt = $con->prepare("INSERT INTO subject_hidden (subject_name,owner_id) VALUES (:subject,:id)");
		$stmt->bindParam(':subject',$subject);
		$stmt->bindParam(':id',$owner_id);
		$stmt->execute();
	} 

}