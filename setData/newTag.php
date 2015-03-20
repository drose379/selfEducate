<?php

class newTag {

	private $Subject;
	private $Tag;

	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);
		$this->Tag = $post["tag"];
		$this->createTag();
		file_put_contents("tag.txt",$this->Tag);
	}

	
	public function getConnection() {
		$connection = new PDO ('mysql:host=localhost;dbname=codeyour_self_educate','codeyour','dar150267');
		return $connection;
	}

	public function createTag() {
		$connection = $this->getConnection();
		$stmt = $connection->prepare("INSERT INTO tag_names (tag_name) VALUES (:tag)");
		$stmt->bindParam(':tag',$this->Tag);
		$stmt->execute();
	}
	
}