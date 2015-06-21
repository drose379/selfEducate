<?php

require_once 'connect.php';

class newCategory {
	public function run() {
		$post = file_get_contents("php://input");
		$post = json_decode($post,true);

		$catName = $post[0];
		$catDesc = $post[1];

		$this->insertCategory($catName,$catDesc);
	}

	public function insertCategory($name,$description) {
		$con = Connection::get();
		$stmt = $con->prepare("INSERT INTO subject_category (category,description) VALUES (:name,:description)");
		$stmt->bindParam(':name',$name);
		$stmt->bindParam(':description',$description);
		$stmt->execute();
	}
}