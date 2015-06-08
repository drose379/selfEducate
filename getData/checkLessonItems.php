<?php

require_once 'connect.php';

class checkLessonItems {

	public function run() {

		$post = file_get_contents("php://input");
		$post = json_decode($post,true);

		$foundItems = [];

		$subject = $post["subject"];
		$lesson = $post["lesson"];

		//call methods to loop over each lesson item table in the db
		//if lesson has at least one of item, add column name to an array,echo array back to application
		$items = $this->hasAlbums($subject,$lesson,$foundItems);
		echo json_encode($items);
	}


	public function hasAlbums($subject,$lesson,$foundItems) {
		$connection = Connection::get();
	
		$stmt = $connection->prepare("SELECT COUNT(*) FROM lesson_albums WHERE subject = :subject AND lesson = :lesson");
		$stmt->bindParam(':subject',$subject);
		$stmt->bindParam(':lesson',$lesson);
		$stmt->execute();
		$itemCount = $stmt->fetchAll();
		
		$exists = $itemCount[0][0];
		
		if ($exists > 1) {
			$foundItems[] = "photoAlbum";
		}
		return $foundItems;
	}

	/*
	public function hasAudio($subject,$lesson) {

	}
	*/
}