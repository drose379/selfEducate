<?php

require 'connection.php';

class checkLessonItems {

	private $foundItems = [];

	public function run() {

		$post = file_get_contents("php://input");
		$post = json_decode($post,true);

		$subject = $post["subject"];
		$lesson = $post["lesson"];

		//APPLICATION MUST PASS SUBJECT AND LESSON TO ENSURE GRABBING CORRECT ITEMS

		//call methods to loop over each lesson item table in the db
		//if lesson has at least one of item, add column name to an array,echo array back to application
		$this->hasAlbums($subject,$lesson);
		//etc
	}

	public function hasAlbums($subject,$lesson) {
		$connection = Connection::get();
		$stmt = $connection->prepare("SELECT COUNT(*) lesson_albums WHERE subject = :subject AND lesson = :lesson LIMIT 1");
		$stmt->bindParam(':subject',$subject);
		$stmt->bindParam(':lesson',$lesson);
		$stmt->execute();
		$itemCount = $stmt->fetch();
		echo $itemCount;
		//get numrows of result, if not 0, add item to foundItems array
	}
}